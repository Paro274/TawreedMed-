<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusChangedNotification;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['customer', 'suppliers', 'items'])
            ->latest()
            ->paginate(20);
        
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['customer', 'items.product', 'orderSuppliers.supplier'])
            ->findOrFail($id);
        
        return view('admin.orders.invoice', compact('order'));
    }

    // ✅ دالة جديدة للطباعة
    public function print($id)
    {
        $order = Order::with(['customer', 'items.product'])->findOrFail($id);
        return view('admin.orders.print', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,rejected',
        ]);
        
        $oldStatus = $order->status;

        $order->update([
            'status' => $request->status,
            'shipped_at' => $request->status === 'shipped' ? now() : $order->shipped_at,
            'delivered_at' => $request->status === 'delivered' ? now() : $order->delivered_at,
        ]);
        
        if ($oldStatus !== $request->status && $order->shipping_email) {
            try {
                Mail::to($order->shipping_email)->send(new OrderStatusChangedNotification($order));
            } catch (\Exception $e) {}
        }
        
        return back()->with('success', 'تم تحديث حالة الطلب بنجاح');
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed',
        ]);
        
        $order->update([
            'payment_status' => $request->payment_status,
            'paid_at' => $request->payment_status === 'paid' ? now() : null,
        ]);
        
        return back()->with('success', 'تم تحديث حالة الدفع بنجاح');
    }

    public function updateCommission(Request $request, $order_id)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'commission_type' => 'required|in:percentage,fixed',
            'commission_value' => 'required|numeric|min:0',
            'commission_collected' => 'nullable',
        ]);

        $orderSupplier = OrderSupplier::where('order_id', $order_id)
                                    ->where('supplier_id', $request->supplier_id)
                                    ->firstOrFail();

        $subtotal = $orderSupplier->subtotal;
        $commissionAmount = 0;

        if ($request->commission_type == 'percentage') {
            $commissionAmount = ($subtotal * $request->commission_value) / 100;
        } else {
            $commissionAmount = $request->commission_value;
        }

        if ($commissionAmount > $subtotal) {
            return back()->with('error', 'خطأ: قيمة العمولة أكبر من إجمالي مستحقات المورد!');
        }

        $orderSupplier->update([
            'commission_type' => $request->commission_type,
            'commission_value' => $request->commission_value,
            'commission_amount' => $commissionAmount,
            'supplier_due' => $subtotal - $commissionAmount,
            'commission_collected' => $request->has('commission_collected'),
        ]);

        return back()->with('success', 'تم تحديث عمولة المورد بنجاح');
    }
}
