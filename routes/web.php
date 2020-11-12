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

Route::get('/m/create', 'MessagesController@create');
Route::get('/m', 'MessagesController@index')->name('message.index');
Route::post('/m', 'MessagesController@store');
Route::get('/m/{message}', 'MessagesController@show')->name('message.show');

Route::get('/profile/{user}', 'ProfilesController@index')->name('profile.show');
Route::get('/profile/{user}/edit', 'ProfilesController@edit')->name('profile.edit');
Route::patch('/profile/{user}', 'ProfilesController@update')->name('profile.update');
