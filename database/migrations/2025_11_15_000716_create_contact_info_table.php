<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contact_info', function (Blueprint $table) {
            $table->id();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        // سجل افتراضي
        DB::table('contact_info')->insert([
            'phone' => '01000000000',
            'email' => 'info@example.com',
            'address' => 'القاهرة، مصر',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('contact_info');
    }
};
