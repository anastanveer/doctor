<?php

namespace App\Mail;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionExpiringSoon extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Subscription $subscription, public int $daysLeft)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Revise MSRA plan is expiring soon',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.subscription-expiring',
            with: [
                'subscription' => $this->subscription,
                'daysLeft' => $this->daysLeft,
                'renewUrl' => route('subscribe'),
            ],
        );
    }
}
