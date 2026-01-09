<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم المورد
            $table->string('email')->unique(); // البريد الإلكتروني
            $table->string('phone')->nullable(); // رقم الهاتف اختياري
            $table->string('password'); // كلمة المرور
            $table->string('logo')->nullable(); // شعار الشركة
            $table->boolean('active')->default(true); // حالة الحساب (مفعل أو معطل)
            $table->timestamps(); // التاريخ (إنشاء - تحديث)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
