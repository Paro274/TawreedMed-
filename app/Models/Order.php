<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_id',
        'supplier_id', // يمكن أن يكون null في الطلبات متعددة الموردين
        'subtotal',
        'shipping_fee',
        'tax',
        'total',
        'payment_method',
        'payment_status',
        'shipping_name',
        'shipping_phone',
        'shipping_email',
        'shipping_governorate',
        'shipping_city',
        'shipping_address',
        'notes',
        'status',
        'paid_at',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . strtoupper(Str::random(8)) . '-' . now()->format('Ymd');
            }
        });
    }

    // Relationships
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    // العلاقة القديمة (للتوافق فقط)
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // ✅ العلاقة الجديدة: تفاصيل العمولات لكل مورد في هذا الطلب
    public function orderSuppliers()
    {
        return $this->hasMany(OrderSupplier::class);
    }

    // ✅ العلاقة لجلب الموردين مباشرة مع بيانات العمولة
    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'order_suppliers')
                    ->withPivot(['subtotal', 'commission_type', 'commission_value', 'commission_amount', 'supplier_due', 'commission_collected'])
                    ->withTimestamps();
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeForCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'بإنتظار الموافقة',
            'processing' => 'جاري التجهيز',
            'shipped' => 'تم الشحن',
            'delivered' => 'تم التسليم',
            'cancelled' => 'لاغي من العميل',
            'rejected' => 'تم رفض الطلب',
            default => 'غير محدد',
        };
    }

    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'processing' => 'info',
            'shipped' => 'primary',
            'delivered' => 'success',
            'cancelled' => 'secondary',
            'rejected' => 'danger',
            default => 'light',
        };
    }

    public function getPaymentStatusLabelAttribute()
    {
        return match($this->payment_status) {
            'pending' => 'قيد الانتظار',
            'paid' => 'مدفوع',
            'failed' => 'فشل',
            default => 'غير محدد',
        };
    }
}
