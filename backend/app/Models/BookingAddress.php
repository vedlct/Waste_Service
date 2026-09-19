<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'booking_id',
    'type',
    'first_name',
    'last_name',
    'company',
    'phone',
    'mobile',
    'email',
    'address_line_1',
    'address_line_2',
    'city',
    'county',
    'postcode',
    'country',
])]
class BookingAddress extends Model
{
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    protected function fullName(): Attribute
    {
        return Attribute::get(fn (): string => trim($this->first_name.' '.$this->last_name));
    }

    /**
     * @return array<int, string>
     */
    public function lines(): array
    {
        return array_values(array_filter([
            $this->address_line_1,
            $this->address_line_2,
            $this->city,
            $this->county,
            $this->postcode,
            $this->country,
        ]));
    }
}
