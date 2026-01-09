<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\CanResetPassword; // ✅ مهم
use App\Notifications\CustomerResetPasswordNotification; // ✅ استدعاء الإشعار المخصص

class User extends Authenticatable implements CanResetPassword // ✅ إضافة Interface
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'status',
        'email_verified_at',
        'last_login',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login' => 'datetime',
        ];
    }

    // ✅ دالة إرسال إشعار استعادة كلمة المرور (عشان الرابط يروح للعميل مش الأدمن)
    public function sendPasswordResetNotification($token)
    {
        // تأكد إن المستخدم ده عميل قبل ما تبعتله الرابط الخاص بالعملاء
        if ($this->isCustomer()) {
            $this->notify(new CustomerResetPasswordNotification($token));
        } else {
            // لو مش عميل (أدمن مثلاً)، خليه يستخدم الطريقة العادية
            $this->notify(new \Illuminate\Auth\Notifications\ResetPassword($token));
        }
    }

    // ✅ Scopes للعملاء
    public function scopeCustomer($query)
    {
        return $query->where('role', 'customer');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    // ✅ علاقات
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // ✅ Accessor للاسم المعروض
    public function getDisplayNameAttribute()
    {
        return $this->name ?: 'عميل';
    }

    // ✅ تحقق من دور العميل
    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    // ✅ تحقق من الحالة النشطة
    public function isActive()
    {
        return $this->status == 1;
    }
}
