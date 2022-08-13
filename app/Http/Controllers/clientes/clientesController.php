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
        $clienteGalaxPay = $request->user()->galaxPayClientes()->where('codigo_cliente_galaxpay', $request->cliente)->first();
        $dataNascimentoClienteGalaxpay = $clienteGalaxPay->campoPersonalizadoClienteGalaxpay()->where('nome_campo_personalizado', 'CP_DATA_NACIMENTO')->first();
        $matriculaClienteGalaxpay = $clienteGalaxPay->campoPersonalizadoClienteGalaxpay()->where('nome_campo_personalizado', 'CP_MATRICULA_LABCARD')->first();

        if(!empty($dataNascimentoClienteGalaxpa)){
            $data['dataNascimentoClienteGalaxpay'] = $dataNascimentoClienteGalaxpay->valor_campo_personalizado;
        }else{
            $data['dataNascimentoClienteGalaxpay'] = $clienteGalaxPay->created_at;
        }
        if(!empty($matriculaClienteGalaxpay)){
            $data['matriculaClienteGalaxpay'] = $matriculaClienteGalaxpay->valor_campo_personalizado;
        }else{
            $data['matriculaClienteGalaxpay'] = $clienteGalaxPay->codigo_cliente_galaxpay;
        }
        // DEFININDO VARIAVEIS PARA SER PASSADAS PARA O PDF
        $data['clienteGalaxPay'] = $clienteGalaxPay;
        $pdf = PDF::loadView('clientes.layoutCards.layoutCardSolidariedadeVerso', $data);
        $pdf->setPaper('catalog #10 1/2 envelope','landscape');
        $pdf->setOption(['defaultFont' => 'serif']);

        // RETORNANDO PDF
        return $pdf->stream();;
        
    }
}
