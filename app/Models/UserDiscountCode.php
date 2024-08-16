<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDiscountCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'discount_code_id',
    ];
}
