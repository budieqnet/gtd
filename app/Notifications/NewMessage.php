<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewMessage extends Notification
{
    use Queueable;

    public int $conversationId;
    public int $senderId;
    public string $senderName;
    public string $preview;

    public function __construct(int $conversationId, int $senderId, string $senderName, string $preview)
    {
        $this->conversationId = $conversationId;
        $this->senderId = $senderId;
        $this->senderName = $senderName;
        $this->preview = $preview;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'conversation_id' => $this->conversationId,
            'sender_id' => $this->senderId,
            'sender_name' => $this->senderName,
            'message' => 'Pesan baru dari ' . $this->senderName . ': ' . $this->preview,
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
