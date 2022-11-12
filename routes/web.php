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
    Route::middleware('RedirectIfAuthenticated')->group(function () {
        Route::get('', 'loginController')->name('login');
        Route::post('/verificaLogin', 'loginController@verificaLogin')->name('verificaLogin');
        Route::get('/cadastraLogin', 'loginController@cadastraLogin')->name('cadastraLogin');
        Route::post('/registraLogin', 'loginController@registraLogin')->name('registraLogin');
        Route::get('/esqueceuSenha', 'loginController@esqueceuSenha')->name('esqueceuSenha');
        Route::post('/esqueceuSenha', 'loginController@enviarEmailRecuperacao')->name('esqueceuSenha.enviarEmailRecuperacao');
        Route::get('/redefinirSenha/{token}', 'loginController@redefinirSenhaToken')->name('password.reset');
        Route::post('/redefinirSenha', 'loginController@redefinirSenha')->name('redefinirSenha');
    });
    Route::get('/logout', 'loginController@logout')->name('logout');
});

// ROTAS DA HOME
Route::middleware(['auth'])->namespace('App\Http\Controllers\home')->group(function () {
    Route::get('home', 'homeController')->name('home');
});

// ROTAS DE CLIENTES
Route::middleware(['auth', 'acessoFuncionario'])->namespace('App\Http\Controllers\clientes')->prefix('clientes')->group(function () {
    Route::get('', 'clientesController')->name('clientes');
    Route::get('/informacoesCliente/{clienteGalaxPay}', 'clientesController@informacoesClienteGalaxPay')->name('clientes.informacoesCliente');
    Route::get('/criar', 'clientesController@criarClienteGalaxPay')->name('clientes.criarClienteGalaxPay');
    Route::put('/editar/{clienteGalaxPay}', 'clientesController@editClienteGalaxPay')->name('clientes.editClienteGalaxPay');
    Route::get('/pesquisa/{pesquisaCliente}', 'clientesController@pesquisaCliente')->name('pesquisaCliente');
    Route::get('/pesquisa/{opcaoPesquisa}/{pesquisaCliente}', 'clientesController@pesquisaClienteDependente')->name('clientes.pesquisaClienteDependente');
    Route::get('/gerarCartaoJs', 'clientesController@gerarCartaoJs')->name('clientes.gerarCartaoJs');
    Route::get('/gerarCartaoCliente', 'clientesController@gerarCartaoCliente')->name('clientes.gerarCartaoCliente');
});

// ROTAS DE EMPRESAS
Route::middleware(['auth'])->namespace('App\Http\Controllers\empresasParceiras')->prefix('empresasParceiras')->group(function () {
    Route::get('', 'empresasParceirasController')->middleware(['acessoFuncionario'])->name('empresasParceiras');
    Route::post('/cadastro', 'empresasParceirasController@cadastroEmpresaParceira')->middleware(['acessoFuncionario'])->name('empresasParceiras.cadastro');
    Route::put('/editar/{empresaParceira}', 'empresasParceirasController@editEmpresaParceira')->middleware(['acessoFuncionario'])->name('empresasParceiras.edit');
    Route::put('/editarPassword/{empresaParceira}', 'empresasParceirasController@editPasswordEmpresaParceira')->name('empresasParceiras.editPassword');
    Route::get('/clientesStatus', 'empresasParceirasController@clientesStatusEmpresaParceira')->name('empresasParceiras.clientesStatus');
    Route::get('/pesquisa/{opcaoPesquisa}/{pesquisaCliente}', 'empresasParceirasController@pesquisaClienteDependente')->name('empresasParceiras.pesquisaClienteDependente');
    Route::get('/delete/{idEmpresaParceiraDelete}', 'empresasParceirasController@deleteEmpresaParceira')->middleware(['acessoFuncionario'])->name('empresasParceiras.delete');
});

// ROTAS DA CAIXA
Route::middleware(['auth', 'acessoFuncionario'])->namespace('App\Http\Controllers\caixa')->prefix('caixa')->group(function () {
    Route::get('', 'caixaController')->name('caixa');
    Route::post('/abrirCaixa', 'caixaController@abrirCaixa')->name('caixa.abrirCaixa');
    Route::post('/fecharCaixa/{caixaFinanceiro}', 'caixaController@fecharCaixa')->name('caixa.fecharCaixa');
    Route::post('/adicionarRecebimento/{caixaFinanceiro}', 'caixaController@adicionarRecebimento')->name('caixa.adicionarRecebimento');
    Route::post('/adicionarDespesa/{caixaFinanceiro}', 'caixaController@adicionarDespesa')->name('caixa.adicionarDespesa');
});

