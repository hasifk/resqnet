<?php
$api->get('/rescueeform', 'App\Http\Controllers\Backend\RescueOperation\RescueOperationController@rescueeForm')->name('rescuee.operation.form');
$api->post('/rescueeoperations', 'App\Http\Controllers\Backend\RescueOperation\RescueOperationController@rescueeOperationActions')->name('rescuee.operation.index');
$api->post('/rescueoperations', 'App\Http\Controllers\Backend\RescueOperation\RescueOperationController@rescuerOperationDetails')->name('rescuer.operation.index');
$api->post('/rescueroperation', 'App\Http\Controllers\Backend\RescueOperation\RescueOperationController@rescuerOperationResponse')->name('rescuer.operation.index');
$api->post('/rescuerlocationupdates', 'App\Http\Controllers\Backend\RescueOperation\RescueOperationController@rescuerLocationUpdates')->name('rescuer.operation.index');
$api->post('/rescuernotifications', 'App\Http\Controllers\Backend\RescueOperation\RescueOperationController@rescuerNotifications')->name('rescuer.operation.index');
$api->post('/notificationresponce', 'App\Http\Controllers\Backend\RescueOperation\RescueOperationController@notificationResponce')->name('rescuer.operation.index');