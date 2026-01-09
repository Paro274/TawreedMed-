<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            
            // حقول أساسية بسيطة
            $table->string('title'); // العنوان الرئيسي
            $table->text('description'); // الوصف
            $table->integer('order')->default(0); // ترتيب العرض
            
            // الزر الأول
            $table->string('button1_text')->nullable(); // نص الزر الأول
            $table->string('button1_link')->nullable(); // رابط الزر الأول
            
            // الزر الثاني
            $table->string('button2_text')->nullable(); // نص الزر الثاني
            $table->string('button2_link')->nullable(); // رابط الزر الثاني
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
