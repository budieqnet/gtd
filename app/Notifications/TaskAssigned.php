<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskAssigned extends Notification
{
    use Queueable;

    public Task $task;
    public string $assignedByName;

    public function __construct(Task $task, string $assignedByName)
    {
        $this->task = $task;
        $this->assignedByName = $assignedByName;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'priority' => $this->task->priority,
            'due_date' => $this->task->due_date?->format('Y-m-d'),
            'assigned_by_name' => $this->assignedByName,
            'message' => 'Menugaskan: ' . $this->task->title,
        ];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'priority' => $this->task->priority,
            'due_date' => $this->task->due_date?->format('Y-m-d'),
            'assigned_by_name' => $this->assignedByName,
            'message' => 'Menugaskan: ' . $this->task->title,
        ];
    }
}
