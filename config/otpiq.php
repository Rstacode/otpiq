<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Otpiq API Key
    |--------------------------------------------------------------------------
    |
    | Here you may specify your Otpiq API key. This will be used to authenticate
    | with the Otpiq API - you can find your API key on your Otpiq dashboard.
    |
    */
    'api_key'          => env('OTPIQ_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Default Provider
    |--------------------------------------------------------------------------
    |
    | This option controls the default provider that will be used to send
    | messages when no specific provider is requested.
    |
    | Supported: "auto", "sms", "whatsapp", "telegram"
    |
    */
    'default_provider' => env('OTPIQ_PROVIDER', 'auto'),
];
