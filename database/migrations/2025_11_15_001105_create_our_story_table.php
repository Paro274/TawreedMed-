<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('our_story', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // سجل افتراضي
        DB::table('our_story')->insert([
            'title' => 'قصتنا',
            'description' => '<p>هذا هو وصف قصتنا. يمكنك استخدام المحرر لإضافة نصوص منسقة وجميلة.</p>',
            'image' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('our_story');
    }
};
