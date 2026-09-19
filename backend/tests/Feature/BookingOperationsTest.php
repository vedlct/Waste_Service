<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use App\Support\CsvCell;
use Illuminate\Support\Str;
use Tests\TestCase;

class BookingOperationsTest extends TestCase
{
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::query()->create([
            'name' => 'Ops Admin',
            'email' => 'ops-admin-'.Str::uuid()->toString().'@example.com',
            'password' => 'password',
            'role' => 'admin',
            'status' => 'active',
        ]);
    }

    private function booking(string $reference, array $attributes = [], array $billing = []): Booking
    {
        $booking = Booking::query()->create(array_merge([
            'reference' => $reference,
            'status' => 'confirmed',
            'payment_status' => 'unpaid',
            'payment_option' => 'pay_on_arrival',
            'collection_date' => '2026-10-05',
            'notice_minutes' => 30,
            'total_pence' => 17500,
        ], $attributes));

        $booking->addresses()->create(array_merge([
            'type' => 'billing',
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@example.com',
            'phone' => '020 8226 6477',
            'address_line_1' => '14 Harbour Road',
            'city' => 'Portsmouth',
            'postcode' => 'PO1 3AX',
        ], $billing));

        $booking->items()->create([
            'line_type' => 'service_item', 'name' => '3 Seat Sofa', 'quantity' => 2,
            'unit_price_pence' => 7500, 'vat_rate_basis_points' => 2000, 'line_total_pence' => 15000,
        ]);
        $booking->items()->create([
            'line_type' => 'extra_charge', 'name' => 'Pay-on-arrival callout fee', 'quantity' => 1,
            'unit_price_pence' => 2500, 'vat_rate_basis_points' => 2000, 'line_total_pence' => 2500,
        ]);

        return $booking;
    }

    public function test_the_day_sheet_lists_that_days_jobs_for_the_crew(): void
    {
        $this->booking('MT-2610-1111', [
            'restricted_access' => 'yes',
            'access_restrictions' => 'Third floor, no lift',
            'collection_notes' => 'Gate code 4321',
        ]);
        $this->booking('MT-2610-2222', ['status' => 'cancelled']);
        $this->booking('MT-2610-3333', ['status' => 'draft']);
        $this->booking('MT-2610-4444', ['collection_date' => '2026-10-06']);

        $this->actingAs($this->admin)
            ->get(route('admin.bookings.day-sheet', ['date' => '2026-10-05']))
            ->assertOk()
            ->assertSee('Monday 5 October 2026')
            ->assertSee('MT-2610-1111')
            ->assertSee('2 x 3 Seat Sofa')
            ->assertDontSee('Pay-on-arrival callout fee', false)
            ->assertSee('Third floor, no lift')
            ->assertSee('Gate code 4321')
            ->assertSee('Collect £175.00')
            ->assertDontSee('MT-2610-2222')
            ->assertDontSee('MT-2610-3333')
            ->assertDontSee('MT-2610-4444');
    }

    public function test_a_paid_booking_shows_nothing_to_collect(): void
    {
        $this->booking('MT-2610-5555', ['payment_status' => 'paid']);

        $this->actingAs($this->admin)
            ->get(route('admin.bookings.day-sheet', ['date' => '2026-10-05']))
            ->assertOk()
            ->assertSee('MT-2610-5555')
            ->assertDontSee('Collect £');
    }

    public function test_the_export_follows_the_list_filters(): void
    {
        $this->booking('MT-2610-6666');
        $this->booking('MT-2610-7777', ['status' => 'submitted']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.bookings.export', ['status' => 'confirmed']))
            ->assertOk()
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        $csv = $response->streamedContent();

        $this->assertStringStartsWith("\xEF\xBB\xBF", $csv, 'Excel needs the BOM to read the pound sign.');
        $this->assertStringContainsString('MT-2610-6666', $csv);
        $this->assertStringNotContainsString('MT-2610-7777', $csv);
        $this->assertStringContainsString('175.00', $csv);
    }

    public function test_the_export_neutralises_spreadsheet_formulas(): void
    {
        $this->booking('MT-2610-8888', [], ['first_name' => '=HYPERLINK("https://evil.example","Click")', 'last_name' => '']);

        $csv = $this->actingAs($this->admin)
            ->get(route('admin.bookings.export'))
            ->assertOk()
            ->streamedContent();

        $this->assertStringContainsString("'=HYPERLINK", $csv);
        $this->assertStringNotContainsString(',"=HYPERLINK', $csv);

        $this->assertSame("'=1+1", CsvCell::safe('=1+1'));
        $this->assertSame("'+44 7700", CsvCell::safe('+44 7700'));
        $this->assertSame("'@SUM(A1)", CsvCell::safe('@SUM(A1)'));
        $this->assertSame('Jane Smith', CsvCell::safe('Jane Smith'));
        $this->assertSame('', CsvCell::safe(null));
    }

    public function test_editors_cannot_reach_the_day_sheet_or_export(): void
    {
        $editor = User::query()->create([
            'name' => 'Ops Editor',
            'email' => 'ops-editor-'.Str::uuid()->toString().'@example.com',
            'password' => 'password',
            'role' => 'editor',
            'status' => 'active',
        ]);

        $this->actingAs($editor)->get(route('admin.bookings.day-sheet'))->assertForbidden();
        $this->actingAs($editor)->get(route('admin.bookings.export'))->assertForbidden();
    }
}
