<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'booking_id',
    'provider',
    'provider_reference',
    'status',
    'type',
    'currency',
    'amount_pence',
    'payload',
    'paid_at',
])]
class BookingPayment extends Model
{
    protected function casts(): array
    {
        return [
            'amount_pence' => 'integer',
            'payload' => 'array',
            'paid_at' => 'datetime',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
