<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $driver;

    public function __construct()
    {
        $this->driver = config('whatsapp.driver', 'log');
    }

    public function send(string $phone, string $message): bool
    {
        if (!config('whatsapp.enabled', false)) {
            return false;
        }

        if ($this->driver === 'log') {
            Log::channel('daily')->info('[WHATSAPP] To: ' . $phone . ' | Message: ' . $message);
            return true;
        }

        return $this->sendViaApi($phone, $message);
    }

    protected function sendViaApi(string $phone, string $message): bool
    {
        $token = config('whatsapp.token');
        $phoneNumberId = config('whatsapp.phone_number_id');

        if (empty($token) || empty($phoneNumberId)) {
            Log::channel('daily')->warning('[WHATSAPP] Token or Phone Number ID not configured');
            return false;
        }

        $response = Http::withToken($token)
            ->post("https://graph.facebook.com/v18.0/{$phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'to' => $phone,
                'type' => 'text',
                'text' => ['body' => $message],
            ]);

        if ($response->failed()) {
            Log::channel('daily')->error('[WHATSAPP] Failed to send: ' . $response->body());
            return false;
        }

        return true;
    }

    public function sendTemplate(string $phone, string $templateName, array $params = []): bool
    {
        if (!config('whatsapp.enabled', false)) {
            return false;
        }

        if ($this->driver === 'log') {
            Log::channel('daily')->info('[WHATSAPP] Template to: ' . $phone . ' | Template: ' . $templateName . ' | Params: ' . json_encode($params));
            return true;
        }

        return $this->sendTemplateViaApi($phone, $templateName, $params);
    }

    protected function sendTemplateViaApi(string $phone, string $templateName, array $params): bool
    {
        $token = config('whatsapp.token');
        $phoneNumberId = config('whatsapp.phone_number_id');

        if (empty($token) || empty($phoneNumberId)) {
            Log::channel('daily')->warning('[WHATSAPP] Token or Phone Number ID not configured');
            return false;
        }

        $components = [];
        if (!empty($params)) {
            $components[] = [
                'type' => 'body',
                'parameters' => array_map(fn($p) => ['type' => 'text', 'text' => $p], $params),
            ];
        }

        $response = Http::withToken($token)
            ->post("https://graph.facebook.com/v18.0/{$phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'to' => $phone,
                'type' => 'template',
                'template' => [
                    'name' => $templateName,
                    'language' => ['code' => 'id'],
                    'components' => $components,
                ],
            ]);

        if ($response->failed()) {
            Log::channel('daily')->error('[WHATSAPP] Failed to send template: ' . $response->body());
            return false;
        }

        return true;
    }
}