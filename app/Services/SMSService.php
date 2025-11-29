<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\AlertLog;

class SMSService
{
    protected $apiKey;
    protected $apiUrl;
    protected $senderName;

    public function __construct()
    {
        $this->apiKey = config('sms.semaphore.api_key');
        $this->apiUrl = config('sms.semaphore.api_url', 'https://api.semaphore.co/api/v4/messages');
        $this->senderName = config('sms.semaphore.sender_name', 'SOLECO');
    }

    /**
     * Send SMS to a single recipient
     */
    public static function send($to, $message)
    {
        $apiKey = env('SEMAPHORE_API_KEY');
        $sender = env('SEMAPHORE_SENDER_NAME', 'EGMS');

        $response = Http::asForm()->post('https://api.semaphore.co/api/v4/messages', [
            'apikey'     => $apiKey,
            'number'     => $to,
            'message'    => $message,
            'sendername' => $sender
        ]);

        return $response->json();
    }

    /**
     * Send SMS to multiple recipients
     */
    public function sendBulkSMS($recipients, $message)
    {
        $results = [];
        $successCount = 0;
        $failureCount = 0;

        foreach ($recipients as $recipient) {
            $phoneNumber = $recipient['phone'] ?? $recipient;
            $result = $this->sendSMS($phoneNumber, $message);
            
            $results[] = [
                'phone' => $phoneNumber,
                'success' => $result['success'],
                'error' => $result['error'] ?? null
            ];

            if ($result['success']) {
                $successCount++;
            } else {
                $failureCount++;
            }
        }

        Log::info("Bulk SMS completed", [
            'total' => count($recipients),
            'success' => $successCount,
            'failures' => $failureCount
        ]);

        return [
            'total' => count($recipients),
            'success' => $successCount,
            'failures' => $failureCount,
            'results' => $results
        ];
    }

    /**
     * Send power outage alert SMS
     */
    public function sendOutageAlert($deviceId, $barangay, $lastSignal, $recipients)
    {
        $message = "⚠ Power outage detected in Barangay {$barangay} — Last signal received {$lastSignal}. SOLECO Monitoring System";
        
        $results = $this->sendBulkSMS($recipients, $message);
        
        // Log the SMS alert
        foreach ($recipients as $recipient) {
            AlertLog::create([
                'device_id' => $deviceId,
                'barangay' => $barangay,
                'message' => $message,
                'alert_type' => 'sms'
            ]);
        }

        return $results;
    }

    /**
     * Test SMS functionality
     */
    public function testSMS($phoneNumber, $message = null)
    {
        $testMessage = $message ?? "Test SMS from SOLECO Monitoring System - " . now()->format('Y-m-d H:i:s');
        return $this->sendSMS($phoneNumber, $testMessage);
    }

    /**
     * Clean phone number format
     */
    protected function cleanPhoneNumber($phoneNumber)
    {
        // Remove all non-numeric characters except +
        $cleaned = preg_replace('/[^0-9+]/', '', $phoneNumber);
        
        // Add +63 if it's a local number without country code
        if (strlen($cleaned) === 10 && !str_starts_with($cleaned, '+')) {
            $cleaned = '+63' . $cleaned;
        } elseif (strlen($cleaned) === 11 && str_starts_with($cleaned, '0')) {
            $cleaned = '+63' . substr($cleaned, 1);
        }

        return $cleaned;
    }

    /**
     * Validate phone number format
     */
    protected function isValidPhoneNumber($phoneNumber)
    {
        // Basic validation for Philippine mobile numbers
        $pattern = '/^\+63[0-9]{10}$/';
        return preg_match($pattern, $phoneNumber);
    }

    /**
     * Get SMS balance/credits (if supported by API)
     */
    public function getBalance()
    {
        try {
            $response = Http::timeout(30)->get('https://api.semaphore.co/api/v4/account', [
                'apikey' => $this->apiKey
            ]);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error("Failed to get SMS balance", ['error' => $e->getMessage()]);
        }

        return null;
    }
}
