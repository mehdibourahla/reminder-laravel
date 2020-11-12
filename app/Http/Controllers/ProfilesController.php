<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\User;
use Illuminate\Support\Facades\Cache;

class ProfilesController extends Controller
{
    public function index(User $user)
    {
        $follows = (auth()->user()) ? auth()->user()->following->contains($user->id) : false;

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

        return view('profiles.index', compact('user', 'follows', 'messagesCount', 'followersCount', 'followingCount'));
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
