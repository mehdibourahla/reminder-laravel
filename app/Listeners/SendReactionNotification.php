<?php

namespace App\Listeners;

use App\Events\Reaction;
use App\Notifications\ReactionNotification;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendReactionNotification
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
     * @param  Reaction  $event
     * @return void
     */
    public function handle(Reaction $event)
    {
        if ($event->up) {
            $reactor = User::with('profile')->whereIn('id', [auth()->user()->id])->first();
            $author = $event->message->user;

            Notification::send($author, new ReactionNotification($reactor, $event->reaction, $event->message));
        }
    }
}
