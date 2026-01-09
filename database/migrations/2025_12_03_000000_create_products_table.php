<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('product_type'); // نوع المنتج
            $table->string('name'); // اسم المنتج
            $table->decimal('price', 10, 2); // السعر
            $table->decimal('discount', 5, 2)->nullable(); // الخصم
            $table->decimal('price_after_discount', 10, 2)->nullable(); // السعر بعد الخصم
            $table->decimal('package_price', 10, 2)->nullable(); // سعر الباكيدج (جديد)
            
            $table->string('company_name')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('full_description')->nullable();
            
            $table->json('active_ingredient')->nullable(); // المادة الفعالة (JSON)
            $table->string('dosage_form')->nullable();
            $table->integer('tablets_per_pack')->nullable();
            
            $table->date('production_date')->nullable();
            $table->date('expiry_date')->nullable();
            
            $table->string('package_type')->nullable(); // نوع الباكيدج (كرتونة/كيس/...)
            $table->integer('units_per_package')->nullable(); // عدد الوحدات في الباكيدج
            
            $table->integer('min_order_quantity')->nullable(); // أقل كمية (قطاعي/وحدة)
            $table->integer('min_order_package')->nullable(); // أقل كمية (جملة/باكيدج) (جديد)
            
            $table->integer('total_units')->nullable(); // المخزون
            
            $table->string('image_1')->nullable();
            $table->string('image_2')->nullable();
            $table->string('image_3')->nullable();
            $table->string('image_4')->nullable();
            
            $table->string('status')->default('معلق');
            $table->string('slug')->nullable();
            $table->integer('views')->default(0);
            $table->boolean('featured')->default(false);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
