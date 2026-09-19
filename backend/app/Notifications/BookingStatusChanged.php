<?php

namespace App\Notifications;

use App\Models\Booking;
use App\Models\SiteSetting;
use App\Support\MarkdownText;
use App\Support\Money;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Tells the customer their booking was confirmed, scheduled or cancelled by the office.
 * Content is factual and never promises more than the status itself says.
 */
class BookingStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Booking $booking)
    {
        $this->afterCommit();
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $booking = $this->booking->loadMissing('billingAddress');
        $siteName = (string) SiteSetting::valueOf('general', 'site_name', config('app.name'));
        $phone = SiteSetting::valueOf('contact', 'phone');
        $date = $booking->collection_date?->format('l j F Y') ?? 'the agreed date';

        $message = (new MailMessage)
            ->greeting('Hello '.MarkdownText::escape($booking->billingAddress?->first_name ?: 'there').',');

        match ($booking->status) {
            'confirmed' => $message
                ->subject("Booking {$booking->reference} confirmed")
                ->line("Good news: your collection on **{$date}** is confirmed.")
                ->line('We will be in touch if anything changes.'),
            'scheduled' => $message
                ->subject("Booking {$booking->reference} scheduled")
                ->line("Your collection is scheduled for **{$date}**.")
                ->line($booking->notice_minutes
                    ? "Our team will call about {$booking->notice_minutes} minutes before arriving."
                    : 'Our team will call before arriving.'),
            'cancelled' => $message
                ->subject("Booking {$booking->reference} cancelled")
                ->line("Your booking **{$booking->reference}** has been cancelled.")
                ->line('If you did not expect this, please contact us and quote your reference.'),
            default => $message
                ->subject("Update on booking {$booking->reference}")
                ->line("Your booking is now: {$booking->statusLabel()}."),
        };

        if ($booking->status !== 'cancelled') {
            $message->line('**Reference:** '.$booking->reference)
                ->line('**Total:** '.Money::format($booking->total_pence).' inc. VAT');
        }

        if ($phone) {
            $message->line('Questions? Call us on '.MarkdownText::escape((string) $phone).'.');
        }

        return $message->salutation(MarkdownText::escape($siteName));
    }
}
