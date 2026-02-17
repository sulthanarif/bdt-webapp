<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgendaEventRegistration extends Model
{
    protected $fillable = [
        'agenda_event_id',
        'member_id',
        'name',
        'email',
        'phone',
        'is_member',
        'member_identifier',
        'event_price',
        'answers',
    ];

    protected $casts = [
        'is_member' => 'boolean',
        'event_price' => 'integer',
        'answers' => 'array',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(AgendaEvent::class, 'agenda_event_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
