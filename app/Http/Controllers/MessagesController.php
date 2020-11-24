<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MessagesController extends Controller
{

    public function getLikes(Message $message)
    {
        $likes = DB::table('users')
            ->join('profiles', 'users.id', 'profiles.user_id')
            ->join('message_profile', 'profiles.id', 'message_profile.profile_id')
            ->where('message_profile.message_id', '=', $message->id)
            ->where('message_profile.type', '=', 'like')
            ->select('users.username', 'profiles.*')
            ->get();
        return response()->json($likes);
    }
    public function getFav(Message $message)
    {
        $fav = DB::table('users')
            ->join('profiles', 'users.id', 'profiles.user_id')
            ->join('message_profile', 'profiles.id', 'message_profile.profile_id')
            ->where('message_profile.message_id', '=', $message->id)
            ->where('message_profile.type', '=', 'fav')
            ->select('users.username', 'profiles.*')
            ->get();
        return response()->json($fav);
    }

    public function getFollowedMessages()
    {
        $followedUsers = auth()->user()->following()->pluck('profiles.user_id');

        $messages = DB::table('users')
            ->join('messages', 'users.id', 'messages.user_id')
            ->join('profiles', 'users.id', 'profiles.user_id')
            ->whereIn('users.id', $followedUsers)
            ->select('users.username', 'profiles.*', 'messages.*')
            ->get();

        $reactions = DB::table('profiles')
            ->join('message_profile', 'profiles.id', 'message_profile.profile_id')
            ->where('profiles.id', '=', auth()->user()->id)
            ->select('message_id', 'type')
            ->get();

        foreach ($messages as $message) {
            $message->type = array();
            foreach ($reactions as $reaction) {
                if ($reaction->message_id == $message->id) {
                    array_push($message->type, $reaction->type);
                }
            }
        }
        return json_encode($messages);
    }

    public function index()
    {
        $user = auth()->user() ? auth()->user() : false;
        return view('messages.index', compact('user'));
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

    public function edit(Message $message)
    {
        $this->authorize('update', $message);
        return view('messages.edit', compact('message'));
    }

    public function update(Message $message)
    {
        $user = auth()->user();
        if ($user->can('update', $message)) {
            $data = request()->validate([
                'caption' => 'required',
                'description' => 'required'
            ]);

            $message->update($data);
        }
        return redirect('/profile/' . auth()->user()->id);
    }

    public function delete(Message $message)
    {
        $user = auth()->user();
        try {
            if ($user->can('delete', $message)) {
                Message::destroy($message->id);
                return response()->json('Message deleted');
            } else {
                return response()->json('You shall not pass ! ', 403);
            }
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
