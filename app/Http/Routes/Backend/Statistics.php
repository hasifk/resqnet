<?php

Route::group([
    'namespace'  => 'Statistics',
], function() {
        Route::get('amountofusers', 'StatisticsController@amountOfUsers')->name('backend.admin.amountofusers');
        Route::post('checkcountry', 'StatisticsController@checkCountry')->name('admin.statistics.checkcountry');
        Route::post('checkarea', 'StatisticsController@checkArea')->name('admin.statistics.checkarea');
});