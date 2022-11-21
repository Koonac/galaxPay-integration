<?php

namespace App\Http\Controllers\parametrosUser;

use App\Http\Controllers\Controller;
use App\Models\galaxpay_parametros;
use App\Models\parametros_user;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class perfilController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return view('parametrosUser.dadosUser', ['user' => $request->user()]);
    }

    public function perfilParametros(Request $request)
    {
        return view('parametrosUser.parametros', ['user' => $request->user()]);
    }

    public function editPassword(Request $request)
    {
        $userEdit       = User::find($request->idUserEdit);
        $senhaAntiga    = $request->oldPassword;
        $novaSenha      = $request->newPassword;
        $confirmaSenha  = $request->confirmNewPassword;

        if (!Hash::check($senhaAntiga, $userEdit->password)) {
            return redirect()->back()->withInput()->withErrors(["Senha inválida."]);
        }
        if ($novaSenha != $confirmaSenha) {
            return redirect()->back()->withInput()->withErrors(["Senha de confirmação incorreta."]);
        }

        // ALTERANDO SNEHA DO USUARIO
        $novaSenha      = Hash::make($request->newPassword);
        $userEdit->update(['password' => $novaSenha]);

        return redirect()->back()->withInput()->with('SUCCESS', ['Senha alterada com sucesso.']);
    }

    public function editUser(Request $request, User $userEdit)
    {
        try {
            // TRATANDO VARIÁVEIS
            $cpfCnpj     = trim($request->cpfCnpj);
            $cpfCnpj     = str_replace(".", "", $cpfCnpj);
            $cpfCnpj     = str_replace(",", "", $cpfCnpj);
            $cpfCnpj     = str_replace("-", "", $cpfCnpj);
            $cpfCnpj     = str_replace("/", "", $cpfCnpj);
            $telefone1   = trim($request->telefone1);
            $telefone1   = str_replace(" ", "", $telefone1);
            $telefone1   = str_replace("-", "", $telefone1);
            $telefone1   = str_replace("(", "", $telefone1);
            $telefone1   = str_replace(")", "", $telefone1);
            $telefone2   = trim($request->telefone2);
            $telefone2   = str_replace(" ", "", $telefone2);
            $telefone2   = str_replace("-", "", $telefone2);
            $telefone2   = str_replace("(", "", $telefone2);
            $telefone2   = str_replace(")", "", $telefone2);

            // ADICIONANDO RECEBIDOS PELO REQUEST AO MODEL
            if (!empty($request->razaoSocial)) $userEdit->razao_social = $request->razaoSocial;
            if (!empty($request->nomeFantasia)) $userEdit->nome_fantasia = $request->nomeFantasia;
            if (!empty($cpfCnpj)) $userEdit->cpf_cnpj = $cpfCnpj;
            if (!empty($request->nomePerfil)) $userEdit->name = $request->nomePerfil;
            if (!empty($telefone1)) $userEdit->telefone_1 = $telefone1;
            if (!empty($telefone2)) $userEdit->telefone_2 = $telefone2;
            if (!empty($request->usuarioPerfil)) $userEdit->login = $request->usuarioPerfil;
            if (!empty($request->emailPerfil)) $userEdit->email = $request->emailPerfil;


            // SALVANDO ALTERAÇÕES
            $userEdit->save();

            // ANALISANDO SE O USER É ADMIN
            if ($userEdit->role == 'Admin') {

                // DEFININDO VALORES DE PARAMETROS DO USUARIO
                if (!empty($request->valorCard)) $userEdit->parametros->valor_card = $request->valorCard;
                if (!empty($request->valorCancelamentoContrato)) $userEdit->parametros->valor_cancelamento_contrato = $request->valorCancelamentoContrato;
                if (!empty($request->cobrarCancelamentoMeses)) $userEdit->parametros->cobrar_cancelamento_meses = $request->cobrarCancelamentoMeses;
                if (!empty($request->contaRecebimentoPadrao)) $userEdit->parametros->conta_recebimento_padrao = $request->contaRecebimentoPadrao;
                if (!empty($request->quantidadeDependentesGalaxpay)) $userEdit->parametros->quantidade_dependentes_galaxpay = $request->quantidadeDependentesGalaxpay;
                if (!empty($request->nomeCampoDependente)) $userEdit->parametros->nome_campo_dependente = $request->nomeCampoDependente;
                if (!empty($request->cpfCampoDependente)) $userEdit->parametros->cpf_campo_dependente = $request->cpfCampoDependente;
                if (!empty($request->nascimentoCampoDependente)) $userEdit->parametros->nascimento_campo_dependente = $request->nascimentoCampoDependente;

                // DEFININDO VALORES DO GALAXPAY PARAMETROS 
                if (!empty($request->galaxId)) $userEdit->galaxPayParametros->galax_id = $request->galaxId;
                if (!empty($request->galaxHash)) $userEdit->galaxPayParametros->galax_hash = $request->galaxHash;
                if (!empty($request->webhookHash)) $userEdit->galaxPayParametros->webhook_hash = $request->webhookHash;

                // SALVANDO ATUALZIÇÕES 
                $userEdit->parametros->save();
                $userEdit->galaxPayParametros->save();
            }

            return redirect()->back()->withInput()->with('SUCCESS', ['Alterações realizadas com sucesso.']);
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['Ocorreu um erro inesperado. Mensagem: ' . $e->getMessage()]);
        }
    }


    public function deleteUser(Request $request)
    {
    }
}
