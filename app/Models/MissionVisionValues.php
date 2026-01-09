<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MissionVisionValues extends Model
{
    protected $table = 'mission_vision_values';
    
    protected $fillable = [
        'mission_title',
        'mission_description',
        'vision_title',
        'vision_description',
        'values_title',
        'values_description'
    ];
}
