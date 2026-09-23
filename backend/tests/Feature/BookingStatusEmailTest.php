<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\SiteSetting;
use App\Models\User;
use App\Notifications\BookingStatusChanged;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Tests\TestCase;

class BookingStatusEmailTest extends TestCase
{
    private User $admin;

    private Booking $booking;

    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();

        $this->admin = User::query()->create([
            'name' => 'Status Admin',
            'email' => 'status-admin-'.Str::uuid()->toString().'@example.com',
            'password' => 'password',
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->booking = Booking::query()->create([
            'reference' => 'MT-2610-9001',
            'status' => 'submitted',
            'payment_status' => 'unpaid',
            'payment_option' => 'pay_on_arrival',
            'collection_date' => '2026-10-07',
            'notice_minutes' => 30,
            'total_pence' => 12000,
        ]);

        $this->booking->addresses()->create([
            'type' => 'billing', 'first_name' => 'Amara', 'last_name' => 'Okafor', 'email' => 'amara@example.com',
            'phone' => '02012345678', 'address_line_1' => '2 Test Road', 'city' => 'Portsmouth', 'postcode' => 'PO1 3AX',
        ]);
    }

    private function changeStatus(string $status, bool $notify = true): void
    {
        $this->actingAs($this->admin)
            ->put(route('admin.bookings.update', $this->booking), [
                'status' => $status,
                'payment_status' => 'unpaid',
                'notify_customer' => $notify ? '1' : '0',
            ])
            ->assertRedirect(route('admin.bookings.show', $this->booking));
    }

    public function test_confirming_a_booking_emails_the_customer(): void
    {
        $this->changeStatus('confirmed');

        Notification::assertSentOnDemand(
            BookingStatusChanged::class,
            fn ($notification, $channels, $notifiable) => $notifiable->routes['mail'] === 'amara@example.com',
        );

        $html = (string) (new BookingStatusChanged($this->booking->refresh()))->toMail(new AnonymousNotifiable)->render();

        $this->assertStringContainsString('MT-2610-9001', $html);
        $this->assertStringContainsString('Wednesday 7 October 2026', $html);
        $this->assertStringContainsString('confirmed', $html);
        $this->assertStringNotContainsString('/admin/', $html, 'Customer email must not link into the admin.');
    }

    public function test_scheduling_mentions_the_call_ahead(): void
    {
        $this->changeStatus('scheduled');

        Notification::assertSentOnDemand(BookingStatusChanged::class);

        $html = (string) (new BookingStatusChanged($this->booking->refresh()))->toMail(new AnonymousNotifiable)->render();
        $this->assertStringContainsString('30 minutes before arriving', $html);
    }

    public function test_cancelling_tells_the_customer_without_a_total(): void
    {
        $this->changeStatus('cancelled');

        Notification::assertSentOnDemand(BookingStatusChanged::class);

        $html = (string) (new BookingStatusChanged($this->booking->refresh()))->toMail(new AnonymousNotifiable)->render();
        $this->assertStringContainsString('has been cancelled', $html);
        $this->assertStringNotContainsString('Total:', $html);
    }

    public function test_the_office_can_untick_the_email(): void
    {
        $this->changeStatus('confirmed', notify: false);

        Notification::assertNothingSent();
    }

    public function test_no_email_when_the_status_does_not_change(): void
    {
        $this->changeStatus('submitted');

        Notification::assertNothingSent();
    }

    public function test_no_email_for_statuses_that_promise_the_customer_nothing(): void
    {
        $this->changeStatus('completed');

        Notification::assertNothingSent();
    }

    public function test_the_setting_switches_status_emails_off(): void
    {
        SiteSetting::query()->where('group', 'notifications')->where('key', 'send_customer_status_updates')->update(['value' => json_encode(false)]);

        $this->changeStatus('confirmed');

        Notification::assertNothingSent();
    }

    public function test_the_booking_screen_offers_the_email_choice(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.bookings.show', $this->booking))
            ->assertOk()
            ->assertSee('Email the customer about a status change')
            ->assertSee('Goes to amara@example.com');
    }
}
