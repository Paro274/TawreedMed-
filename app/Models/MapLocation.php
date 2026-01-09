<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MapLocation extends Model
{
    protected $table = 'map_location';
    
    protected $fillable = ['map_link', 'address'];
}
