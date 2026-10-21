<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifySubmissionEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $formTitle,
        public readonly string $verificationUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verify your SnapForm response',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.verify-submission',
        );
    }
}
