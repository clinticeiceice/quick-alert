<?php 

namespace App\Services;

use Minishlink\WebPush\WebPush;

class WebPushService
{
    private $webpush;

    public function __construct()
    {
        $this->webpush = new WebPush([
            'VAPID' => [

            ]
        ]);
    }
}