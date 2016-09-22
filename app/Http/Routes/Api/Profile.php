<?php

$api->post('/saveprofileimage', 'App\Http\Controllers\Frontend\User\ProfileController@saveProfileImage')->name('user.saveprofileimage.index');



$api->post('/updateprofile', 'App\Http\Controllers\Frontend\Auth\AuthController@updateProfile');
$api->post('/updaterescuerprofile', 'App\Http\Controllers\Frontend\Auth\AuthController@updaterescuerProfile');
$api->post('/editprofile', 'App\Http\Controllers\Frontend\Auth\AuthController@editProfile');
$api->post('/savedoctors', 'App\Http\Controllers\Frontend\User\ProfileController@saveDoctors');
$api->post('/editdoctors', 'App\Http\Controllers\Frontend\User\ProfileController@editDoctors');
$api->post('/updatedoctors', 'App\Http\Controllers\Frontend\User\ProfileController@updateDoctors');
$api->post('/updatemedicalcondition', 'App\Http\Controllers\Frontend\User\ProfileController@updateMedicalCondition');
$api->post('password/change', 'App\Http\Controllers\Frontend\Auth\PasswordController@changePassword')->name('auth.password.update');
$api->post('updateonlinestatus', 'App\Http\Controllers\Frontend\User\ProfileController@updateOnlineStatus')->name('user.online.status');
$api->post('saveemergencycontacts', 'App\Http\Controllers\Frontend\User\ProfileController@saveEmergencyContacts');
$api->post('viewemergencycontacts', 'App\Http\Controllers\Frontend\User\ProfileController@viewEmergencyContacts');
$api->post('savehealthinsurance', 'App\Http\Controllers\Frontend\User\ProfileController@saveHealthInsurance');
$api->post('viewhealthinsurance', 'App\Http\Controllers\Frontend\User\ProfileController@viewHealthInsurance');
$api->post('upgrade', 'App\Http\Controllers\Frontend\User\ProfileController@upgradeStatus');

$api->post('/updatefbinfo', 'App\Http\Controllers\Frontend\Auth\AuthController@updateFbInfo');
