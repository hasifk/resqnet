<?php

Route::group([
    'namespace'  => 'Newsfeed',
], function() {
        Route::get('/newsfeeds', 'AdminNewsfeedController@showNewsfeeds')->name('admin.newsfeed.index');

});