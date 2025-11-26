<?php

namespace App\Services;

use App\Models\Device;
use App\Models\AlertLog;
use App\Notifications\OutageDetected;
use App\Notifications\SMSAlert;
use App\Services\SMSService;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class OutageDetectionService
{
    /**
     * Check for devices that have been offline for more than 5 minutes
     * and send push notifications for power outages
     */
    public function checkForOutages()
    {
        $outageThreshold = now()->subMinutes(5);
        
        // Find devices that haven't sent a signal for more than 5 minutes
        $offlineDevices = Device::where('last_seen', '<', $outageThreshold)
            ->orWhereNull('last_seen')
            ->get();
            
        Log::info("Outage Detection: Found " . $offlineDevices->count() . " offline devices");
        
        foreach ($offlineDevices as $device) {
            $this->handleDeviceOutage($device);
        }
    }
    
    /**
     * Handle a single device outage
     */
    protected function handleDeviceOutage(Device $device)
    {
        // Check if we already logged an alert for this device recently (within last 10 minutes)
        $recentAlert = AlertLog::where('device_id', $device->device_id)
            ->where('alert_type', 'power_outage')
            ->where('created_at', '>', now()->subMinutes(10))
            ->first();
            
        if ($recentAlert) {
            Log::info("Outage Detection: Alert already sent for device {$device->device_id} recently");
            return;
        }
        
        // Create alert log entry
        $lastSignal = $device->last_seen ? $device->last_seen->format('Y-m-d H:i:s') : 'Never';
        $message = "Power outage detected in Barangay {$device->barangay} — Last signal received {$lastSignal}";
        
        $alertLog = AlertLog::create([
            'device_id' => $device->device_id,
            'barangay' => $device->barangay,
            'message' => $message,
            'alert_type' => 'power_outage'
        ]);
        
        Log::info("Outage Detection: Created alert log for device {$device->device_id}");
        
        // Send push notification to all subscribed users
        $this->sendOutageNotification($device, $lastSignal);
    }
    
    /**
     * Send push notification for device outage
     */
    protected function sendOutageNotification(Device $device, $lastSignal)
    {
        try {
            // Send Web Push notifications
            $this->sendWebPushNotification($device, $lastSignal);
            
            // Send SMS notifications
            $this->sendSMSNotification($device, $lastSignal);
            
        } catch (\Exception $e) {
            Log::error("Outage Detection: Failed to send notifications for device {$device->device_id}: " . $e->getMessage());
        }
    }

    /**
     * Send Web Push notification
     */
    protected function sendWebPushNotification(Device $device, $lastSignal)
    {
        try {
            // Get all push subscriptions (in a real app, you'd filter by admin users)
            $subscriptions = \NotificationChannels\WebPush\PushSubscription::all();
            
            if ($subscriptions->isEmpty()) {
                Log::info("Outage Detection: No push subscriptions found");
                return;
            }
            
            // Create notification instance
            $notification = new OutageDetected(
                $device->device_id,
                $device->barangay,
                $lastSignal
            );
            
            // Send notification to all subscriptions
            foreach ($subscriptions as $subscription) {
                $subscription->notify($notification);
            }
            
            Log::info("Outage Detection: Sent push notification for device {$device->device_id} to {$subscriptions->count()} subscribers");
            
        } catch (\Exception $e) {
            Log::error("Outage Detection: Failed to send push notification for device {$device->device_id}: " . $e->getMessage());
        }
    }

    /**
     * Send SMS notification
     */
    protected function sendSMSNotification(Device $device, $lastSignal)
    {
        try {
            // Get SMS recipients from alert settings or database
            $recipients = $this->getSMSRecipients();
            
            if (empty($recipients)) {
                Log::info("Outage Detection: No SMS recipients found");
                return;
            }

            $smsService = app(SMSService::class);
            
            // Send SMS alerts
            $result = $smsService->sendOutageAlert(
                $device->device_id,
                $device->barangay,
                $lastSignal,
                $recipients
            );

            Log::info("Outage Detection: Sent SMS alerts for device {$device->device_id}", [
                'total_recipients' => $result['total'],
                'successful' => $result['success'],
                'failed' => $result['failures']
            ]);

            // Log failed SMS attempts
            foreach ($result['results'] as $smsResult) {
                if (!$smsResult['success']) {
                    AlertLog::create([
                        'device_id' => $device->device_id,
                        'barangay' => $device->barangay,
                        'message' => "SMS failed: " . $smsResult['error'],
                        'alert_type' => 'sms_error'
                    ]);
                }
            }
            
        } catch (\Exception $e) {
            Log::error("Outage Detection: Failed to send SMS for device {$device->device_id}: " . $e->getMessage());
            
            // Log SMS failure
            AlertLog::create([
                'device_id' => $device->device_id,
                'barangay' => $device->barangay,
                'message' => "SMS service error: " . $e->getMessage(),
                'alert_type' => 'sms_error'
            ]);
        }
    }

    /**
     * Get SMS recipients from database or settings
     */
    protected function getSMSRecipients()
    {
        // This should be implemented based on your recipient management system
        // For now, return a sample structure
        return [
            ['phone' => '+639123456789', 'name' => 'Admin 1'],
            ['phone' => '+639987654321', 'name' => 'Admin 2'],
        ];
    }
    
    /**
     * Test method to simulate an outage for testing purposes
     */
    public function simulateOutage($deviceId)
    {
        $device = Device::where('device_id', $deviceId)->first();
        
        if (!$device) {
            throw new \Exception("Device with ID {$deviceId} not found");
        }
        
        Log::info("Outage Detection: Simulating outage for device {$deviceId}");
        $this->handleDeviceOutage($device);
    }
}
