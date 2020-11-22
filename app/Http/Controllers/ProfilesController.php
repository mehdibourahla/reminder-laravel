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

    public function __construct(Request $request){
        $this->currentProfile = Profile::findOrFail($request->route('user')); 
    }

    public function isFollowed(User $user){
        $response = (auth()->user()) ? auth()->user()->following->contains($user->id) : false;
        return json_encode($response);
    }

    public function getProfileMessages(){
        
        $messages = DB::table('users')
        ->join('messages','users.id','messages.user_id')
        ->join('profiles','users.id','profiles.user_id')
        ->where('users.id','=',$this->currentProfile->id)
        ->select('users.username','profiles.*','messages.*')
        ->get();

        $reactions = auth()->user() ? DB::table('profiles')
        ->join('message_profile','profiles.id','message_profile.profile_id')
        ->where('profiles.id','=',auth()->user()->id)
        ->select('message_id','type')
        ->get() : [];
        
        foreach ($messages as $message) {
            $message->type = array();
            foreach($reactions as $reaction) {
                if($reaction->message_id == $message->id){
                    array_push($message->type, $reaction->type);
                }
            }
        }
        return response()->json($messages);
    }
    public function getReactions(){
            $user = auth()->user();
            if($user->can('getReactions',$this->currentProfile)){
                $messages = ($user) ? DB::table('users')
                ->join('messages','messages.user_id','users.id')
                ->join('profiles','users.id','profiles.user_id')
                ->join('message_profile','message_profile.message_id','messages.id')
                ->where('message_profile.profile_id','=',$user->id)
                ->select('users.*','profiles.*','messages.*')
                ->distinct()
                ->get() : [];

                $reactions = ($user) ? DB::table('profiles')
                ->join('message_profile','profiles.id','message_profile.profile_id')
                ->where('profiles.id','=',$user->id)
                ->select('message_id','type')
                ->get() : [];
                
                foreach ($messages as $message) {
                    $message->type = array();
                    foreach($reactions as $reaction) {
                        if($reaction->message_id == $message->id){
                            array_push($message->type, $reaction->type);
                        }
                    }
                }
                return response()->json($messages);
            }
            else{
                return response()->json("You're not authorized",403);
            }
        
}

    public function index(User $user)
    {
        $messagesCount = Cache::remember(
            'count.messages' . $user->id,
            now()->addSeconds(30),
            function () use ($user) {
                return $user->messages->count();
            }
        );

        $followersCount = Cache::remember(
            'count.followers' . $user->id,
            now()->addSeconds(30),
            function () use ($user) {
                return $user->profile->followers()->count();
            }
        );

        $followingCount = Cache::remember(
            'count.following' . $user->id,
            now()->addSeconds(30),
            function () use ($user) {
                return $user->following()->count();
            }
        );

        $isAuthenticated = auth()->user() ? auth()->user() : false;
        
        return view('profiles.index', compact('user', 'messagesCount', 'followersCount', 'followingCount','isAuthenticated'));
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
}
