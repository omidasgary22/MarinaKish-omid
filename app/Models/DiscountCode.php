<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'code',
        'discount_percentage',
        'quantity',
        'starts_at',
        'expires_at',
    ];

    protected $dates = ['start_at','expires_at'];
}
