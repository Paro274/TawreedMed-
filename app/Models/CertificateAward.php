<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateAward extends Model
{
    protected $table = 'certificates_awards';
    
    protected $fillable = [
        'icon',
        'title',
        'description',
        'order'
    ];
    
    // إضافة scope للترتيب
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('id', 'asc');
    }
}
