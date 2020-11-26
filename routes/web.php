<?php

use App\Mail\NewUserWelcomeMail;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/email', function () {
    return new NewUserWelcomeMail();
});


// CRUD Profile
Route::get('/profile/{user}', 'ProfilesController@show')->name('profile.show');
Route::get('/profile/{user}/edit', 'ProfilesController@edit')->name('profile.edit');
Route::patch('/profile/{user}', 'ProfilesController@update')->name('profile.update');

// API | Profile
Route::get('/api/profile/{user}/followingCount', 'ProfilesController@getFollowingCount');
Route::get('/api/profile/{user}/followersCount', 'ProfilesController@getFollowersCount');
Route::get('/api/profile/{user}/messagesCount', 'ProfilesController@getMessageCount');
Route::get('/api/profile/{user}/details', 'ProfilesController@getDetails');
Route::get('/api/profile/{user}/m/', 'ProfilesController@getProfileMessages');
Route::get('/api/profile/{user}/likes/', 'ProfilesController@getReactions');
Route::get('/api/profile/{user}/fav/', 'ProfilesController@getReactions');
Route::get('/api/profile/{user}/hidden/', 'ProfilesController@getReactions');
Route::get('/api/profile/{user}/follow', 'ProfilesController@isFollowed');
Route::get('/api/profile/{user}/followers', 'ProfilesController@getFollowers');
Route::get('/api/profile/{user}/following', 'ProfilesController@getFollowing');
Route::post('/api/profile/{user}/follow', 'ProfilesController@follow');


// CRUD Message
Route::get('/m', 'MessagesController@index')->name('message.index');
Route::post('/m', 'MessagesController@store')->name('message.store');
Route::get('/m/create', 'MessagesController@create')->name('message.create');
Route::delete('/m/{message}', 'MessagesController@delete')->name('message.delete');
Route::patch('/m/{message}', 'MessagesController@update')->name('message.update');
Route::get('/m/{message}', 'MessagesController@show')->name('message.show');
Route::get('/m/{message}/edit', 'MessagesController@edit')->name('message.edit');

// API | Message
Route::get('/api/m/{message}/fav', 'MessagesController@getFav');
Route::get('/api/m/{message}/likes', 'MessagesController@getLikes');
Route::get('/api/m/followedMsgs', 'MessagesController@getFollowedMessages');
Route::post('/api/m/{message}/{reaction}', 'MessagesController@postReaction');
