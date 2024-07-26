<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use HasFactory, SoftDeletes;


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
