<?php
Route::group([
    'namespace' => 'UserGroups',
        ], function() {
    Route::get('/usergroups', 'App\Http\Controllers\Backend\UserGroups\UserGroupsBackendController@userGroups')->name('user.groups.viewgroups');
    Route::get('/usergroup', 'App\Http\Controllers\Backend\UserGroups\UserGroupsBackendController@userGroup')->name('user.groups.view');
    Route::get('/setadministrator', 'App\Http\Controllers\Backend\UserGroups\UserGroupsBackendController@setAdministrator')->name('user.groups.setadmin');
    Route::get('/addmembers', 'App\Http\Controllers\Backend\UserGroups\UserGroupsBackendController@addMembers')->name('user.groups.addmemebers');
    Route::get('/viewmembers', 'App\Http\Controllers\Backend\UserGroups\UserGroupsBackendController@viewMembers')->name('user.groups.viewmemebers');
    Route::get('/postnewsfeed', 'App\Http\Controllers\Backend\UserGroups\UserGroupsBackendController@postNewsFeed')->name('user.groups.postnewsfeeds');
});
