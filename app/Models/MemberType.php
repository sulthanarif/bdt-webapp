<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'label',
        'accent_color',
        'is_full_color',
        'pricing',
        'duration_days',
        'is_student',
        'is_daily',
        'is_active',
        'order',
    ];

    protected $casts = [
        'pricing' => 'integer',
        'duration_days' => 'integer',
        'is_student' => 'boolean',
        'is_daily' => 'boolean',
        'is_active' => 'boolean',
        'is_full_color' => 'boolean',
        'order' => 'integer',
    ];

    public function benefits(): HasMany
    {
        return $this->hasMany(MemberTypeBenefit::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    public function transactionMemberships(): HasMany
    {
        return $this->hasMany(TransactionMembership::class);
    }

    public function transactionVisits(): HasMany
    {
        return $this->hasMany(TransactionVisit::class);
    }
}
