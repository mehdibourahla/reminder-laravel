<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\Tag;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MessagesController extends Controller
{

    public function postReaction(Message $message)
    {
        $profile = auth()->user()->profile->id;
        $type = request()->reaction;

        if ($message->checkReaction($profile, $type) > 0) {
            return $message->reactions()->wherePivot('type', '=', $type)->detach(
                $profile
            );
        } else {
            return $message->reactions()->attach(
                $profile,
                ['type' => $type]
            );
        }
    }

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

    public function getMessageTags(Message $message)
    {
        return $message->tags()->get(['tags.id', 'label']);
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

        $created_message = auth()->user()->messages()->create($data);

        $tags = json_decode(request()->tags);
        foreach ($tags as $tag) {
            $created_tag = Tag::create(['label' => $tag]);
            $created_message->tags()->attach($created_tag);
        }

        return redirect('/profile/' . auth()->user()->id);
    }

    public function edit(Message $message)
    {
        $this->authorize('update', $message);
        $tags = array_column($message->tags()->get(['label'])->toArray(), 'label');
        $tags = implode(",", $tags);
        return view('messages.edit', compact('message', 'tags'));
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
            DB::table('message_tag')->where('message_id', $message->id)->delete();
            $tags = json_decode(request()->tags);
            foreach ($tags as $tag) {
                $created_tag = Tag::create(['label' => $tag]);
                $message->tags()->attach($created_tag);
            }
        }
        return redirect('/profile/' . auth()->user()->id);
    }

    public function delete(Message $message)
    {
        $user = auth()->user();
        try {
            if ($user->can('delete', $message)) {
                DB::table('message_tag')->where('message_id', $message->id)->delete();
                DB::table('message_profile')->where('message_id', $message->id)->delete();
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
