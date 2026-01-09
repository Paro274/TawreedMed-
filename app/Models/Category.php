<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'product_type',
        'slug',
        'description',
        'image',
        'status'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            $category->slug = Str::slug($category->name);
        });
    }

    // العلاقات
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Scopes
    public function scopeOfType($query, $type)
    {
        return $query->where('product_type', $type);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
