<?php
$api->get('/newsfeed/{id}', 'App\Http\Controllers\Backend\Newsfeed\NewsfeedController@showNewsfeeds')->name('user.newsfeed.index');
$api->get('/newsfeedviews/{id}', 'App\Http\Controllers\Backend\Newsfeed\NewsfeedController@showNewsfeed')->name('user.newsfeedview.index');
$api->get('/mynewsfeed/{id}', 'App\Http\Controllers\Backend\Newsfeed\NewsfeedController@showMyNewsfeeds')->name('my.newsfeed.index');
$api->post('/savenewsfeed', 'App\Http\Controllers\Backend\Newsfeed\NewsfeedController@createNewsfeed')->name('user.savenewsfeed.index');
$api->post('/updatenewsfeed', 'App\Http\Controllers\Backend\Newsfeed\NewsfeedController@updateNewsfeed')->name('user.updatenewsfeed.index');
$api->get('/editnewsfeed/{id}', 'App\Http\Controllers\Backend\Newsfeed\NewsfeedController@editNewsfeed')->name('user.editnewsfeed.index');
$api->get('/deletenewsfeed/{id}', 'App\Http\Controllers\Backend\Newsfeed\NewsfeedController@deleteNewsfeed')->name('user.deletenewsfeed.index');