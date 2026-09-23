<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\ContactEnquiry;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminSearchTest extends TestCase
{
    private Booking $booking;

    protected function setUp(): void
    {
        parent::setUp();

        $this->booking = Booking::query()->create([
            'reference' => 'MT-2609-7777',
            'status' => 'submitted',
            'payment_status' => 'unpaid',
            'payment_option' => 'pay_on_arrival',
            'collection_date' => now()->addDays(2)->toDateString(),
            'total_pence' => 9500,
        ]);

        $this->booking->addresses()->create([
            'type' => 'billing',
            'first_name' => 'Priya',
            'last_name' => 'Chowdhury',
            'email' => 'priya.chowdhury@example.com',
            'phone' => '020 8226 6477',
            'mobile' => '07700 900123',
            'address_line_1' => '14 Harbour Road',
            'city' => 'Portsmouth',
            'postcode' => 'PO1 3AX',
            'country' => 'United Kingdom',
        ]);
    }

    private function found(string $term): bool
    {
        return Booking::query()->search($term)->whereKey($this->booking->id)->exists();
    }

    public function test_a_booking_is_found_by_what_a_customer_quotes_on_the_phone(): void
    {
        $this->assertTrue($this->found('MT-2609-7777'), 'reference');
        $this->assertTrue($this->found('Priya Chowdhury'), 'full name');
        $this->assertTrue($this->found('chowdhury'), 'surname, any case');
        $this->assertTrue($this->found('priya.chowdhury@example.com'), 'email');
        $this->assertTrue($this->found('Harbour Road'), 'first address line');
    }

    public function test_postcodes_match_with_or_without_the_space(): void
    {
        $this->assertTrue($this->found('PO1 3AX'));
        $this->assertTrue($this->found('po13ax'));
        $this->assertTrue($this->found('PO1'));
    }

    public function test_phone_numbers_match_in_any_format(): void
    {
        $this->assertTrue($this->found('02082266477'), 'landline without spaces');
        $this->assertTrue($this->found('020-8226-6477'), 'landline with dashes');
        $this->assertTrue($this->found('07700900123'), 'mobile');
    }

    public function test_unrelated_terms_find_nothing(): void
    {
        $this->assertFalse($this->found('Nobody Here'));
        $this->assertFalse($this->found('SW1A'));
        $this->assertFalse($this->found('0799'), 'a short digit run that is not in either number');
    }

    public function test_the_bookings_list_uses_the_search(): void
    {
        $admin = User::query()->create([
            'name' => 'Search Admin',
            'email' => 'search-admin-'.Str::uuid()->toString().'@example.com',
            'password' => 'password',
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->actingAs($admin)
            ->getJson(route('admin.bookings.data', ['search' => ['value' => 'po13ax']]))
            ->assertOk()
            ->assertSee('MT-2609-7777');

        $this->actingAs($admin)
            ->getJson(route('admin.bookings.data', ['search' => ['value' => 'no such customer']]))
            ->assertOk()
            ->assertDontSee('MT-2609-7777');
    }

    public function test_enquiries_are_found_by_phone_and_email(): void
    {
        $admin = User::query()->create([
            'name' => 'Search Admin',
            'email' => 'search-admin-'.Str::uuid()->toString().'@example.com',
            'password' => 'password',
            'role' => 'admin',
            'status' => 'active',
        ]);

        ContactEnquiry::query()->create([
            'name' => 'Omar Siddiqui',
            'email' => 'omar@example.com',
            'phone' => '+44 7700 900456',
            'service_label' => 'Garden clearance',
            'message' => 'Hedge cuttings to collect.',
            'status' => 'new',
            'source' => 'website',
        ]);

        foreach (['7700900456', 'omar@example.com', 'hedge cuttings'] as $term) {
            $this->actingAs($admin)
                ->getJson(route('admin.enquiries.data', ['scope' => '', 'search' => ['value' => $term]]))
                ->assertOk()
                ->assertSee('Omar Siddiqui');
        }

        $this->actingAs($admin)
            ->getJson(route('admin.enquiries.data', ['scope' => '', 'search' => ['value' => 'somebody else']]))
            ->assertOk()
            ->assertDontSee('Omar Siddiqui');
    }
}
