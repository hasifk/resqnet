<?php
Route::group([
    'namespace'  => 'Payment',
], function() {
        Route::get('/paiddetailss', 'App\Http\Controllers\Backend\Payment\PaymentController@paidUserDetails')->name('user.payment.index');
        
});