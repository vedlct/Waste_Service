<?php

namespace App\Models;

use App\Models\Concerns\HasPricingStatus;
use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'slug',
    'name',
    'description',
    'amount_pence',
    'is_variable',
    'charge_type',
    'pricing_status',
    'is_active',
    'sort_order',
])]
class ExtraCharge extends Model
{
    use LogsActivity;
    use HasPricingStatus;

    protected function casts(): array
    {
        return [
            'amount_pence' => 'integer',
            'is_variable' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
