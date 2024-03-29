<?php

namespace App\Http\Controllers\clientes;

use App\Http\Controllers\api\galaxPayControllerAPI;
use App\Http\Controllers\Controller;
use App\Models\caixa_financeiro;
use App\Models\campo_personalizado_cliente_galaxpay;
use App\Models\clientes_dependentes_galaxpay;
use App\Models\clientes_galaxpay;
use App\Models\contratos;
use App\Models\historico_atendimento_cliente;
use App\Models\logs_alteracao;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Exception;
use Nette\Utils\Json;
use PhpParser\Node\Expr\Print_;

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
        switch ($request->user()->role) {
            case 'empresaParceira':
                $userLinkedId = $request->user()->userPrimario->user_linked_id;
                $userPrimario = User::find($userLinkedId);
                return view('clientes.clientes', ['galaxPayClientes' => $userPrimario->galaxPayClientes]);
                break;
            case 'Funcionario':
                $userLinkedId = $request->user()->userPrimarioFuncionario->user_linked_id;
                $userPrimario = User::find($userLinkedId);
                return view('clientes.clientes', ['galaxPayClientes' => $userPrimario->galaxPayClientes]);
                break;
            default:
                return view('clientes.clientes', ['galaxPayClientes' => $request->user()->galaxPayClientes]);
                break;
        }
    }

    public function dadosClienteGalaxPay(Request $request, clientes_galaxpay $clienteGalaxPay)
    {
        return view('clientes.dadosCliente', ['galaxPayCliente' => $clienteGalaxPay]);
    }

    public function contratosClienteGalaxPay(Request $request, clientes_galaxpay $clienteGalaxPay)
    {
        return view('clientes.contratosCliente', ['galaxPayCliente' => $clienteGalaxPay]);
    }

    public function transacoesClienteGalaxPay(Request $request, clientes_galaxpay $clienteGalaxPay)
    {
        return view('clientes.transacoesCliente', ['galaxPayCliente' => $clienteGalaxPay]);
    }

    public function criarClienteGalaxPay(Request $request)
    {
        return view('clientes.criarCliente');
    }

    public function criarContratoClienteView(Request $request, clientes_galaxpay $clienteGalaxPay)
    {
        return view('clientes.criarContrato', ['clienteGalaxPay' => $clienteGalaxPay]);
    }

    public function criarContratoClienteGalaxPay(Request $request, clientes_galaxpay $clienteGalaxPay)
    {
        try {
            // TRATANDO VARIAVEIS
            $valorContrato = str_replace(',', '.', str_replace('.', '', $request->valorContrato));
            if (empty($valorContrato)) $valorContrato = 0;
            $valorContrato = number_format($valorContrato, 2, '.', ',');
            $valorDesconto = str_replace(',', '.', str_replace('.', '', $request->valorDesconto));
            if (empty($valorDesconto)) $valorDesconto = 0;
            $valorDesconto = number_format($valorDesconto, 2, '.', ',');
            $informacaoAdicional = trim($request->informacaoAdicional);
            $primeiraDataPagamento = implode('-', array_reverse(explode('/', $request->primeiraDataPagamento)));
            $duracaoContrato = $request->duracaoContrato;
            if (empty($duracaoContrato)) $duracaoContrato = 0;
            $aplicarDesconto = $request->aplicarDesconto;
            if (empty($aplicarDesconto)) $aplicarDesconto = 'N';

            // INICIALIZANDO MODEL
            $contrato = new contratos;

            // ATRIBUINDO VALORES AS VARIÁVEIS
            $contrato->cliente_galaxpay = $clienteGalaxPay->id;
            $contrato->plano_codigo_contrato = $request->planoCodigoContrato;
            $contrato->valor_contrato = $valorContrato;
            $contrato->duracao_contrato = $duracaoContrato;
            $contrato->periodicidade_pagamento = $request->periodicidadePagamento;
            $contrato->primeira_data_pagamento = $primeiraDataPagamento;
            $contrato->forma_pagamento = $request->formaPagamento;
            $contrato->informacao_adicional = $informacaoAdicional;
            $contrato->status = 'sending api';

            $contrato->percentual_multa = $request->percentualMulta;
            $contrato->percentual_juros = $request->percentualJuros;
            $contrato->observacao_boleto = $request->observacaoBoleto;
            $contrato->qtde_pagamento_pos_vencimento = $request->qtdePagamentoPosVencimento;
            $contrato->aplicar_desconto = $aplicarDesconto;
            $contrato->tipo_desconto = $request->tipoDesconto;
            $contrato->qtde_dias_validade_desconto = $request->qtdeDiasValidadeDesconto;
            $contrato->valor_desconto = $valorDesconto;

            // SALVANDO NO BANCO DE DADOS
            $contrato->save();

            // CAHMANDO ROTA PARA CADASTRO A API
            return redirect()->route('galaxPay.criarContrato', $contrato);
        } catch (Exception $e) {
            $retorno['statusRetorno'] = 'ERROR';
            return redirect()->back()->withInput()->withErrors(['Ocorreu um erro inesperado. Mensagem: ' . $e->getMessage()]);
        }
        ddd($request->all());
    }

    public function editClienteGalaxPay(Request $request, clientes_galaxpay $clienteGalaxPay)
    {
        // TRATANDO VARIÁVEIS
        $cpfCnpjCliente = trim($request->cpfCnpjClienteGalaxPay);
        $cpfCnpjCliente = str_replace(".", "", $cpfCnpjCliente);
        $cpfCnpjCliente = str_replace(",", "", $cpfCnpjCliente);
        $cpfCnpjCliente = str_replace("-", "", $cpfCnpjCliente);
        $cpfCnpjCliente = str_replace("/", "", $cpfCnpjCliente);
        $telefoneCliente1 = trim($request->telefoneClienteGalaxPay1);
        $telefoneCliente1 = str_replace(" ", "", $telefoneCliente1);
        $telefoneCliente1 = str_replace("-", "", $telefoneCliente1);
        $telefoneCliente1 = str_replace("(", "", $telefoneCliente1);
        $telefoneCliente1 = str_replace(")", "", $telefoneCliente1);
        $telefoneCliente2 = trim($request->telefoneClienteGalaxPay2);
        $telefoneCliente2 = str_replace(" ", "", $telefoneCliente2);
        $telefoneCliente2 = str_replace("-", "", $telefoneCliente2);
        $telefoneCliente2 = str_replace("(", "", $telefoneCliente2);
        $telefoneCliente2 = str_replace(")", "", $telefoneCliente2);

        // ATRIBUINDO NOVOS VALORES AO CLIENTE
        $clienteGalaxPay->nome_cliente                          = $request->nomeClienteGalaxPay;
        $clienteGalaxPay->cpf_cnpj_cliente                      = $cpfCnpjCliente;
        $clienteGalaxPay->email_cliente_1                       = $request->emailClienteGalaxPay1;
        $clienteGalaxPay->email_cliente_2                       = $request->emailClienteGalaxPay2;
        $clienteGalaxPay->telefone_cliente_1                    = $telefoneCliente1;
        $clienteGalaxPay->telefone_cliente_2                    = $telefoneCliente2;
        // ATRIBUINDO NOVOS VALORES AO ENDEREÇO CLIENTE
        if (!empty($clienteGalaxPay->enderecoClienteGalaxpay)) {
            $clienteGalaxPay->enderecoClienteGalaxpay->cep          = $request->cepClienteGalaxPay;
            $clienteGalaxPay->enderecoClienteGalaxpay->logradouro   = $request->logradouroClienteGalaxPay;
            $clienteGalaxPay->enderecoClienteGalaxpay->numero       = $request->numeroClienteGalaxPay;
            $clienteGalaxPay->enderecoClienteGalaxpay->complemento  = $request->complementoClienteGalaxPay;
            $clienteGalaxPay->enderecoClienteGalaxpay->bairro       = $request->bairroClienteGalaxPay;
            $clienteGalaxPay->enderecoClienteGalaxpay->cidade       = $request->cidadeClienteGalaxPay;
            $clienteGalaxPay->enderecoClienteGalaxpay->estado       = $request->estadoClienteGalaxPay;
        }

        // SALVANDO NOVOS DADOS
        $clienteGalaxPay->save();
        if (!empty($clienteGalaxPay->enderecoClienteGalaxpay)) $clienteGalaxPay->enderecoClienteGalaxpay->save();

        // SALVANDO JSON PARA LOG
        $jsonAlteracao = json_encode($clienteGalaxPay);

        $retorno = galaxPayControllerAPI::editarClienteGalaxPay($request, $clienteGalaxPay);
        if ($retorno['statusRetorno'] != 'SUCCESS') {
            return redirect()->back()->withInput()->withErrors(["Erro ao atualizar cliente na Galaxpay. \n" . $retorno['msgRetorno']]);
        }

        // ATRIBUINDO VALORES DE HISTORICO DE ALTERAÇÕES
        $historicoAlteracaoCliente = new historico_atendimento_cliente;
        $historicoAlteracaoCliente->user_id = $request->user()->id;
        $historicoAlteracaoCliente->cliente_galaxpay_id = $clienteGalaxPay->id;
        $historicoAlteracaoCliente->nome_usuario_alteracao = $request->user()->name;
        $historicoAlteracaoCliente->observacao_alteracao = $request->comentarioEdit;

        // ATRIBUINDO VALORES DE LOGS
        $logsAlteracao = new logs_alteracao;
        $logsAlteracao->user_id = $request->user()->id;
        $logsAlteracao->nome_user = $request->user()->name;
        $logsAlteracao->comentario_alteracao = $request->comentarioEdit;
        $logsAlteracao->detalhe_alteracao = 'UPDATE CLIENTE GALAXPAY';
        $logsAlteracao->json_alteracao = $jsonAlteracao;

        // SALVANDO NOVOS DADOS
        $logsAlteracao->save();
        $historicoAlteracaoCliente->save();

        // return redirect()->route('galaxPay.clientes', $clienteGalaxPay);
        return redirect()->back()->withInput()->with('SUCCESS', ['Alterações realizadas com sucesso.']);
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

            $dependentesCliente[] = $galaxPayClientes->campoPersonalizadoClienteGalaxpay()->where('nome_campo_personalizado', 'CP_NOME_DEPENDENTE_01')->first();
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

    public function gerarCartaoJs(Request $request)
    {
        // CAPTURANDO CLIENTE COM BASE NO ID FORNECIDO PELA ROTA
        $codigosImpressao = $request->codigosImpressao;
        $codigosImpressao = json_decode(base64_decode($codigosImpressao));
        $codigoClientesGalaxpay = $codigosImpressao->codigoClientesGalaxpay;
        $codigoDependentesClienteGalaxpay = $codigosImpressao->codigoDependentesClienteGalaxpay;

        // ANALISANDO SE EXISTE CODIGOS DE CLIENTE PARA IMPRESSÃO
        if (!empty($codigoClientesGalaxpay)) {
            // PERCORRENDO LAÇO DE CLIENTES PARA IMPRESSÃO
            foreach ($codigoClientesGalaxpay as $codigoClienteGalaxpay) {
                // CAPTURANDO MODEL DO CLIENTE PARA IMPRESSAO
                $clienteGalaxPay = clientes_galaxpay::where('codigo_cliente_galaxpay', $codigoClienteGalaxpay)->first();
                // MONTANDO CARD DE IMPRESSÃO DO CLIENTE
                $cardImpressao['nomeCliente'] = $clienteGalaxPay->nome_cliente;
                $cardImpressao['matriculaCliente'] = $clienteGalaxPay->matricula;
                $cardImpressao['dataEmissão'] = date('Y-m-d H:i:s');
                $dataNascimentoClienteGalaxpay = $clienteGalaxPay->campoPersonalizadoClienteGalaxpay()->where('nome_campo_personalizado', 'CP_DATA_NACIMENTO')->first();
                if (!empty($dataNascimentoClienteGalaxpa)) {
                    $cardImpressao['dataNascimentoCliente'] = $dataNascimentoClienteGalaxpay->valor_campo_personalizado;
                } else {
                    $cardImpressao['dataNascimentoCliente'] = date('Y-m-d');
                }
                // ADICIONANDO AO ARRAY DE IMPRESSAO
                $data['cardImpressao'][] = $cardImpressao;
            }
        }

        // ANALISANDO SE EXISTE DEPENDENTES
        if (!empty($codigoDependentesClienteGalaxpay)) {
            // PERCORRENDO LAÇO DE DEPENDENTES PARA IMPRESSÃO
            foreach ($codigoDependentesClienteGalaxpay as $codigoDependenteClienteGalaxpay) {
                // CAPTURANDO MODEL DO DEPENDENTE PARA IMPRESSAO
                $clientesDependentesGalaxpay = clientes_dependentes_galaxpay::find($codigoDependenteClienteGalaxpay);

                // MONTANDO CARD DE IMPRESSÃO DO DEPENDENTE
                $cardImpressao['nomeCliente'] = $clientesDependentesGalaxpay->nome_cliente_dependente;
                $cardImpressao['matriculaCliente'] = $clientesDependentesGalaxpay->matricula_cliente_dependente;
                $cardImpressao['dataEmissão'] = date('Y-m-d H:i:s');
                $cardImpressao['dataNascimentoCliente'] = $clientesDependentesGalaxpay->nascimento_cliente_dependente;

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

    public function gerarCartaoCliente(Request $request, clientes_galaxpay $clienteGalaxpay)
    {
        try {
            // ANALISANDO SE EXISTE CODIGOS DE CLIENTE PARA IMPRESSÃO
            if (!empty($clienteGalaxpay)) {

                // MONTANDO CARD DE IMPRESSÃO DO CLIENTE
                $cardImpressao['nomeCliente'] = $clienteGalaxpay->nome_cliente;
                $cardImpressao['matriculaCliente'] = $clienteGalaxpay->matricula;
                $cardImpressao['dataEmissão'] = date('Y-m-d H:i:s');
                $dataNascimentoClienteGalaxpay = $clienteGalaxpay->campoPersonalizadoClienteGalaxpay()->where('nome_campo_personalizado', 'CP_DATA_NACIMENTO')->first();
                if (!empty($dataNascimentoClienteGalaxpa)) {
                    $cardImpressao['dataNascimentoCliente'] = $dataNascimentoClienteGalaxpay->valor_campo_personalizado;
                } else {
                    $cardImpressao['dataNascimentoCliente'] = date('Y-m-d');
                }
                // ADICIONANDO AO ARRAY DE IMPRESSAO
                $data['cardImpressao'][] = $cardImpressao;
            }

            // ANALISANDO SE EXISTE DEPENDENTES
            if (count($clienteGalaxpay->clientesDependentesGalaxpay) > 0) {
                // PERCORRENDO LAÇO DE DEPENDENTES PARA IMPRESSÃO
                foreach ($clienteGalaxpay->clientesDependentesGalaxpay as $clienteDependenteGalaxpay) {
                    // MONTANDO CARD DE IMPRESSÃO DO DEPENDENTE
                    $cardImpressao['nomeCliente'] = $clienteDependenteGalaxpay->nome_cliente_dependente;
                    $cardImpressao['matriculaCliente'] = $clienteDependenteGalaxpay->matricula_cliente_dependente;
                    $cardImpressao['dataEmissão'] = date('Y-m-d H:i:s');
                    $cardImpressao['dataNascimentoCliente'] = $clienteDependenteGalaxpay->nascimento_cliente_dependente;

                    // ADICIONANDO AO ARRAY DE IMPRESSAO
                    $data['cardImpressao'][] = $cardImpressao;
                }
            }

            // CAPTURANDO TOTAL DE REGISTROS PARA IMPRESSÃO
            $data['qtdImpressao'] = count($data['cardImpressao']);

            // ANALISANDO SE DEVE GERAR FINANCEIRO
            if ($request->gerarFinanceiro == 'SIM') {
                $caixaAberto = caixa_financeiro::where('status_caixa', 'A')->first();

                if (empty($caixaAberto)) {
                    throw new Exception('Não há caixa aberto para geração de financeiro.');
                } else {
                    $rotaAdcionarRecebimento = redirect()->route('caixa.adicionarRecebimento.cartao', [
                        'caixaFinanceiro' => $caixaAberto,
                        'valorRecebimento' => $request->user()->parametros->valor_card,
                        'observacaoRecebimento' => 'Cartão gerado.',
                        'galaxPayCliente' => $clienteGalaxpay,
                        'contaRecebimento' => $request->user()->parametros->conta_recebimento_padrao,
                        'dados' => $data
                    ]);
                    // RETORNANDO ROTA
                    return $rotaAdcionarRecebimento;
                }
            } else if ($request->gerarFinanceiro == 'NAO') {
                // DEFININDO VARIAVEIS PARA SER PASSADAS PARA O PDF
                $pdf = PDF::loadView('clientes.layoutCards.layoutCardSolidariedadeVerso', $data);
                $pdf->setPaper('catalog #10 1/2 envelope', 'landscape');
                $pdf->setOption(['defaultFont' => 'serif']);

                return $pdf->stream();
            } else {
                throw new Exception('Parametros não identificados.');
            }
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['Ocorreu um erro inesperado ao gerar cartão do cliente. ' . "\n" . $e->getMessage()]);
        }
    }

    function trataCpfCnpj($valor)
    {
        $valor = trim($valor);
        $valor = str_replace(".", "", $valor);
        $valor = str_replace(",", "", $valor);
        $valor = str_replace("-", "", $valor);
        $valor = str_replace("/", "", $valor);
        return $valor;
    }
}
