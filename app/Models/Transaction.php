<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'member_id',
        'created_by',
        'customer_name',
        'customer_email',
        'customer_phone',
        'invoice_id',
        'status',
        'amount_total',
        'currency',
        'channel',
        'payment_method',
        'payment_reference',
        'paid_at',
        'expires_at',
        'gateway_payload',
    ];

    protected $casts = [
        'amount_total' => 'integer',
        'paid_at' => 'datetime',
        'expires_at' => 'datetime',
        'gateway_payload' => 'array',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function membershipItems(): HasMany
    {
        return $this->hasMany(TransactionMembership::class);
    }

    public function visitItems(): HasMany
    {
        return $this->hasMany(TransactionVisit::class);
    }
}
