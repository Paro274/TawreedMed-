<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('map_location', function (Blueprint $table) {
            $table->id();
            $table->text('map_link')->nullable(); // رابط Google Maps
            $table->text('address')->nullable(); // العنوان النصي (اختياري)
            $table->timestamps();
        });

        DB::table('map_location')->insert([
            'map_link' => 'https://maps.app.goo.gl/VPRmkKaQV5T6ABTSA',
            'address' => 'القاهرة، مصر',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('map_location');
    }
};
