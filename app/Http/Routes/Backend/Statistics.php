<?php

Route::group([
    'namespace'  => 'Statistics',
], function() {
        Route::get('amountofusers', 'StatisticsController@amountOfUsers')->name('backend.admin.amountofusers');
        Route::get('useramount', 'StatisticsController@userAmount')->name('admin.statistics.useramount');
        Route::get('amountofrescuers', 'StatisticsController@amountOfRescuers')->name('admin.statistics.amountofrescuers');
        Route::get('rescueramount', 'StatisticsController@rescuerAmount')->name('admin.statistics.rescueramount');
});