<?php

namespace App\Support;

use App\Models\Booking;
use App\Models\ExtraCharge;
use App\Models\LoadPackage;
use App\Models\ServiceItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Turns a validated public booking submission into a stored booking.
 *
 * Prices are always read from the catalogue here. The cart lives in the browser's
 * localStorage, so anything it sends about money is ignored; only the catalogue id and
 * the quantity are trusted. Every stored line is a snapshot, so later catalogue edits
 * never rewrite an existing booking.
 */
class BookingBuilder
{
    /**
     * @param array<string, mixed> $data
     */
    public function build(array $data): Booking
    {
        $lines = $this->itemLines($data['items']);
        $lines = array_merge($lines, $this->automaticExtraCharges($data['collection']));
        $totals = $this->totals($lines);

        return DB::transaction(function () use ($data, $lines, $totals): Booking {
            $collection = $data['collection'];

            $booking = Booking::create([
                'reference' => BookingReference::generate(),
                'status' => $this->initialStatus($collection['payment_option']),
                'payment_status' => 'unpaid',
                'payment_option' => $collection['payment_option'],
                'collection_date' => $collection['collection_date'],
                'is_saturday_collection' => $collection['saturday_collection'],
                'notice_minutes' => $collection['notice_minutes'],
                'access_surcharge_acknowledged' => $collection['access_confirmed'],
                'restricted_access' => $collection['restricted_access'],
                'access_restrictions' => $collection['access_restrictions'] ?? null,
                'large_items' => $collection['large_items'] ?? null,
                'collection_notes' => $collection['collection_notes'] ?? null,
                'currency' => 'GBP',
                ...$totals,
            ]);

            $booking->fill($booking->workflowTimestamps($booking->status))->save();

            foreach ($lines as $line) {
                $booking->items()->create($line);
            }

            $booking->addresses()->create(['type' => 'billing'] + $data['billing']);

            if (! empty($data['collection_address'])) {
                $booking->addresses()->create(['type' => 'collection'] + $data['collection_address']);
            }

            return $booking->load(['items', 'billingAddress', 'collectionAddress']);
        });
    }

    /**
     * A pay-on-arrival job has nothing to pay online, so it is submitted straight away.
     * A pay-now job stays a draft until Phase 11 marks the payment successful.
     */
    private function initialStatus(string $paymentOption): string
    {
        return config('bookings.payment_options.'.$paymentOption.'.starts_submitted', false)
            ? 'submitted'
            : 'draft';
    }

    /**
     * @param array<int, array<string, mixed>> $items
     * @return array<int, array<string, mixed>>
     */
    private function itemLines(array $items): array
    {
        $lines = [];

        foreach ($items as $index => $item) {
            $lines[] = match ($item['type']) {
                'service_item' => $this->serviceItemLine($item, $index),
                'load_package' => $this->loadPackageLine($item, $index),
                default => throw ValidationException::withMessages([
                    "items.{$index}.type" => 'That item type cannot be booked.',
                ]),
            };
        }

        return $lines;
    }

    /**
     * @param array<string, mixed> $item
     * @return array<string, mixed>
     */
    private function serviceItemLine(array $item, int $index): array
    {
        $record = ServiceItem::query()->active()->find($item['id']);

        if (! $record) {
            throw ValidationException::withMessages([
                "items.{$index}.id" => 'That item is no longer available.',
            ]);
        }

        if ($record->pricing_status === 'quote_required') {
            throw ValidationException::withMessages([
                "items.{$index}.id" => $record->name.' is quoted per job and cannot be booked online.',
            ]);
        }

        return $this->line(
            lineType: 'service_item',
            keys: ['service_item_id' => $record->id],
            sku: $record->sku,
            name: $record->name,
            description: $record->description,
            unitPricePence: $record->price_pence,
            vatBasisPoints: $record->vat_rate_basis_points,
            quantity: $item['quantity'],
            metadata: ['pricing_status' => $record->pricing_status],
        );
    }

