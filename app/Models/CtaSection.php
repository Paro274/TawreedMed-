<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CtaSection extends Model
{
    protected $fillable = [
        'title',
        'description',
        'button1_text',
        'button1_link',
        'button2_text',
        'button2_link'
    ];
}
