<?php

namespace App\Support;

use App\Models\Booking;
use App\Models\ContactEnquiry;
use App\Models\Review;
use App\Models\SiteSetting;
use App\Notifications\BookingConfirmation;
use App\Notifications\BookingStatusChanged;
use App\Notifications\NewBooking;
use App\Notifications\NewContactEnquiry;
use App\Notifications\NewReview;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Throwable;

/**
 * Sends the emails triggered by public website submissions, and the customer updates
 * triggered when the office changes a booking's status.
 *
 * Which alerts go out, and to whom, is controlled from the private `notifications` group in
 * Site Settings. A mail problem must never fail the visitor's submission, so every send is
 * isolated and reported instead of thrown.
 */
class WebsiteNotifier
{
    public static function enquiryReceived(ContactEnquiry $enquiry): void
    {
        if (static::enabled('notify_new_enquiry')) {
            static::sendToOffice(new NewContactEnquiry($enquiry));
        }
    }

    public static function bookingReceived(Booking $booking): void
    {
        if (static::enabled('notify_new_booking')) {
            static::sendToOffice(new NewBooking($booking));
        }

        $customerEmail = $booking->billingAddress?->email;

        if (static::enabled('send_customer_booking_confirmation') && static::isEmail($customerEmail)) {
            static::send($customerEmail, new BookingConfirmation($booking));
        }
    }

    /**
     * Emails the customer when the office moves a booking into a status that promises them
     * something (config `bookings.statuses.*.notifies_customer`).
     */
    public static function bookingStatusChanged(Booking $booking, string $previousStatus): void
    {
        if ($booking->status === $previousStatus
            || ! config('bookings.statuses.'.$booking->status.'.notifies_customer', false)
            || ! static::enabled('send_customer_status_updates')) {
            return;
        }

        $email = $booking->billingAddress?->email;

        if (static::isEmail($email)) {
            static::send($email, new BookingStatusChanged($booking));
        }
    }

    public static function reviewReceived(Review $review): void
    {
        if (static::enabled('notify_new_review')) {
            static::sendToOffice(new NewReview($review));
        }
    }

    /**
     * The dedicated alert address, falling back to the public contact email.
     */
    public static function officeEmail(): ?string
    {
        foreach ([
            SiteSetting::valueOf('notifications', 'office_email'),
            SiteSetting::valueOf('contact', 'email'),
        ] as $candidate) {
            if (static::isEmail($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    private static function sendToOffice(Notification $notification): void
    {
        $email = static::officeEmail();

        if ($email === null) {
            report(new \RuntimeException('No valid office email is configured, so a website alert was not sent.'));

            return;
        }

        static::send($email, $notification);
    }

    private static function send(string $email, Notification $notification): void
    {
        try {
            NotificationFacade::route('mail', $email)->notify($notification);
        } catch (Throwable $exception) {
            report($exception);
        }
    }

    private static function enabled(string $key): bool
    {
        return (bool) SiteSetting::valueOf('notifications', $key, true);
    }

    private static function isEmail(mixed $value): bool
    {
        return is_string($value) && filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }
}
