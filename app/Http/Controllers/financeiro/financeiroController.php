<?php

namespace App\Http\Controllers\financeiro;

use App\Http\Controllers\Controller;
use App\Models\caixa_financeiro;
use App\Models\despesas;
use App\Models\recebimentos;
use Illuminate\Http\Request;

class financeiroController extends Controller
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
            $caixaFinanceiro = $request->user()->caixaFinanceiro->where('status_caixa', 'A')->first();
        }

        // RETORNANDO VIEW
        return view('financeiro.financeiro', ['caixaFinanceiro' => $caixaFinanceiro]);
    }

    public function abrirCaixa(Request $request)
    {
        // INICIANDO OBJETO
        $caixaFinanceiro = new caixa_financeiro;

        // TRATANDO VARIAVEIS
        $valorCaixaAbertura = preg_replace('/[^0-9]/', '', $request->valorCaixaAbertura);
        if (empty($valorCaixaAbertura)) $valorCaixaAbertura = 0;
        $valorCaixaAbertura = number_format($valorCaixaAbertura, 2, '.', ',');

        // ATRIBUINDO VALORES
        $caixaFinanceiro->status_caixa = 'A';
        $caixaFinanceiro->id_user_abertura = $request->user()->id;
        $caixaFinanceiro->nome_user_abertura = $request->user()->name;
        $caixaFinanceiro->valor_abertura = $valorCaixaAbertura;
        $caixaFinanceiro->data_abertura = date('Y-m-d H:i:s');

        // SALVANDO NO BANCO
        $caixaFinanceiro->save();

        // REDIRECIONANDO PARA PAGINA
        return redirect()->route('financeiro', ['idCaixaFinanceiro' => $caixaFinanceiro->id]);
    }

    public function fecharCaixa(Request $request, caixa_financeiro $caixaFinanceiro)
    {
        // TRATANDO VARIAVEIS
        $valorFechamento = preg_replace('/[^0-9]/', '', $request->valorTotalCaixa);
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
        return redirect()->route('financeiro');
    }

    public function adicionarRecebimento(Request $request, caixa_financeiro $caixaFinanceiro)
    {
        // TRATANDO VARIAVEIS
        $valorRecebimento = str_replace('.', '', $request->valorRecebimento);
        $valorRecebimento = preg_replace('/,/', '.', $valorRecebimento);
        if (empty($valorRecebimento)) $valorRecebimento = 0;
        $valorRecebimento = number_format($valorRecebimento, 2, '.', ',');
        $observacaoRecebimento = trim($request->observacaoRecebimento);

        // INICIALIZANDO MODEL
        $recebimentos = new recebimentos;
        // ATRIBUINDO VALORES DE VARIAVEIS
        $recebimentos->valor_recebimento        = $valorRecebimento;
        $recebimentos->observacao_recebimento   = $observacaoRecebimento;
        $recebimentos->data_recebimento         = date('Y-m-d H:i:s');
        $caixaFinanceiro->recebimentos()->save($recebimentos);

        return redirect()->back()->with(['SUCCESS' => ['Recebimento adicionado com sucesso ao caixa']]);
    }

    public function adicionarDespesa(Request $request, caixa_financeiro $caixaFinanceiro)
    {
        // TRATANDO VARIAVEIS
        $valorDespesa = str_replace('.', '', $request->valorDespesa);
        $valorDespesa = preg_replace('/,/', '.', $valorDespesa);
        if (empty($valorDespesa)) $valorDespesa = 0;
        $valorDespesa = number_format($valorDespesa, 2, '.', ',');
        $observacaoDespesa = trim($request->observacaoDespesa);

        // INICIALIZANDO MODEL
        $despesas = new despesas;
        // ATRIBUINDO VALORES DE VARIAVEIS
        $despesas->valor_despesa        = $valorDespesa;
        $despesas->observacao_despesa   = $observacaoDespesa;
        $despesas->data_despesa         = date('Y-m-d H:i:s');
        $caixaFinanceiro->despesas()->save($despesas);

        return redirect()->back()->with(['SUCCESS' => ['Despesa adicionada com sucesso ao caixa']]);
    }
}
