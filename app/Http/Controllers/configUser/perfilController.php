<?php

namespace App\Http\Controllers\configUser;

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
        return view('configUser.perfil', ['user' => $request->user()]);
    }
    
    public function editPassword(Request $request)
    {
        $userEdit       = User::find($request->idUserEdit);
        $senhaAntiga    = $request->oldPassword;
        $novaSenha      = $request->newPassword; 
        $confirmaSenha  = $request->confirmNewPassword; 
        
        if(!Hash::check($senhaAntiga, $userEdit->password)){
            return redirect()->back()->withInput()->withErrors(["Senha inválida."]);
        }
        if($novaSenha != $confirmaSenha){
            return redirect()->back()->withInput()->withErrors(["Senha de confirmação incorreta."]);
        }
        
        // ALTERANDO SNEHA DO USUARIO
        $novaSenha      = Hash::make($request->newPassword); 
        $userEdit->update(['password' => $novaSenha]);

        return redirect()->back()->withInput()->with('SUCCESS', ['Senha alterada com sucesso.']);

    }

    public function editUser(Request $request)
    {
        // CAPTURANDO MODEL DO USUARIO QUE ESTA EDITANDO
        $userEdit = User::find($request->idUserEdit);

        // CAPTURANDO MODEL DE PARAMETROS DA GALAXPAY ASSOCIADO AO USUARIO QUE ESTA EDITANDO
        $galaxpayParametros = galaxpay_parametros::firstWhere('user_id', $request->idUserEdit);

        // VERIFICANDO SE JA EXISTE PARAMETROS ASSOCIADOS A ESTE USER
        if(empty($galaxpayParametros)){
            // CASO NAO TENHA CRIA UM NOVO OBJETO
            $galaxpayParametros = new galaxpay_parametros();
            $galaxpayParametros->user_id = $request->idUserEdit;
        }
                
        // ADICIONANDO RECEBIDOS PELO REQUEST AO MODEL
        $userEdit->name = $request->nomePerfil; 
        // $userEdit->cpf = $request->cpfPerfil;
        $userEdit->login = $request->usuarioPerfil; 
        $userEdit->email = $request->emailPerfil; 
        $galaxpayParametros->galax_id = $request->galaxId;
        $galaxpayParametros->galax_hash = $request->galaxHash;
        
        $request->empresaPerfil;
        $request->cnpjPerfil;
        
        // SALVANDO ALTERAÇÕES
        $galaxpayParametros->save();
        $userEdit->save();

        return redirect()->back()->withInput()->with('SUCCESS', ['Alterações realizadas com sucesso.']);
    }

    public function deleteUser(Request $request)
    {

    }
}
