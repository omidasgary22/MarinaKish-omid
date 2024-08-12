<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sans extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'start_time',
        'capacity',
    ];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
