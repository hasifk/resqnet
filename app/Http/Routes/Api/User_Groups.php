<?php
$api->post('/usergroups', 'App\Http\Controllers\Backend\UserGroups\UserGroupsController@userGroups')->name('user.groups.index');
$api->post('/creategroup', 'App\Http\Controllers\Backend\UserGroups\UserGroupsController@CreateUserGroups')->name('user.groups.index');
$api->post('/usergroup', 'App\Http\Controllers\Backend\UserGroups\UserGroupsController@userGroup')->name('user.groups.index');
$api->post('/joinedgrouplists', 'App\Http\Controllers\Backend\UserGroups\UserGroupsController@joinedGroupLists')->name('user.groups.index');
$api->post('/joinusers', 'App\Http\Controllers\Backend\UserGroups\UserGroupsController@joinUsers')->name('user.groups.index');
$api->post('/addmembers', 'App\Http\Controllers\Backend\UserGroups\UserGroupsController@addMembers')->name('user.groups.index'); 
$api->post('/viewmembers', 'App\Http\Controllers\Backend\UserGroups\UserGroupsController@viewMembers')->name('user.groups.index'); 
$api->post('/postnewsfeed', 'App\Http\Controllers\Backend\UserGroups\UserGroupsController@postNewsFeed')->name('user.groups.index'); 
$api->post('/addemergencygroups', 'App\Http\Controllers\Backend\UserGroups\UserGroupsController@addEmergencyGroups')->name('user.groups.index'); 
$api->post('/paymentdetails', 'App\Http\Controllers\Backend\Payment\PaymentController@paymentDetails')->name('user.groups.index');