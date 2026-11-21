<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TaskAssignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $taskTitle;
    public $taskDescription;
    public $taskPriority;
    public $dueDate;
    public $assignedFromName;
    public $assignedFromPosition;
    public $taskId;

    /**
     * Create a new message instance.
     */
    public function __construct($task, $assignedByName)
    {
        $this->taskTitle = $task['title'];
        $this->taskDescription = $task['description'] ?? '';
        $this->taskPriority = $task['priority'];
        $this->dueDate = $task['due_date'] ? \Carbon\Carbon::parse($task['due_date'])->format('l, d F Y - H:i') : 'Tidak ada deadline';
        $this->assignedFromName = $assignedByName;
        $this->taskId = $task['id'];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tugas Baru Ditugaskan Kepada Anda',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.task_assigned',
            with: [
                'taskTitle' => $this->taskTitle,
                'taskDescription' => $this->taskDescription,
                'taskPriority' => $this->taskPriority,
                'dueDate' => $this->dueDate,
                'assignedFromName' => $this->assignedFromName,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
