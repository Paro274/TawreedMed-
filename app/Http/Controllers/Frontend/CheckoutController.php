<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderSupplier; // ✅ هام
use App\Models\Product;
use App\Models\Admin;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewOrderAdminNotification;
use App\Mail\NewOrderSupplierNotification;

class CheckoutController extends Controller
{
    public function index()
    {
        $customerGuard = Auth::guard('customer');
        
        if (session()->has('admin') || session()->has('supplier')) {
            return redirect()->route('frontend.home')->with('error', 'عذراً، لا يمكن للمسؤولين أو الموردين إتمام الطلبات. يرجى تسجيل الدخول كعميل.');
        }

        if (!$customerGuard->check()) {
            return redirect()->route('frontend.customer.login')
                ->with('error', 'يرجى تسجيل الدخول لإتمام الطلب')
                ->with('auth_tab', 'login');
        }

        $this->mergeSessionCartToDb($customerGuard->id());

        $items = $this->getCartItems($customerGuard->id());
        
        if ($items->isEmpty()) {
            return redirect()->route('frontend.cart.index')->with('error', 'سلة المشتريات فارغة');
        }
        
        $total = $items->sum(fn($item) => $item->price * $item->quantity);
        $customer = $customerGuard->user();
        
        $shippingFee = 0;
        $tax = 0;
        $grandTotal = $total + $shippingFee + $tax;
        
        return view('frontend.checkout.index', compact('items', 'total', 'shippingFee', 'tax', 'grandTotal', 'customer'));
    }

