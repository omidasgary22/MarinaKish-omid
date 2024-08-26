<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscountCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'code',
        'discount_percentage',
        'quantity',
        'start_at',
        'expires_at',
    ];

    protected $dates = ['start_at','expires_at'];

    public function reservation()
    {
        return $this->hasMany(Reservation::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_discount_codes');
    }
}
