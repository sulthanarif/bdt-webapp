<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentPage extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'body',
    ];

    public static function ensure(string $slug, string $title, string $body = ''): self
    {
        return static::firstOrCreate(
            ['slug' => $slug],
            ['title' => $title, 'body' => $body]
        );
    }
}
