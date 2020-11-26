<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Message;
use App\Profile;
use Illuminate\Support\Facades\Cache;

class ProfilesController extends Controller
{
    private $currentProfile;

    public function __construct(Request $request)
    {
        $this->currentProfile = Profile::findOrFail($request->route('user'));
    }


    public function show(User $user)
    {
        $isAuthenticated = auth()->user() ? auth()->user() : false;

        return view('profiles.show', compact('user', 'isAuthenticated'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user->profile);
        return view('profiles.edit', compact('user'));
    }

    public function update(User $user)
    {
        $dataUser = request()->validate([
            'username' => 'required',
        ]);
        $dataProfile = request()->validate([
            'bio' => '',
            'url' => 'nullable|url',
            'picture' => 'image',
        ]);

        if (request('picture')) {
            $imagePath = request('picture')->store('profile', 'public');

            $image = Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1000);
            $image->save();
            $imgArray = ['picture' => $imagePath];
        }

        auth()->user()->update($dataUser);
        auth()->user()->profile->update(array_merge(
            $dataProfile,
            $imgArray ?? []
        ));

        return redirect("/profile/{$user->id}");
    }

    // API 
    public function isFollowed(User $user)
    {
        $response = (auth()->user()) ? auth()->user()->following->contains($user->id) : false;
        return json_encode($response);
    }

    public function follow(User $user)
    {
        return auth()->user()->following()->toggle($user->profile);
    }

    public function removeFollower(User $user)
    {
        return $user->following()->detach(auth()->user());
    }

    public function getProfileMessages()
    {

        $messages = DB::table('users')
            ->join('messages', 'users.id', 'messages.user_id')
            ->join('profiles', 'users.id', 'profiles.user_id')
            ->where('users.id', '=', $this->currentProfile->id)
            ->select('users.username', 'profiles.*', 'messages.*')
            ->get();

        $reactions = auth()->user() ? DB::table('profiles')
            ->join('message_profile', 'profiles.id', 'message_profile.profile_id')
            ->where('profiles.id', '=', auth()->user()->id)
            ->select('message_id', 'type')
            ->get() : [];

        foreach ($messages as $message) {
            $message->type = array();
            foreach ($reactions as $reaction) {
                if ($reaction->message_id == $message->id) {
                    array_push($message->type, $reaction->type);
                }
            }
        }
        return response()->json($messages);
    }
    public function getReactions()
    {
        $user = auth()->user();
        if ($user->can('getReactions', $this->currentProfile)) {
            $messages = ($user) ? DB::table('users')
                ->join('messages', 'messages.user_id', 'users.id')
                ->join('profiles', 'users.id', 'profiles.user_id')
                ->join('message_profile', 'message_profile.message_id', 'messages.id')
                ->where('message_profile.profile_id', '=', $user->id)
                ->select('users.*', 'profiles.*', 'messages.*')
                ->distinct()
                ->get() : [];

            $reactions = ($user) ? DB::table('profiles')
                ->join('message_profile', 'profiles.id', 'message_profile.profile_id')
                ->where('profiles.id', '=', $user->id)
                ->select('message_id', 'type')
                ->get() : [];

            foreach ($messages as $message) {
                $message->type = array();
                foreach ($reactions as $reaction) {
                    if ($reaction->message_id == $message->id) {
                        array_push($message->type, $reaction->type);
                    }
                }
            }
            return response()->json($messages);
        } else {
            return response()->json("You're not authorized", 403);
        }
    }

    public function getMessageCount(User $user)
    {
        $messagesCount =  $user->messages->count();

        return response()->json($messagesCount);
    }

    public function getFollowersCount(User $user)
    {
        $followersCount =  $user->profile->followers()->count();

        return response()->json($followersCount);
    }

    public function getFollowingCount(User $user)
    {
        $followingCount =  $user->following()->count();

        return response()->json($followingCount);
    }

    public function getDetails(User $user)
    {
        $profileDetails = DB::table('users')
            ->join('profiles', 'users.id', 'profiles.user_id')
            ->where('users.id', '=', $user->id)
            ->select('users.username', 'users.name', 'profiles.*')
            ->first();
        return response()->json($profileDetails);
    }

    public function getFollowers(User $user)
    {
        $followers = $user->profile->followers()
            ->join('profiles', 'profiles.user_id', 'profile_user.user_id')
            ->select('users.username', 'users.name', 'profiles.picture', 'profiles.id')
            ->get();

        return response()->json($followers);
    }
    public function getFollowing(User $user)
    {
        $following = $user->following()
            ->join('users', 'users.id', 'profile_user.user_id')
            ->select('users.username', 'users.name', 'profiles.picture', 'profiles.id')
            ->get();

        return response()->json($following);
    }
}
