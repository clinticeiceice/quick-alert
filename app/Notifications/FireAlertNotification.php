<?php

namespace App\Notifications;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class FireAlertNotification extends Notification  implements ShouldBroadcast
{

    protected $data;
    protected $designatedTo;
    protected $soundAlert;

    public function __construct($data, $designatedTo, $soundAlert)
    {
        $this->data = $data;
        $this->designatedTo = $designatedTo;
        $this->soundAlert = $soundAlert;
    }

    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    // ✅ Store notification record in "notifications" table
    public function toArray($notifiable)
    {
        return [
            'user_id'   => $this->data->user_id,
            'report_id' => $this->data->report_id,
            'role'      => $this->data->role,
            'message'   => $this->data->message,
        ];
    }


    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'notification' => 'hello',
            'role' => 1,
        ]);
    }

    public function toWebPush($notifiable, $notification)
    {
        $title = "New Report";

        switch ($this->designatedTo) {
            case 'rescue':
                $title = "Rescue Report Alert!";
                break;
            case 'pnp':
                $title = "PNP Report Alert!";
                break;
            case 'bfp':
                $title = "BFP Report Alert!";
                break;
            
            default:
                $title = "New Report Alert!";
                break;
        }

        return (new WebPushMessage)
            ->title($title)
            ->body($this->data->message)
            ->action('View Order', 'view_order')
            ->data([
                'notification' => $this->data,
                'role' => $this->data->role,
                'soundAlert' => $this->soundAlert
            ]);
    }
}
