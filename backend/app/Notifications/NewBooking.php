<?php

namespace App\Notifications;

use App\Models\Booking;
use App\Support\MarkdownText;
use App\Support\Money;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Office alert for a booking placed on the website.
 */
class NewBooking extends Notification implements ShouldQueue
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
        $booking = $this->booking->loadMissing(['items', 'billingAddress', 'collectionAddress']);
        $billing = $booking->billingAddress;
        $site = $booking->collectionAddress ?? $billing;

        $message = (new MailMessage)
            ->subject("New booking {$booking->reference} for ".($booking->collection_date?->format('D j M') ?? 'no date'))
            ->greeting("New booking {$booking->reference}")
            ->line('**Customer:** '.MarkdownText::escape($billing?->full_name))
            ->line('**Phone:** '.MarkdownText::escape($billing?->phone))
            ->line('**Email:** '.MarkdownText::escape($billing?->email))
            ->line('**Collection:** '.($booking->collection_date?->format('l j F Y') ?? 'Not set')
                .($booking->is_saturday_collection ? ' (Saturday)' : ''))
            ->line('**Collect from:** '.MarkdownText::escape(implode(', ', $site?->lines() ?? [])))
            ->line('**Payment:** '.$booking->paymentOptionLabel()
                .($booking->status === 'draft' ? ' (awaiting payment, chase by hand)' : ''));

        foreach ($booking->items as $item) {
            $message->line('- '.$item->quantity.' x '.MarkdownText::escape($item->name).' '.Money::format($item->line_total_pence));
        }

        $message->line('**Total:** '.Money::format($booking->total_pence).' inc. VAT');

        if ($booking->restricted_access === 'yes' && $booking->access_restrictions) {
            $message->line('**Access restrictions:** '.MarkdownText::escape($booking->access_restrictions));
        }

        if ($billing?->email) {
            $message->replyTo($billing->email, $billing->full_name);
        }

        return $message->action('Open in admin', route('admin.bookings.show', $booking));
    }
}
