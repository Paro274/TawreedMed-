<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journey extends Model
{
    protected $table = 'journey';
    
    protected $fillable = [
        'year',
        'title',
        'description',
        'order'
    ];
    
    // إضافة scope للترتيب
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('year', 'asc');
    }
}
