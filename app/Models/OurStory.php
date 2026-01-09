<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OurStory extends Model
{
    protected $table = 'our_story';
    
    protected $fillable = [
        'title',
        'description',
        'image'
    ];
}
