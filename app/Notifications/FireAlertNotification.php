<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FireAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $report;

    public function __construct($report)
    {
        $this->report = $report;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // You can add 'broadcast' for real-time
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('🚨 Fire Alert')
            ->line('A fire has been reported!')
            ->line('Severity Level: ' . $this->report->level)
            ->line('Location/Details: ' . $this->report->description)
            ->action('View Report', url('/reports/' . $this->report->id));
    }

    public function toArray($notifiable)
    {
        return [
            'report_id' => $this->report->id,
            'level' => $this->report->level,
            'description' => $this->report->description,
        ];
    }
}
