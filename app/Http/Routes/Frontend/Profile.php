<?php
Route::get('/avatar/{id}/{image}', 'User\ProfileController@getAvatar')->name('user.get.avatar');