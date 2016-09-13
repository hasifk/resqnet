<?php
$api->post('password/email', 'App\Http\Controllers\Frontend\Auth\PasswordController@sendResetLinkEmail');
$api->get('password/reset/{token?}', 'App\Http\Controllers\Frontend\Auth\PasswordController@showResetForm')
    ->name('auth.password.reset');
$api->post('password/reset', 'App\Http\Controllers\Frontend\Auth\PasswordController@reset');