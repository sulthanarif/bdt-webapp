<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberTypeBenefit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'member_type_id',
        'label',
        'is_included',
        'order',
    ];

    protected $casts = [
        'is_included' => 'boolean',
        'order' => 'integer',
    ];

    public function memberType(): BelongsTo
    {
        return $this->belongsTo(MemberType::class);
    }
}
