<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreBookingRequest;
use App\Support\BookingBuilder;
use App\Support\WebsiteNotifier;

class BookingController extends Controller
{
    /**
     * Accepts a booking from the public cart and checkout.
     *
     * Prices are recomputed from the catalogue inside the builder; nothing about money in
     * the request body is trusted. Payment capture itself is Phase 11, so a pay-now
     * booking comes back as a draft awaiting payment.
     */
    public function store(StoreBookingRequest $request, BookingBuilder $builder)
    {
        $booking = $builder->build($request->bookingData());

        WebsiteNotifier::bookingReceived($booking);

        return response()->json([
            'ok' => true,
            'reference' => $booking->reference,
            'status' => $booking->status,
            'payment_status' => $booking->payment_status,
            'payment_option' => $booking->payment_option,
            'requires_payment' => $booking->status === 'draft',
            'totals' => [
                'currency' => $booking->currency,
                'subtotal_pence' => $booking->subtotal_pence,
                'extra_charges_pence' => $booking->extra_charges_pence,
                'vat_pence' => $booking->vat_pence,
                'total_pence' => $booking->total_pence,
            ],
            'lines' => $booking->items->map(fn ($item) => [
                'line_type' => $item->line_type,
                'name' => $item->name,
                'quantity' => $item->quantity,
                'unit_price_pence' => $item->unit_price_pence,
                'line_total_pence' => $item->line_total_pence,
            ])->all(),
        ], 201);
    }
}
