<?php

namespace App\Mail;

use App\Models\DemoRequest;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class NewDemoRequest extends Mailable
{
    public function __construct(public DemoRequest $lead) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address($this->lead->email, $this->lead->name)],
            subject: 'New demo request: ' . $this->lead->organization,
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.new-demo');
    }
}
