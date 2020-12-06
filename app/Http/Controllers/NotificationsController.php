<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationsController extends Controller
{
    public function getNotifications()
    {
        return auth()->user()->unreadNotifications()->paginate(5);
    }
}
