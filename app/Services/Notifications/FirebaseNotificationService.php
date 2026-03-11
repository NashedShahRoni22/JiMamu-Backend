<?php

namespace App\Services\Notifications;

use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseNotificationService
{
    public function send($token, $title, $body, $data = [])
    {
        $messaging = Firebase::messaging();

        $message = CloudMessage::withTarget('token', $token)
            ->withNotification(Notification::create($title, $body))
            ->withData($data);

        return $messaging->send($message);
    }
}