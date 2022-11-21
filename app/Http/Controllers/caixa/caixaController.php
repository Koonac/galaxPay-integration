<?php

namespace App\Http\Controllers\caixa;

use App\Http\Controllers\Controller;
use App\Models\caixa_financeiro;
use App\Models\clientes_galaxpay;
use App\Models\contas;
use App\Models\despesas;
use App\Models\recebimentos;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use Symfony\Component\VarDumper\VarDumper;

class caixaController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // ANALISANDO SE TEM CAIXA ABERTO
        if (isset($request->idCaixaFinanceiro)) {
            $caixaFinanceiro = caixa_financeiro::find($request->idCaixaFinanceiro);
        } else {
            $caixaFinanceiro = caixa_financeiro::where('status_caixa', 'A')->first();
        }

        // RETORNANDO VIEW
        return view('caixa.caixa', ['caixaFinanceiro' => $caixaFinanceiro]);
    }

    public function abrirCaixa(Request $request)
    {
        // INICIANDO OBJETO
        $caixaFinanceiro = new caixa_financeiro;

        // TRATANDO VARIAVEIS
        $valorCaixaAbertura = str_replace(',', '.', str_replace('.', '', $request->valorCaixaAbertura));
        if (empty($valorCaixaAbertura)) $valorCaixaAbertura = 0;
        $valorCaixaAbertura = number_format($valorCaixaAbertura, 2, '.', ',');

        // ATRIBUINDO VALORES
        $caixaFinanceiro->status_caixa = 'A';
        $caixaFinanceiro->id_user_abertura = $request->user()->id;
        $caixaFinanceiro->nome_user_abertura = $request->user()->name;
        $caixaFinanceiro->valor_abertura = $valorCaixaAbertura;
        $caixaFinanceiro->valor_caixa = $valorCaixaAbertura;
        $caixaFinanceiro->data_abertura = date('Y-m-d H:i:s');

        // SALVANDO NO BANCO
        $caixaFinanceiro->save();

        // REDIRECIONANDO PARA PAGINA
        return redirect()->route('caixa', ['idCaixaFinanceiro' => $caixaFinanceiro->id]);
    }

    public function fecharCaixa(Request $request, caixa_financeiro $caixaFinanceiro)
    {
        // TRATANDO VARIAVEIS
        $valorFechamento = str_replace(',', '.', str_replace('.', '', $request->valorTotalCaixa));
        if (empty($valorFechamento)) $valorFechamento = 0;
        $valorFechamento = number_format($valorFechamento, 2, '.', ',');

        // ATRIBUINDO VALORES
        $caixaFinanceiro->status_caixa = 'F';
        $caixaFinanceiro->id_user_fechamento = $request->user()->id;
        $caixaFinanceiro->nome_user_fechamento = $request->user()->name;
        $caixaFinanceiro->valor_fechamento = $valorFechamento;
        $caixaFinanceiro->data_fechamento = date('Y-m-d H:i:s');

        // SALVANDO NO BANCO
        $caixaFinanceiro->save();

        // REDIRECIONANDO PARA PAGINA
        return redirect()->route('caixa');
    }

    public function adicionarRecebimento(Request $request, caixa_financeiro $caixaFinanceiro)
    {
        // TRATANDO VARIAVEIS
        $valorRecebimento = str_replace(',', '.', str_replace('.', '', $request->valorRecebimento));
        if (empty($valorRecebimento)) $valorRecebimento = 0;
        $valorRecebimento = number_format($valorRecebimento, 2, '.', ',');
        $observacaoRecebimento = trim($request->observacaoRecebimento);

        // INICIALIZANDO MODEL
        $recebimentos = new recebimentos;

        // ATRIBUINDO VALORES DE VARIAVEIS
        $recebimentos->valor_recebimento        = $valorRecebimento;
        $recebimentos->observacao_recebimento   = $observacaoRecebimento;
        $recebimentos->data_recebimento         = date('Y-m-d H:i:s');
        $recebimentos->user_create              = $request->user()->id;
        $recebimentos->conta_recebimento        = $request->contaRecebimento;
        $recebimentos->cliente_galaxpay_recebimento        = $request->galaxPayCliente;

        $valorAtualCaixa = str_replace(',', '', $caixaFinanceiro->valor_caixa);
        $valorRecebimento = str_replace(',', '', $valorRecebimento);
        $valorCaixa = number_format(($valorAtualCaixa + $valorRecebimento), 2, '.', ',');
        $caixaFinanceiro->valor_caixa = $valorCaixa;
        $caixaFinanceiro->recebimentos()->save($recebimentos);
        $caixaFinanceiro->save();

        // INICILIZANDO MODEL DE CONTAS
        $conta = contas::find($request->contaRecebimento);

        // ANALISANDO SE EXISTE ESTA CONTA
        if (isset($conta)) {
            // INICIALIZANDO VARIÁVEIS
            $valorConta = str_replace(',', '', $conta->valor_conta);
            $valorConta = number_format(($valorConta + $valorRecebimento), 2, '.', ',');
            $conta->valor_conta = $valorConta;

            // SALVANDO NOVOS DADOS
            $conta->save();
        }

        if ($request->dados) {
            // DEFININDO VARIAVEIS PARA SER PASSADAS PARA O PDF
            $pdf = PDF::loadView('clientes.layoutCards.layoutCardSolidariedadeVerso', $request->dados);
            $pdf->setPaper('catalog #10 1/2 envelope', 'landscape');
            $pdf->setOption(['defaultFont' => 'serif']);

            return $pdf->stream();
        } else {
            // REDIRECIONANDO PARA PAGINA ANTERIOR
            return redirect()->back()->with(['SUCCESS' => ['Recebimento adicionado com sucesso ao caixa']]);
        }
    }

    public function adicionarDespesa(Request $request, caixa_financeiro $caixaFinanceiro)
    {
        // TRATANDO VARIAVEIS
        $valorDespesa = str_replace(',', '.', str_replace('.', '', $request->valorDespesa));
        if (empty($valorDespesa)) $valorDespesa = 0;
        $valorDespesa = number_format($valorDespesa, 2, '.', ',');
        $observacaoDespesa = trim($request->observacaoDespesa);

        // INICIALIZANDO MODEL
        $despesas = new despesas;

        // ATRIBUINDO VALORES DE VARIAVEIS
        $despesas->valor_despesa        = $valorDespesa;
        $despesas->observacao_despesa   = $observacaoDespesa;
        $despesas->data_despesa         = date('Y-m-d H:i:s');
        $despesas->user_create              = $request->user()->id;
        $despesas->conta_despesa        = $request->contaDespesa;
        $valorAtualCaixa = str_replace(',', '', $caixaFinanceiro->valor_caixa);
        $valorDespesa = str_replace(',', '', $valorDespesa);
        $valorCaixa = number_format(($valorAtualCaixa - $valorDespesa), 2, '.', ',');
        $caixaFinanceiro->valor_caixa = $valorCaixa;
        $caixaFinanceiro->despesas()->save($despesas);
        $caixaFinanceiro->save();

        // INICILIZANDO MODEL DE CONTAS
        $conta = contas::find($request->contaDespesa);

        // ANALISANDO SE EXISTE ESTA CONTA
        if (isset($conta)) {
            // INICIALIZANDO VARIÁVEIS
            $valorConta = str_replace(',', '', $conta->valor_conta);
            $valorConta = number_format(($valorConta - $valorDespesa), 2, '.', ',');
            $conta->valor_conta = $valorConta;

            // SALVANDO NOVOS DADOS
            $conta->save();
        }

        return redirect()->back()->with(['SUCCESS' => ['Despesa adicionada com sucesso ao caixa']]);
    }
}
