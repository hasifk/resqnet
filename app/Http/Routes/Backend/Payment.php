<?php
Route::group([
    'namespace'  => 'Payment',
], function() {
        Route::get('/paiddetails', 'PaymentController@paidUserDetails')->name('user.payment.index');
        
});