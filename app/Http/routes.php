<?php

Route::group(['middleware' => 'web'], function() {
    /**
     * Switch between the included languages
     */
    Route::group(['namespace' => 'Language'], function () {
        require (__DIR__ . '/Routes/Language/Language.php');
    });

    /**
     * Frontend Routes
     * Namespaces indicate folder structure
     */
    Route::group(['namespace' => 'Frontend'], function () {
        require (__DIR__ . '/Routes/Frontend/Frontend.php');
        require (__DIR__ . '/Routes/Frontend/Access.php');
    });
});

/**
 * Backend Routes
 * Namespaces indicate folder structure
 * Admin middleware groups web, auth, and routeNeedsPermission
 */
Route::group(['namespace' => 'Backend', 'prefix' => 'admin', 'middleware' => 'admin'], function () {
    /**
     * These routes need view-backend permission
     * (good if you want to allow more than one group in the backend,
     * then limit the backend features by different roles or permissions)
     *
     * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
     */
    require (__DIR__ . '/Routes/Backend/Dashboard.php');
    require (__DIR__ . '/Routes/Backend/Access.php');
    require (__DIR__ . '/Routes/Backend/Newsfeed.php');
    require (__DIR__ . '/Routes/Backend/LogViewer.php');
});


$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['middleware' => 'cors'], function ($api) {

    $api->post('/login', [ 'uses' => 'App\Http\Controllers\AuthController@postLogin' ]);
    $api->post('/register', [ 'uses' => 'App\Http\Controllers\AuthController@postRegister' ]);
  /*  $api->post('/password1', [ 'uses' => 'App\Http\Controllers\Auth\PasswordController@postEmail' ]);
    $api->post('/password/reset1', [ 'uses' => 'App\Http\Controllers\Auth\PasswordController@postReset' ]);*/

    $api->group(['middleware' => 'jwt.refresh'], function ($api) {
        $api->post('/refresh-token', [ 'uses' => 'App\Http\Controllers\AuthController@refreshToken' ]);
    });

    $api->group(['middleware' => ['jwt.auth']], function ($api) {
        $api->get('/newsfeeds', 'App\Http\Controllers\Backend\Newsfeed\NewsfeedController@showNewsfeeds');
        $api->get('/save_newsfeed', 'App\Http\Controllers\Backend\Newsfeed\NewsfeedController@createNewsfeed');
        $api->get('/edit_newsfeed/{id}', 'App\Http\Controllers\Backend\Newsfeed\NewsfeedController@editNewsfeed');
        $api->get('/delete_newsfeed/{id}', 'App\Http\Controllers\Backend\Newsfeed\NewsfeedController@deleteNewsfeed');
        
        /*$api->post('/me', 'App\Http\Controllers\AuthController@getMe');
        $api->get('/task', 'App\Http\Controllers\TaskController@index');
        $api->post('/task', 'App\Http\Controllers\TaskController@store');
        $api->post('/task/delete/{id}', 'App\Http\Controllers\TaskController@destroy');
        $api->post('/task/edit/{id}', 'App\Http\Controllers\TaskController@edit');
        $api->post('/task/update/{id}', 'App\Http\Controllers\TaskController@update');*/
    });
});