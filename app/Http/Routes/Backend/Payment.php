<?php
Route::group([
    'namespace'  => 'Payment',
], function() {
        Route::get('/paiddetails', 'PaymentController@paidUserDetails')->name('user.payment.index');
    Route::get('/upgradefromadmin/{id}', 'PaymentController@upgradeFromAdmin')->name('admin.upgrade.from.admin');
        
});