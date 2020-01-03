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


Route::get('/test/pay','Testcontroller@alipay');



Route::post('/api/user/regist','Api\KekeController@regist');
Route::post('/api/user/login','Api\KekeController@login');
