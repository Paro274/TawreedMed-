<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // التحقق من وجود الجدول أولاً
        if (!Schema::hasTable('suppliers')) {
            // إنشاء الجدول من الصفر إذا مش موجود
            Schema::create('suppliers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('phone', 20)->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('logo')->nullable();
                $table->string('city', 100)->nullable();
                $table->text('description')->nullable();
                $table->tinyInteger('status')->default(1); // مفعّل افتراضياً
                $table->timestamp('last_login_at')->nullable();
                $table->rememberToken();
                $table->timestamps();
                
                $table->index('status');
                $table->index('email');
            });
        } else {
            // إضافة الـ columns المفقودة للجدول الموجود
            Schema::table('suppliers', function (Blueprint $table) {
                // الاسم
                if (!Schema::hasColumn('suppliers', 'name')) {
                    $table->string('name')->after('id');
                }
                
                // البريد الإلكتروني
                if (!Schema::hasColumn('suppliers', 'email')) {
                    $table->string('email')->unique()->after('name');
                } elseif (!Schema::hasColumn('suppliers', 'email') && Schema::hasColumn('suppliers', 'email')) {
                    // إضافة unique constraint إذا مش موجود
                    $table->unique('email');
                }
                
                // رقم الهاتف
                if (!Schema::hasColumn('suppliers', 'phone')) {
                    $table->string('phone', 20)->nullable()->after('email');
                }
                
                // التحقق من البريد
                if (!Schema::hasColumn('suppliers', 'email_verified_at')) {
                    $table->timestamp('email_verified_at')->nullable()->after('phone');
                }
                
                // كلمة المرور
                if (!Schema::hasColumn('suppliers', 'password')) {
                    $table->string('password')->after('email_verified_at');
                }
                
                // الشعار
                if (!Schema::hasColumn('suppliers', 'logo')) {
                    $table->string('logo')->nullable()->after('password');
                }
                
                // المدينة
                if (!Schema::hasColumn('suppliers', 'city')) {
                    $table->string('city', 100)->nullable()->after('logo');
                }
                
                // الوصف
                if (!Schema::hasColumn('suppliers', 'description')) {
                    $table->text('description')->nullable()->after('city');
                }
                
                // الحالة
                if (!Schema::hasColumn('suppliers', 'status')) {
                    $table->tinyInteger('status')->default(1)->after('description');
                    $table->index('status');
                } else {
                    // تحديث القيمة الافتراضية للحالة لتكون 1 (مفعّل)
                    \DB::statement('ALTER TABLE suppliers MODIFY status TINYINT(1) DEFAULT 1');
                }
                
                // آخر تسجيل دخول
                if (!Schema::hasColumn('suppliers', 'last_login_at')) {
                    $table->timestamp('last_login_at')->nullable()->after('status');
                }
                
                // Remember token
                if (!Schema::hasColumn('suppliers', 'remember_token')) {
                    $table->rememberToken()->after('last_login_at');
                }
                
                // التأكد من وجود created_at و updated_at
                if (!Schema::hasColumn('suppliers', 'created_at')) {
                    $table->timestamps();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
