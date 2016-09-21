<?php
$api->get('register', 'App\Http\Controllers\Frontend\Auth\AuthController@showRegistrationForm')->name('auth.register');
$api->post('/login', [ 'uses' => 'App\Http\Controllers\AuthController@postLogin']);
$api->post('/register', 'App\Http\Controllers\Frontend\Auth\AuthController@register');
$api->post('/fbregister', 'App\Http\Controllers\Frontend\Auth\AuthController@fbRegister');
$api->post('/rescuerregister', 'App\Http\Controllers\Frontend\Auth\AuthController@rescuerregister');
$api->get('getstates/{id}', function($id) {
    $states = DB::table('states')->where('country_id', $id)->select(['id', 'name'])->get();
    return response()->json($states);
});
$api->get('getdepartments/{id}', function($id) {
    $depts = \DB::table('departments')->where('rescuertype_id',$id)->select(['id', 'department'])->get();
    return response()->json($depts);
});


$api->get('getareas/{id}', function($id){
    $areas = DB::table('cities')->where('state_id', $id)->select(['id', 'name'])->get();
    return response()->json($areas);
});

$api->group(['middleware' => 'jwt.refresh'], function ($api) {
    $api->post('/refresh-token', [ 'uses' => 'App\Http\Controllers\AuthController@refreshToken']);
});