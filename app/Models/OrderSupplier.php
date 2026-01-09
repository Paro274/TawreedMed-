<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderSupplier extends Model
{
    use HasFactory;

    protected $table = 'order_suppliers';

    protected $fillable = [
        'order_id',
        'supplier_id',
        'subtotal',
        'commission_type',
        'commission_value',
        'commission_amount',
        'supplier_due',
        'commission_collected',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
