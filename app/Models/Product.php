<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Product extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'name',
        'price',
        'time',
        'Discount percentage',
        'age_limited',
        'total',
        'pending',
        'off_suggestion',
        'description',
        'started_at',
        'ended_at',
        'tip',
    ];

    protected  $casts = [
        'name' => 'string',
        'price' => 'integer',
        'time' => 'integer',
        'Discount percentage' => 'integer',
        'age_limited' => 'integer',
        'total' => 'integer',
        'pending' => 'integer',
        'off_suggestion' => 'string',
        'description' => 'string',
        'started_at' => 'datetime:H:i',
        'ended_at' => 'datetime:H:i',
        'tip' => 'string',
    ];

    // public function registerMediaCollections(): void
    // {
    //     $this->addMediaCollection('image')->singeFile();
    // }



    public function comments()
    {
        return $this->hasMany(Comment::class);
    }


    public function sans()
    {
        return $this->hasMany(sans::class);
    }
}
