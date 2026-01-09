<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'icon',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    // Scope للترتيب حسب الترتيب
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('id', 'asc');
    }

    // التحقق من صحة الأيقونة
    public function getIsValidIconAttribute()
    {
        $allIcons = collect(config('medical-icons', []))->flatten()->unique()->toArray();
        return in_array($this->icon, $allIcons);
    }
}
