<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            
            // البيانات المالية للمورد في هذا الطلب
            $table->decimal('subtotal', 10, 2)->default(0); // مجموع منتجات المورد ده بس
            
            // بيانات العمولة (يحددها الأدمن لاحقاً)
            $table->enum('commission_type', ['percentage', 'fixed'])->nullable(); // نوع العمولة
            $table->decimal('commission_value', 10, 2)->nullable(); // القيمة (مثلا 10 أو 50)
            $table->decimal('commission_amount', 10, 2)->default(0); // القيمة النهائية المخصومة (بالجنيه)
            $table->decimal('supplier_due', 10, 2)->default(0); // المبلغ المستحق للمورد (بعد خصم العمولة)
            
            $table->boolean('commission_collected')->default(false); // هل المورد حول العمولة للأدمن؟
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_suppliers');
    }
};
