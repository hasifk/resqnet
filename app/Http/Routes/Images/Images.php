<?php
Route::get('/avatar/{id}/{image}', 'Frontend\User\ProfileController@getAvatar')->name('user.get.avatar');
Route::get('/image/{id}/{image}', 'Backend\Newsfeed\NewsfeedController@getImage')->name('newsfeed.get.image');
Route::get('/gp_image/{id}/{image}', 'Backend\UserGroups\UserGroupsController@getImage')->name('usergroups.get.image');