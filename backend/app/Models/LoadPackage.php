<?php

namespace App\Models;

use App\Models\Concerns\HasPricingStatus;
use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'slug',
    'name',
    'price_inc_vat_pence',
    'price_ex_vat_pence',
    'vat_rate_basis_points',
    'max_weight_kg',
    'volume_cubic_yards',
    'sack_equivalent',
    'loading_time_minutes',
    'pricing_status',
    'is_popular',
    'is_active',
    'sort_order',
])]
class LoadPackage extends Model
{
    use LogsActivity;
    use HasPricingStatus;
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'price_inc_vat_pence' => 'integer',
            'price_ex_vat_pence' => 'integer',
            'vat_rate_basis_points' => 'integer',
            'max_weight_kg' => 'integer',
            'volume_cubic_yards' => 'decimal:2',
            'sack_equivalent' => 'integer',
            'loading_time_minutes' => 'integer',
            'is_popular' => 'boolean',
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
