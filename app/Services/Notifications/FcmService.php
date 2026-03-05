<?php

namespace App\Services\Notifications;

use Illuminate\Support\Facades\Http;

class FcmService
{
    public function send($tokens, $title, $body, $data = [])
    {
        return Http::withHeaders([
            'Authorization' => 'key=' . config('services.fcm.server_key'),
            'Content-Type' => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', [
            'registration_ids' => is_array($tokens) ? $tokens : [$tokens],
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'data' => $data,
        ])->json();
    }
}
