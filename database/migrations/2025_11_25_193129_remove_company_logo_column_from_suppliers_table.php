<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, migrate any data from company_logo to logo
        if (Schema::hasColumn('suppliers', 'company_logo') && Schema::hasColumn('suppliers', 'logo')) {
            // Copy company_logo data to logo where logo is null
            \DB::statement("UPDATE suppliers SET logo = company_logo WHERE (logo IS NULL OR logo = '') AND company_logo IS NOT NULL AND company_logo != ''");
            
            // Now drop the company_logo column
            Schema::table('suppliers', function (Blueprint $table) {
                $table->dropColumn('company_logo');
            });
            
            \Log::info('✅ تم نقل البيانات من company_logo إلى logo وحذف عمود company_logo');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-add company_logo column if needed for rollback
        if (!Schema::hasColumn('suppliers', 'company_logo')) {
            Schema::table('suppliers', function (Blueprint $table) {
                $table->string('company_logo')->nullable()->after('logo');
            });
            
            \Log::info('✅ تم إعادة إنشاء عمود company_logo');
        }
    }
};
