<?php
$api->get('/rescueeform', 'App\Http\Controllers\Backend\RescueOperation\RescueOperationController@rescueeForm')->name('rescuee.operation.form');
$api->post('/rescueeoperations', 'App\Http\Controllers\Backend\RescueOperation\RescueOperationController@rescueeOperationActions')->name('rescuee.operation.actions');
$api->post('/rescueoperations', 'App\Http\Controllers\Backend\RescueOperation\RescueOperationController@rescuerOperationDetails')->name('rescuer.operation.details');
$api->post('/rescueroperation', 'App\Http\Controllers\Backend\RescueOperation\RescueOperationController@rescuerOperationResponse')->name('rescuer.operation.response');
$api->post('/rescuerlocationupdates', 'App\Http\Controllers\Backend\RescueOperation\RescueOperationController@rescuerLocationUpdates')->name('rescuer.operation.locupdate');
$api->post('/rescuernotifications', 'App\Http\Controllers\Backend\RescueOperation\RescueOperationController@rescuerNotifications')->name('rescuer.operation.notifications');
$api->post('/notificationresponce', 'App\Http\Controllers\Backend\RescueOperation\RescueOperationController@notificationResponce')->name('rescuer.operation.notifresponse');
$api->post('/latestnotification', 'App\Http\Controllers\Backend\RescueOperation\RescueOperationController@latestNotification')->name('rescuer.operation.latestnotif');
$api->post('/rescueeoperationcancel', 'App\Http\Controllers\Backend\RescueOperation\RescueOperationController@rescueeOperationCancel')->name('rescuer.operation.cancel');
$api->post('/operationfinishing', 'App\Http\Controllers\Backend\RescueOperation\RescueOperationController@operationFinishing')->name('rescuer.operation.finishing');