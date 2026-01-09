<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            
            $table->string('title'); // العنوان
            $table->text('description'); // الوصف
            $table->string('icon'); // اسم الأيقونة
            $table->integer('order')->default(0); // ترتيب العرض
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('features');
    }
};
