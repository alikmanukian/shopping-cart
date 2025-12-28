<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class DailySalesReport extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param  array<string, mixed>  $report
     */
    public function __construct(public array $report) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        /** @var string $date */
        $date = $this->report['date'];

        return new Envelope(
            subject: 'Daily Sales Report - '.$date,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.daily-sales-report',
            with: [
                'report' => $this->report,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
