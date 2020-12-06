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
Route::get('/profile/{user}/edit', 'ProfilesController@edit')->name('profile.edit')->middleware('auth');
Route::patch('/profile/{user}', 'ProfilesController@update')->name('profile.update')->middleware('auth');

// API | Profile
Route::get('/api/profile/{user}/followingCount', 'ProfilesController@getFollowingCount');
Route::get('/api/profile/{user}/followersCount', 'ProfilesController@getFollowersCount');
Route::get('/api/profile/{user}/messagesCount', 'ProfilesController@getMessageCount');
Route::get('/api/profile/{user}/details', 'ProfilesController@getDetails');
Route::get('/api/profile/m/', 'MessagesController@getMessages');
Route::get('/api/profile/likes/', 'MessagesController@getMessages')->defaults('filter', 'likes');
Route::get('/api/profile/favourites/', 'MessagesController@getMessages')->defaults('filter', 'favourites');
Route::get('/api/profile/hidden/', 'MessagesController@getMessages')->defaults('filter', 'hidden');
Route::get('/api/profile/{user}/follow', 'ProfilesController@isFollowed');
Route::get('/api/profile/{user}/followers', 'ProfilesController@getFollowers');
Route::get('/api/profile/{user}/following', 'ProfilesController@getFollowing');
Route::post('/api/profile/{user}/follow', 'ProfilesController@follow')->middleware('auth');
Route::get('/api/profile/{user}/removeFollower', 'ProfilesController@removeFollower')->middleware('auth');


// CRUD Message
Route::get('/m', 'MessagesController@index')->name('message.index')->middleware('auth');
Route::post('/m', 'MessagesController@store')->name('message.store')->middleware('auth');
Route::get('/m/create', 'MessagesController@create')->name('message.create')->middleware('auth');
Route::delete('/m/{message}', 'MessagesController@delete')->name('message.delete')->middleware('auth');
Route::patch('/m/{message}', 'MessagesController@update')->name('message.update')->middleware('auth');
Route::get('/m/{message}', 'MessagesController@show')->name('message.show');
Route::get('/m/{message}/edit', 'MessagesController@edit')->name('message.edit')->middleware('auth');

// API | Message
Route::get('/api/m/{message}/fav', 'MessagesController@getFav');
Route::get('/api/m/{message}/likes', 'MessagesController@getLikes');
Route::get('/api/m/{message}/tags', 'MessagesController@getMessageTags')->name('message.tags');
Route::get('/api/m/followedMsgs', 'MessagesController@getMessages')->middleware('auth');
Route::post('/api/m/{message}/{reaction}', 'MessagesController@postReaction')->middleware('auth');


// API | TAG
Route::get('/api/tag/{text}/suggestions', 'MessagesController@getSuggestions');
Route::get('/api/tag', 'MessagesController@getMessages');
Route::get('/tag/{tag}', 'MessagesController@showTag')->name('tag.show');


// API | NOTIFICATION
Route::get('/api/notification/getNotifications', 'NotificationsController@getNotifications');
