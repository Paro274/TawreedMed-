<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    private function getGuard()
    {
        return Auth::guard('customer');
    }

    // ✅ عرض السلة
    public function index()
    {
        $cartItems = $this->getCartItems();
        $total = $this->calculateTotal($cartItems);
        $cartCount = $this->getCartCount();

        return view('frontend.cart.index', [
            'items' => $cartItems, 
            'total' => $total,
            'cartCount' => $cartCount
        ]);
    }

    // ✅ إضافة منتج للسلة
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // ✅ منع الأدمن والموردين من إضافة منتجات للسلة
        if (session()->has('admin') || session()->has('supplier')) {
            return response()->json([
                'success' => false,
                'message' => 'عذراً، لا يمكن للمسؤولين أو الموردين إضافة منتجات للسلة. يرجى تسجيل الدخول كعميل.',
                'icon' => 'error'
            ], 403);
        }

        $productId = $request->product_id;
        $quantity = $request->quantity ?? 1;

        // ✅ جلب المنتج
        $product = Product::with('supplier')->findOrFail($productId);
        
        // ⛔ [تم التعديل] تم تعطيل التحقق من التوفر بطلب من العميل
        /*
        if (!$product->is_available) {
            return response()->json([
                'success' => false,
                'message' => 'عذراً، هذا المنتج غير متوفر حالياً!',
                'icon' => 'error'
            ], 400);
        }
        */

        // ⛔ [تم التعديل] تم تعطيل التحقق من الكمية بطلب من العميل
        /*
        if (is_numeric($product->available_quantity) && $quantity > $product->available_quantity) {
            return response()->json([
                'success' => false,
                'message' => "عذراً، الكمية المطلوبة (${quantity}) غير متوفرة! المتوفر في المخزون: {$product->available_quantity} فقط.",
                'icon' => 'warning'
            ], 400);
        }
        */

        $guard = $this->getGuard();
        $currentQtyInCart = 0;

        // حساب الكمية الموجودة حالياً في السلة
        if ($guard->check()) {
            $currentQtyInCart = DB::table('cart_items')
                ->where('user_id', $guard->id())
                ->where('product_id', $productId)
                ->value('quantity') ?? 0;
        } else {
            $cart = session('cart', []);
            if (isset($cart[$productId])) {
                $currentQtyInCart = $cart[$productId]['quantity'];
            }
        }

        // ✅ التحقق من الكمية الكلية (الموجودة + الجديدة)
        $totalRequestedQty = $currentQtyInCart + $quantity;
        
        // ⛔ [تم التعديل] تم تعطيل التحقق من أقل كمية للطلب بطلب من العميل
        /*
        $minQty = $product->min_order_quantity ?? 1;
        if ($totalRequestedQty < $minQty) {
            return response()->json([
                'success' => false,
                'message' => "عذراً، أقل كمية يمكن طلبها من هذا المنتج هي {$minQty}",
                'icon' => 'warning'
            ], 400);
        }
        */
        
        // ⛔ [تم التعديل] تم تعطيل التحقق من تجاوز المخزون الإجمالي
        /*
        if (is_numeric($product->available_quantity) && $totalRequestedQty > $product->available_quantity) {
            if ($request->is_buy_now && $currentQtyInCart >= $product->available_quantity) {
                return response()->json([
                    'success' => true,
                    'message' => 'المنتج موجود بالفعل في السلة بأقصى كمية متاحة. جاري الانتقال للدفع...',
                    'icon' => 'success',
                    'count' => $this->getCartCount(),
                    'cart_count' => $this->getCartCount(),
                    'cart_total' => $this->calculateTotal($this->getCartItems())
                ]);
            }

            $remaining = max(0, $product->available_quantity - $currentQtyInCart);
            return response()->json([
                'success' => false,
                'message' => "عذراً، الكمية المطلوبة غير متوفرة! لديك {$currentQtyInCart} في السلة، والمتبقي في المخزن {$remaining} فقط.",
                'icon' => 'warning'
            ], 400);
        }
        */

        if ($guard->check()) {
            // ✅ المستخدم مسجل دخول - حفظ في قاعدة البيانات
            $userId = $guard->id();
            
            if ($currentQtyInCart > 0) {
                DB::table('cart_items')
                    ->where('user_id', $userId)
                    ->where('product_id', $productId)
                    ->update([
                        'quantity' => $totalRequestedQty,
                        'price' => $product->final_price,
                        'updated_at' => now()
                    ]);
            } else {
                DB::table('cart_items')->insert([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $product->final_price,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        } else {
            // ✅ المستخدم غير مسجل - حفظ في الـ session
            $cart = session('cart', []);
            
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] = $totalRequestedQty;
            } else {
                $cart[$productId] = [
                    'id' => $productId,
                    'product_id' => $productId,
                    'name' => $product->name,
                    'price' => floatval($product->final_price),
                    'quantity' => $quantity,
                    'image_1' => $product->image_1,
                    'slug' => $product->slug,
                    'supplier_name' => $product->supplier->name ?? 'غير محدد'
                ];
            }
            
            Session::put('cart', $cart);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة المنتج للسلة بنجاح!',
            'icon' => 'success',
            'count' => $this->getCartCount(),
            'cart_count' => $this->getCartCount(),
            'cart_total' => $this->calculateTotal($this->getCartItems())
        ]);
    }

    // ✅ تحديث كمية منتج في السلة
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $guard = $this->getGuard();
        $quantity = $request->quantity;

        // ⛔ [تم التعديل] تم تعطيل التحقق في التحديث أيضاً
        /*
        $product = Product::find($id);
        if ($product) {
            if (!$product->is_available) {
                 return response()->json([
                    'success' => false,
                    'message' => 'عذراً، هذا المنتج غير متوفر حالياً!',
                    'icon' => 'error'
                ], 400);
            }

            if (is_numeric($product->available_quantity) && $quantity > $product->available_quantity) {
                 return response()->json([
                    'success' => false,
                    'message' => "عذراً، الكمية المطلوبة (${quantity}) غير متوفرة! المتوفر في المخزون: {$product->available_quantity} فقط.",
                    'icon' => 'warning'
                ], 400);
            }
        }
        */

        if ($guard->check()) {
            DB::table('cart_items')
                ->where('user_id', $guard->id())
                ->where('product_id', $id)
                ->update([
                    'quantity' => $quantity,
                    'updated_at' => now()
                ]);
        } else {
            $cart = session('cart', []);
            if (isset($cart[$id])) {
                $cart[$id]['quantity'] = $quantity;
                Session::put('cart', $cart);
            }
        }

        $updatedItems = $this->getCartItems();
        $total = $this->calculateTotal($updatedItems);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث السلة بنجاح!',
            'icon' => 'success',
            'total' => $total,
            'cart_count' => $this->getCartCount()
        ]);
    }

    // ✅ حذف منتج من السلة
    public function remove($id)
    {
        $guard = $this->getGuard();

        if ($guard->check()) {
            DB::table('cart_items')
                ->where('user_id', $guard->id())
                ->where('product_id', $id)
                ->delete();
        } else {
            $cart = session('cart', []);
            unset($cart[$id]);
            Session::put('cart', $cart);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم حذف المنتج من السلة بنجاح',
            'icon' => 'success',
            'cart_count' => $this->getCartCount(),
            'cart_total' => $this->calculateTotal($this->getCartItems())
        ]);
    }

    // ✅ تفريغ السلة كاملة
    public function clear()
    {
        $guard = $this->getGuard();

        if ($guard->check()) {
            DB::table('cart_items')->where('user_id', $guard->id())->delete();
        } else {
            Session::forget('cart');
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تفريغ السلة بالكامل',
            'icon' => 'success',
            'cart_count' => 0,
            'cart_total' => 0
        ]);
    }

    // ✅ الحصول على عدد عناصر السلة
    public function getCartCount()
    {
        $guard = $this->getGuard();

        if ($guard->check()) {
            return (int) DB::table('cart_items')->where('user_id', $guard->id())->sum('quantity');
        }

        $cart = session('cart', []);
        return collect($cart)->sum('quantity');
    }

    // ✅ الحصول على عناصر السلة
    private function getCartItems()
    {
        $guard = $this->getGuard();

        if ($guard->check()) {
            $items = DB::table('cart_items as ci')
                ->join('products as p', 'ci.product_id', '=', 'p.id')
                ->leftJoin('suppliers as s', 'p.supplier_id', '=', 's.id')
                ->where('ci.user_id', $guard->id())
                ->select(
                    'ci.id as cart_item_id', 
                    'ci.product_id as id', 
                    'ci.product_id',
                    'ci.quantity', 
                    'p.name',
                    'p.slug',
                    'p.image_1',
                    'p.price',
                    'p.discount',
                    'p.price_after_discount',
                    's.name as supplier_name'
                )
                ->orderBy('ci.created_at', 'desc')
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

        $cart = session('cart', []);
        return collect($cart)->map(function($item) {
            return (object) $item;
        });
    }

    // ✅ حساب المجموع الكلي
    private function calculateTotal($cartItems)
    {
        return $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });
    }

    // ✅ الانتقال للدفع
    public function checkout()
    {
        return redirect()->route('frontend.cart.checkout');
    }

    // ✅ الحصول على معلومات السلة للـ AJAX
    public function getCartInfo()
    {
        $cartItems = $this->getCartItems();
        $total = $this->calculateTotal($cartItems);
        $count = $this->getCartCount();

        return response()->json([
            'count' => $count,
            'total' => number_format($total, 2),
            'items_count' => $cartItems->count()
        ]);
    }
}
