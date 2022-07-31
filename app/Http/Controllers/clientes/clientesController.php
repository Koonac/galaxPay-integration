<?php

namespace App\Http\Controllers\clientes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class clientesController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return view('clientes.clientes', ['user' => $request->user(), 'galaxPayClientes' => $request->user()->galaxPayClientes]);
    }

    public function pesquisaCliente(Request $request){
        // PESQUISANDO CLIENTES ASSOCIADOS AO USUARIO BASEADO NO CAMPO DE PESQUISA
        $galaxPayClientes = $request->user()->galaxPayClientes()->where('nome_cliente', 'LIKE', '%'.$request->pesquisaCliente.'%')
        ->orWhere('codigo_cliente_galaxpay', 'LIKE', '%'.$request->pesquisaCliente.'%')
        ->orWhere('cpf_cnpj_cliente', 'LIKE', '%'.$request->pesquisaCliente.'%')
        ->get();

        // ANALISANDO SE REGISTRO É VAZIO
        if($galaxPayClientes->count() <= 0){
            // RETORNANDO VIEW
            return view('components.messages.returnMessages', ['WARNING' => ['Nenhum registro encontrado.']]);
        }else{
            // RETORNANDO VIEW
            return view('components.listas.listClientes', ['galaxPayClientes' => $galaxPayClientes]);
        }
        
    }

    public function gerarCartao(Request $request)
    {
        // CAPTURANDO CLIENTE COM BASE NO ID FORNECIDO PELA ROTA
        $data['clienteGalaxPay'] = $request->user()->galaxPayClientes()->where('codigo_cliente_galaxpay', $request->cliente)->first();

        // Se quiser que fique no formato a4 retrato: ->setPaper('a4', 'landscape')
        return PDF::loadView('clientes.layoutCards.layoutCardSolidariedade', $data)->setOption(['dpi' => 300])->stream();
        
    }
}
