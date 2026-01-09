<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mission_vision_values', function (Blueprint $table) {
            $table->id();
            $table->string('mission_title')->nullable();
            $table->longText('mission_description')->nullable();
            $table->string('vision_title')->nullable();
            $table->longText('vision_description')->nullable();
            $table->string('values_title')->nullable();
            $table->longText('values_description')->nullable();
            $table->timestamps();
        });

        // سجل افتراضي
        DB::table('mission_vision_values')->insert([
            'mission_title' => 'رسالتنا',
            'mission_description' => '<p>نسعى لتقديم أفضل الخدمات والمنتجات لعملائنا بجودة عالية واحترافية.</p>',
            'vision_title' => 'رؤيتنا',
            'vision_description' => '<p>أن نكون الخيار الأول والأفضل في مجالنا على المستوى المحلي والإقليمي.</p>',
            'values_title' => 'قيمنا',
            'values_description' => '<p>الجودة، الأمانة، الالتزام، والتميز في كل ما نقدمه لعملائنا.</p>',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('mission_vision_values');
    }
};