// ROTAS DA FINANCEIRO
Route::middleware(['auth', 'acessoFuncionario'])->namespace('App\Http\Controllers\financeiro')->prefix('financeiro')->group(function () {
    Route::get('', 'financeiroController')->name('financeiro');
    Route::post('/criar', 'financeiroController@criarConta')->name('financeiro.criarConta');
    Route::get('/visualizar/{conta}', 'financeiroController@visualizarConta')->name('financeiro.visualizarConta');
    Route::put('/editar/{conta}', 'financeiroController@editarConta')->name('financeiro.editarConta');
    Route::put('/excluir/{conta}', 'financeiroController@excluirConta')->name('financeiro.excluirConta');
});

// ROTAS DE EMPRESAS
Route::middleware(['auth', 'acessoFuncionario'])->namespace('App\Http\Controllers\funcionarios')->prefix('funcionarios')->group(function () {
    Route::get('', 'funcionariosController')->name('funcionarios');
    Route::post('/cadastro', 'funcionariosController@cadastroFuncionario')->name('funcionarios.cadastro');
    Route::put('/editar/{funcionario}', 'funcionariosController@editFuncionario')->name('funcionarios.edit');
    Route::get('/perfil', 'funcionariosController@perfilFuncionario')->name('funcionarios.perfil');
    Route::put('/perfil/alterarSenha/{idUserEdit}', 'funcionariosController@editPasswordFuncionario')->name('funcionarios.editPassword');
    Route::get('/delete/{idFuncionarioDelete}', 'funcionariosController@deleteFuncionario')->name('funcionarios.delete');
});


// ROTAS DE CONFIGURAÇÕES DO USUARIO LOGADO
Route::middleware(['auth'])->namespace('App\Http\Controllers\parametrosUser')->group(function () {
    Route::get('/perfil', 'perfilController')->name('perfil');
    Route::put('/perfil/alterarSenha/{idUserEdit}', 'perfilController@editPassword')->name('editPassword');
    Route::put('/perfil/atualizaUsuario/{userEdit}', 'perfilController@editUser')->name('editUser');
    Route::delete('/perfil/delete/{idUserDelete}', 'perfilController@deleteUser')->name('deleteUser');
});

// ROTAS DA API - GALAXPAY
Route::middleware(['auth'])->namespace('App\Http\Controllers\api')->prefix('galaxPay')->group(function () {
    Route::get('', 'galaxPayControllerAPI')->middleware(['acessoFuncionario'])->name('galaxPay');
    Route::get('/generateAcessToken', 'galaxPayControllerAPI@generateAcessToken')->middleware(['acessoFuncionario'])->name('galaxPay.accessToken');
    Route::get('/importaClientesGalaxPay', 'galaxPayControllerAPI@importaClientesGalaxPay')->middleware(['acessoFuncionario'])->name('galaxPay.clientes');
    Route::get('/importaContratoCliente/{clienteGalaxpay}', 'galaxPayControllerAPI@importaContratoCliente')->middleware(['acessoFuncionario'])->name('galaxPay.importaContratoCliente');
    Route::post('/criarClienteGalaxPay', 'galaxPayControllerAPI@criarClienteGalaxPay')->name('galaxPay.criarClienteGalaxPay');
    Route::get('/atualizaClienteGalaxPay/{clienteGalaxpay}', 'galaxPayControllerAPI@atualizaClienteGalaxPay')->name('galaxPay.atualiza');
    Route::get('/pesquisaCliente/{searchOption}/{search}', 'galaxPayControllerAPI@pesquisaClienteGalaxPay')->middleware(['acessoFuncionario'])->name('galaxPay.pesquisaClientes');
    Route::get('/editar/{clienteGalaxPay}', 'galaxPayControllerAPI@editarClienteGalaxPay')->middleware(['acessoFuncionario'])->name('galaxPay.clientes');
});
