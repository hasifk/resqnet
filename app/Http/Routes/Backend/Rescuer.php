<?php

Route::group([
    'namespace'  => 'Rescuer',
], function() {
        Route::get('/departments', 'AdminRescuerController@showRescuerDepts')->name('backend.admin.rescure_departments');
        Route::get('/departments/{id}', 'AdminRescuerController@showRescuerDept')->name('backend.admin.rescure_department');
        Route::get('/department_create', 'AdminRescuerController@createRescuerDept')->name('backend.admin.department_create');
        Route::post('/department_save', 'AdminRescuerController@saveRescuerDept')->name('backend.admin.department_save');

});