<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('id', 'asc');
    }
}
