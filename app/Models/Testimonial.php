<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'rating',
        'review',
        'job_title',
        'governorate',
        'status',
        'order'
    ];

    protected $casts = [
        'status' => 'boolean',
        'rating' => 'integer',
        'order' => 'integer'
    ];

    // Scope للتقييمات النشطة فقط
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    // Scope لترتيب التقييمات
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('created_at', 'desc');
    }

    // Accessor لمسار الصورة
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }
        return asset($this->image);
    }
}
