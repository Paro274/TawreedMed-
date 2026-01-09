<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            // إضافة عمود السوبر أدمن (افتراضياً لا)
            $table->boolean('is_super_admin')->default(false)->after('password');
            
            // إضافة عمود الصلاحيات (JSON لتخزين مصفوفة الصلاحيات)
            $table->json('permissions')->nullable()->after('is_super_admin');
        });
    }

    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn(['is_super_admin', 'permissions']);
        });
    }
};
