<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    public function create($userId, $type, $title, $message = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
        ]);
    }
}