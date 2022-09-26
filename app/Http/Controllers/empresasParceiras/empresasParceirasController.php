<?php

namespace App\Http\Controllers\empresasParceiras;

use App\Http\Controllers\Controller;
use App\Models\empresas_parceiras;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class empresasParceirasController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return view('empresasParceiras.empresasParceiras');
    }

    public function cadastroEmpresaParceira(Request $request)
    {
        // CRIANDO MODELO DE USUARIO
        $empresaParceiraUser = new User();
        $empresaParceira = new empresas_parceiras();

        // INICIALIZANDO VARIAVEIS        
        $emailLogin = $request->emailLogin;
        $razaoSocial = $request->razaoSocial;
        $nomeFantasia = $request->nomeFantasia;
        $userLogin = $request->userLogin;
        $cpfCnpj = $request->cpfCnpj;
        $password = $this->passwordGenerate(10);

        // DEFININDO VALORES PARA CADASTRO
        $empresaParceiraUser->name = $razaoSocial;
        $empresaParceiraUser->razao_social = $razaoSocial;
        $empresaParceiraUser->nome_fantasia = $nomeFantasia;
        $empresaParceiraUser->cpf_cnpj = $cpfCnpj;
        $empresaParceiraUser->login = $userLogin;
        $empresaParceiraUser->email = $emailLogin;
        $empresaParceiraUser->password = Hash::make($password);
        $empresaParceiraUser->role = 'empresaParceira';

        // SALVANDO NOVO CADASTRO NO BANCO
        $empresaParceiraUser->save();

        // CRIANDO VINCULO DO USER CRIADO COM O USUARIO LOGADO
        $empresaParceira->user_id = $empresaParceiraUser->id;
        $empresaParceira->user_linked_id = $request->user()->id;

        // SALVANDO NOVO CADASTRO NO BANCO
        $empresaParceira->save();

        // REDIRECIONANDO A PAGINA
        return redirect()->route('empresasParceiras')->with('SUCCESS', ['Empresa cadastrada com sucesso. [<strong>Login:</strong> ' . $userLogin . ' <strong>Senha:</strong> ' . $password . ']']);
    }

    public function loginEmpresaParceira(Request $request)
    {
        return view('empresasParceiras.loginEmpresasParceiras');
    }

    public function verificaLoginEmpresaParceira(Request $request)
    {
        $credenciais = [
            'login' => $request->userLogin,
            'password' => $request->senhaLogin
        ];

        if (Auth::attempt($credenciais)) {
            return redirect()->route('empresasParceiras.clientesStatus');
        } else {
            return redirect()->back()->withInput()->withErrors(['Usuário/Senha Incorretos']);
        }
    }

    public function clientesStatusEmpresaParceira(Request $request)
    {
        return view('clientes.clienteStatus');
    }

    public function perfilEmpresaParceira(Request $request)
    {
        return view('parametrosUser.perfil', ['user' => $request->user()]);
    }

    public function editPasswordEmpresaParceira(Request $request)
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

    // FUNÇÃO PARA GERAR SENHA ALEATORIO
    function passwordGenerate($qtdeCaracteres)
    {
        $caracteres = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($caracteres), 0, $qtdeCaracteres);
    }
}
