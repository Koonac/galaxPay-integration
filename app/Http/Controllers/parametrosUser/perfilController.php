<?php

namespace App\Http\Controllers\parametrosUser;

use App\Http\Controllers\Controller;
use App\Models\galaxpay_parametros;
use App\Models\User;
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
        return view('parametrosUser.perfil', ['user' => $request->user()]);
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
        $userEdit->razao_social     = $request->razaoSocial;
        $userEdit->nome_fantasia    = $request->nomeFantasia;
        $userEdit->cpf_cnpj         = $cpfCnpj;
        $userEdit->name             = $request->nomePerfil;
        $userEdit->telefone_1       = $telefone1;
        $userEdit->telefone_2       = $telefone2;
        $userEdit->login            = $request->usuarioPerfil;
        $userEdit->email            = $request->emailPerfil;

        // SALVANDO ALTERAÇÕES
        $userEdit->save();

        // ANALISANDO SE O USER É ADMIN
        if ($userEdit->role == 'Admin') {
            $userEdit->galaxPayParametros->galax_id = $request->galaxId;
            $userEdit->galaxPayParametros->galax_hash = $request->galaxHash;
            $userEdit->galaxPayParametros->save();
        }

        return redirect()->back()->withInput()->with('SUCCESS', ['Alterações realizadas com sucesso.']);
    }

    public function deleteUser(Request $request)
    {
    }
}
