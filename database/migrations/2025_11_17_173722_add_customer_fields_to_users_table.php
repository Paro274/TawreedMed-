<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->enum('role', ['customer', 'admin', 'supplier'])->default('customer')->after('phone');
            $table->tinyInteger('status')->default(1)->after('role');
            $table->timestamp('email_verified_at')->nullable()->change();
            $table->timestamp('last_login')->nullable()->after('email_verified_at');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'role', 'status', 'last_login']);
            $table->timestamp('email_verified_at')->nullable(false)->change();
        });
    }
};
