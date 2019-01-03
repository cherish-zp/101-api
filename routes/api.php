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

bcscale(8);
Route::namespace('Api')->group(function() {

    Route::post('signIn', 'AuthController@signIn');
    Route::post('logout', 'AuthController@logout');
    Route::post('signUp', 'AuthController@signUp');
    //验证码
    Route::post('captcha/sms', 'CaptchaController@sms');
    Route::post('sendMessage', 'sendMessageController@index');
    //banner
    Route::post('banner','BannerController@index');
    //忘记密码
    Route::post('forgetPassword', 'AuthController@forgetPassword');
});

Route::middleware(['jwt.api.auth','jwt.auth'])->namespace('Api')->group(function(){
    //修改密码
    Route::post('passwordReset', 'AuthController@passwordReset');
    //用户资产
    Route::post('userInfo', 'AuthController@me');
    //用户排队
    Route::post('queue', 'EnterController@queue');
    //用户进场
    Route::post('enter', 'EnterController@enter');
    //积分复投
    Route::post('repeat','RepeatController@integralRepeat');
});
