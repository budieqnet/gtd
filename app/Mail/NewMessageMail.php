<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public $senderName;
    public $messageText;
    public $conversationId;

    public function __construct($senderName, $messageText, $conversationId)
    {
        $this->senderName = $senderName;
        $this->messageText = $messageText;
        $this->conversationId = $conversationId;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pesan Baru dari ' . $this->senderName,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new_message',
            with: [
                'senderName' => $this->senderName,
                'messageText' => $this->messageText,
                'conversationId' => $this->conversationId,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
