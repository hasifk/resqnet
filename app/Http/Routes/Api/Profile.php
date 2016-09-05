<?php

$api->post('/saveprofileimage', 'App\Http\Controllers\Frontend\User\ProfileController@saveProfileImage')->name('user.saveprofileimage.index');



$api->post('/updateprofile', 'App\Http\Controllers\Frontend\Auth\AuthController@updateProfile');
$api->post('/updaterescuerprofile', 'App\Http\Controllers\Frontend\Auth\AuthController@updaterescuerProfile');
$api->get('/editprofile/{id}', 'App\Http\Controllers\Frontend\Auth\AuthController@editProfile');
$api->post('/savedoctors', 'App\Http\Controllers\Frontend\User\ProfileController@saveDoctors');
$api->get('/editdoctors/{id}', 'App\Http\Controllers\Frontend\User\ProfileController@editDoctors');
$api->post('/updatedoctors', 'App\Http\Controllers\Frontend\User\ProfileController@updateDoctors');
$api->post('/updatemedicalcondition', 'App\Http\Controllers\Frontend\User\ProfileController@updateMedicalCondition');
$api->post('password/change', 'App\Http\Controllers\Frontend\Auth\PasswordController@changePassword')->name('auth.password.update');
$api->post('updateonlinestatus', 'App\Http\Controllers\Frontend\User\ProfileController@updateOnlineStatus')->name('user.online.status');