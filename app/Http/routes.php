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
    require (__DIR__ . '/Routes/Backend/Rescuer.php');
    require (__DIR__ . '/Routes/Backend/RescueOperation.php');
    require (__DIR__ . '/Routes/Backend/Notifications.php');
    require (__DIR__ . '/Routes/Backend/Statistics.php');
    require (__DIR__ . '/Routes/Backend/LogViewer.php');
});


$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['middleware' => 'cors'], function ($api) {
   $api->get('register', 'App\Http\Controllers\Frontend\Auth\AuthController@showRegistrationForm')->name('auth.register');
    $api->post('/login', [ 'uses' => 'App\Http\Controllers\AuthController@postLogin']);
    $api->post('/register', 'App\Http\Controllers\Frontend\Auth\AuthController@register');
    $api->post('/rescuerregister', 'App\Http\Controllers\Frontend\Auth\AuthController@rescuerregister');
    $api->get('/registrationform', 'App\Http\Controllers\Frontend\Auth\AuthController@showRegistrationForm');
    $api->get('get-states/{id}', function($id){
	$states = DB::table('states')->where('country_id', $id)->select(['id', 'name'])->get();
	return response()->json($states);
});

    $api->group(['middleware' => 'jwt.refresh'], function ($api) {
        $api->post('/refresh-token', [ 'uses' => 'App\Http\Controllers\AuthController@refreshToken']);
    });

    $api->group(['middleware' => ['jwt.auth']], function ($api) {

        //Newsfeeds
        $api->get('/newsfeed', 'App\Http\Controllers\Backend\Newsfeed\NewsfeedController@showNewsfeeds')->name('user.newsfeed.index');
        $api->post('/saveprofileimage', 'App\Http\Controllers\Frontend\User\ProfileController@saveProfileImage')->name('user.saveprofileimage.index');
        $api->post('/savenewsfeed', 'App\Http\Controllers\Backend\Newsfeed\NewsfeedController@createNewsfeed')->name('user.savenewsfeed.index');
        $api->get('/editnewsfeed/{id}', 'App\Http\Controllers\Backend\Newsfeed\NewsfeedController@editNewsfeed')->name('user.editnewsfeed.index');
        $api->get('/deletenewsfeed/{id}', 'App\Http\Controllers\Backend\Newsfeed\NewsfeedController@deleteNewsfeed')->name('user.deletenewsfeed.index');

        $api->post('/updateprofile', 'App\Http\Controllers\Frontend\Auth\AuthController@updateProfile');
        $api->post('/updaterescuerprofile', 'App\Http\Controllers\Frontend\Auth\AuthController@updaterescuerProfile');
        $api->get('/editprofile/{id}', 'App\Http\Controllers\Frontend\Auth\AuthController@editProfile');
        //Rescue Operations
        $api->get('/rescueeform', 'App\Http\Controllers\Backend\RescueOperation\RescueOperationController@rescueeForm')->name('rescuee.operation.form');
        $api->post('/rescueeoperations', 'App\Http\Controllers\Backend\RescueOperation\RescueOperationController@rescueeOperationActions')->name('rescuee.operation.index');
        $api->post('/rescueroperation', 'App\Http\Controllers\Backend\RescueOperation\RescueOperationController@rescuerOperationResponse')->name('rescuer.operation.index');
    });
});
