<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\ContactEnquiry;
use App\Models\PriceCategory;
use App\Models\Review;
use App\Models\ServiceItem;
use App\Models\SiteSetting;
use App\Models\User;
use App\Notifications\BookingConfirmation;
use App\Notifications\NewBooking;
use App\Notifications\NewContactEnquiry;
use App\Notifications\NewReview;
use App\Support\MarkdownText;
use App\Support\WebsiteNotifier;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    // Each test is rolled back, so settings changed here never outlive the test.
    private function setNotificationSetting(string $key, mixed $value): void
    {
        SiteSetting::query()->where('group', 'notifications')->where('key', $key)->firstOrFail()->update(['value' => $value]);
    }

    public function test_enquiry_submit_emails_the_office(): void
    {
        Notification::fake();
        $this->setNotificationSetting('office_email', 'alerts@example.com');
        $token = Str::uuid()->toString();

        $this->postJson(route('api.v1.enquiries.store'), [
            'name' => "Alert Customer {$token}",
            'phone' => '02012345678',
            'email' => "alert-{$token}@example.com",
            'service' => 'garden',
            'message' => 'Please quote for a garden clearance this week.',
        ])->assertCreated();

        Notification::assertSentOnDemand(
            NewContactEnquiry::class,
            fn ($notification, $channels, $notifiable) => $notifiable->routes['mail'] === 'alerts@example.com'
                && str_contains($notification->enquiry->name, $token),
        );

        ContactEnquiry::query()->where('email', "alert-{$token}@example.com")->forceDelete();
    }

    public function test_office_email_falls_back_to_the_contact_email(): void
    {
        $this->setNotificationSetting('office_email', null);

        $this->assertSame(SiteSetting::valueOf('contact', 'email'), WebsiteNotifier::officeEmail());
    }

    public function test_disabled_alerts_are_not_sent(): void
    {
        Notification::fake();
        $this->setNotificationSetting('notify_new_review', false);
        $token = Str::uuid()->toString();

        $this->postJson(route('api.v1.reviews.store'), [
            'reviewer_name' => "Quiet Reviewer {$token}",
            'rating' => 4,
            'body' => 'Good service, would book again.',
        ])->assertCreated();

        Notification::assertNothingSent();

        Review::query()->where('reviewer_name', "Quiet Reviewer {$token}")->delete();
    }

    public function test_review_submit_emails_the_office(): void
    {
        Notification::fake();
        $this->setNotificationSetting('notify_new_review', true);
        $token = Str::uuid()->toString();

        $this->postJson(route('api.v1.reviews.store'), [
            'reviewer_name' => "Loud Reviewer {$token}",
            'rating' => 5,
            'body' => 'Excellent service from start to finish.',
        ])->assertCreated();

        Notification::assertSentOnDemand(NewReview::class);

        $review = Review::query()->where('reviewer_name', "Loud Reviewer {$token}")->firstOrFail();
        $html = (string) (new NewReview($review))->toMail(new AnonymousNotifiable)->render();
        $this->assertStringContainsString("Loud Reviewer {$token}", $html);
        $this->assertStringContainsString(route('admin.reviews.edit', $review), $html);

        $review->delete();
    }

    public function test_booking_submit_emails_the_office_and_the_customer(): void
    {
        Notification::fake();
        $this->setNotificationSetting('office_email', 'alerts@example.com');
        $this->setNotificationSetting('notify_new_booking', true);
        $this->setNotificationSetting('send_customer_booking_confirmation', true);
        $token = Str::uuid()->toString();

        $item = ServiceItem::query()->create([
            'price_category_id' => PriceCategory::query()->firstOrFail()->id,
            'sku' => "NOTIFY-{$token}",
            'slug' => "notify-item-{$token}",
            'name' => "Notify Item {$token}",
            'price_pence' => 4000,
            'vat_rate_basis_points' => 2000,
            'pricing_status' => 'confirmed',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $response = $this->postJson(route('api.v1.bookings.store'), [
            'items' => [['type' => 'service_item', 'id' => $item->id, 'quantity' => 1]],
            'collection' => [
                'collection_date' => Carbon::now()->next(Carbon::MONDAY)->toDateString(),
                'saturday_collection' => false,
                'notice_minutes' => 30,
                'payment_option' => 'arrival',
                'access_confirmed' => true,
                'restricted_access' => 'no',
            ],
            'billing' => [
                'first_name' => 'Notify',
                'last_name' => "Customer {$token}",
                'phone' => '02012345678',
                'email' => "customer-{$token}@example.com",
                'address_line_1' => '1 Test Road',
                'city' => 'Portsmouth',
                'postcode' => 'PO1 3AX',
            ],
        ])->assertCreated();

        Notification::assertSentOnDemand(
            NewBooking::class,
            fn ($notification, $channels, $notifiable) => $notifiable->routes['mail'] === 'alerts@example.com',
        );
        Notification::assertSentOnDemand(
            BookingConfirmation::class,
            fn ($notification, $channels, $notifiable) => $notifiable->routes['mail'] === "customer-{$token}@example.com",
        );

        $booking = Booking::query()->where('reference', $response->json('reference'))->firstOrFail();

        // Fakes skip rendering, and a broken template would only surface in the queue worker.
        $office = (string) (new NewBooking($booking))->toMail(new AnonymousNotifiable)->render();
        $customer = (string) (new BookingConfirmation($booking))->toMail(new AnonymousNotifiable)->render();

        $this->assertStringContainsString($booking->reference, $office);
        $this->assertStringContainsString("Notify Item {$token}", $office);
        $this->assertStringContainsString(route('admin.bookings.show', $booking), $office);
        $this->assertStringContainsString($booking->reference, $customer);
        $this->assertStringNotContainsString('/admin/', $customer, 'Customer email must not link into the admin.');
        $booking->items()->delete();
        $booking->addresses()->delete();
        $booking->forceDelete();
        $item->forceDelete();
    }

    public function test_notifications_are_queued_and_wait_for_the_transaction(): void
    {
        foreach ([NewContactEnquiry::class, NewBooking::class, NewReview::class, BookingConfirmation::class] as $class) {
            $this->assertContains(\Illuminate\Contracts\Queue\ShouldQueue::class, class_implements($class));
        }

        $notification = new NewReview(new Review);
        $this->assertTrue($notification->afterCommit);
    }

    public function test_visitor_text_cannot_inject_links_into_office_email(): void
    {
        $enquiry = new ContactEnquiry([
            'name' => 'Mallory',
            'email' => 'mallory@example.com',
            'phone' => '02012345678',
            'service_label' => 'House clearance',
            'message' => 'Urgent: [verify your account](https://evil.example.com) now',
        ]);
        $enquiry->id = 999999;

        $html = (string) (new NewContactEnquiry($enquiry))->toMail(new AnonymousNotifiable)->render();

        $this->assertStringNotContainsString('href="https://evil.example.com"', $html);
        $this->assertStringContainsString('verify your account', $html);
        $this->assertSame('a\[b\]\(c\)', MarkdownText::escape('a[b](c)'));
    }

    public function test_login_records_the_last_login_time(): void
    {
        $token = Str::uuid()->toString();

        $user = User::query()->create([
            'name' => 'Login Tracker',
            'email' => "login-tracker-{$token}@example.com",
            'password' => 'password',
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->assertNull($user->last_login_at);

        $this->post(route('login'), ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect();

        $this->assertNotNull($user->refresh()->last_login_at);

        $user->delete();
    }
}
