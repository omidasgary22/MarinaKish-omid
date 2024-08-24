<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone_number', 
        'national_code', 
        'code', 
        'expires_at'
    ];


    protected $dates = [
        'expires_at',
    ];
}
