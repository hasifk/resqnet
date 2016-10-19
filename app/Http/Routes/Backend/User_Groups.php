<?php
Route::group([
    'namespace' => 'UserGroups',
        ], function() {
    Route::get('/usergroups', 'UserGroupsBackendController@userGroups')->name('user.groups.viewgroups');
    Route::get('/usergroup/{id}', 'UserGroupsBackendController@userGroup')->name('user.groups.view');
    Route::get('/setadministrator', 'UserGroupsBackendController@setAdministrator')->name('user.groups.setadmin');
    Route::get('/addmembers', 'UserGroupsBackendController@addMembers')->name('user.groups.addmemebers');
    Route::get('/viewmembers', 'UserGroupsBackendController@viewMembers')->name('user.groups.viewmemebers');
    Route::get('/postnewsfeed', 'UserGroupsBackendController@postNewsFeed')->name('user.groups.postnewsfeeds');
});
