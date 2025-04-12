<?php
namespace App\Services\Notifications;


use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;

class FirebaseService
{
    public function sendToToken(string $deviceToken, string $title, string $body, array $data = [])
    {
        $messaging = Firebase::messaging();

        $message = CloudMessage::withTarget('token', $deviceToken)
            ->withNotification(Notification::create($title, $body))
            ->withData($data);

        return $messaging->send($message);
    }
}
