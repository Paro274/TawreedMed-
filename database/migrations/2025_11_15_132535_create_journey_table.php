<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('journey', function (Blueprint $table) {
            $table->id();
            $table->string('year'); // السنة
            $table->string('title'); // العنوان
            $table->text('description'); // الوصف
            $table->integer('order')->default(0); // للترتيب
            $table->timestamps();
        });

        // عنصر افتراضي للتجربة
        DB::table('journey')->insert([
            'year' => '2020',
            'title' => 'بداية المشوار',
            'description' => 'تأسست الشركة وبدأنا رحلتنا في تقديم أفضل الخدمات.',
            'order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('journey');
    }
};
