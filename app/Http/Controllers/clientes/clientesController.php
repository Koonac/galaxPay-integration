<?php

namespace App\Http\Controllers\clientes;

use App\Http\Controllers\Controller;
use App\Models\campo_personalizado_cliente_galaxpay;
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

    public function pesquisaCliente(Request $request)
    {
        // PESQUISANDO CLIENTES ASSOCIADOS AO USUARIO BASEADO NO CAMPO DE PESQUISA
        $galaxPayClientes = $request->user()->galaxPayClientes()->where('nome_cliente', 'LIKE', '%' . $request->pesquisaCliente . '%')
            ->orWhere('codigo_cliente_galaxpay', 'LIKE', '%' . $request->pesquisaCliente . '%')
            ->orWhere('cpf_cnpj_cliente', 'LIKE', '%' . $request->pesquisaCliente . '%')
            ->get();

        // ANALISANDO SE REGISTRO É VAZIO
        if ($galaxPayClientes->count() <= 0) {
            // RETORNANDO VIEW
            return view('components.messages.returnMessages', ['WARNING' => ['Nenhum registro encontrado.']]);
        } else {

            $dependentesCliente[] = $galaxPayClientes->campoPersonalizadoClienteGalaxpay()->where('nome_campo_personalizado', 'CP_NOME_DEPENDENTE_02')->first();
            $dependentesCliente[] = $galaxPayClientes->campoPersonalizadoClienteGalaxpay()->where('nome_campo_personalizado', 'CP_NOME_DEPENDENTE_03')->first();
            $dependentesCliente[] = $galaxPayClientes->campoPersonalizadoClienteGalaxpay()->where('nome_campo_personalizado', 'CP_NOME_DEPENDENTE_04')->first();
            $dependentesCliente[] = $galaxPayClientes->campoPersonalizadoClienteGalaxpay()->where('nome_campo_personalizado', 'CP_NOME_DEPENDENTE_05')->first();
            $dependentesCliente[] = $galaxPayClientes->campoPersonalizadoClienteGalaxpay()->where('nome_campo_personalizado', 'CP_NOME_DEPENDENTE_06')->first();
            $dependentesCliente[] = $galaxPayClientes->campoPersonalizadoClienteGalaxpay()->where('nome_campo_personalizado', 'CP_NOME_DEPENDENTE_07')->first();

            // RETORNANDO VIEW
            return view('components.listas.listClientes', ['galaxPayClientes' => $galaxPayClientes, 'dependentesCliente' => $dependentesCliente]);
        }
    }

    public function gerarCartao(Request $request)
    {
        // CAPTURANDO CLIENTE COM BASE NO ID FORNECIDO PELA ROTA
        $codigosImpressao = $request->codigosImpressao;
        $codigosImpressao = json_decode(base64_decode($codigosImpressao));
        $codigoClienteGalaxpay = $codigosImpressao->codigoClienteGalaxpay;
        $dependentesCliente = $codigosImpressao->dependentesCliente;

        if (!empty($codigoClienteGalaxpay)) {
            $clienteGalaxPay = $request->user()->galaxPayClientes()->where('codigo_cliente_galaxpay', $codigoClienteGalaxpay)->first();
            // MONTANDO CARD DE IMPORESSÃO DO CLIENTE PRINCIPAL
            $cardImpressao['nomeCliente'] = $clienteGalaxPay->nome_cliente;
            $cardImpressao['matriculaCliente'] = $clienteGalaxPay->matricula;
            $cardImpressao['dataEmissão'] = date('Y-m-d H:i:s');
            $dataNascimentoClienteGalaxpay = $clienteGalaxPay->campoPersonalizadoClienteGalaxpay()->where('nome_campo_personalizado', 'CP_DATA_NACIMENTO')->first();
            if (!empty($dataNascimentoClienteGalaxpa)) {
                $cardImpressao['dataNascimentoCliente'] = $dataNascimentoClienteGalaxpay->valor_campo_personalizado;
            } else {
                $cardImpressao['dataNascimentoCliente'] = $clienteGalaxPay->created_at;
            }
            // ADICIONANDO AO ARRAY DE IMPRESSAO
            $data['cardImpressao'][] = $cardImpressao;
        }

        // ANALISANDO SE EXITE DEPENDENTES
        if (isset($dependentesCliente)) {
            // PERCORRENDO LAÇO DE DEPENDENTES
            foreach ($dependentesCliente as $dependente) {

                // VERIFICANDO VARIAVEIS
                isset($dependente->nomeDependente) ? $nomeDependente = $dependente->nomeDependente : $nomeDependente = '';
                isset($dependente->cpfDependente) ? $cpfDependente = $dependente->cpfDependente : $cpfDependente = '';
                isset($dependente->nascimentoDependente) ? $nascimentoDependente = $dependente->nascimentoDependente : $nascimentoDependente = '';

                // INICIALIZANDO VARIAVEIS
                $campoPersonalizadoClienteGalaxpay = new campo_personalizado_cliente_galaxpay();
                $modelNomeDependente         = $campoPersonalizadoClienteGalaxpay->find($nomeDependente);
                $modelCpfDependente          = $campoPersonalizadoClienteGalaxpay->find($cpfDependente);

                if ($campoPersonalizadoClienteGalaxpay->find($nascimentoDependente)) {
                    $nascimentoDependente = $campoPersonalizadoClienteGalaxpay->find($nascimentoDependente)->valor_campo_personalizado;
                } else {
                    $nascimentoDependente = '1970-01-01';
                }
                // MONTANDO CARD DE IMPRESSAO DE DEPENDENTES
                $cardImpressao['nomeCliente'] = $modelNomeDependente->valor_campo_personalizado;
                $cardImpressao['matriculaCliente'] = str_pad(date('Y') . $modelNomeDependente->cliente_galaxpay_id . $modelNomeDependente->id, 10, 0);;
                $cardImpressao['dataEmissão'] = date('Y-m-d H:i:s');
                $cardImpressao['dataNascimentoCliente'] = $nascimentoDependente;

                // ADICIONANDO AO ARRAY DE IMPRESSAO
                $data['cardImpressao'][] = $cardImpressao;
            }
        }

        // CAPTURANDO TOTAL DE REGISTROS PARA IMPRESSÃO
        $data['qtdImpressao'] = count($data['cardImpressao']);

        // DEFININDO VARIAVEIS PARA SER PASSADAS PARA O PDF
        $pdf = PDF::loadView('clientes.layoutCards.layoutCardSolidariedadeVerso', $data);
        $pdf->setPaper('catalog #10 1/2 envelope', 'landscape');
        $pdf->setOption(['defaultFont' => 'serif']);

        // RETORNANDO PDF
        return $pdf->stream();
    }
}
