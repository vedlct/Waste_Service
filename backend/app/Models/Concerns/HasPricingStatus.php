<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * Shared behaviour for catalogue records that carry the `pricing_status` workflow.
 */
trait HasPricingStatus
{
    /**
     * @return array<int, string>
     */
    public static function pricingStatuses(): array
    {
        return array_keys(config('pricing.statuses', []));
    }

    public function scopeWithPricingStatus(Builder $query, string $status): Builder
    {
        return $query->where('pricing_status', $status);
    }

    /**
     * True when the status means the price still has to be reviewed before it is trusted.
     */
    public function needsPriceReview(): bool
    {
        return (bool) config('pricing.statuses.'.$this->pricing_status.'.warning', false);
    }

    public function pricingStatusLabel(): string
    {
        return config('pricing.statuses.'.$this->pricing_status.'.label')
            ?? str($this->pricing_status)->headline()->toString();
    }
}
