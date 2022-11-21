<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\caixa_financeiro;
use App\Models\clientes_galaxpay;
use App\Models\galaxpay_parametros;
use App\Models\recebimentos;
use App\Models\transacoes_galaxpay;
use App\Models\User;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Print_;

class webhookController extends Controller
{
    function webhook(Request $request)
    {

        // CAPUTRANDO PARAMETROS DE WEBHOOK TOKEN
        $galaxpayParametros = galaxpay_parametros::where('webhook_hash', $request->confirmHash)->first();
        if (empty($galaxpayParametros)) {
            return response('Unauthorized - Webhook token', 401);
        }
        $userTransacao = User::find($galaxpayParametros->user_id);


        // ANALISANDO EVENTO DO WEBHOOK
        switch ($request->event) {
            case 'transaction.updateStatus':

                if (($request->Transaction['status'] != 'authorized') && ($request->Transaction['status'] != 'payedBoleto') && ($request->Transaction['status'] != 'payedPix')) {
                    return response('Sem método para este status', 202);
                }

                // INICIALIZANDO VARIÁVEIS
                $valorTransacao = str_replace(',', '.', str_replace('.', '', $request->Transaction['value']));
                $decimal = substr($valorTransacao, -2);
                $inteiro = substr($valorTransacao, 0, -2);
                $valorTransacao = $inteiro . '.' . $decimal;
                if (empty($valorTransacao)) $valorTransacao = '0.00';
                $valorTransacao = number_format($valorTransacao, 2, '.', ',');

                // PESQUISANDO CLIENTE PELO DOCUMENTO
                if (isset($request->Subscription[0]['Customer']['document'])) {
                    $clienteGalaxpay = clientes_galaxpay::where('cpf_cnpj_cliente', $request->Subscription[0]['Customer']['document'])->first();
                } else if (isset($request->Charge['Customer']['document'])) {
                    $clienteGalaxpay = clientes_galaxpay::where('cpf_cnpj_cliente', $request->Charge['Customer']['document'])->first();
                } else {
                    return response('Cliente não identificado', 502);
                }

                // ANALISANDO SE O CLIENTE FOI ENCONTRADO
                // REALIZAR AJUSTE CASO NAO TENHA SIDO ENCONTRADO --HENRIQUE DEV
                if (empty($clienteGalaxpay)) {
                    return response('Cliente não identificado', 502);
                }

                // INICIALIZANDO MODEL
                $recebimentos = new recebimentos;

                $caixaFinanceiro = $userTransacao->caixaAberto;
                if (empty($caixaFinanceiro)) {
                    // ATRIBUINDO VALORES
                    $caixaFinanceiro = new caixa_financeiro();
                    $caixaFinanceiro->status_caixa = 'A';
                    $caixaFinanceiro->id_user_abertura = $userTransacao->id;
                    $caixaFinanceiro->nome_user_abertura = $userTransacao->name;
                    $caixaFinanceiro->valor_abertura = '0.00';
                    $caixaFinanceiro->valor_caixa = '0.00';
                    $caixaFinanceiro->data_abertura = date('Y-m-d H:i:s');
                    $caixaFinanceiro->save();
                }

                // ATRIBUINDO VALORES DE VARIAVEIS
                $recebimentos->valor_recebimento        = $valorTransacao;
                $recebimentos->observacao_recebimento   = 'Recebido via webhook Nº ' . $request->Transaction['myId'];
                $recebimentos->data_recebimento         = date('Y-m-d H:i:s');
                $recebimentos->user_create              = $userTransacao->id;
                $recebimentos->conta_recebimento        = $userTransacao->parametros->conta_recebimento_padrao;
                $recebimentos->cliente_galaxpay_recebimento        = $clienteGalaxpay->id;

                $valorAtualCaixa = str_replace(',', '', $caixaFinanceiro->valor_caixa);
                $valorTransacao = str_replace(',', '', $valorTransacao);
                $valorCaixa = number_format(($valorAtualCaixa + $valorTransacao), 2, '.', ',');
                $caixaFinanceiro->valor_caixa = $valorCaixa;
                $caixaFinanceiro->recebimentos()->save($recebimentos);
                $caixaFinanceiro->save();

                break;
            case 'xxx':
                break;
            default:
                return response('Evento não identificado', 202);
                break;
        }

        return response('Ok', 200);
    }
}
