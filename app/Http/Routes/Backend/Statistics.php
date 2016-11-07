<?php

Route::group([
    'namespace'  => 'Statistics',
], function() {
        Route::get('amountofusers', 'StatisticsController@amountOfUsers')->name('backend.admin.amountofusers');
        Route::get('useramount', 'StatisticsController@userAmount')->name('admin.statistics.useramount');
        Route::get('amountofrescuers', 'StatisticsController@amountOfRescuers')->name('admin.statistics.amountofrescuers');
        Route::get('rescueramount', 'StatisticsController@rescuerAmount')->name('admin.statistics.rescueramount');
        Route::get('amountofnewsfeeds', 'StatisticsController@amountOfNewsfeeds')->name('admin.statistics.amountofnewsfeeds');
        Route::get('newsfeedamount', 'StatisticsController@newsfeedAmount')->name('admin.statistics.newsfeedamount');
        Route::get('amountofpanicsignals', 'StatisticsController@amountOfPanicSignals')->name('admin.statistics.amountofpanicsignals');
        Route::get('panicsignalamount', 'StatisticsController@panicsignalAmount')->name('admin.statistics.panicsignalsamount');
        Route::get('listsofrescuers', 'StatisticsController@listsOfRescuers')->name('admin.statistics.listsofrescuers');
        Route::get('listsofrescuer/{id}/show', 'StatisticsController@listsOfRescuer')->name('admin.statistics.listsofrescuer');
        Route::get('rescuerslists', 'StatisticsController@rescuersLists')->name('admin.statistics.rescuerslists');
        Route::get('deletepanic/{id}', 'StatisticsController@deletePanic')->name('admin.statistics.deletepanic');
        
       
});