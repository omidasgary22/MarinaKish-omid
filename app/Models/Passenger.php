<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Passenger extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'Name and surname',
        'gender',
        'age',
        'national_code',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
