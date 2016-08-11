<?php
$api->get('/newsfeed', 'App\Http\Controllers\Backend\Newsfeed\NewsfeedController@showNewsfeeds')->name('user.newsfeed.index');

$api->post('/savenewsfeed', 'App\Http\Controllers\Backend\Newsfeed\NewsfeedController@createNewsfeed')->name('user.savenewsfeed.index');
$api->get('/editnewsfeed/{id}', 'App\Http\Controllers\Backend\Newsfeed\NewsfeedController@editNewsfeed')->name('user.editnewsfeed.index');
$api->get('/deletenewsfeed/{id}', 'App\Http\Controllers\Backend\Newsfeed\NewsfeedController@deleteNewsfeed')->name('user.deletenewsfeed.index');