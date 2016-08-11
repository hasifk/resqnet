<?php
$api->post('/saveprofileimage', 'App\Http\Controllers\Frontend\User\ProfileController@saveProfileImage')->name('user.saveprofileimage.index');
$api->post('/updateprofile', 'App\Http\Controllers\Frontend\Auth\AuthController@updateProfile');
$api->post('/updaterescuerprofile', 'App\Http\Controllers\Frontend\Auth\AuthController@updaterescuerProfile');
$api->get('/editprofile/{id}', 'App\Http\Controllers\Frontend\Auth\AuthController@editProfile');