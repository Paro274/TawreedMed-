<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cta_sections', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('button1_text')->nullable();
            $table->string('button1_link')->nullable();
            $table->string('button2_text')->nullable();
            $table->string('button2_link')->nullable();
            $table->timestamps();
        });

        // سجل افتراضي
        DB::table('cta_sections')->insert([
            'title' => 'ابدأ رحلتك معنا الآن',
            'description' => 'انضم إلى آلاف العملاء الراضين واحصل على أفضل الخدمات والمنتجات',
            'button1_text' => 'اتصل بنا',
            'button1_link' => '/contact',
            'button2_text' => 'تصفح المنتجات',
            'button2_link' => '/products',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('cta_sections');
    }
};
