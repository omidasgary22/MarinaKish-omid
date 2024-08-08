<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;

class Blog extends Model implements HasMedia
{
    use HasFactory, SoftDeletes,InteractsWithMedia;


    protected $fillable = [
        'title',
        'summary',
        'content',
        'duration_of_study'
    ];

    protected $casts = [
        'title' => 'string',
        'summary' => 'string',
        'content' => 'string',
        'duration_of_study' => 'integer',
        'deleted_at' => 'datetime',
        'craeted_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    
}
