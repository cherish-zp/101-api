<?php



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

Route::namespace('Api')->group(function() {
    Route::post('signIn', 'AuthController@signIn');
    Route::post('logout', 'AuthController@logout');
    Route::post('signUp', 'AuthController@signUp');
    Route::post('me', 'AuthController@me');
});

Route::middleware(['jwt.api.auth','jwt.auth'])->namespace('Api')->group(function($router){
    $router->post('passwordReset', 'AuthController@passwordReset');

});
