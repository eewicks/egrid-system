<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SMS Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for SMS services used in the SOLECO monitoring system.
    | Supports multiple SMS providers with Semaphore as the primary service.
    |
    */

    'default' => env('SMS_DEFAULT_PROVIDER', 'semaphore'),

    'providers' => [
        'semaphore' => [
            'api_key' => env('SEMAPHORE_API_KEY'),
            'api_url' => env('SEMAPHORE_API_URL', 'https://api.semaphore.co/api/v4/messages'),
            'sender_name' => env('SEMAPHORE_SENDER_NAME', 'SOLECO'),
            'enabled' => env('SEMAPHORE_ENABLED', true),
        ],
        
        'twilio' => [
            'account_sid' => env('TWILIO_ACCOUNT_SID'),
            'auth_token' => env('TWILIO_AUTH_TOKEN'),
            'from_number' => env('TWILIO_FROM_NUMBER'),
            'enabled' => env('TWILIO_ENABLED', false),
        ],
    ],

    'semaphore' => [
        'api_key' => env('SEMAPHORE_API_KEY'),
        'api_url' => env('SEMAPHORE_API_URL', 'https://api.semaphore.co/api/v4/messages'),
        'sender_name' => env('SEMAPHORE_SENDER_NAME', 'SOLECO'),
        'enabled' => env('SEMAPHORE_ENABLED', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Settings
    |--------------------------------------------------------------------------
    |
    | General SMS settings for the monitoring system.
    |
    */

    'settings' => [
        'max_recipients' => env('SMS_MAX_RECIPIENTS', 50),
        'message_length_limit' => env('SMS_MESSAGE_LENGTH_LIMIT', 160),
        'retry_attempts' => env('SMS_RETRY_ATTEMPTS', 3),
        'retry_delay' => env('SMS_RETRY_DELAY', 5), // seconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Message Templates
    |--------------------------------------------------------------------------
    |
    | Predefined message templates for different alert types.
    |
    */

    'templates' => [
        'power_outage' => "⚠ Power outage detected in Barangay {barangay} — Last signal received {last_signal}. SOLECO Monitoring System",
        'device_online' => "✅ Device {device_id} in Barangay {barangay} is back online. SOLECO Monitoring System",
        'test_alert' => "Test SMS from SOLECO Monitoring System - {timestamp}",
    ],
];
