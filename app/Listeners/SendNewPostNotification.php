<?php

namespace App\Listeners;

use App\Events\NewPost;
use App\Notifications\NewPostNotification;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendNewPostNotification
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
     * @param  NewPost  $event
     * @return void
     */
    public function handle(NewPost $event)
    {
        $author = User::with('profile')->whereIn('id', [auth()->user()->id])->first();
        $followers = auth()->user()->profile->followers;
        $message = $event->message;

        Notification::send($followers, new NewPostNotification($author, $message));
    }
}
