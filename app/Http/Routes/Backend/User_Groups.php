<?php
$api->post('/usergroups', 'App\Http\Controllers\Backend\UserGroups\UserGroupsBackendController@userGroups')->name('user.groups.viewgroups');
$api->post('/creategroup', 'App\Http\Controllers\Backend\UserGroups\UserGroupsBackendController@CreateUserGroups')->name('user.groups.create');
$api->post('/usergroup', 'App\Http\Controllers\Backend\UserGroups\UserGroupsBackendController@userGroup')->name('user.groups.view');
$api->post('/setadministrator', 'App\Http\Controllers\Backend\UserGroups\UserGroupsBackendController@setAdministrator')->name('user.groups.setadmin');
$api->post('/addmembers', 'App\Http\Controllers\Backend\UserGroups\UserGroupsBackendController@addMembers')->name('user.groups.addmemebers');
$api->post('/viewmembers', 'App\Http\Controllers\Backend\UserGroups\UserGroupsBackendController@viewMembers')->name('user.groups.viewmemebers');
$api->post('/postnewsfeed', 'App\Http\Controllers\Backend\UserGroups\UserGroupsBackendController@postNewsFeed')->name('user.groups.postnewsfeeds');
//$api->post('/rescueoperations', 'App\Http\Controllers\Backend\RescueOperation\RescueOperationController@rescuerOperationDetails')->name('rescuer.operation.index');
//$api->post('/rescueroperation', 'App\Http\Controllers\Backend\RescueOperation\RescueOperationController@rescuerOperationResponse')->name('rescuer.operation.index');
//$api->post('/rescuerlocationupdates', 'App\Http\Controllers\Backend\RescueOperation\RescueOperationController@rescuerLocationUpdates')->name('rescuer.operation.index');
//$api->post('/rescuernotifications', 'App\Http\Controllers\Backend\RescueOperation\RescueOperationController@rescuerNotifications')->name('rescuer.operation.index');
//$api->post('/notificationresponce', 'App\Http\Controllers\Backend\RescueOperation\RescueOperationController@notificationResponce')->name('rescuer.operation.index');
//$api->post('/latestnotification', 'App\Http\Controllers\Backend\RescueOperation\RescueOperationController@latestNotification')->name('rescuer.operation.index');
//$api->post('/rescueeoperationcancel', 'App\Http\Controllers\Backend\RescueOperation\RescueOperationController@rescueeOperationCancel')->name('rescuer.operation.index');