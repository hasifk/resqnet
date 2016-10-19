<?php
Route::group([
    'namespace'  => 'Notifications',
], function() {
        Route::get('/notifications', 'NotificationController@notifications')->name('backend.admin.notifications');
        Route::get('/notifications/{id}', 'NotificationController@notification')->name('backend.admin.notification');
        Route::get('/notification_create', 'NotificationController@create')->name('backend.admin.notificationcreate');
        Route::post('/notification_save', 'NotificationController@notificationSave')->name('backend.admin.notificationsave');
        Route::get('notificationdelete','NotificationController@NotificationDelete')->name('backend.admin.notificationdelete');
        Route::get('getstates/{id}','NotificationController@states')->name('backend.admin.notificationstate');
        Route::get('getareas/{id}','NotificationController@areas')->name('backend.admin.notificationsarea');
        Route::get('search','NotificationController@search')->name('backend.admin.notificationsearch');
});