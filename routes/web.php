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
// ROTAS DE LOGIN
Route::namespace('App\Http\Controllers\login')->group(function () {
    Route::get('', 'loginController')->name('login');
    Route::post('/verificaLogin', 'loginController@verificaLogin')->name('verificaLogin');
    Route::get('/cadastraLogin', 'loginController@cadastraLogin')->name('cadastraLogin');
    Route::post('/registraLogin', 'loginController@registraLogin')->name('registraLogin');
    Route::get('/test', 'loginController@test')->name('test');
    Route::get('/logout', 'loginController@logout')->name('logout');
});

// ROTAS DA HOME
Route::middleware(['auth'])->namespace('App\Http\Controllers\home')->group(function () {
    Route::get('home', 'homeController')->name('home');
});

// ROTAS DE CLIENTES
Route::middleware(['auth'])->namespace('App\Http\Controllers\clientes')->group(function () {
    Route::get('/clientes', 'clientesController')->name('clientes');
    Route::get('/clientes/pesquisa/{pesquisaCliente}', 'clientesController@pesquisaCliente')->name('pesquisaCliente');
    Route::get('/clientes/gerarCartao', 'clientesController@gerarCartao')->name('clientes.gerarCartao');
});

// ROTAS DE EMPRESAS
Route::middleware(['auth'])->namespace('App\Http\Controllers\empresas')->group(function () {
    Route::get('/empresas', 'empresasController')->name('empresas');
});

// ROTAS DA RELATÓRIOS
Route::middleware(['auth'])->namespace('App\Http\Controllers\relatorios')->group(function () {
    Route::get('/relatorios', 'relatoriosController')->name('relatorios');
});

// ROTAS DE CONFIGURAÇÕES DO USUARIO LOGADO
Route::middleware(['auth'])->namespace('App\Http\Controllers\configUser')->group(function () {
    Route::get('/perfil', 'perfilController')->name('perfil');
    Route::put('/perfil/alterarSenha/{idUserEdit}', 'perfilController@editPassword')->name('editPassword');
    Route::put('/perfil/atualizaUsuario/{idUserEdit}', 'perfilController@editUser')->name('editUser');
    Route::delete('/perfil/delete/{idUserDelete}', 'perfilController@deleteUser')->name('deleteUser');
});

// ROTAS DA API - GALAXPAY
Route::middleware(['auth'])->namespace('App\Http\Controllers\api')->group(function () {
    Route::get('/galaxPay/generateAcessToken', 'galaxPayControllerAPI@generateAcessToken')->name('galaxpay.accessToken');
    Route::get('/galaxPay/importaClientesGalaxPay', 'galaxPayControllerAPI@importaClientesGalaxPay')->name('galaxpay.clientes');
    Route::get('/galaxPay/pesquisaCliente/{searchOption}/{search}', 'galaxPayControllerAPI@pesquisaClientesGalaxPay')->name('galaxpay.pesquisaClientes');
});
