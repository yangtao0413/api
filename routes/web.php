<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test/pay','Testcontroller@alipay');        //去支付
Route::get('/goods','Testcontroller@goods');
Route::get('/goods2','Testcontroller@goods2');
Route::get('/test/grab','Testcontroller@grab');
Route::get('/test/alipay/return','Alipay\PayController@aliReturn');
Route::post('/test/alipay/notify','Alipay\PayController@notify');
// 接口
Route::get('/api/test','Api\Testcontroller@test');
Route::post('/api/user/regist','Api\KekeController@regist');//用户注册
Route::post('/api/user/login','Api\KekeController@login'); //用户登录
Route::get('/api/user/list','Api\Testcontroller@userList')->middleware('filter');      //用户列表

Route::get('/test/abc','Testcontroller@abc');
Route::get('/test/cba','Testcontroller@cba');







