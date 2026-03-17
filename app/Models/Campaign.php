<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'code',
        'discount_type',
        'discount_value',
        'valid_from',
        'valid_until',
        'is_active',
        'max_uses',
        'current_uses',
        'target_type',
        'target_items',
        'applicable_to',
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
        'target_items' => 'array',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->max_uses !== null && $this->current_uses >= $this->max_uses) {
            return false;
        }

        $now = now();
        if ($this->valid_from && $this->valid_from > $now) {
            return false;
        }

        if ($this->valid_until && $this->valid_until < $now) {
            return false;
        }

        return true;
    }

    public function calculateDiscount(int $originalPrice, int $itemId = 0): int
    {
        if (!$this->isValid()) {
            return 0;
        }

        if ($this->discount_type === 'duration') {
            return 0;
        }

        $discountValueToUse = $this->discount_value;
        if ($this->target_type !== 'any' && $itemId > 0 && is_array($this->target_items) && array_key_exists((string)$itemId, $this->target_items)) {
            $override = $this->target_items[(string)$itemId];
            if ($override !== null && $override !== '') {
                $discountValueToUse = (int) $override;
            }
        }

        if ($discountValueToUse === null) {
            return 0;
        }

        if ($this->discount_type === 'fixed') {
            return min($discountValueToUse, $originalPrice);
        }

        if ($this->discount_type === 'percentage') {
            $discount = (int) round($originalPrice * ($discountValueToUse / 100));
            return min($discount, $originalPrice);
        }

        return 0;
    }

    public function getDurationBonus(int $itemId = 0): int
    {
        if (!$this->isValid() || $this->discount_type !== 'duration') {
            return 0;
        }

        $bonusToUse = $this->discount_value;
        if ($this->target_type !== 'any' && $itemId > 0 && is_array($this->target_items) && array_key_exists((string)$itemId, $this->target_items)) {
            $override = $this->target_items[(string)$itemId];
            if ($override !== null && $override !== '') {
                $bonusToUse = (int) $override;
            }
        }

        return (int) ($bonusToUse ?? 0);
    }

    public static function getActiveAutoPromo(string $type = 'any', int $itemId = 0, bool $isRenewal = false): ?self
    {
        return self::whereNull('code')
            ->where('is_active', true)
            ->get()
            ->first(fn($campaign) => $campaign->isValidFor($type, $itemId, $isRenewal));
    }

    public function isValidFor(string $type, int $itemId, bool $isRenewal = false): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        if ($this->target_type !== 'any' && $this->target_type !== $type) {
            return false;
        }

        if ($this->target_type !== 'any' && is_array($this->target_items) && !empty($this->target_items)) {
            // target_items is an associative array: ["id" => "override_value", ...]
            if (!array_key_exists((string)$itemId, $this->target_items)) {
                return false;
            }
        }

        if ($type === 'membership') {
            if ($this->applicable_to === 'new_member' && $isRenewal) {
                return false;
            }
            if ($this->applicable_to === 'renewal' && !$isRenewal) {
                return false;
            }
        }

        // Untuk duration: pastikan ada bonus nyata (override per-plan atau global value > 0)
        if ($this->discount_type === 'duration' && $this->target_type !== 'any' && $itemId > 0) {
            if ($this->getDurationBonus($itemId) <= 0) {
                return false;
            }
        }

        return true;
    }
}
