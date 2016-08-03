<?php

Route::group([
    'namespace'  => 'Notifications',
], function() {
        Route::get('/notifications', 'NotificationController@notifications')->name('backend.admin.notifications');
        Route::get('/notification_create', 'NotificationController@create')->name('backend.admin.notificationcreate');
        Route::post('/notification_save', 'NotificationController@notificationSave')->name('backend.admin.notificationsave');
});