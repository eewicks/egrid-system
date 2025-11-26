<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Services\SMSService;

class SMSAlert extends Notification implements ShouldQueue
{
    use Queueable;

    protected $deviceId;
    protected $barangay;
    protected $lastSignal;
    protected $alertType;

    /**
     * Create a new notification instance.
     */
    public function __construct($deviceId, $barangay, $lastSignal, $alertType = 'power_outage')
    {
        $this->deviceId = $deviceId;
        $this->barangay = $barangay;
        $this->lastSignal = $lastSignal;
        $this->alertType = $alertType;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['sms'];
    }

    /**
     * Get the SMS representation of the notification.
     */
    public function toSms($notifiable)
    {
        $smsService = app(SMSService::class);
        
        $message = $this->getMessageTemplate();
        
        return [
            'message' => $message,
            'phone' => $notifiable->phone ?? $notifiable,
        ];
    }

    /**
     * Get the message template based on alert type
     */
    protected function getMessageTemplate()
    {
        $templates = config('sms.templates');
        $template = $templates[$this->alertType] ?? $templates['power_outage'];
        
        return str_replace([
            '{device_id}',
            '{barangay}',
            '{last_signal}',
            '{timestamp}'
        ], [
            $this->deviceId,
            $this->barangay,
            $this->lastSignal,
            now()->format('Y-m-d H:i:s')
        ], $template);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'device_id' => $this->deviceId,
            'barangay' => $this->barangay,
            'last_signal' => $this->lastSignal,
            'alert_type' => $this->alertType,
            'message' => $this->getMessageTemplate()
        ];
    }
}
