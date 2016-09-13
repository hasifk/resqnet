<?php
$api->post('/newsfeed', 'App\Http\Controllers\Backend\Newsfeed\NewsfeedController@showNewsfeeds')->name('user.newsfeed.index');
$api->post('/newsfeedviews', 'App\Http\Controllers\Backend\Newsfeed\NewsfeedController@showNewsfeed')->name('user.newsfeedview.index');
$api->post('/mynewsfeed', 'App\Http\Controllers\Backend\Newsfeed\NewsfeedController@showMyNewsfeeds')->name('my.newsfeed.index');
$api->post('/savenewsfeed', 'App\Http\Controllers\Backend\Newsfeed\NewsfeedController@createNewsfeed')->name('user.savenewsfeed.index');
$api->post('/updatenewsfeed', 'App\Http\Controllers\Backend\Newsfeed\NewsfeedController@updateNewsfeed')->name('user.updatenewsfeed.index');
$api->post('/editnewsfeed', 'App\Http\Controllers\Backend\Newsfeed\NewsfeedController@editNewsfeed')->name('user.editnewsfeed.index');
$api->post('/deletenewsfeed', 'App\Http\Controllers\Backend\Newsfeed\NewsfeedController@deleteNewsfeed')->name('user.deletenewsfeed.index');