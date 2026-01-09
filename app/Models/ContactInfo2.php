<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactInfo2 extends Model
{
    protected $table = 'contact_info2';
    
    protected $fillable = [
        'phone1_title', 'phone1',
        'phone2_title', 'phone2',
        'phone3_title', 'phone3',
        'email1_title', 'email1',
        'email2_title', 'email2',
        'email3_title', 'email3',
        'address_title', 'address_text'
    ];
}
