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
 * Sent to the customer's billing email so they have their reference in writing.
 * Content is purely factual; it never promises a slot the office has not confirmed.
 */
class BookingConfirmation extends Notification implements ShouldQueue
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
        $booking = $this->booking->loadMissing(['items', 'billingAddress']);
        $siteName = SiteSetting::valueOf('general', 'site_name', config('app.name'));
        $phone = SiteSetting::valueOf('contact', 'phone');

        $message = (new MailMessage)
            ->subject("Your booking reference {$booking->reference}")
            ->greeting('Thank you, '.MarkdownText::escape($booking->billingAddress?->first_name ?: 'for your booking').'.')
            ->line("We have received your booking. Your reference is **{$booking->reference}**.")
            ->line('**Requested collection:** '.($booking->collection_date?->format('l j F Y') ?? 'To be arranged'));

        foreach ($booking->items as $item) {
            $message->line('- '.$item->quantity.' x '.MarkdownText::escape($item->name).' '.Money::format($item->line_total_pence));
        }

        $message
            ->line('**Total:** '.Money::format($booking->total_pence).' inc. VAT')
            ->line($booking->status === 'draft'
                ? 'Our team will contact you to arrange payment and confirm your collection.'
                : 'Our team will contact you to confirm your collection.');

        if ($phone) {
            $message->line('Questions? Call us on '.MarkdownText::escape((string) $phone).' and quote your reference.');
        }

        return $message->salutation(MarkdownText::escape((string) $siteName));
    }
}
