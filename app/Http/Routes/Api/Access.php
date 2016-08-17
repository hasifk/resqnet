<?php
$api->post('password/change', 'App\Http\Controllers\Frontend\Auth\PasswordController@changePassword')->name('auth.password.update');