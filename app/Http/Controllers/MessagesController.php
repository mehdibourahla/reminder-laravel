<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\User;
use Illuminate\Support\Facades\Log;

class MessagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(User $user)
    {
        $users = auth()->user()->following()->pluck('profiles.user_id');

        $likes = auth()->user()->profile->reacts()->where('type','=','like')->pluck('message_id');

        $favourites = auth()->user()->profile->reacts()->where('type','=','fav')->pluck('message_id');

        $hides = auth()->user()->profile->reacts()->where('type','=','hide')->pluck('message_id');

        $messages = Message::whereIn('user_id', $users)->with('user')->latest()->paginate(5);

        Log::debug($likes);
        
        return view('messages.index', compact('messages','likes','favourites','hides'));
    }
    public function create()
    {
        return view('messages.create');
    }

    public function show(Message $message)
    {
        return view('messages.show', compact('message'));
    }

    public function store()
    {
        $data = request()->validate([
            'caption' => 'required',
            'description' => 'required'
        ]);

        auth()->user()->messages()->create($data);

        return redirect('/profile/' . auth()->user()->id);
    }
}
