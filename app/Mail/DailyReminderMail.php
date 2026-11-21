<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailyReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $tasks;
    public $employeeName;

    public function __construct($tasks, $employeeName)
    {
        $this->tasks = $tasks;
        $this->employeeName = $employeeName;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '☀️ Morning Reminder: Sikat Habis Tugasmu, ' . $this->employeeName . '!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.daily_reminder',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
