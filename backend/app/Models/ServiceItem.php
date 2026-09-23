<?php

namespace App\Models;

use App\Models\Concerns\HasPricingStatus;
use App\Models\Concerns\LogsActivity;
use App\Support\Money;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'price_category_id',
    'image_id',
    'sku',
    'slug',
    'name',
    'description',
    'price_pence',
    'vat_rate_basis_points',
    'pricing_status',
    'is_active',
    'sort_order',
])]
class ServiceItem extends Model
{
    use LogsActivity;
    use HasPricingStatus;
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'price_pence' => 'integer',
            'vat_rate_basis_points' => 'integer',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function priceCategory(): BelongsTo
    {
        return $this->belongsTo(PriceCategory::class);
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'image_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    protected function priceExVatPence(): Attribute
    {
        return Attribute::get(fn (): ?int => Money::exVatPence($this->price_pence, $this->vat_rate_basis_points));
    }
}
