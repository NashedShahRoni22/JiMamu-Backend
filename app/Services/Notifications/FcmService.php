<?php

namespace App\Services\Notifications;

use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FcmService
{
    public function __construct(private Messaging $messaging) {}

    // Single device
    public function sendToDevice(string $token, string $title, string $body, string $type, array $data = []): void
    {
        $message = CloudMessage::withTarget('token', $token)
            ->withNotification(Notification::create($title, $body))
            ->withData(array_merge([
                'type'  => $type,
                'route' => '/' . $type,
            ], $data));

        $this->messaging->send($message);
    }

    // Multiple devices
    public function sendToMultiple(array $tokens, string $title, string $body, string $type, array $data = []): void
    {
        $message = CloudMessage::new()
            ->withNotification(Notification::create($title, $body))
            ->withData(array_merge([
                'type'  => $type,
                'route' => '/' . $type,
            ], $data));

        $this->messaging->sendMulticast($message, $tokens);
    }

    // Topic
    public function sendToTopic(string $topic, string $title, string $body, string $type, array $data = []): void
    {
        $message = CloudMessage::withTarget('topic', $topic)
            ->withNotification(Notification::create($title, $body))
            ->withData(array_merge([
                'type'  => $type,
                'route' => '/' . $type,
            ], $data));

        $this->messaging->send($message);
    }
}