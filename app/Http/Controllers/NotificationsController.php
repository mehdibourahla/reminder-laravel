<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class NotificationsController extends Controller
{
    public function getNotifications()
    {
        $notifications = auth()->user()->notifications()->paginate(5);
        $structuredNotifications = [];
        foreach ($notifications as $notification) {
            array_push($structuredNotifications, $this->setObjects($notification));
        }

        return $structuredNotifications;
    }
    public function getLastNotification()
    {
        $notification = auth()->user()->unreadNotifications()->first();
        $structuredNotification = $this->setObjects($notification);
        return $structuredNotification;
    }

    public function markAsRead($notification)
    {
        return auth()->user()->unreadNotifications()->where('notifications.id', $notification)->get()
            ->markAsRead();
    }
    public function markAllAsRead()
    {
        return auth()->user()->unreadNotifications->markAsRead();
    }

    private function setObjects($notification)
    {
        $id = $notification->id;
        $user = null;
        $creation_date = $notification->created_at;
        $redirection = "";
        $type = -1;
        $message = "";
        if (strpos($notification->type, "Reaction")) {
            $user = $notification->data['user'];
            $type = 1;
            $message = "reacted to your message.";
            $redirection = "/m/" . $notification->data['message']['id'];
        } else if (strpos($notification->type, "Post")) {
            $user = $notification->data['author'];
            $type = 2;
            $message = "posted a new message.";
            $redirection = "/m/" . $notification->data['message']['id'];
        } else if (strpos($notification->type, "Follower")) {

            $user = $notification->data['follower'];
            $type = 3;
            $message = "started following you.";
            $redirection = "/profile/" . $notification->data['follower']['id'];
        } else if (strpos($notification->type, "Reminder")) {
            $type = 4;
            $user = $notification->data['author'];
            $message = $notification->data['message']['caption'];
            $redirection = "/m/" . $notification->data['message']['id'];
        }
        $isRead = $notification->read_at == null ? false : true;
        $res = [

            'id' => $id,
            'user' => $user,
            'creation_date' => $creation_date,
            'redirection' => $redirection,
            'type' => $type,
            'message' => $message,
            'notifiable' => $notification->notifiable_id,
            'isRead' => $isRead


        ];
        return $res;
    }
}
