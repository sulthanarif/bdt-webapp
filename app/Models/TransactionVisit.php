<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'member_type_id',
        'visit_date',
        'gender',
        'qty',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'qty' => 'integer',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function memberType(): BelongsTo
    {
        return $this->belongsTo(MemberType::class);
    }
}
