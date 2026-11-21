<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskStatusUpdated extends Notification
{
    use Queueable;

    public Task $task;
    public string $oldStatus;
    public string $newStatus;
    public string $updatedByName;

    public function __construct(Task $task, string $oldStatus, string $newStatus, string $updatedByName)
    {
        $this->task = $task;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->updatedByName = $updatedByName;
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
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'updated_by_name' => $this->updatedByName,
            'message' => $this->updatedByName . ' mengubah status "' . $this->task->title . '" dari ' . $this->oldStatus . ' ke ' . $this->newStatus,
        ];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'updated_by_name' => $this->updatedByName,
            'message' => $this->updatedByName . ' mengubah status "' . $this->task->title . '" dari ' . $this->oldStatus . ' ke ' . $this->newStatus,
        ];
    }
}
