<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForgetVerificationCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone_number',
        'code',
        'expires_at',
    ];

    public $timestamps = true;
}
