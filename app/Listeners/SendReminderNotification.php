<?php

namespace App\Listeners;

use App\Notifications\ReminderNotification;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendReminderNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $users = User::get();
        foreach ($users as $user) {
            foreach ($event->messages as $message) {
                if ($user->id == $message->user_id) {
                    $author = User::with('profile')->whereIn('id', [$message->user_id])->first();
                    Notification::send($user, new ReminderNotification($message, $author));
                }
            }
        }
    }
}
