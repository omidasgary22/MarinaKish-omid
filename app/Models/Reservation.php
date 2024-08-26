<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_date',
        'sans_id',
        'user_id',
        'product_id',
        'total_amount',
        'discount_code',
        'discount_amount', 
        'final_amount', 
        'status',
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

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function ticketts()
    {
        return $this->hasMany(Tickett::class);
    }


    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    // protected function isCode(): Attribute
    // {
    //     return new Attribute(
    //         get: fn() => 'jhhh',
    //     );
    // }
    // protected $appends = [
    //     'is_code'
    // ];
}
