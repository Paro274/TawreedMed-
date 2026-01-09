<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'username',
        'email',
        'password',
        'is_super_admin',
        'permissions',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_super_admin' => 'boolean',
        'permissions' => 'array',
    ];

    /**
     * ✅ قائمة جميع الصلاحيات المتاحة في النظام
     */
    public static function availablePermissions()
    {
        return [
            'admins' => '👥 إدارة المشرفين',
            'suppliers' => '🏪 الموردين',
            'products' => '📦 المنتجات',
            'categories' => '📂 التصنيفات',
            'orders' => '🛒 الطلبات والفواتير',
            'sliders' => '🖼️ البنرات (Sliders)',
            'statistics' => '📊 الإحصائيات',
            'features' => '✨ مميزات الموقع',
            'about' => 'ℹ️ من نحن',
            'story' => '📜 قصتنا',
            'mvv' => '👁️ الرؤية والرسالة',
            'team' => '👔 فريق العمل',
            'journey' => '🚀 رحلتنا',
            'partners' => '🤝 شركاء النجاح',
            'certificates' => '🏆 الشهادات والجوائز',
            'testimonials' => '💬 آراء العملاء',
            'cta' => '📣 قسم (Call to Action)',
            'faqs' => '❓ الأسئلة الشائعة',
            'contact' => '📞 معلومات التواصل (الرئيسية)',
            'contact2' => '📱 معلومات التواصل (الإضافية)',
            'map' => '🗺️ الخريطة والموقع',
        ];
    }

    /**
     * ✅ التحقق من امتلاك صلاحية معينة
     */
    public function hasPermission($permission)
    {
        // السوبر أدمن عنده كل الصلاحيات
        if ($this->is_super_admin) {
            return true;
        }

        // التحقق من وجود الصلاحية في القائمة
        return in_array($permission, $this->permissions ?? []);
    }
}
