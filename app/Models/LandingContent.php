<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
            'content' => config('landing.content'),
        ]);
    }
}

