<?php

namespace App\Http\Controllers\financeiro;

use App\Http\Controllers\Controller;
use App\Models\caixa_financeiro;
use App\Models\contas;
use App\Models\despesas;
use App\Models\logs_alteracao;
use App\Models\recebimentos;
use Illuminate\Http\Request;
use Symfony\Component\VarDumper\VarDumper;

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
        $contas = $request->user()->contas;

        // RETORNANDO VIEW
        return view('financeiro.financeiro', ['contas' => $contas]);
    }

    public function visualizarConta(Request $request, contas $conta)
    {

        return view('financeiro.informacoesConta', ['conta' => $conta]);
    }

    public function criarConta(Request $request)
    {
        // CRIANDO MODEL
        $conta = new contas;

        // INICILIZANDO VARIÁVEIS
        $descricaoConta = trim($request->descricaoConta);

        // ATRIBUINDO VALORES
        $conta->descricao_conta = $descricaoConta;
        $conta->valor_conta = '0.00';
        $conta->id_user = $request->user()->id;

        // SALVANDO ALTREAÇÕES
        $conta->save();

        return redirect()->back()->with(['SUCCESS' => ['Conta criada com sucesso.']]);
    }

    public function editarConta(Request $request, contas $conta)
    {
        // INICILIZANDO VARIÁVEIS
        $descricaoConta = trim($request->descricaoConta);
        $valorConta     = $request->valorConta;
        $valorConta     = str_replace(',', '.', str_replace('.', '', $request->valorConta));
        $valorConta     = number_format($valorConta, 2, '.', ',');

        // ATRIBUINDO VALORES
        $conta->descricao_conta = $descricaoConta;
        $conta->valor_conta = $valorConta;

        // CRIANDO JSON
        $jsonAlteracao = json_encode($conta);

        // SALVANDO ALTREAÇÕES
        $conta->save();

        // ATRIBUINDO VALORES DE LOGS
        $logsAlteracao = new logs_alteracao();
        $logsAlteracao->user_id = $request->user()->id;
        $logsAlteracao->nome_user = $request->user()->name;
        $logsAlteracao->comentario_alteracao = $request->comentarioEdit;
        $logsAlteracao->detalhe_alteracao = 'UPDATE CONTA SISTEMA';
        $logsAlteracao->json_alteracao = $jsonAlteracao;

        // SALVANDO ALTREAÇÕES
        $logsAlteracao->save();

        return redirect()->back()->with(['SUCCESS' => ['Conta alterada com sucesso.']]);
    }

    public function excluirConta(Request $request, contas $conta)
    {
        // ATRIBUINDO VALORES
        $conta->deleted = 'S';

        // SALVANDO ALTREAÇÕES
        $conta->save();

        return redirect()->back()->with(['SUCCESS' => ['Conta excluida com sucesso.']]);
    }
}
