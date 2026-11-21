<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TaskStatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $taskTitle;
    public $oldStatus;
    public $newStatus;
    public $updatedByName;
    public $taskId;

    public function __construct($task, $oldStatus, $newStatus, $updatedByName)
    {
        $this->taskTitle = $task['title'];
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->updatedByName = $updatedByName;
        $this->taskId = $task['id'];
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Status Tugas Diperbarui: ' . $this->taskTitle,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.task_status_updated',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
