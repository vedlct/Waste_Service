<?php

namespace App\Notifications;

use App\Models\ContactEnquiry;
use App\Support\MarkdownText;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Office alert for a contact form submission. Replaces the email the retired Next.js
 * contact route used to send through Resend.
 */
class NewContactEnquiry extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public ContactEnquiry $enquiry)
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
        $enquiry = $this->enquiry;
        $service = $enquiry->service_label ?: 'General enquiry';

        return (new MailMessage)
            ->subject("New {$service} enquiry from {$enquiry->name}")
            ->replyTo($enquiry->email, $enquiry->name)
            ->greeting('New website enquiry')
            ->line('**Name:** '.MarkdownText::escape($enquiry->name))
            ->line('**Phone:** '.MarkdownText::escape($enquiry->phone))
            ->line('**Email:** '.MarkdownText::escape($enquiry->email))
            ->line('**Service:** '.MarkdownText::escape($service))
            ->line('**Message:**')
            ->line(MarkdownText::escape($enquiry->message))
            ->action('Open in admin', route('admin.enquiries.show', $enquiry))
            ->line('Reply to this email to answer the customer directly.');
    }
}