    public function store(Request $request)
    {
        $customerGuard = Auth::guard('customer');

        if (session()->has('admin') || session()->has('supplier')) {
            return redirect()->route('frontend.home')->with('error', 'عذراً، لا يمكن للمسؤولين أو الموردين إتمام الطلبات.');
        }

        if (!$customerGuard->check()) {
            return redirect()->route('frontend.customer.login')->with('error', 'يرجى تسجيل الدخول');
        }
        
        $items = $this->getCartItems($customerGuard->id());

        if ($items->isEmpty()) {
            return redirect()->route('frontend.cart.index')->with('error', 'سلة المشتريات فارغة');
        }
        
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_governorate' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:255',
            'shipping_address' => 'required|string',
            'payment_method' => 'required|in:cash',
        ]);
        
        try {
            DB::beginTransaction();
            
            $subtotal = $items->sum(fn($item) => $item->price * $item->quantity);
            $shippingFee = 0;
            $tax = 0;
            $total = $subtotal + $shippingFee + $tax;
            
            $customer = $customerGuard->user();
            
            // إنشاء الطلب الأساسي
            $order = Order::create([
                'customer_id' => $customer->id,
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'tax' => $tax,
                'total' => $total,
                'payment_method' => 'cash',
                'payment_status' => 'pending',
                'shipping_name' => $request->shipping_name,
                'shipping_phone' => $request->shipping_phone,
                'shipping_email' => $request->shipping_email ?? $customer->email,
                'shipping_governorate' => $request->shipping_governorate,
                'shipping_city' => $request->shipping_city,
                'shipping_address' => $request->shipping_address,
                'notes' => $request->notes,
                'status' => 'pending',
            ]);
            
            // مصفوفة لتجميع بيانات الموردين
            $supplierData = [];
            $supplierItemsForMail = []; // للإيميلات

            foreach ($items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    // إذا كان المنتج يتبع لمورد، نجمع بياناته
                    if ($product->supplier_id) {
                        if (!isset($supplierData[$product->supplier_id])) {
                            $supplierData[$product->supplier_id] = 0;
                            $supplierItemsForMail[$product->supplier_id] = [];
                        }
                        // جمع السعر الإجمالي لمنتجات هذا المورد
                        $supplierData[$product->supplier_id] += ($item->price * $item->quantity);
                        
                        // لو ده أول مورد، نحطه كأساسي (اختياري للتوافق)
                        if (!$order->supplier_id) {
                            $order->update(['supplier_id' => $product->supplier_id]);
                        }
                    }

                    $orderItem = OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_slug' => $product->slug,
                        'product_image' => $product->image_1,
                        'unit_price' => $item->price,
                        'quantity' => $item->quantity,
                        'total_price' => $item->price * $item->quantity,
                    ]);

                    if ($product->supplier_id) {
                        $supplierItemsForMail[$product->supplier_id][] = $orderItem;
                    }

                    $product->decrement('total_units', $item->quantity);
                }
            }

            // ✅ إنشاء سجلات الموردين في جدول order_suppliers
            foreach ($supplierData as $supplierId => $supplierSubtotal) {
                OrderSupplier::create([
                    'order_id' => $order->id,
                    'supplier_id' => $supplierId,
                    'subtotal' => $supplierSubtotal,
                    'commission_type' => null, // لم تحدد بعد
                    'commission_value' => null,
                    'commission_amount' => 0,
                    'supplier_due' => $supplierSubtotal, // المبلغ كله للمورد مبدئياً
                    'commission_collected' => false,
                ]);
            }
            
            DB::table('cart_items')->where('user_id', $customer->id)->delete();
            
            DB::commit();
            
            // --- إرسال الإيميلات ---
            try {
                // للأدمن
                $admin = Admin::where('is_super_admin', 1)->first();
                if ($admin && $admin->email) {
                    Mail::to($admin->email)->send(new NewOrderAdminNotification($order));
                }

                // للموردين
                foreach ($supplierItemsForMail as $supplierId => $orderItems) {
                    $supplier = Supplier::find($supplierId);
                    if ($supplier && $supplier->email) {
                        $itemsCollection = collect($orderItems);
                        Mail::to($supplier->email)->send(new NewOrderSupplierNotification($order, $supplier, $itemsCollection));
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Order Emails Error: ' . $e->getMessage());
            }
            // -----------------------

            return redirect()
                ->route('frontend.customer.orders.invoice', $order->id)
                ->with('success', 'تم إنشاء طلبك بنجاح! رقم الطلب: ' . $order->order_number);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'حدث خطأ أثناء إنشاء الطلب: ' . $e->getMessage()])
                ->withInput();
        }
    }

    private function getCartItems($userId)
    {
        $items = DB::table('cart_items as ci')
            ->join('products as p', 'ci.product_id', '=', 'p.id')
            ->leftJoin('suppliers as s', 'p.supplier_id', '=', 's.id')
            ->where('ci.user_id', $userId)
            ->select(
                'ci.id', 'ci.product_id', 'ci.quantity', 
                'p.name', 'p.slug', 'p.image_1', 'p.price', 
                'p.discount', 'p.price_after_discount', 
                's.name as supplier_name'
            )
            ->get();

        return $items->map(function($item) {
            if ($item->discount > 0 && $item->price_after_discount !== null) {
                $item->price = $item->price_after_discount;
            } elseif ($item->discount > 0) {
                $item->price = $item->price - ($item->price * $item->discount / 100);
            }
            return $item;
        });
    }

    private function mergeSessionCartToDb($userId)
    {
        $sessionCart = Session::get('cart', []);
        
        if (!empty($sessionCart)) {
            foreach ($sessionCart as $productId => $item) {
                $dbItem = DB::table('cart_items')
                    ->where('user_id', $userId)
                    ->where('product_id', $productId)
                    ->first();
                    
                if ($dbItem) {
                    DB::table('cart_items')
                        ->where('id', $dbItem->id)
                        ->update(['quantity' => $dbItem->quantity + $item['quantity'], 'updated_at' => now()]);
                } else {
                    DB::table('cart_items')->insert([
                        'user_id' => $userId,
                        'product_id' => $productId,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
            Session::forget('cart');
        }
    }
}
