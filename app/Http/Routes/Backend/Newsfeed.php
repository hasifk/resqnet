<?php
Route::group([
    'namespace'  => 'Newsfeed',
], function() {
        Route::get('/newsfeeds', 'AdminNewsfeedController@showNewsfeeds')->name('admin.newsfeed.index');
        Route::get('/newsfeedsearch', 'AdminNewsfeedController@newsFeedSearch')->name('admin.newsfeed.newsfeedsearch');
        Route::get('/newsfeed/{id}/show', 'AdminNewsfeedController@newsFeedShow')->name('admin.newsfeed.newsfeedshow');
        Route::get('/newsfeed/{id}/delete', 'AdminNewsfeedController@deleteNewsFeed')->name('admin.newsfeed.deletenewsfeed');
});