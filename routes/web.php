<?php

use Illuminate\Support\Facades\Route;

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
Route::namespace('App\Http\Controllers\login')->group(function(){
    Route::get('/', 'loginController')->name('login');
    Route::post('/verificaLogin', 'loginController@verificaLogin')->name('verificaLogin');
    Route::get('/cadastraLogin', 'loginController@cadastraLogin')->name('cadastraLogin');
    Route::post('/registraLogin', 'loginController@registraLogin')->name('registraLogin');
    Route::get('/logout', 'loginController@logout')->name('logout');
});

Route::middleware(['auth'])->namespace('App\Http\Controllers\home')->group(function(){
    Route::get('/home', 'homeController')->name('home');
});

Route::namespace('App\Http\Controllers\api')->group(function(){
    Route::get('/galaxPay', 'galaxPayAPI@getClientesGalaxPay')->name('galaxPay');
});