<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class OutageDetected extends Notification
{
    use Queueable;

    protected $deviceId;
    protected $barangay;
    protected $lastSignal;

    /**
     * Create a new notification instance.
     */
    public function __construct($deviceId, $barangay, $lastSignal)
    {
        $this->deviceId = $deviceId;
        $this->barangay = $barangay;
        $this->lastSignal = $lastSignal;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [WebPushChannel::class];
    }

    /**
     * Get the web push representation of the notification.
     */
    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('⚠ Power Outage Detected')
            ->body("Power outage detected in Barangay {$this->barangay} — Last signal received {$this->lastSignal}")
            ->icon('/photos/icon.png')
            ->badge('/photos/icon.png')
            ->action('View Dashboard', 'view_dashboard')
            ->data([
                'url' => route('admin.dashboardtest'),
                'device_id' => $this->deviceId,
                'barangay' => $this->barangay
            ]);
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
            'message' => "Power outage detected in Barangay {$this->barangay} — Last signal received {$this->lastSignal}"
        ];
    }
}
