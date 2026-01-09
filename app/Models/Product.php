<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'category_id',
        'product_type',
        'name',
        'price',
        'discount',
        'price_after_discount',
        'package_price',
        'company_name',
        'short_description',
        'full_description',
        'active_ingredient',
        'dosage_form',
        'tablets_per_pack',
        'production_date',
        'expiry_date',
        'package_type',
        'units_per_package',
        'min_order_quantity',
        'min_order_package',
        'total_units',
        'image_1',
        'image_2',
        'image_3',
        'image_4',
        'status',
        'slug',
        'views',
        'featured',
    ];

    protected $casts = [
        'active_ingredient'   => 'array',
        'price'               => 'decimal:2',
        'discount'            => 'decimal:2',
        'price_after_discount'=> 'decimal:2',
        'package_price'       => 'decimal:2',
        'production_date'     => 'date',
        'expiry_date'         => 'date',
        'views'               => 'integer',
        'featured'            => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });

        static::updating(function ($product) {
            $product->slug = Str::slug($product->name);
        });
    }

    // العلاقات
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // =========================================================
    // ✅ الحل السحري: دالة Accessor + دالة عادية بنفس الاسم
    // =========================================================

    /**
     * 1. Accessor: يتم استدعاؤه عبر $product->image_url
     */
    public function getImageUrlAttribute()
    {
        return $this->resolveImageUrl($this->image_1);
    }

    /**
     * 2. Method: يتم استدعاؤه عبر $product->getImageUrl()
     * هذا هو الحل لرسالة الخطأ اللي بتطلعلك
     */
    public function getImageUrl()
    {
        return $this->resolveImageUrl($this->image_1);
    }

    /**
     * دالة خاصة موحدة عشان منطق الصور ما يتكررش
     */
    private function resolveImageUrl($path)
    {
        if (empty($path)) {
            return asset('frontend/images/default-product.png'); 
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        if (File::exists(public_path($path))) {
            return asset($path);
        }

        if (Storage::disk('public')->exists($path)) {
            return asset('storage/' . $path);
        }

        if (Str::startsWith($path, 'uploads/')) {
            return asset($path);
        }

        return asset('storage/' . $path);
    }

    // دالة مساعدة لجلب أي صورة (1, 2, 3, 4)
    public function getSpecificImage($imageNumber = 1)
    {
        $field = 'image_' . $imageNumber;
        return $this->resolveImageUrl($this->$field);
    }

    // =========================================================

    // Accessors الأساسية
    public function getDiscountedPriceAttribute()
    {
        if ($this->discount && $this->discount > 0) {
            return $this->price - ($this->price * $this->discount / 100);
        }
        return $this->price;
    }

    public function getHasDiscountAttribute()
    {
        return $this->discount && $this->discount > 0;
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2) . ' ج.م';
    }

    public function getFormattedDiscountedPriceAttribute()
    {
        return number_format($this->discounted_price, 2) . ' ج.م';
    }

    public function getFinalPriceAttribute()
    {
        return $this->has_discount ? $this->discounted_price : $this->price;
    }

    // ✅ Accessor جديد لحساب سعر الباكيدج النهائي (بعد الخصم إذا وجد)
    public function getFinalPackagePriceAttribute()
    {
        // سعر الوحدة النهائي (سواء بخصم أو بدون)
        $unitPrice = $this->final_price;
        
        // عدد الوحدات في الباكيدج (لو مش محدد نعتبره 1)
        $units = $this->units_per_package && $this->units_per_package > 0 ? $this->units_per_package : 1;
        
        // الحسبة: السعر النهائي × عدد الوحدات
        return $unitPrice * $units;
    }

    public function getUnitTypeAttribute()
    {
        if ($this->package_type) return $this->package_type;
        if ($this->units_per_package && $this->units_per_package > 1) return 'كرتونة';
        
        switch ($this->product_type) {
            case 'أدوية': return 'علبة';
            case 'مستلزمات طبية': return $this->units_per_package >= 1 ? 'عبوة' : 'قطعة';
            case 'منتجات تجميل': return 'عبوة';
            default: return 'وحدة';
        }
    }

    public function getIsAvailableAttribute()
    {
        // "Unavailable" appears ONLY if the quantity is exactly 0.
        // If it's NULL or Negative, it's considered available (backorder allowed).
        return $this->total_units !== 0;
    }

    // Accessor لسعر الباكيدج
    public function getBoxInfoAttribute()
    {
        if (!$this->units_per_package) return null;
        
        $unitWord = $this->units_per_package > 1 ? 'وحدة' : 'وحدات';
        $packageType = $this->package_type ?: $this->unit_type;
        $minOrder = $this->min_order_quantity ?? 1;
        
        if ($minOrder > 1) {
            return "{$packageType} واحدة تحتوي على {$this->units_per_package} {$unitWord} - الحد الأدنى للطلب: {$minOrder} {$packageType}";
        }
        return "{$packageType} واحدة تحتوي على {$this->units_per_package} {$unitWord}";
    }

    // Scopes
    public function scopeMedicalSupplies($query)
    {
        return $query->where('product_type', 'مستلزمات طبية');
    }

    public function scopeAvailable($query)
    {
        return $query->where(function($q) {
            $q->where('total_units', '>', 0)
              ->orWhere(function($subQ) {
                  $subQ->where('product_type', 'مستلزمات طبية')->where('price', '>', 0);
              });
        });
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'مقبول');
    }

    public function scopeMedicines($query)
    {
        return $query->where('product_type', 'أدوية');
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('company_name', 'like', '%' . $search . '%')
                  ->orWhere('active_ingredient', 'like', '%' . $search . '%');
            });
        }
        return $query;
    }
}
