<?php

namespace App\Support;

use App\Models\Booking;
use RuntimeException;

/**
 * Booking references are read out over the phone, so they stay short and use digits only
 * after the prefix and year/month block: MT-2609-4821.
 */
class BookingReference
{
    public static function generate(): string
    {
        $prefix = config('bookings.reference.prefix', 'MT');
        $digits = max(3, (int) config('bookings.reference.digits', 4));
        $attempts = max(1, (int) config('bookings.reference.max_attempts', 20));
        $period = now()->format('ym');
        $min = (int) str_pad('1', $digits, '0');
        $max = (int) str_repeat('9', $digits);

        for ($attempt = 0; $attempt < $attempts; $attempt++) {
            $reference = sprintf('%s-%s-%d', $prefix, $period, random_int($min, $max));

            if (! Booking::withTrashed()->where('reference', $reference)->exists()) {
                return $reference;
            }
        }

        throw new RuntimeException('Could not generate a unique booking reference after '.$attempts.' attempts.');
    }
}
