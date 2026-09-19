<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'coverage_region_id',
    'slug',
    'name',
    'postcode_prefix',
    'latitude',
    'longitude',
    'is_featured',
    'is_active',
    'sort_order',
])]
class CoverageArea extends Model
{
    use LogsActivity;

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(CoverageRegion::class, 'coverage_region_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Matches a customer postcode against the stored outward-code prefix.
     * Used later by the booking serviceability check.
     */
    public function scopeMatchingPostcode(Builder $query, string $postcode): Builder
    {
        return $query->whereNotNull('postcode_prefix')
            ->where('postcode_prefix', static::outwardCode($postcode));
    }

    /**
     * The outward code (district) of a UK postcode: "PO16 7AB" and "po167ab" both give
     * "PO16". Matched exactly, because a prefix match would let PO1 claim PO16 and PO19.
     * The inward code is always three characters, so anything longer than four is split.
     */
    public static function outwardCode(string $postcode): string
    {
        $normalised = strtoupper(preg_replace('/\s+/', '', $postcode) ?? '');

        return strlen($normalised) > 4 ? substr($normalised, 0, -3) : $normalised;
    }

    protected function hasCoordinates(): Attribute
    {
        return Attribute::get(fn (): bool => $this->latitude !== null && $this->longitude !== null);
    }

    protected function mapUrl(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->has_coordinates
            ? 'https://www.google.com/maps/search/?api=1&query='.$this->latitude.','.$this->longitude
            : null);
    }
}
