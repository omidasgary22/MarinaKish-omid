<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tickett extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'reservation_id',
        'purchase_time',
        'status',
        'user_info',
        'passenger_info',
        'sans_info',
        'ticket_count',
       'total_amount',
        //'discount_percent',
        'discount_amount',
        'final_amount',
    ];

    protected $casts = [
        'user_info' => 'array',
        'passenger_info' => 'array',
        'sans_info' => 'array',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
