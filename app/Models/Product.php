<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'time',
        'age_limited',
        'total',
        'pending',
        'description',
        'started_at',
        'ended_at',
    ];
}
