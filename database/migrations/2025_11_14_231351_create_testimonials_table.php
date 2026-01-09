<?php
// database/migrations/xxxx_create_testimonials_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // الاسم
            $table->string('image')->nullable(); // الصورة
            $table->integer('rating')->default(5); // التقييم من 1-5
            $table->text('review'); // التقييم/التعليق
            $table->string('job_title')->nullable(); // المسمى الوظيفي
            $table->string('governorate'); // المحافظة
            $table->boolean('status')->default(1); // نشط/غير نشط
            $table->integer('order')->default(0); // ترتيب العرض
            $table->timestamps();
        });

        // إضافة بعض البيانات الافتراضية
        DB::table('testimonials')->insert([
            [
                'name' => 'أحمد محمد',
                'image' => null,
                'rating' => 5,
                'review' => 'خدمة ممتازة وسريعة، أنصح بشدة!',
                'job_title' => 'مهندس برمجيات',
                'governorate' => 'القاهرة',
                'status' => 1,
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'فاطمة علي',
                'image' => null,
                'rating' => 4,
                'review' => 'جودة عالية للمنتجات، شحن سريع',
                'job_title' => 'مديرة تسويق',
                'governorate' => 'الجيزة',
                'status' => 1,
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'محمد صالح',
                'image' => null,
                'rating' => 5,
                'review' => 'تجربة رائعة، سأتعامل معاهم مرة تانية',
                'job_title' => 'صاحب شركة',
                'governorate' => 'الإسكندرية',
                'status' => 1,
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('testimonials');
    }
};
