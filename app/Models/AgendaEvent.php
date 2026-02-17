<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AgendaEvent extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'category_label',
        'category_style',
        'status_label',
        'image_path',
        'image_url',
        'image_alt',
        'date_label',
        'location_label',
        'description',
        'cta_label',
        'cta_url',
        'cta_style',
        'use_internal_registration',
        'registration_fields',
        'price',
        'starts_at',
        'ends_at',
        'order',
        'is_active',
        'is_finished',
    ];

    protected $casts = [
        'price' => 'integer',
        'order' => 'integer',
        'is_active' => 'boolean',
        'is_finished' => 'boolean',
        'use_internal_registration' => 'boolean',
        'registration_fields' => 'array',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function registrations(): HasMany
    {
        return $this->hasMany(AgendaEventRegistration::class);
    }
}
