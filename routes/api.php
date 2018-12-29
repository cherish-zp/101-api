<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('Api')->group(function($router) {

    $router->post('login', 'AuthController@login');
    $router->post('logout', 'AuthController@logout');
    $router->post('signUp', 'AuthController@signUp',function (){
        dd(11);
    });
    $router->post('me', 'AuthController@me');
    $router->post('passwordReset', 'AuthController@passwordReset');

});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
