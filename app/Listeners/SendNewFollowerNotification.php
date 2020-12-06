<?php

namespace App\Listeners;

use App\Events\NewFollower;
use App\Notifications\NewFollowerNotification;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendNewFollowerNotification
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
     * @param  NewFollower  $event
     * @return void
     */
    public function handle(NewFollower $event)
    {
        $follower = User::with('profile')->whereIn('id', [auth()->user()->id])->first();

        Notification::send($event->following, new NewFollowerNotification($follower));
    }
}
