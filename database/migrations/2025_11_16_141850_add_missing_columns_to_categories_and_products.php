<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // إضافة الأعمدة للـ categories بدون unique أولاً
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'slug')) {
                $table->string('slug')->nullable()->after('name');
            }
            if (!Schema::hasColumn('categories', 'description')) {
                $table->text('description')->nullable()->after('product_type');
            }
            if (!Schema::hasColumn('categories', 'image')) {
                $table->string('image')->nullable()->after('description');
            }
            if (!Schema::hasColumn('categories', 'status')) {
                $table->enum('status', ['active', 'inactive'])->default('active')->after('image');
            }
        });

        // إضافة الأعمدة للـ products بدون unique أولاً
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'slug')) {
                $table->string('slug')->nullable()->after('name');
            }
            if (!Schema::hasColumn('products', 'views')) {
                $table->integer('views')->default(0)->after('status');
            }
            if (!Schema::hasColumn('products', 'featured')) {
                $table->boolean('featured')->default(false)->after('views');
            }
        });

        // تحديث الـ slugs للتصنيفات
        $categories = DB::table('categories')->get();
        foreach ($categories as $category) {
            $slug = Str::slug($category->name);
            
            // التأكد من أن الـ slug فريد
            $count = 1;
            $originalSlug = $slug;
            while (DB::table('categories')->where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
            
            DB::table('categories')
                ->where('id', $category->id)
                ->update(['slug' => $slug]);
        }

        // تحديث الـ slugs للمنتجات
        $products = DB::table('products')->get();
        foreach ($products as $product) {
            $slug = Str::slug($product->name);
            
            // التأكد من أن الـ slug فريد
            $count = 1;
            $originalSlug = $slug;
            while (DB::table('products')->where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
            
            DB::table('products')
                ->where('id', $product->id)
                ->update(['slug' => $slug]);
        }

        // دلوقتي نضيف الـ unique constraint بعد ما كل الـ slugs بقت فريدة
        Schema::table('categories', function (Blueprint $table) {
            $table->unique('slug');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'slug')) {
                $table->dropUnique(['slug']);
                $table->dropColumn(['slug', 'description', 'image', 'status']);
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'slug')) {
                $table->dropUnique(['slug']);
                $table->dropColumn(['slug', 'views', 'featured']);
            }
        });
    }
};
