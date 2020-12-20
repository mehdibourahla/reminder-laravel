<?php

namespace App\Http\Controllers;

use App\Events\NewPost;
use App\Events\Reaction;
use Illuminate\Http\Request;
use App\Message;
use App\Tag;
use App\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MessagesController extends Controller
{

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
        $tags = json_decode(request()->tags);
        request()->merge(['tags' => $tags]);
        $message_data = request()->validate([
            'caption' => 'required',
            'description' => 'required',

        ]);

        $tag_data = request()->validate([
            'tags' => 'required|array',
            'tags.*' => 'required|distinct'
        ]);

        $created_message = auth()->user()->messages()->create($message_data);
        event(new NewPost($created_message));

        foreach ($tags as $tag) {
            if (Tag::where('label', '=', $tag)->exists()) {
                $created_tag = Tag::where('label', $tag)->first();
            } else {
                $created_tag = Tag::create(['label' => $tag]);
            }
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
            $tags = json_decode(request()->tags);
            request()->merge(['tags' => $tags]);
            $message_data = request()->validate([
                'caption' => 'required',
                'description' => 'required',

            ]);

            $tag_data = request()->validate([
                'tags' => 'required|array',
                'tags.*' => 'required|distinct'
            ]);

            $message->update($message_data);
            DB::table('message_tag')->where('message_id', $message->id)->delete();
            Tag::doesntHave('messages')->delete();
            foreach ($tags as $tag) {
                if (Tag::where('label', '=', $tag)->exists()) {
                    $created_tag = Tag::where('label', $tag)->first();
                } else {
                    $created_tag = Tag::create(['label' => $tag]);
                }
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
                Tag::doesntHave('messages')->delete();
                Message::destroy($message->id);
                return response()->json('Message deleted');
            } else {
                return response()->json('You shall not pass ! ', 403);
            }
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function showTag(Tag $tag)
    {
        return view('tags.show', compact('tag'));
    }

    public function postReaction(Message $message)
    {

        $profile = auth()->user()->profile->id;
        $type = request()->reaction;

        $query = $message->reactions();

        if ($message->checkReaction($profile, $type) > 0) {
            $query = $query->wherePivot('type', '=', $type)->detach(
                $profile
            );
            event(new Reaction($type, $message, false));
        } else {
            $query = $query->attach(
                $profile,
                ['type' => $type]
            );
            if ($type != 'hide') {
                event(new Reaction($type, $message, true));
            }
        }

        return $query;
    }

    public function getReactions(Message $message)
    {
        $reactions = $message->reactions()->pluck('message_profile.type');
        return $reactions;
    }

    public function userReactions(Message $message)
    {
        $reactions = auth()->user() ?
            auth()->user()->profile->reacts()->where('message_id', $message->id)->pluck('message_profile.type') :
            [];
        return $reactions;
    }


    public function getMessages(Request $request, $filter = null)
    {
        $messages = Message::select(
            'users.id',
            'users.username',
            'profiles.*',
            'messages.*',
            DB::raw('group_concat(type) as type ')
        )
            ->join('users', 'users.id', 'messages.user_id')
            ->join('profiles', 'users.id', 'profiles.user_id')
            ->leftJoin('message_profile', 'message_profile.message_id', 'messages.id');

        // Explorer (Followed users' messages)
        if ($request->has('user')) {
            $user = User::find($request->user);
            $followedUsers = $user->following()->pluck('profiles.user_id');
            $messages = $messages->whereIn('messages.user_id', $followedUsers);
        }
        // Profile (Profile's messages)
        if ($request->has('profile')) {
            if ($filter) {
                $userReactions = auth()->user()->profile->reacts()->pluck('messages.id');
                $messages = $messages->whereIn('messages.id', $userReactions);
                if ($filter == 'likes') {
                    $messages = $messages->where('message_profile.type', 'like');
                }
                if ($filter == 'favourites') {
                    $messages = $messages->where('message_profile.type', 'fav');
                }
                if ($filter == 'hidden') {
                    $messages = $messages->where('message_profile.type', 'hide');
                }
            } else {
                $profile = $request->profile;
                $messages = $messages->where('messages.user_id', $profile);
            }
        }
        // Tag  (Tag's messages)
        if ($request->has('tag')) {
            $tag = Tag::find($request->tag)->messages()->pluck('messages.id');
            $messages = $messages->whereIn('messages.id', $tag);
        }

        // GET 
        $messages = $messages
            ->groupBy('messages.id');
        $messages = $messages->orderBy('messages.created_at', 'DESC')->paginate(5)->items();
        return json_encode($messages);
    }

    public function getMessageTags(Message $message)
    {
        return $message->tags()->get(['tags.id', 'label']);
    }

    public function getSuggestions(Request $request)
    {
        $text = $request->route('text');
        $suggestions = Tag::where('label', 'LIKE', "%{$text}%")->withCount('messages')->take(5)->get();
        return json_encode($suggestions);
    }
}
