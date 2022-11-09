<?php

namespace App\Http\Controllers\empresasParceiras;

use App\Http\Controllers\api\galaxPayControllerAPI;
use App\Http\Controllers\Controller;
use App\Models\clientes_dependentes_galaxpay;
use App\Models\clientes_galaxpay;
use App\Models\empresas_parceiras;
use App\Models\User;
use Exception;
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
        // ANALISANDO VARIAVEIS
        $empresasParceiras = array();

        // CAPTURANDO AS EMPRESAS ASSOCIADAS AO USUARIO

        switch ($request->user()->role) {
            case 'empresaParceira':
                $userLinkedId = $request->user()->userPrimario->user_linked_id;
                $userPrimario = User::find($userLinkedId);
                $empresasAssociadas = $userPrimario->empresasAssociadas;
                break;
            case 'Funcionario':
                $userLinkedId = $request->user()->userPrimarioFuncionario->user_linked_id;
                $userPrimario = User::find($userLinkedId);
                $empresasAssociadas = $userPrimario->empresasAssociadas;
                break;
            default:
                $empresasAssociadas = $request->user()->empresasAssociadas;
                break;
        }

        // ANALISANDO SE FOI ENCOTRADO EMPRESAS ASSOCIADAS
        if (count($empresasAssociadas) > 0) {
            // PERCORRENDO O LAÇO DAS EMPRESAS ASSOCIADAS PARA CAPTURAR OS DADOS DA EMPRESA PARCEIRA
            foreach ($empresasAssociadas as $empresaAssociada) {
                $empresasParceiras[] = User::find($empresaAssociada->user_id);
            }
        }

        // RETORNANDO VIEW
        return view('empresasParceiras.empresasParceiras', ['empresasParceiras' => $empresasParceiras]);
    }

    public function cadastroEmpresaParceira(Request $request)
    {
        try {
            // CRIANDO MODELO DE USUARIO
            $empresaParceiraUser = new User();
            $empresaParceira = new empresas_parceiras();

            // TRATANDO VARIÁVEIS
            $cpfCnpjEmpresa     = trim($request->cpfCnpj);
            $cpfCnpjEmpresa     = str_replace(".", "", $cpfCnpjEmpresa);
            $cpfCnpjEmpresa     = str_replace(",", "", $cpfCnpjEmpresa);
            $cpfCnpjEmpresa     = str_replace("-", "", $cpfCnpjEmpresa);
            $cpfCnpjEmpresa     = str_replace("/", "", $cpfCnpjEmpresa);
            $telefoneEmpresa1   = trim($request->telefone1Empresa);
            $telefoneEmpresa1   = str_replace(" ", "", $telefoneEmpresa1);
            $telefoneEmpresa1   = str_replace("-", "", $telefoneEmpresa1);
            $telefoneEmpresa1   = str_replace("(", "", $telefoneEmpresa1);
            $telefoneEmpresa1   = str_replace(")", "", $telefoneEmpresa1);
            $telefoneEmpresa2   = trim($request->telefone2Empresa);
            $telefoneEmpresa2   = str_replace(" ", "", $telefoneEmpresa2);
            $telefoneEmpresa2   = str_replace("-", "", $telefoneEmpresa2);
            $telefoneEmpresa2   = str_replace("(", "", $telefoneEmpresa2);
            $telefoneEmpresa2   = str_replace(")", "", $telefoneEmpresa2);
            $emailLogin         = $request->emailLogin;
            $razaoSocial        = $request->razaoSocial;
            $nomeFantasia       = $request->nomeFantasia;
            $userLogin          = $request->userLogin;
            $password           = $this->passwordGenerate(10);

            // DEFININDO VALORES PARA CADASTRO
            $empresaParceiraUser->name = $razaoSocial;
            $empresaParceiraUser->razao_social = $razaoSocial;
            $empresaParceiraUser->nome_fantasia = $nomeFantasia;
            $empresaParceiraUser->cpf_cnpj = $cpfCnpjEmpresa;
            $empresaParceiraUser->login = $userLogin;
            $empresaParceiraUser->email = strtoupper($emailLogin);
            $empresaParceiraUser->telefone_1 = $telefoneEmpresa1;
            $empresaParceiraUser->telefone_2 = $telefoneEmpresa2;
            $empresaParceiraUser->password = Hash::make($password);
            $empresaParceiraUser->role = 'empresaParceira';

            // SALVANDO NOVO CADASTRO NO BANCO
            $empresaParceiraUser->save();

            switch ($request->user()->role) {
                case 'empresaParceira':
                    $userLinkedId = $request->user()->userPrimario->user_linked_id;
                    $userPrimario = User::find($userLinkedId);
                    $idUserPrimario = $userPrimario->id;
                    break;
                case 'Funcionario':
                    $userLinkedId = $request->user()->userPrimarioFuncionario->user_linked_id;
                    $userPrimario = User::find($userLinkedId);
                    $idUserPrimario = $userPrimario->id;
                    break;
                default:
                    $idUserPrimario = $request->user()->id;
                    break;
            }

            // CRIANDO VINCULO DO USER CRIADO COM O USUARIO LOGADO
            $empresaParceira->user_id = $empresaParceiraUser->id;
            $empresaParceira->user_linked_id = $idUserPrimario;

            // SALVANDO NOVO CADASTRO NO BANCO
            $empresaParceira->save();

            // REDIRECIONANDO A PAGINA
            return redirect()->route('empresasParceiras')->with('SUCCESS', ['Empresa cadastrada com sucesso. [Login: ' . $userLogin . ' Senha: ' . $password . ']']);
        } catch (Exception $e) {
            // REDIRECIONANDO A PAGINA
            return redirect()->back()->withInput()->withErrors([$e->getMessage()]);
        }
    }

    public function editEmpresaParceira(Request $request, User $empresaParceira)
    {
        try {
            // TRATANDO VARIÁVEIS
            $cpfCnpjEmpresa     = trim($request->cpfCnpj);
            $cpfCnpjEmpresa     = str_replace(".", "", $cpfCnpjEmpresa);
            $cpfCnpjEmpresa     = str_replace(",", "", $cpfCnpjEmpresa);
            $cpfCnpjEmpresa     = str_replace("-", "", $cpfCnpjEmpresa);
            $cpfCnpjEmpresa     = str_replace("/", "", $cpfCnpjEmpresa);
            $telefoneEmpresa1   = trim($request->telefone1Empresa);
            $telefoneEmpresa1   = str_replace(" ", "", $telefoneEmpresa1);
            $telefoneEmpresa1   = str_replace("-", "", $telefoneEmpresa1);
            $telefoneEmpresa1   = str_replace("(", "", $telefoneEmpresa1);
            $telefoneEmpresa1   = str_replace(")", "", $telefoneEmpresa1);
            $telefoneEmpresa2   = trim($request->telefone2Empresa);
            $telefoneEmpresa2   = str_replace(" ", "", $telefoneEmpresa2);
            $telefoneEmpresa2   = str_replace("-", "", $telefoneEmpresa2);
            $telefoneEmpresa2   = str_replace("(", "", $telefoneEmpresa2);
            $telefoneEmpresa2   = str_replace(")", "", $telefoneEmpresa2);
            $emailLogin         = $request->emailLogin;
            $razaoSocial        = $request->razaoSocial;
            $nomeFantasia       = $request->nomeFantasia;
            $userLogin          = $request->userLogin;

            // DEFININDO VALORES PARA CADASTRO
            $empresaParceira->name = $razaoSocial;
            $empresaParceira->razao_social = $razaoSocial;
            $empresaParceira->nome_fantasia = $nomeFantasia;
            $empresaParceira->cpf_cnpj = $cpfCnpjEmpresa;
            $empresaParceira->login = $userLogin;
            $empresaParceira->email = $emailLogin;
            $empresaParceira->telefone_1 = $telefoneEmpresa1;
            $empresaParceira->telefone_2 = $telefoneEmpresa2;

            // SALVANDO NOVO CADASTRO NO BANCO
            $empresaParceira->save();

            // REDIRECIONANDO A PAGINA
            return redirect()->route('empresasParceiras')->with('SUCCESS', ['Empresa alterada com sucesso.']);
        } catch (Exception $e) {
            // REDIRECIONANDO A PAGINA
            return redirect()->back()->withInput()->withErrors([$e->getMessage()]);
        }
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

    public function pesquisaClienteDependente(Request $request)
    {
        // ANALISANDO TIPO DE PESQUISA
        switch ($request->opcaoPesquisa) {
            case 'matricula':
                // PESQUISANDO DEPENDENTE POR CPF
                $clientesDependentesGalaxpay = clientes_dependentes_galaxpay::where('matricula_cliente_dependente', $request->pesquisaCliente)->first();

                // ANALISANDO SE DEPENDENTE FOI ENCONTRADO PARA PESQUISAR O CLIENTE
                if (empty($clientesDependentesGalaxpay)) {
                    $clienteGalaxpay = clientes_galaxpay::where('matricula', $request->pesquisaCliente)->first();
                } else {
                    $clienteGalaxpay = clientes_galaxpay::find($clientesDependentesGalaxpay->cliente_galaxpay_id);
                }
                break;
            case 'cpfCnpj':
                // PESQUISANDO DEPENDENTE POR CPF
                $clientesDependentesGalaxpay = clientes_dependentes_galaxpay::where('cpf_cliente_dependente', $request->pesquisaCliente)->first();

                // ANALISANDO SE DEPENDENTE FOI ENCONTRADO PARA PESQUISAR O CLIENTE
                if (empty($clientesDependentesGalaxpay)) {
                    $clienteGalaxpay = clientes_galaxpay::where('cpf_cnpj_cliente', $request->pesquisaCliente)->first();
                } else {
                    $clienteGalaxpay = clientes_galaxpay::find($clientesDependentesGalaxpay->cliente_galaxpay_id);
                }
                break;
            default:
                // PESQUISANDO DEPENDENTE POR CPF
                $clientesDependentesGalaxpay = clientes_dependentes_galaxpay::where('cpf_cliente_dependente', $request->pesquisaCliente)->first();

                // ANALISANDO SE DEPENDENTE FOI ENCONTRADO PARA PESQUISAR O CLIENTE
                if (empty($clientesDependentesGalaxpay)) {
                    $clienteGalaxpay = clientes_galaxpay::where('cpf_cnpj_cliente', $request->pesquisaCliente)->first();
                } else {
                    $clienteGalaxpay = clientes_galaxpay::find($clientesDependentesGalaxpay->cliente_galaxpay_id);
                }
                break;
        }

        // ANALISANDO SE FOI ENCOTNRADO ALGUM CLIENTE
        if (!empty($clienteGalaxpay)) {
            galaxPayControllerAPI::atualizaClienteGalaxPay($request, $clienteGalaxpay);
        }

        return view('clientes.infoClienteStatus', ['clienteGalaxpay' => $clienteGalaxpay]);
    }

    public function editPasswordEmpresaParceira(Request $request, User $empresaParceira)
    {
        $senhaAntiga    = $request->oldPassword;
        $novaSenha      = $request->newPassword;
        $confirmaSenha  = $request->confirmNewPassword;

        if (!Hash::check($senhaAntiga, $empresaParceira->password)) {
            return redirect()->back()->withInput()->withErrors(["Senha inválida."]);
        }
        if ($novaSenha != $confirmaSenha) {
            return redirect()->back()->withInput()->withErrors(["Senha de confirmação incorreta."]);
        }

        // ALTERANDO SNEHA DO USUARIO
        $novaSenha      = Hash::make($request->newPassword);
        $empresaParceira->update(['password' => $novaSenha]);

        return redirect()->back()->withInput()->with('SUCCESS', ['Senha alterada com sucesso.']);
    }

    // FUNÇÃO PARA GERAR SENHA ALEATORIO
    function passwordGenerate($qtdeCaracteres)
    {
        $caracteres = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($caracteres), 0, $qtdeCaracteres);
    }

    function deleteEmpresaParceira(Request $request)
    {
        $userEmpresaParceira = User::find($request->idEmpresaParceiraDelete);
        $userEmpresaParceira->delete();

        return redirect()->back()->with('SUCCESS', ['Empresa parceira deletada com sucesso.']);
    }
}
