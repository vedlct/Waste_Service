<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\ExtraCharge;
use App\Models\LoadPackage;
use App\Models\PriceCategory;
use App\Models\ServiceItem;
use App\Models\User;
use App\Support\BookingReference;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Tests\TestCase;

class BookingTest extends TestCase
{
    private function admin(string $token): User
    {
        return User::query()->updateOrCreate(
            ['email' => "booking-admin-{$token}@example.com"],
            ['name' => 'Booking Admin Test', 'password' => 'password', 'role' => 'admin', 'status' => 'active'],
        );
    }

    private function serviceItem(string $token, array $overrides = []): ServiceItem
    {
        $category = PriceCategory::query()->firstOrFail();

        return ServiceItem::query()->create(array_merge([
            'price_category_id' => $category->id,
            'sku' => "BOOK-SKU-{$token}",
            'slug' => "book-item-{$token}",
            'name' => "Bookable Item {$token}",
            'price_pence' => 7500,
            'vat_rate_basis_points' => 2000,
            'pricing_status' => 'confirmed',
            'is_active' => true,
            'sort_order' => 0,
        ], $overrides));
    }

    /**
     * @return array<string, string>
     */
    private function address(string $token, string $postcode = 'PO1 3AX'): array
    {
        return [
            'first_name' => 'Jane',
            'last_name' => "Smith {$token}",
            'company' => '',
            'phone' => '02012345678',
            'mobile' => '07700900123',
            'email' => "booker-{$token}@example.com",
            'address_line_1' => '14 Test Street',
            'address_line_2' => '',
            'city' => 'Portsmouth',
            'county' => 'Hampshire',
            'postcode' => $postcode,
            'country' => 'United Kingdom',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(string $token, ServiceItem $item, array $overrides = []): array
    {
        return array_replace_recursive([
            'items' => [
                ['type' => 'service_item', 'id' => $item->id, 'quantity' => 2],
            ],
            'collection' => [
                'collection_date' => Carbon::now()->next(Carbon::MONDAY)->toDateString(),
                'saturday_collection' => false,
                'notice_minutes' => 30,
                'payment_option' => 'arrival',
                'access_confirmed' => true,
                'restricted_access' => 'no',
                'access_restrictions' => null,
                'large_items' => 'One piano',
                'collection_notes' => 'Gate code 1234',
            ],
            'billing' => $this->address($token),
            'collection_address' => null,
        ], $overrides);
    }

    public function test_reference_generator_produces_a_readable_unique_reference(): void
    {
        $reference = BookingReference::generate();

        $this->assertMatchesRegularExpression('/^MT-\d{4}-\d{4}$/', $reference);
        $this->assertNotSame($reference, BookingReference::generate());
    }

    public function test_public_submit_prices_the_booking_from_the_catalogue(): void
    {
        $token = Str::uuid()->toString();
        $item = $this->serviceItem($token);

        $response = $this->postJson(route('api.v1.bookings.store'), $this->payload($token, $item))
            ->assertCreated()
            ->assertJson(['ok' => true, 'status' => 'submitted', 'requires_payment' => false]);

        $booking = Booking::query()->where('reference', $response->json('reference'))->firstOrFail();

        $this->assertSame('submitted', $booking->status);
        $this->assertSame('unpaid', $booking->payment_status);
        $this->assertSame('pay_on_arrival', $booking->payment_option);
        $this->assertNotNull($booking->submitted_at);
        $this->assertNull($booking->confirmed_at);

        // Two items at 75.00 plus the seeded 25.00 pay-on-arrival callout fee.
        $this->assertSame(15000, $booking->subtotal_pence);
        $this->assertSame(2500, $booking->extra_charges_pence);
        $this->assertSame(17500, $booking->total_pence);

        $line = $booking->items()->where('line_type', 'service_item')->firstOrFail();
        $this->assertSame(7500, $line->unit_price_pence);
        $this->assertSame(15000, $line->line_total_pence);
        $this->assertSame($item->sku, $line->catalogue_sku);

        $this->assertNotNull($booking->billingAddress);
        $this->assertNull($booking->collectionAddress);

        $booking->items()->delete();
        $booking->addresses()->delete();
        $booking->forceDelete();
        $item->forceDelete();
    }

    public function test_client_supplied_prices_are_ignored(): void
    {
        $token = Str::uuid()->toString();
        $item = $this->serviceItem($token);

        $payload = $this->payload($token, $item);
        $payload['items'][0]['unit_price_pence'] = 1;
        $payload['items'][0]['price'] = 0.01;
        $payload['total_pence'] = 1;

        $response = $this->postJson(route('api.v1.bookings.store'), $payload)->assertCreated();

        $booking = Booking::query()->where('reference', $response->json('reference'))->firstOrFail();

        // The catalogue price wins, not the number the browser sent.
        $this->assertSame(15000, $booking->subtotal_pence);
        $this->assertSame(7500, $booking->items()->where('line_type', 'service_item')->value('unit_price_pence'));

        $booking->items()->delete();
        $booking->addresses()->delete();
        $booking->forceDelete();
        $item->forceDelete();
    }

    public function test_pay_now_booking_stays_a_draft_until_payment(): void
    {
        $token = Str::uuid()->toString();
        $item = $this->serviceItem($token);

        $payload = $this->payload($token, $item);
        $payload['collection']['payment_option'] = 'now';

        $response = $this->postJson(route('api.v1.bookings.store'), $payload)
            ->assertCreated()
            ->assertJson(['status' => 'draft', 'requires_payment' => true]);

        $booking = Booking::query()->where('reference', $response->json('reference'))->firstOrFail();

        $this->assertSame('draft', $booking->status);
        $this->assertNull($booking->submitted_at);
        // No callout fee on a pay-now booking.
        $this->assertSame(0, $booking->extra_charges_pence);

        $booking->items()->delete();
        $booking->addresses()->delete();
        $booking->forceDelete();
        $item->forceDelete();
    }

    public function test_saturday_collection_adds_the_surcharge_and_requires_a_saturday(): void
    {
        $token = Str::uuid()->toString();
        $item = $this->serviceItem($token);

        $payload = $this->payload($token, $item);
        $payload['collection']['saturday_collection'] = true;

        // A Saturday collection on a Monday is rejected.
        $this->postJson(route('api.v1.bookings.store'), $payload)
            ->assertStatus(422)
            ->assertJsonValidationErrors('collection.collection_date');

        $payload['collection']['collection_date'] = Carbon::now()->next(Carbon::SATURDAY)->toDateString();

        $response = $this->postJson(route('api.v1.bookings.store'), $payload)->assertCreated();

        $booking = Booking::query()->where('reference', $response->json('reference'))->firstOrFail();
        $surcharge = ExtraCharge::query()->where('slug', 'saturday-collection')->firstOrFail();

        $this->assertTrue($booking->is_saturday_collection);
        $this->assertSame(
            $surcharge->amount_pence + 2500,
            $booking->extra_charges_pence,
            'Saturday surcharge and the pay-on-arrival callout fee should both be applied.',
        );

        $booking->items()->delete();
        $booking->addresses()->delete();
        $booking->forceDelete();
        $item->forceDelete();
    }

    public function test_quote_required_items_cannot_be_booked_online(): void
    {
        $token = Str::uuid()->toString();
        $item = $this->serviceItem($token, ['pricing_status' => 'quote_required', 'price_pence' => 0]);

        $this->postJson(route('api.v1.bookings.store'), $this->payload($token, $item))
            ->assertStatus(422)
            ->assertJsonValidationErrors('items.0.id');

        $item->forceDelete();
    }

    public function test_restricted_access_requires_a_description(): void
    {
        $token = Str::uuid()->toString();
        $item = $this->serviceItem($token);

        $payload = $this->payload($token, $item);
        $payload['collection']['restricted_access'] = 'yes';
        $payload['collection']['access_restrictions'] = null;

        $this->postJson(route('api.v1.bookings.store'), $payload)
            ->assertStatus(422)
            ->assertJsonValidationErrors('collection.access_restrictions');

        $item->forceDelete();
    }

    public function test_a_separate_collection_address_is_stored(): void
    {
        $token = Str::uuid()->toString();
        $item = $this->serviceItem($token);

        $payload = $this->payload($token, $item);
        $payload['collection_address'] = $this->address($token.'-collect', 'SW1A 1AA');

        $response = $this->postJson(route('api.v1.bookings.store'), $payload)->assertCreated();

        $booking = Booking::query()->where('reference', $response->json('reference'))->firstOrFail();

        $this->assertNotNull($booking->collectionAddress);
        $this->assertSame('SW1A 1AA', $booking->collectionAddress->postcode);
        $this->assertSame('PO1 3AX', $booking->billingAddress->postcode);

        $booking->items()->delete();
        $booking->addresses()->delete();
        $booking->forceDelete();
        $item->forceDelete();
    }

    public function test_load_packages_can_be_booked(): void
    {
        $token = Str::uuid()->toString();
        $package = LoadPackage::query()->active()->firstOrFail();

        $item = $this->serviceItem($token);
        $payload = $this->payload($token, $item);
        $payload['items'] = [['type' => 'load_package', 'id' => $package->id, 'quantity' => 1]];

        $response = $this->postJson(route('api.v1.bookings.store'), $payload)->assertCreated();

        $booking = Booking::query()->where('reference', $response->json('reference'))->firstOrFail();
        $line = $booking->items()->where('line_type', 'load_package')->firstOrFail();

        $this->assertSame($package->id, $line->load_package_id);
        $this->assertSame($package->price_inc_vat_pence, $line->unit_price_pence);

        $booking->items()->delete();
        $booking->addresses()->delete();
        $booking->forceDelete();
        $item->forceDelete();
    }

    public function test_booking_submit_honeypot_is_accepted_silently(): void
    {
        $token = Str::uuid()->toString();
        $item = $this->serviceItem($token);

        $payload = $this->payload($token, $item);
        $payload[config('bookings.submit.honeypot_field')] = 'https://spam.example.com';

        $this->postJson(route('api.v1.bookings.store'), $payload)
            ->assertOk()
            ->assertExactJson(['ok' => true]);

        $this->assertSame(0, Booking::query()->whereHas('billingAddress', fn ($query) => $query->where('email', "booker-{$token}@example.com"))->count());

        $item->forceDelete();
    }

    public function test_admin_can_view_the_booking_list_and_detail(): void
    {
        $token = Str::uuid()->toString();
        $admin = $this->admin($token);
        $item = $this->serviceItem($token);

        $response = $this->postJson(route('api.v1.bookings.store'), $this->payload($token, $item))->assertCreated();
        $booking = Booking::query()->where('reference', $response->json('reference'))->firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.bookings.index'))
            ->assertOk()
            ->assertSee('Manage bookings');

        $this->actingAs($admin)
            ->getJson(route('admin.bookings.data', ['search' => ['value' => $booking->reference]]))
            ->assertOk()
            ->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data'])
            ->assertSee($booking->reference);

        $this->actingAs($admin)
            ->get(route('admin.bookings.show', $booking))
            ->assertOk()
            ->assertSee($booking->reference)
            ->assertSee('Booking lines')
            ->assertSee('Gate code 1234')
            ->assertSee('One piano');

        $booking->items()->delete();
        $booking->addresses()->delete();
        $booking->forceDelete();
        $item->forceDelete();
    }

    public function test_out_of_area_postcode_warns_on_the_detail_screen_without_blocking_submit(): void
    {
        $token = Str::uuid()->toString();
        $admin = $this->admin($token);
        $item = $this->serviceItem($token);

        $payload = $this->payload($token, $item);
        $payload['billing']['postcode'] = 'ZZ99 9ZZ';

        $response = $this->postJson(route('api.v1.bookings.store'), $payload)->assertCreated();
        $booking = Booking::query()->where('reference', $response->json('reference'))->firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.bookings.show', $booking))
            ->assertOk()
            ->assertSee('outside the listed coverage areas');

        $booking->items()->delete();
        $booking->addresses()->delete();
        $booking->forceDelete();
        $item->forceDelete();
    }

    public function test_status_drives_the_workflow_timestamps_and_notes_are_saved(): void
    {
        $token = Str::uuid()->toString();
        $admin = $this->admin($token);
        $item = $this->serviceItem($token);

        $payload = $this->payload($token, $item);
        $payload['collection']['payment_option'] = 'now';

        $response = $this->postJson(route('api.v1.bookings.store'), $payload)->assertCreated();
        $booking = Booking::query()->where('reference', $response->json('reference'))->firstOrFail();

        $this->assertNull($booking->submitted_at);

        $this->actingAs($admin)
            ->put(route('admin.bookings.update', $booking), [
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'admin_notes' => 'Customer called to confirm the gate code.',
            ])
            ->assertRedirect(route('admin.bookings.show', $booking))
            ->assertSessionHas('success', 'Booking updated successfully.');

        $booking->refresh();

        $this->assertSame('confirmed', $booking->status);
        $this->assertSame('paid', $booking->payment_status);
        $this->assertNotNull($booking->submitted_at);
        $this->assertNotNull($booking->confirmed_at);
        $this->assertSame('Customer called to confirm the gate code.', $booking->adminNotes());

        // Dropping back to draft clears both stamps.
        $this->actingAs($admin)
            ->put(route('admin.bookings.update', $booking), [
                'status' => 'draft',
                'payment_status' => 'unpaid',
                'admin_notes' => '',
            ])
            ->assertRedirect(route('admin.bookings.show', $booking));

        $booking->refresh();

        $this->assertNull($booking->submitted_at);
        $this->assertNull($booking->confirmed_at);
        $this->assertNull($booking->adminNotes());

        $booking->items()->delete();
        $booking->addresses()->delete();
        $booking->forceDelete();
        $item->forceDelete();
    }

    public function test_cancelling_keeps_the_existing_timestamps(): void
    {
        $token = Str::uuid()->toString();
        $admin = $this->admin($token);
        $item = $this->serviceItem($token);

        $response = $this->postJson(route('api.v1.bookings.store'), $this->payload($token, $item))->assertCreated();
        $booking = Booking::query()->where('reference', $response->json('reference'))->firstOrFail();

        $submittedAt = $booking->submitted_at;
        $this->assertNotNull($submittedAt);

        $this->actingAs($admin)
            ->put(route('admin.bookings.update', $booking), [
                'status' => 'cancelled',
                'payment_status' => 'refunded',
            ])
            ->assertRedirect(route('admin.bookings.show', $booking));

        $booking->refresh();

        $this->assertSame('cancelled', $booking->status);
        $this->assertSame($submittedAt->toDateTimeString(), $booking->submitted_at->toDateTimeString());

        $booking->items()->delete();
        $booking->addresses()->delete();
        $booking->forceDelete();
        $item->forceDelete();
    }

    public function test_booking_delete_is_a_soft_delete(): void
    {
        $token = Str::uuid()->toString();
        $admin = $this->admin($token);
        $item = $this->serviceItem($token);

        $response = $this->postJson(route('api.v1.bookings.store'), $this->payload($token, $item))->assertCreated();
        $booking = Booking::query()->where('reference', $response->json('reference'))->firstOrFail();

        $this->actingAs($admin)
            ->delete(route('admin.bookings.destroy', $booking))
            ->assertRedirect(route('admin.bookings.index'));

        $this->assertSoftDeleted('bookings', ['id' => $booking->id]);

        $booking->items()->delete();
        $booking->addresses()->delete();
        $booking->forceDelete();
        $item->forceDelete();
    }
}
