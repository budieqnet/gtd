<?php

return [
    'enabled' => env('WHATSAPP_ENABLED', false),
    'token' => env('WHATSAPP_TOKEN', ''),
    'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID', ''),
    'from' => env('WHATSAPP_FROM', ''),
    'driver' => env('WHATSAPP_DRIVER', 'log'),
];