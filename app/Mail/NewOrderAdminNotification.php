<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOrderAdminNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject('طلب جديد رقم: ' . $this->order->order_number)
                    ->view('emails.order-admin')
                    ->with([
                        'order' => $this->order,
                        'items' => $this->order->items,
                        'customer' => $this->order->customer,
                    ]);
    }
}
