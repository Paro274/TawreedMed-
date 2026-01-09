<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('certificates_awards', function (Blueprint $table) {
            $table->id();
            $table->string('icon'); // أيقونة FontAwesome
            $table->string('title'); // العنوان
            $table->text('description'); // الوصف
            $table->integer('order')->default(0); // للترتيب
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('certificates_awards');
    }
};
