<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_date',
        'sans_id',
        'user_id',
        'product_id',
        'discount_code_id',
        'total_amount',
        'discount_code_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sans()
    {
        return $this->belongsTo(Sans::class);
    }

    public function passengers()
    {
        return $this->belongsToMany(Passenger::class, 'reservation_passenger');
    }

    public function discountCode()
    {
        return $this->belongsTo(DiscountCode::class);
    }
}
