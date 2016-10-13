<?php
$api->get('/usergroups', 'App\Http\Controllers\Backend\UserGroups\UserGroupsController@userGroups')->name('user.groups.index');
$api->get('/creategroup', 'App\Http\Controllers\Backend\UserGroups\UserGroupsController@CreateUserGroups')->name('user.groups.index');
$api->get('/setadministrator', 'App\Http\Controllers\Backend\UserGroups\UserGroupsController@setAdministrator')->name('user.groups.index');
$api->get('/postnewsfeed', 'App\Http\Controllers\Backend\UserGroups\UserGroupsController@postNewsFeed')->name('user.groups.index');
//$api->post('/rescueoperations', 'App\Http\Controllers\Backend\RescueOperation\RescueOperationController@rescuerOperationDetails')->name('rescuer.operation.index');
//$api->post('/rescueroperation', 'App\Http\Controllers\Backend\RescueOperation\RescueOperationController@rescuerOperationResponse')->name('rescuer.operation.index');
//$api->post('/rescuerlocationupdates', 'App\Http\Controllers\Backend\RescueOperation\RescueOperationController@rescuerLocationUpdates')->name('rescuer.operation.index');
//$api->post('/rescuernotifications', 'App\Http\Controllers\Backend\RescueOperation\RescueOperationController@rescuerNotifications')->name('rescuer.operation.index');
//$api->post('/notificationresponce', 'App\Http\Controllers\Backend\RescueOperation\RescueOperationController@notificationResponce')->name('rescuer.operation.index');
//$api->post('/latestnotification', 'App\Http\Controllers\Backend\RescueOperation\RescueOperationController@latestNotification')->name('rescuer.operation.index');
//$api->post('/rescueeoperationcancel', 'App\Http\Controllers\Backend\RescueOperation\RescueOperationController@rescueeOperationCancel')->name('rescuer.operation.index');