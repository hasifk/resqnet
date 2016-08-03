<?php

Route::group([
    'namespace'  => 'RescueOperation',
], function() {
        Route::get('/operations', 'AdminOperationController@operations')->name('backend.admin.rescue_operations');
});