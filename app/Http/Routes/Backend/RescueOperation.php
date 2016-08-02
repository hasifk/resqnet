<?php

Route::group([
    'namespace'  => 'RescueOperation',
], function() {
        Route::get('/rescueoperations', 'AdminOperationController@operations')->name('backend.admin.rescue_operations');

});