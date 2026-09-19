<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A booking line is a snapshot. Names and prices are copied at booking time so historical
 * bookings survive catalogue changes, and the catalogue ids are kept only as a back link.
 */
#[Fillable([
    'booking_id',
    'service_item_id',
    'load_package_id',
    'extra_charge_id',
    'line_type',
    'catalogue_sku',
    'name',
    'description',
    'quantity',
    'unit_price_pence',
    'vat_rate_basis_points',
    'line_total_pence',
    'metadata',
])]
class BookingItem extends Model
{
    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_price_pence' => 'integer',
            'vat_rate_basis_points' => 'integer',
            'line_total_pence' => 'integer',
            'metadata' => 'array',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function serviceItem(): BelongsTo
    {
        return $this->belongsTo(ServiceItem::class);
    }

    public function loadPackage(): BelongsTo
    {
        return $this->belongsTo(LoadPackage::class);
    }

    public function extraCharge(): BelongsTo
    {
        return $this->belongsTo(ExtraCharge::class);
    }

    public function lineTypeLabel(): string
    {
        return config('bookings.line_types.'.$this->line_type)
            ?? str($this->line_type)->headline()->toString();
    }
}
