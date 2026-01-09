<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('statistics', function (Blueprint $table) {
            $table->id();
            
            // حقول الإحصائية الأساسية
            $table->string('title'); // العنوان
            $table->string('number'); // الرقم
            $table->integer('order')->default(0); // ترتيب العرض
            
            // الإحصائية الثانية
            $table->string('title2')->nullable(); // عنوان ثاني (اختياري)
            $table->string('number2')->nullable(); // رقم ثاني (اختياري)
            
            // الإحصائية الثالثة
            $table->string('title3')->nullable(); // عنوان ثالث (اختياري)
            $table->string('number3')->nullable(); // رقم ثالث (اختياري)
            
            // الإحصائية الرابعة
            $table->string('title4')->nullable(); // عنوان رابع (اختياري)
            $table->string('number4')->nullable(); // رقم رابع (اختياري)
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statistics');
    }
};
