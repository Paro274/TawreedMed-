<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'account_type',
        'message',
        'ip_address',
        'user_agent',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];
}
