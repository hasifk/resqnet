<?php

Route::group([
    'namespace'  => 'Resquer',
], function() {
        Route::get('/resquer', 'AdminResquerController@CreateResquerType')->name('admin.resquer.index');
        Route::get('/resquerp', 'AdminResquerController@CreateResquerType')->name('admin.resquer.index');
        Route::get('/resquer_save', 'AdminResquerController@CreateResquerType')->name('admin.resquer.index');

});