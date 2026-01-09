<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateAboutSectionsTable extends Migration
{
    public function up()
    {
        Schema::create('about_sections', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // أدخل سجل افتراضي واحد لعنصر "من نحن"
        DB::table('about_sections')->insert([
            'title' => 'عنوان قسم من نحن',
            'description' => 'هذا هو وصف قسم من نحن',
            'image' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('about_sections');
    }
}
