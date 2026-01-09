<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;
use App\Notifications\SupplierResetPasswordNotification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class Supplier extends Authenticatable implements CanResetPassword
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'logo', 'status',
        'last_login_at', 'email_verified_at', 'last_activity_at',
        'login_count', 'last_login_ip', 'created_by',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'status' => 'integer',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new SupplierResetPasswordNotification($token));
    }

    // Accessors for Logo
    public function getLogoUrlAttribute(): string
    {
        if (empty($this->logo)) {
            return asset('frontend/images/default-company-logo.jpg');
        }
        if (str_contains($this->logo, 'default-company-logo.jpg')) {
            return asset('frontend/images/default-company-logo.jpg');
        }
        if (File::exists(public_path($this->logo))) {
            return asset($this->logo);
        }
        if (Storage::disk('public')->exists($this->logo)) {
            return asset('storage/' . $this->logo);
        }
        return asset('frontend/images/default-company-logo.jpg');
    }

    public function hasCustomLogo(): bool
    {
        if (!$this->logo || str_contains($this->logo, 'default')) {
            return false;
        }
        return File::exists(public_path($this->logo)) || Storage::disk('public')->exists($this->logo);
    }

    // Relationships
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // ✅ العلاقة الجديدة: الطلبات التي يشارك فيها المورد
    public function sharedOrders()
    {
        return $this->belongsToMany(Order::class, 'order_suppliers')
                    ->withPivot(['subtotal', 'commission_type', 'commission_value', 'commission_amount', 'supplier_due', 'commission_collected'])
                    ->withTimestamps();
    }
}
