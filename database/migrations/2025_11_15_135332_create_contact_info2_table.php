<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contact_info2', function (Blueprint $table) {
            $table->id();
            // أرقام التليفون
            $table->string('phone1_title')->nullable();
            $table->string('phone1')->nullable();
            $table->string('phone2_title')->nullable();
            $table->string('phone2')->nullable();
            $table->string('phone3_title')->nullable();
            $table->string('phone3')->nullable();
            // الإيميلات
            $table->string('email1_title')->nullable();
            $table->string('email1')->nullable();
            $table->string('email2_title')->nullable();
            $table->string('email2')->nullable();
            $table->string('email3_title')->nullable();
            $table->string('email3')->nullable();
            // العنوان
            $table->string('address_title')->nullable();
            $table->longText('address_text')->nullable(); // نص بمحرر TinyMCE
            $table->timestamps();
        });

        // سجل افتراضي
        DB::table('contact_info2')->insert([
            'phone1_title' => 'خدمة العملاء',
            'phone1' => '01000000000',
            'phone2_title' => 'المبيعات',
            'phone2' => '01000000001',
            'phone3_title' => 'الدعم الفني',
            'phone3' => '01000000002',
            'email1_title' => 'البريد الرئيسي',
            'email1' => 'info@example.com',
            'email2_title' => 'المبيعات',
            'email2' => 'sales@example.com',
            'email3_title' => 'الدعم',
            'email3' => 'support@example.com',
            'address_title' => 'العنوان',
            'address_text' => '<p>القاهرة، مصر</p>',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('contact_info2');
    }
};
