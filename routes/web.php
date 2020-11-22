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

Route::get('/m', 'MessagesController@index')->name('message.index');
Route::post('/m', 'MessagesController@store');
Route::get('/m/followedMsgs', 'MessagesController@getFollowedMessages');
Route::get('/m/create', 'MessagesController@create');
Route::delete('/m/{message}', 'MessagesController@delete')->name('message.delete');
Route::patch('/m/{message}', 'MessagesController@update')->name('message.update');
Route::get('/m/{message}', 'MessagesController@show')->name('message.show');
Route::get('/m/{message}/edit', 'MessagesController@edit')->name('message.edit');


Route::get('/profile/{user}', 'ProfilesController@index')->name('profile.show');
Route::get('/profile/{user}/m/', 'ProfilesController@getProfileMessages')->name('profile.show');
Route::get('/profile/{user}/likes/', 'ProfilesController@getReactions')->name('profile.show');
Route::get('/profile/{user}/fav/', 'ProfilesController@getReactions')->name('profile.show');
Route::get('/profile/{user}/hidden/', 'ProfilesController@getReactions')->name('profile.show');
Route::get('/profile/{user}/edit', 'ProfilesController@edit')->name('profile.edit');
Route::patch('/profile/{user}', 'ProfilesController@update')->name('profile.update');
