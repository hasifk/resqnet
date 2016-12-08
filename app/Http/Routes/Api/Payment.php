<?php
$api->post('/paymentdetails', 'App\Http\Controllers\Backend\Payment\PaymentController@paymentDetails')->name('user.groups.index');
$api->post('/paiddetails', 'App\Http\Controllers\Backend\Payment\PaymentController@paidUserDetails')->name('user.payment.index');