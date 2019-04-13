<?php

namespace Kavenegar\Channels;

use Illuminate\Notifications\Notification;
use Kavenegar\Facades\Kavenegar;

class KavenegarChannel
{
    /**
     * Send the given notification.
     *
     * @param mixed                                  $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $receptor = $notifiable->routeNotificationFor('sms');

        switch ($this->detectType($notification)) {
            case 'lookup':
                Kavenegar::lookup($notification->getLookupTemplate(), $receptor, $notifiable->getLookupToken());
                break;

            case 'message':
                Kavenegar::send($receptor, $notification->toSMS());
                break;

        }
    }

    /**
     * Detect type of notification.
     *
     * @param Notification $notification
     *
     * @return string
     */
    public function detectType(Notification $notification)
    {
        if (method_exists($notification, 'getLookupTemplate')) {
            return 'lookup';
        } else {
            return 'message';
        }
    }
}
