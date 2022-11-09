<?php

namespace App\Http\Controllers\funcionarios;

use App\Http\Controllers\Controller;
use App\Models\clientes_dependentes_galaxpay;
use App\Models\clientes_galaxpay;
use App\Models\empresas_parceiras;
use App\Models\funcionarios;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class funcionariosController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // ANALISANDO VARIAVEIS
        $funcionarios = array();

        // CAPTURANDO AS EMPRESAS ASSOCIADAS AO USUARIO
        $funcionariosAssociadas = $request->user()->funcionariosAssociadas;

        // ANALISANDO SE FOI ENCOTRADO EMPRESAS ASSOCIADAS
        if (count($funcionariosAssociadas) > 0) {
            // PERCORRENDO O LAÇO DAS EMPRESAS ASSOCIADAS PARA CAPTURAR OS DADOS DA EMPRESA PARCEIRA
            foreach ($funcionariosAssociadas as $funcionarioAssociado) {
                $funcionarios[] = User::find($funcionarioAssociado->user_id);
            }
        }

        // RETORNANDO VIEW
        return view('funcionarios.funcionarios', ['funcionarios' => $funcionarios]);
    }

    public function cadastroFuncionario(Request $request)
    {
        try {
            // CRIANDO MODELO DE USUARIO
            $funcionarioUser = new User();
            $funcionario = new funcionarios();

            // INICIALIZANDO VARIAVEIS        
            $emailLogin = strtoupper($request->emailLogin);
            $nomeFuncionario = $request->nomeFuncionario;
            $cpfFuncionario = $request->cpfFuncionario;
            $telefone1Funcionario = $request->telefone1Funcionario;
            $telefone2Funcionario = $request->telefone2Funcionario;
            $userLogin = $request->userLogin;
            $userPass = $request->userPass;

            // DEFININDO VALORES PARA CADASTRO
            $funcionarioUser->name = $nomeFuncionario;
            $funcionarioUser->cpf_cnpj = $cpfFuncionario;
            $funcionarioUser->telefone_1 = $telefone1Funcionario;
            $funcionarioUser->telefone_1 = $telefone2Funcionario;
            $funcionarioUser->login = $userLogin;
            $funcionarioUser->email = $emailLogin;
            $funcionarioUser->password = Hash::make($userPass);
            $funcionarioUser->role = 'Funcionario';

            // SALVANDO NOVO CADASTRO NO BANCO
            $funcionarioUser->save();

            // CRIANDO VINCULO DO USER CRIADO COM O USUARIO LOGADO
            $funcionario->user_id = $funcionarioUser->id;
            $funcionario->user_linked_id = $request->user()->id;

            // SALVANDO NOVO CADASTRO NO BANCO
            $funcionario->save();

            // REDIRECIONANDO A PAGINA
            return redirect()->route('funcionarios')->with('SUCCESS', ['Funcionário cadastrado com sucesso. [Login: ' . $userLogin . ' Senha: ' . $userPass . ']']);
        } catch (Exception $e) {
            // REDIRECIONANDO A PAGINA
            return redirect()->back()->withInput()->withErrors([$e->getMessage()]);
        }
    }

    public function editFuncionario(Request $request, User $funcionario)
    {
        try {
            // TRATANDO VARIÁVEIS
            $cpfCnpjFuncionario     = trim($request->cpfCnpj);
            $cpfCnpjFuncionario     = str_replace(".", "", $cpfCnpjFuncionario);
            $cpfCnpjFuncionario     = str_replace(",", "", $cpfCnpjFuncionario);
            $cpfCnpjFuncionario     = str_replace("-", "", $cpfCnpjFuncionario);
            $cpfCnpjFuncionario     = str_replace("/", "", $cpfCnpjFuncionario);
            $telefoneFuncionario1   = trim($request->telefone1Funcionario);
            $telefoneFuncionario1   = str_replace(" ", "", $telefoneFuncionario1);
            $telefoneFuncionario1   = str_replace("-", "", $telefoneFuncionario1);
            $telefoneFuncionario1   = str_replace("(", "", $telefoneFuncionario1);
            $telefoneFuncionario1   = str_replace(")", "", $telefoneFuncionario1);
            $telefoneFuncionario2   = trim($request->telefone2Funcionario);
            $telefoneFuncionario2   = str_replace(" ", "", $telefoneFuncionario2);
            $telefoneFuncionario2   = str_replace("-", "", $telefoneFuncionario2);
            $telefoneFuncionario2   = str_replace("(", "", $telefoneFuncionario2);
            $telefoneFuncionario2   = str_replace(")", "", $telefoneFuncionario2);
            $emailLogin             = $request->emailLogin;
            $nomeFuncionario        = $request->nomeFuncionario;
            $userLogin              = $request->userLogin;
            empty($request->permitirClientes) ? $acessoClientes = 'N' : $acessoClientes = 'S';
            empty($request->permitirFinanceiro) ? $acessoFinanceiro = 'N' : $acessoFinanceiro = 'S';
            empty($request->permitirEmpresas) ? $acessoEmpresas = 'N' : $acessoEmpresas = 'S';
            empty($request->permitirGalaxPay) ? $acessoGalaxpay = 'N' : $acessoGalaxpay = 'S';

            // DEFININDO VALORES PARA ALTERAÇÃO
            $funcionario->name          = $nomeFuncionario;
            $funcionario->cpf_cnpj      = $cpfCnpjFuncionario;
            $funcionario->login         = $userLogin;
            $funcionario->email         = $emailLogin;
            $funcionario->telefone_1    = $telefoneFuncionario1;
            $funcionario->telefone_2    = $telefoneFuncionario2;
            $funcionario->funcionarioPermissoes->acesso_clientes = $acessoClientes;
            $funcionario->funcionarioPermissoes->acesso_financeiro = $acessoFinanceiro;
            $funcionario->funcionarioPermissoes->acesso_empresas = $acessoEmpresas;
            $funcionario->funcionarioPermissoes->acesso_galaxpay = $acessoGalaxpay;

            // SALVANDO NOVOS DADOS NO BANCO
            $funcionario->save();
            $funcionario->funcionarioPermissoes->save();

            // REDIRECIONANDO A PAGINA
            return redirect()->route('funcionarios')->with('SUCCESS', ['Funcionário alterado com sucesso.']);
        } catch (Exception $e) {
            // REDIRECIONANDO A PAGINA
            return redirect()->back()->withInput()->withErrors([$e->getMessage()]);
        }
    }

    public function perfilFuncionario(Request $request)
    {
        return view('parametrosUser.perfil', ['user' => $request->user()]);
    }

    public function editPasswordFuncionario(Request $request)
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

    function deleteFuncionario(Request $request)
    {
        $userFuncionario = User::find($request->idFuncionarioDelete);
        $userFuncionario->delete();

        return redirect()->back()->with('SUCCESS', ['Funcionário deletado com sucesso.']);
    }
}
