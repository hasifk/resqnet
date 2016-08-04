<?php

Route::group([
    'namespace'  => 'Statistics',
], function() {
        Route::get('amountofusers', 'StatisticsController@amountOfUsers')->name('backend.admin.amountofusers');
});