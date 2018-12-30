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

    //banner
    Route::post('banner','BannerController@index');
});

Route::middleware(['jwt.api.auth','jwt.auth'])->namespace('Api')->group(function(){
    //修改密码
    Route::post('passwordReset', 'AuthController@passwordReset');
    //用户资产
    Route::post('userInfo', 'AuthController@me');

});
