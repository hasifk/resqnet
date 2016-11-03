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

    require (__DIR__ . '/Routes/Images/Images.php');
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
    require (__DIR__ . '/Routes/Backend/Rescuer.php');
    require (__DIR__ . '/Routes/Backend/RescueOperation.php');
    require (__DIR__ . '/Routes/Backend/Notifications.php');
    require (__DIR__ . '/Routes/Backend/Statistics.php');
    require (__DIR__ . '/Routes/Backend/LogViewer.php');
    require (__DIR__ . '/Routes/Backend/User_Groups.php');
});


$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['middleware' => 'cors'], function ($api) {

    require (__DIR__ . '/Routes/Api/Reg_Login.php');
    require (__DIR__ . '/Routes/Api/Access.php');
   $api->post('/paypal', 'App\Http\Controllers\Backend\Payment\PaymentController@paymentSave')->name('user.groups.index');
   /* $api->group(['middleware' => ['jwt.auth']], function ($api) {*/
    $api->group(['middleware' => ['appdcr']], function ($api) {
        require (__DIR__ . '/Routes/Api/Profile.php');
        require (__DIR__ . '/Routes/Api/Newsfeed.php');
        require (__DIR__ . '/Routes/Api/RescueOperations.php');
        require (__DIR__ . '/Routes/Api/User_Groups.php');
    });
    /*});*/
});
