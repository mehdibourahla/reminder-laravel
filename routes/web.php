<?php

use App\Http\Controllers\FollowsController;
use App\Http\Controllers\ReactController;
use App\Mail\NewUserWelcomeMail;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/email', function () {
    return new NewUserWelcomeMail();
});

Route::post('/react/{message}/{reaction}', 'ReactController@store');

Route::post('follow/{user}', 'FollowsController@store');
Route::get('follow/{user}', 'ProfilesController@isFollowed');

Route::get('/m/{message}/fav', 'MessagesController@getFav')->name('message.index');
Route::get('/m/{message}/likes', 'MessagesController@getLikes')->name('message.index');
Route::get('/m', 'MessagesController@index')->name('message.index');
Route::post('/m', 'MessagesController@store');
Route::get('/m/followedMsgs', 'MessagesController@getFollowedMessages');
Route::get('/m/create', 'MessagesController@create');
Route::delete('/m/{message}', 'MessagesController@delete')->name('message.delete');
Route::patch('/m/{message}', 'MessagesController@update')->name('message.update');
Route::get('/m/{message}', 'MessagesController@show')->name('message.show');
Route::get('/m/{message}/edit', 'MessagesController@edit')->name('message.edit');


Route::get('/profile/{user}/followingCount', 'ProfilesController@getFollowingCount')->name('profile.show');
Route::get('/profile/{user}/followersCount', 'ProfilesController@getFollowersCount')->name('profile.show');
Route::get('/profile/{user}/messagesCount', 'ProfilesController@getMessageCount')->name('profile.show');
Route::get('/profile/{user}/details', 'ProfilesController@getDetails')->name('profile.show');
Route::get('/profile/{user}', 'ProfilesController@index')->name('profile.show');
Route::get('/profile/{user}/m/', 'ProfilesController@getProfileMessages')->name('profile.show');
Route::get('/profile/{user}/likes/', 'ProfilesController@getReactions')->name('profile.show');
Route::get('/profile/{user}/fav/', 'ProfilesController@getReactions')->name('profile.show');
Route::get('/profile/{user}/hidden/', 'ProfilesController@getReactions')->name('profile.show');
Route::get('/profile/{user}/edit', 'ProfilesController@edit')->name('profile.edit');
Route::patch('/profile/{user}', 'ProfilesController@update')->name('profile.update');
