<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    /**
     * عرض جميع طلبات المورد
     */
    public function index()
    {
        $supplierId = Session::get('supplier');
        if (!$supplierId) {
            return redirect()->route('supplier.login');
        }
        
        $orders = Order::with(['customer', 'items.product'])
            ->where('supplier_id', $supplierId)
            ->latest()
            ->paginate(20);
        
        return view('supplier.orders.index', compact('orders'));
    }

    /**
     * عرض تفاصيل الطلب/الفاتورة
     */
    public function show($id)
    {
        $supplierId = Session::get('supplier');
        if (!$supplierId) {
            return redirect()->route('supplier.login');
        }
        
        $order = Order::with(['customer', 'items.product'])
            ->where('supplier_id', $supplierId)
            ->findOrFail($id);
        
        return view('supplier.orders.invoice', compact('order'));
    }

    /**
     * تحديث حالة الطلب
     */
    public function updateStatus(Request $request, $id)
    {
        $supplierId = Session::get('supplier');
        if (!$supplierId) {
            return redirect()->route('supplier.login');
        }
        
        $order = Order::where('supplier_id', $supplierId)
            ->findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
        ]);
        
        $order->update([
            'status' => $request->status,
            'shipped_at' => $request->status === 'shipped' ? now() : $order->shipped_at,
            'delivered_at' => $request->status === 'delivered' ? now() : $order->delivered_at,
        ]);
        
        return back()->with('success', 'تم تحديث حالة الطلب بنجاح');
    }
}
