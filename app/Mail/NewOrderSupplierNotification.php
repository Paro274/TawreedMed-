<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\Supplier;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class NewOrderSupplierNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $supplier;
    public $supplierItems; // ✅ منتجات هذا المورد فقط

    public function __construct(Order $order, Supplier $supplier, Collection $supplierItems)
    {
        $this->order = $order;
        $this->supplier = $supplier;
        $this->supplierItems = $supplierItems;
    }

    public function build()
    {
        return $this->subject('طلب جديد رقم: ' . $this->order->order_number)
                    ->view('emails.order-supplier')
                    ->with([
                        'order' => $this->order,
                        'supplier' => $this->supplier,
                        'items' => $this->supplierItems,
                        'customer' => $this->order->customer,
                    ]);
    }
}
