<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'number', 'order',
        'title2', 'number2',
        'title3', 'number3',
        'title4', 'number4',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    // Scope للترتيب حسب الترتيب
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('id', 'asc');
    }

    // جلب جميع الإحصائيات
    public static function getAllStats()
    {
        $stats = self::ordered()->get();
        $allStats = [];
        
        foreach ($stats as $stat) {
            $allStats[] = [
                'title' => $stat->title,
                'number' => $stat->number,
                'order' => $stat->order,
            ];
            
            // إضافة الإحصائيات الإضافية إذا كانت موجودة
            if ($stat->title2) {
                $allStats[] = [
                    'title' => $stat->title2,
                    'number' => $stat->number2,
                    'order' => $stat->order + 1,
                ];
            }
            
            if ($stat->title3) {
                $allStats[] = [
                    'title' => $stat->title3,
                    'number' => $stat->number3,
                    'order' => $stat->order + 2,
                ];
            }
            
            if ($stat->title4) {
                $allStats[] = [
                    'title' => $stat->title4,
                    'number' => $stat->number4,
                    'order' => $stat->order + 3,
                ];
            }
        }
        
        // ترتيب حسب الـ order
        return collect($allStats)->sortBy('order')->values();
    }
}
