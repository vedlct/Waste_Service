<?php

namespace App\Notifications;

use App\Models\Review;
use App\Support\MarkdownText;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Office alert for a visitor review waiting for moderation.
 */
class NewReview extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Review $review)
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
        $review = $this->review;

        return (new MailMessage)
            ->subject("New {$review->rating}-star review waiting for moderation")
            ->greeting('New review to moderate')
            ->line('**From:** '.MarkdownText::escape($review->reviewer_name))
            ->line('**Rating:** '.$review->rating.' out of '.config('reviews.max_rating', 5))
            ->line(MarkdownText::escape($review->body))
            ->action('Moderate in admin', route('admin.reviews.edit', $review))
            ->line('It stays hidden from the website until it is published.');
    }
}
