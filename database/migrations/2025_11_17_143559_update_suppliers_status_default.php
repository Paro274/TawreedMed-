<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    public function up(): void
    {
        // ✅ تحديث الـ default value للـ status إلى 1
        if (Schema::hasColumn('suppliers', 'status')) {
            if ($this->isMySql()) {
                DB::statement("ALTER TABLE suppliers MODIFY COLUMN status TINYINT(1) DEFAULT 1");
                Log::info('✅ تم تحديث default value للـ status إلى 1 (MySQL)');
            }
            
            $updated = DB::table('suppliers')
                        ->where('status', 0)
                        ->update(['status' => 1]);
            
            Log::info("✅ تم تحديث {$updated} مورد من معطل إلى مفعّل");
        }

        // ✅ تحديث باقي الحقول المهمة
        if (Schema::hasColumn('suppliers', 'email_verified_at')) {
            $updated = DB::table('suppliers')
                        ->whereNull('email_verified_at')
                        ->update(['email_verified_at' => now()]);
            Log::info("✅ تم تحديث {$updated} مورد بتاريخ التحقق");
        }

        if (Schema::hasColumn('suppliers', 'logo')) {
            $updated = DB::table('suppliers')
                        ->whereNull('logo')
                        ->orWhere('logo', '')
                        ->update(['logo' => 'default/logo.png']);
            Log::info("✅ تم تعيين اللوجو الافتراضي لـ {$updated} مورد");
        }
    }

    public function down(): void
    {
        // إرجاع الـ default إلى 0 (للـ rollback)
        if (Schema::hasColumn('suppliers', 'status') && $this->isMySql()) {
            DB::statement("ALTER TABLE suppliers MODIFY COLUMN status TINYINT(1) DEFAULT 0");
            Log::info('🔄 تم إرجاع default value للـ status إلى 0 (MySQL)');
        }
    }

    private function isMySql(): bool
    {
        return DB::getDriverName() === 'mysql';
    }
};
