<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    public function up(): void
    {
        // ✅ 1. إضافة الأعمدة الأساسية واحدة واحدة مع التحقق
        $this->addColumnIfNotExists('logo', 'string', ['nullable' => true, 'after' => 'password']);
        $this->addColumnIfNotExists('status', 'tinyInteger', ['default' => 1, 'after' => 'logo']);
        $this->addIndexIfNotExists('status');
        
        $this->addColumnIfNotExists('last_login_at', 'timestamp', ['nullable' => true, 'after' => 'status']);
        $this->addColumnIfNotExists('email_verified_at', 'timestamp', ['nullable' => true, 'after' => 'phone']);
        $this->addColumnIfNotExists('remember_token', 'string', ['nullable' => true, 'after' => 'last_login_at', 'length' => 100]);
        
        // ✅ 2. الحقول الإضافية
        $this->addColumnIfNotExists('last_activity_at', 'timestamp', ['nullable' => true, 'after' => 'last_login_at']);
        $this->addColumnIfNotExists('login_count', 'integer', ['default' => 0, 'after' => 'last_activity_at']);
        $this->addColumnIfNotExists('last_login_ip', 'string', ['nullable' => true, 'after' => 'login_count']);
        $this->addColumnIfNotExists('created_by', 'string', ['nullable' => true, 'after' => 'last_login_ip']);
        
        // ✅ 3. الحقول الاختيارية
        $this->addColumnIfNotExists('city', 'string', ['nullable' => true, 'length' => 100, 'after' => 'logo']);
        $this->addColumnIfNotExists('description', 'text', ['nullable' => true, 'after' => 'city']);
        
        // ✅ 4. التعامل مع active column
        $this->handleActiveColumn();
        
        // ✅ 5. تحديث الـ default values
        $this->updateDefaultValues();
        
        Log::info('✅ تم تنفيذ migration suppliers بنجاح!');
    }

    /**
     * إضافة عمود إذا لم يكن موجوداً
     */
    private function addColumnIfNotExists(string $column, string $type, array $options = []): void
    {
        if (!Schema::hasColumn('suppliers', $column)) {
            Schema::table('suppliers', function (Blueprint $table) use ($column, $type, $options) {
                $columnDef = $table->$type($column);
                
                if (isset($options['nullable']) && $options['nullable']) {
                    $columnDef->nullable();
                }
                
                if (isset($options['default'])) {
                    $columnDef->default($options['default']);
                }
                
                if (isset($options['length'])) {
                    $columnDef->length($options['length']);
                }
                
                if (isset($options['after'])) {
                    $columnDef->after($options['after']);
                }
                
                Log::info("✅ تم إضافة عمود: {$column} ({$type})");
            });
        } else {
            Log::info("ℹ️ العمود موجود بالفعل: {$column}");
        }
    }

    /**
     * إضافة index إذا لم يكن موجوداً
     */
    private function addIndexIfNotExists(string $column): void
    {
        $indexName = "suppliers_{$column}_index";

        $hasIndex = match ($this->getDriver()) {
            'sqlite' => collect(DB::select("PRAGMA index_list('suppliers')"))
                ->pluck('name')
                ->contains($indexName),
            default => !empty(DB::select("SHOW INDEX FROM suppliers WHERE Key_name = ?", [$indexName])),
        };

        if ($hasIndex) {
            Log::info("ℹ️ الـ index موجود بالفعل للعمود: {$column}");
            return;
        }

        Schema::table('suppliers', function (Blueprint $table) use ($column) {
            $table->index($column);
            Log::info("✅ تم إضافة index للعمود: {$column}");
        });
    }

    /**
     * التعامل مع active column
     */
    private function handleActiveColumn(): void
    {
        if (Schema::hasColumn('suppliers', 'active')) {
            // لو active موجود و status مش موجود
            if (!Schema::hasColumn('suppliers', 'status')) {
                // أضف status وانسخ القيم
                $this->addColumnIfNotExists('status', 'tinyInteger', ['default' => 1, 'after' => 'logo']);
                DB::statement('UPDATE suppliers SET status = CASE WHEN active = 1 THEN 1 ELSE 0 END');
                Log::info('✅ تم تحويل active إلى status');
            }
            
            // امسح active في كل الحالات
            Schema::table('suppliers', function (Blueprint $table) {
                $table->dropColumn('active');
                Log::info('✅ تم إزالة عمود active');
            });
        }
    }

    /**
     * تحديث القيم الافتراضية
     */
    private function updateDefaultValues(): void
    {
        try {
            // تحديث status
            if (Schema::hasColumn('suppliers', 'status')) {
                DB::statement("UPDATE suppliers SET status = 1 WHERE status IS NULL OR status = 0");
                Log::info('✅ تم تحديث status الافتراضي');
            }

            // تحديث email_verified_at
            if (Schema::hasColumn('suppliers', 'email_verified_at')) {
                DB::statement("UPDATE suppliers SET email_verified_at = CURRENT_TIMESTAMP WHERE email_verified_at IS NULL");
                Log::info('✅ تم تحديث email_verified_at الافتراضي');
            }

            // تحديث login_count
            if (Schema::hasColumn('suppliers', 'login_count')) {
                DB::statement("UPDATE suppliers SET login_count = 0 WHERE login_count IS NULL");
                Log::info('✅ تم تحديث login_count الافتراضي');
            }

            // تحديث logo
            if (Schema::hasColumn('suppliers', 'logo')) {
                DB::statement("UPDATE suppliers SET logo = 'default/logo.png' WHERE logo IS NULL OR logo = ''");
                Log::info('✅ تم تعيين اللوجو الافتراضي');
            }

        } catch (\Exception $e) {
            Log::warning('تحذير في تحديث الـ default values: ' . $e->getMessage());
        }
    }

    public function down(): void
    {
        // ✅ Rollback آمن - إزالة الأعمدة الإضافية فقط
        $columnsToDrop = [
            'last_activity_at',
            'login_count',
            'last_login_ip',
            'created_by',
            'city',
            'description',
            'logo'
        ];

        foreach ($columnsToDrop as $column) {
            if (Schema::hasColumn('suppliers', $column)) {
                try {
                    Schema::table('suppliers', function (Blueprint $table) use ($column) {
                        $table->dropColumn($column);
                    });
                    Log::info("✅ تم إزالة عمود {$column} في الـ rollback");
                } catch (\Exception $e) {
                    Log::warning("تحذير: فشل في إزالة {$column}: " . $e->getMessage());
                }
            }
        }

        // ✅ إعادة إنشاء active إذا كان status موجود
        if (Schema::hasColumn('suppliers', 'status') && !Schema::hasColumn('suppliers', 'active')) {
            Schema::table('suppliers', function (Blueprint $table) {
                $table->boolean('active')->default(true)->after('logo');
            });
            DB::statement('UPDATE suppliers SET active = (status = 1)');
            Schema::table('suppliers', function (Blueprint $table) {
                $table->dropColumn('status');
            });
            Log::info('✅ تم إعادة إنشاء active من status');
        }

        // ✅ إزالة الحقول الأساسية بحذر
        $basicColumns = ['last_login_at', 'email_verified_at', 'remember_token'];
        foreach ($basicColumns as $column) {
            if (Schema::hasColumn('suppliers', $column)) {
                try {
                    Schema::table('suppliers', function (Blueprint $table) use ($column) {
                        $table->dropColumn($column);
                    });
                    Log::info("✅ تم إزالة {$column} في الـ rollback");
                } catch (\Exception $e) {
                    Log::warning("تحذير: فشل في إزالة {$column}: " . $e->getMessage());
                }
            }
        }
    }
    private function getDriver(): string
    {
        return DB::getDriverName();
    }
};