    /**
     * @param array<string, mixed> $item
     * @return array<string, mixed>
     */
    private function loadPackageLine(array $item, int $index): array
    {
        $record = LoadPackage::query()->active()->find($item['id']);

        if (! $record) {
            throw ValidationException::withMessages([
                "items.{$index}.id" => 'That load package is no longer available.',
            ]);
        }

        return $this->line(
            lineType: 'load_package',
            keys: ['load_package_id' => $record->id],
            sku: $record->slug,
            name: $record->name,
            description: null,
            unitPricePence: $record->price_inc_vat_pence,
            vatBasisPoints: $record->vat_rate_basis_points,
            quantity: $item['quantity'],
            metadata: [
                'pricing_status' => $record->pricing_status,
                'max_weight_kg' => $record->max_weight_kg,
                'volume_cubic_yards' => $record->volume_cubic_yards,
            ],
        );
    }

    /**
     * Surcharges the customer never picks directly: they follow from the collection choices.
     *
     * @param array<string, mixed> $collection
     * @return array<int, array<string, mixed>>
     */
    private function automaticExtraCharges(array $collection): array
    {
        $slugs = [];

        if ($collection['saturday_collection']) {
            $slugs[] = config('bookings.saturday_extra_charge_slug');
        }

        $slugs[] = config('bookings.payment_options.'.$collection['payment_option'].'.extra_charge_slug');

        $lines = [];

        foreach (array_filter($slugs) as $slug) {
            $charge = ExtraCharge::query()->active()->where('slug', $slug)->first();

            // A variable charge is quoted per job, so it never becomes a priced line.
            if (! $charge || $charge->is_variable || ! $charge->amount_pence) {
                continue;
            }

            $lines[] = $this->line(
                lineType: 'extra_charge',
                keys: ['extra_charge_id' => $charge->id],
                sku: $charge->slug,
                name: $charge->name,
                description: $charge->description,
                unitPricePence: $charge->amount_pence,
                vatBasisPoints: (int) config('pricing.default_vat_basis_points', 2000),
                quantity: 1,
                metadata: ['charge_type' => $charge->charge_type],
            );
        }

        return $lines;
    }

    /**
     * @param array<string, int> $keys
     * @param array<string, mixed> $metadata
     * @return array<string, mixed>
     */
    private function line(
        string $lineType,
        array $keys,
        ?string $sku,
        string $name,
        ?string $description,
        int $unitPricePence,
        int $vatBasisPoints,
        int $quantity,
        array $metadata = [],
    ): array {
        return $keys + [
            'line_type' => $lineType,
            'catalogue_sku' => $sku,
            'name' => $name,
            'description' => $description,
            'quantity' => $quantity,
            'unit_price_pence' => $unitPricePence,
            'vat_rate_basis_points' => $vatBasisPoints,
            'line_total_pence' => $unitPricePence * $quantity,
            'metadata' => $metadata ?: null,
        ];
    }

    /**
     * Catalogue prices are stored inclusive of VAT, so `subtotal_pence` and
     * `total_pence` are inc-VAT and `vat_pence` is the tax portion inside them.
     *
     * @param array<int, array<string, mixed>> $lines
     * @return array<string, int>
     */
    private function totals(array $lines): array
    {
        $subtotal = 0;
        $extras = 0;
        $vat = 0;

        foreach ($lines as $line) {
            $lineTotal = $line['line_total_pence'];
            $exVat = Money::exVatPence($lineTotal, $line['vat_rate_basis_points']) ?? $lineTotal;
            $vat += $lineTotal - $exVat;

            if ($line['line_type'] === 'extra_charge') {
                $extras += $lineTotal;
            } else {
                $subtotal += $lineTotal;
            }
        }

        return [
            'subtotal_pence' => $subtotal,
            'discount_pence' => 0,
            'extra_charges_pence' => $extras,
            'vat_pence' => $vat,
            'total_pence' => $subtotal + $extras,
        ];
    }
}
