<?php

namespace App\Mail;

use App\Models\QuoteRequest;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class NewQuoteRequest extends Mailable
{
    public function __construct(public QuoteRequest $lead) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address($this->lead->email, $this->lead->name)],
            subject: 'New quote request: ' . $this->lead->organization,
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.new-quote');
    }
}
