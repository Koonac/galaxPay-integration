<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\caixa_financeiro;
use App\Models\campo_personalizado_cliente_galaxpay;
use App\Models\clientes_dependentes_galaxpay;
use App\Models\clientes_galaxpay;
use App\Models\contratos;
use App\Models\endereco_cliente_galaxpay;
use App\Models\transacoes_galaxpay;
use App\Models\User;
use DateInterval;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class galaxPayControllerAPI extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return view('galaxPay.galaxPayClientes');
    }

    public function generateAcessToken(Request $request)
    {

        // INICIALIZANDO VARIAVEIS
        $permissoesApi = 'customers.read customers.write plans.read plans.write transactions.read transactions.write webhooks.write cards.read cards.write card-brands.read subscriptions.read subscriptions.write charges.read charges.write boletos.read';
        $permissaoUserLogado = $request->user()->role;
        $userPrimario = User::find($request->user()->id);
        switch ($permissaoUserLogado) {
            case 'empresaParceira':
                $userLinkedId = $request->user()->userPrimario->user_linked_id;
                $userPrimario = User::find($userLinkedId);
                break;
            case 'Funcionario':
                $userLinkedId = $request->user()->userPrimarioFuncionario->user_linked_id;
                $userPrimario = User::find($userLinkedId);
                break;
        }

        // CAPTURANDO PARAMETROS DO USUARIO
        $galaxPayParametros = $userPrimario->galaxPayParametros;

        // ANALISANDO SE EXISTEM PARAMETROS DEFINIDOS
        if ((empty($galaxPayParametros)) || $galaxPayParametros->count() <= 0) {
            $retorno['statusRetorno'] = 'ERRO';
            $retorno['msgErro'] = 'Parametros de conexão com GalaxPay não encontrados.';
            return $retorno;
        }

        // INICIALIZANDO VARIAVEIS
        $galaxId        = $galaxPayParametros->galax_id;
        $galaxHash      = $galaxPayParametros->galax_hash;
        $accessToken    = $galaxPayParametros->galax_token;

        // TRAZENDO DATA DE CADASTRO DO TOKEN
        $dateRefresh = DateTime::createFromFormat('Y-m-d H:i:s', $galaxPayParametros->refresh_token);
        // INICILIZANDO VARIAVEL COM A DATA ATUAL
        // TEM QUE AJSUTAR ISSO, TA DANDO ALGUM BUG AQUI --HENRIQUE DEV
        $expiracaoToken = DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        // RETIRANDO 10 MINUTOS DA DATA ATUAL PARA COMPARAÇÃO
        $expiracaoToken->sub(new DateInterval('PT10M'));

        if ($expiracaoToken > $dateRefresh) {

            // MONTANDO CORPO PARA ENVIO API
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode("$galaxId:$galaxHash"),
                'Content-Type' => 'application/json'
            ])->withBody(json_encode([
                'grant_type' => 'authorization_code',
                'scope' => $permissoesApi
            ]), 'json')->post('https://api.sandbox.cloud.galaxpay.com.br/v2/token');

            // SALVANDO TOKEN NO BANCO DE DADOS 
            $galaxPayParametros->galax_token = $response['access_token'];
            $galaxPayParametros->refresh_token = DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
            $galaxPayParametros->save();
            $accessToken = $response['access_token'];
        }

        //RETORNANDO RESPOSTA
        $retorno['statusRetorno'] = 'SUCCESS';
        $retorno['access_token'] = $accessToken;
        return $retorno;
    }

    /*****************/
    /**** CLIENTE ****/
    /*****************/
    // ESTA FUNÇÃO NÃO ESTA IMPORTANDO DEPENDENTES SOMENTE CAMPOS EXTRAS
    public function importaClientesGalaxPay(Request $request)
    {
        // CAPTURANDO ACCESS TOKEN
        $generateAcessToken = $this->generateAcessToken($request);
        // ANALISANDO STATUS DE RETORNO DA FUNÇÃO
        if ($generateAcessToken['statusRetorno'] != 'SUCCESS') {
            // REDIRECIONANDO COM ERRO
            return redirect()->back()->withErrors(['Erro: ' . $generateAcessToken['msgErro']]);
        }
        $accessToken                = $generateAcessToken['access_token'];
        $lacoCliente                = true;
        $registrosImportados        = 0;
        $totalRegistrosCapturados   = 0;
        $startAt                    = 0;
        $controleLaco = 0;

        while ($lacoCliente) {
            set_time_limit(0);
            if ($controleLaco == 5) {
                sleep(2);
                $controleLaco = 0;
            }
            // MONTANDO CORPO PARA ENVIO DA API
            $response = Http::withHeaders([
                'Authorization' => "Bearer $accessToken",
                'Content-Type' => 'application/json'
            ])->get("https://api.sandbox.cloud.galaxpay.com.br/v2/customers?startAt=$startAt&limit=100");

            // CAPTURANDO RESPOSTA DA API
            $response = json_decode($response);

            // VERIFICANDO ERRO
            if (!empty($response->error)) {
                // REDIRECIONANDO COM ERRO
                return redirect()->back()->withErrors(['Erro: ' . $response->error->message]);
            } else {
                // ANALISANDO TOTAL DE REGISTROS
                $totalRegistrosCapturados += $response->totalQtdFoundInPage;

                if ($totalRegistrosCapturados <= 0) {
                    // REDIRECIONANDO COM ERRO
                    return redirect()->back()->with(['WARNING' => ['Não há registros para sincronização']]);
                } else {
                    // PERCORRENDO LAÇO
                    foreach ($response->Customers as $customer) {
                        $listClientesGalaxpay['listClientesGalaxpay'][] = $customer;
                        // INICIALIZANDO VARIAVEIS
                        $codigoClienteGalaxpay          = $customer->galaxPayId;
                        $nomeCliente                    = $customer->name;
                        $cpfCnpjCliente                 = $customer->document;
                        empty($customer->emails[0]) ? $emailCliente1 = '' : $emailCliente1 = $customer->emails[0];
                        empty($customer->emails[1]) ? $emailCliente2 = '' : $emailCliente2 = $customer->emails[1];
                        empty($customer->phones[0]) ? $telefoneCliente1 = '' : $telefoneCliente1 = $customer->phones[0];
                        empty($customer->phones[1]) ? $telefoneCliente2 = '' : $telefoneCliente2 = $customer->phones[1];
                        empty($customer->xxxx) ? $issNfCliente = '' : $issNfCliente = $customer->xxxx;
                        empty($customer->xxxx) ? $inscricaoMunicipalCliente = '' : $inscricaoMunicipalCliente = $customer->xxxx;
                        $statusCliente                  = $customer->status;
                        $createdAt                      = $customer->createdAt;
                        $updatedAt                      = $customer->updatedAt;
                        // ENDEREÇO
                        $cep                            = $customer->Address->zipCode;
                        $logradouro                     = $customer->Address->street;
                        $numero                         = $customer->Address->number;
                        $complemento                    = $customer->Address->complement;
                        $bairro                         = $customer->Address->neighborhood;
                        $cidade                         = $customer->Address->city;
                        $estado                         = $customer->Address->state;
                        $cadastraEnderecoCliente        = true;
                        // CAMPOS EXTRAS
                        $campoExtras                    = $customer->ExtraFields;

                        // PERCORRENDO LAÇO DE ENDEREÇOS
                        foreach ($customer->Address as $keyAddress => $valueAddress) {
                            // ANALISANDO SE O CAMPO COMPLEMENT É VAZIO
                            if ($keyAddress == 'complement' && empty($valueAddress)) continue;

                            // ANALISANDO SE EXISTE ALGUM CAMPO VAZIO NO ENDEREÇO
                            if (empty($valueAddress)) {
                                $cadastraEnderecoCliente = false;
                            };
                        }

                        // CRIANDO MODEL 
                        $galaxPayClientesAssociado = $request->user()->galaxPayClientes();

                        // VERIFICANDO SE O CLIENTE JA ESTA CADASTRADO
                        $clienteCadastrado = $galaxPayClientesAssociado->firstWhere('codigo_cliente_galaxpay', $codigoClienteGalaxpay);

                        // CASO SEJA ENCONTRADO REFAZ O LAÇO
                        if (!empty($clienteCadastrado)) continue;

                        // CRIANDO MODELS PARA INSERIR
                        $clienteGalaxpay                    = new clientes_galaxpay();
                        $enderecoClienteGalaxpay            = new endereco_cliente_galaxpay();

                        // ATRIBUINDO VALORES AO MODEL
                        $clienteGalaxpay->codigo_cliente_galaxpay          = $codigoClienteGalaxpay;
                        $clienteGalaxpay->nome_cliente                     = $nomeCliente;
                        $clienteGalaxpay->cpf_cnpj_cliente                 = $cpfCnpjCliente;
                        $clienteGalaxpay->email_cliente_1                  = $emailCliente1;
                        $clienteGalaxpay->email_cliente_2                  = $emailCliente2;
                        $clienteGalaxpay->telefone_cliente_1               = $telefoneCliente1;
                        $clienteGalaxpay->telefone_cliente_2               = $telefoneCliente2;
                        $clienteGalaxpay->iss_nf_cliente                   = $issNfCliente;
                        $clienteGalaxpay->inscricao_municipal_cliente      = $inscricaoMunicipalCliente;
                        $clienteGalaxpay->status_cliente                   = $statusCliente;
                        $clienteGalaxpay->createdAt                        = $createdAt;
                        $clienteGalaxpay->updatedAt                        = $updatedAt;
                        // ENDEREÇO CLIENTE
                        $enderecoClienteGalaxpay->cep               = $cep;
                        $enderecoClienteGalaxpay->logradouro        = $logradouro;
                        $enderecoClienteGalaxpay->numero            = $numero;
                        $enderecoClienteGalaxpay->complemento       = $complemento;
                        $enderecoClienteGalaxpay->bairro            = $bairro;
                        $enderecoClienteGalaxpay->cidade            = $cidade;
                        $enderecoClienteGalaxpay->estado            = $estado;

                        // SALVANDO NO BANCO DE DADOS
                        $galaxPayClientesAssociado->save($clienteGalaxpay);
                        if ($cadastraEnderecoCliente) {
                            $clienteGalaxpay->enderecoClienteGalaxpay()->save($enderecoClienteGalaxpay);
                        };

                        // VERIFICANDO CAMPOS EXTRAS
                        if (!empty($campoExtras)) {
                            // PERCORRENDO LAÇO
                            foreach ($campoExtras as $campoExtras) {
                                // CRIANDO MODEL PARA INSERT
                                $campoPersonalizadoClienteGalaxpay[]  = new campo_personalizado_cliente_galaxpay([
                                    'nome_campo_personalizado' => $campoExtras->tagName,
                                    'valor_campo_personalizado' => $campoExtras->tagValue
                                ]);
                            }
                            // INSERIDNO CAMPOS EXTRAS NO BANCO
                            $clienteGalaxpay->campoPersonalizadoClienteGalaxpay()->saveMany($campoPersonalizadoClienteGalaxpay);

                            // ZERANDO VARIAVEL
                            unset($campoPersonalizadoClienteGalaxpay);
                        }

                        // INCREMENTANDO VARIAVEL
                        $registrosImportados++;
                    }
                }

                // DEFININDO POR ONDE DEVE COMEÇAR A PESQUISAR NOVOS CLIENTES 
                $startAt = $totalRegistrosCapturados;

                // ANALISANDO SE JA CHEGOU NO LIMITE
                if ($response->totalQtdFoundInPage < 100) {
                    $lacoCliente = false;
                }
            }

            $controleLaco++;
        }

        //RETORNANDO RESPOSTA
        return redirect()->back()->with('SUCCESS', ["Foram importado(s) $registrosImportados novo(s) registros."]);
    }

    public function importaClienteGalaxPay(Request $request, $responseApi)
    {
        // ANALISANDO QUANTIDADE DE REGISTRO
        if ($responseApi->totalQtdFoundInPage <= 0) {
            // REDIRECIONANDO COM WARNING
            return view('components.messages.returnMessages', ['WARNING' => ['Nenhum registro encontrado.']]);
        } else {
            // INICIALIZANDO VARIAVEIS
            $meuId                          = $responseApi->Customers[0]->myId;
            $codigoClienteGalaxpay          = $responseApi->Customers[0]->galaxPayId;
            $nomeCliente                    = $responseApi->Customers[0]->name;
            $cpfCnpjCliente                 = $responseApi->Customers[0]->document;
            empty($responseApi->Customers[0]->emails[0]) ? $emailCliente1 = '' : $emailCliente1 = $responseApi->Customers[0]->emails[0];
            empty($responseApi->Customers[0]->emails[1]) ? $emailCliente2 = '' : $emailCliente2 = $responseApi->Customers[0]->emails[1];
            empty($responseApi->Customers[0]->phones[0]) ? $telefoneCliente1 = '' : $telefoneCliente1 = $responseApi->Customers[0]->phones[0];
            empty($responseApi->Customers[0]->phones[1]) ? $telefoneCliente2 = '' : $telefoneCliente2 = $responseApi->Customers[0]->phones[1];
            empty($responseApi->Customers[0]->xxxx) ? $issNfCliente = '' : $issNfCliente = $responseApi->Customers[0]->xxxx;
            empty($responseApi->Customers[0]->xxxx) ? $inscricaoMunicipalCliente = '' : $inscricaoMunicipalCliente = $responseApi->Customers[0]->xxxx;
            $statusCliente                  = $responseApi->Customers[0]->status;
            $createdAt                      = $responseApi->Customers[0]->createdAt;
            $updatedAt                      = $responseApi->Customers[0]->updatedAt;
            // ENDEREÇO
            $cep                            = $responseApi->Customers[0]->Address->zipCode;
            $logradouro                     = $responseApi->Customers[0]->Address->street;
            $numero                         = $responseApi->Customers[0]->Address->number;
            $complemento                    = $responseApi->Customers[0]->Address->complement;
            $bairro                         = $responseApi->Customers[0]->Address->neighborhood;
            $cidade                         = $responseApi->Customers[0]->Address->city;
            $estado                         = $responseApi->Customers[0]->Address->state;
            $cadastraEnderecoCliente        = true;
            $campoExtras                    = $responseApi->Customers[0]->ExtraFields;

            // GERANDO NUMERO DE MATRICULA (ANO ATUAL + CODIGO DO CLIENTE NA GALAXPAY + 3 ULTIMOS NUMERO DO CPF)
            $matricula = date('Y') . $codigoClienteGalaxpay . substr($cpfCnpjCliente, -3);

            // ANALISANDO TIPO DE USUARIO LOGADO
            $permissaoUserLogado = $request->user()->role;
            $userPrimario = User::find($request->user()->id);
            switch ($permissaoUserLogado) {
                case 'empresaParceira':
                    $userLinkedId = $request->user()->userPrimario->user_linked_id;
                    $userPrimario = User::find($userLinkedId);
                    break;
                case 'Funcionario':
                    $userLinkedId = $request->user()->userPrimarioFuncionario->user_linked_id;
                    $userPrimario = User::find($userLinkedId);
                    break;
            }

            // PERCORRENDO LAÇO DE ENDEREÇOS
            foreach ($responseApi->Customers[0]->Address as $keyAddress => $valueAddress) {
                // ANALISANDO SE O CAMPO COMPLEMENT É VAZIO
                if ($keyAddress == 'complement' && empty($valueAddress)) continue;

                // ANALISANDO SE EXISTE ALGUM CAMPO VAZIO NO ENDEREÇO
                if (empty($valueAddress)) {
                    $cadastraEnderecoCliente = false;
                };
            }

            // CRIANDO MODELS PARA INSERIR
            $clienteGalaxpay                    = new clientes_galaxpay();
            $enderecoClienteGalaxpay            = new endereco_cliente_galaxpay();

            // ATRIBUINDO VALORES AO MODEL
            $clienteGalaxpay->codigo_cliente_galaxpay          = $codigoClienteGalaxpay;
            $clienteGalaxpay->meu_id                           = $meuId;
            $clienteGalaxpay->matricula                        = $matricula;
            $clienteGalaxpay->nome_cliente                     = $nomeCliente;
            $clienteGalaxpay->cpf_cnpj_cliente                 = $cpfCnpjCliente;
            $clienteGalaxpay->email_cliente_1                  = $emailCliente1;
            $clienteGalaxpay->email_cliente_2                  = $emailCliente2;
            $clienteGalaxpay->telefone_cliente_1               = $telefoneCliente1;
            $clienteGalaxpay->telefone_cliente_2               = $telefoneCliente2;
            $clienteGalaxpay->iss_nf_cliente                   = $issNfCliente;
            $clienteGalaxpay->inscricao_municipal_cliente      = $inscricaoMunicipalCliente;
            $clienteGalaxpay->status_cliente                   = $statusCliente;
            $clienteGalaxpay->createdAt                        = $createdAt;
            $clienteGalaxpay->updatedAt                        = $updatedAt;
            // ENDEREÇO CLIENTE
            $enderecoClienteGalaxpay->cep               = $cep;
            $enderecoClienteGalaxpay->logradouro        = $logradouro;
            $enderecoClienteGalaxpay->numero            = $numero;
            $enderecoClienteGalaxpay->complemento       = $complemento;
            $enderecoClienteGalaxpay->bairro            = $bairro;
            $enderecoClienteGalaxpay->cidade            = $cidade;
            $enderecoClienteGalaxpay->estado            = $estado;

            // CRIANDO MODEL ASSOCIADO O USUARIO LOGADO
            $galaxPayClientesAssociado = $userPrimario->galaxPayClientes();

            // SALVANDO NO BANCO DE DADOS
            $galaxPayClientesAssociado->save($clienteGalaxpay);

            // ANALISANDO SE DEVE SALVAR O ENDEREÇO DO CLIENTE
            if ($cadastraEnderecoCliente) {
                $clienteGalaxpay->enderecoClienteGalaxpay()->save($enderecoClienteGalaxpay);
            };

            // VERIFICANDO CAMPOS EXTRAS
            if (!empty($campoExtras)) {

                // ANALISANDO CAMPOS PERSONALIZADOS DEPNDENTES
                $qtdeDependentesLimite = $userPrimario->parametros->quantidade_dependentes_galaxpay;

                for ($i = 0; $i < $qtdeDependentesLimite; $i++) {
                    // INCREMENTANDO VARIAVEL PARA PESQUISA
                    $nomeCampoPersonalizadoDependentes = $userPrimario->parametros->nome_campo_dependente . str_pad((1 + $i), '2', '0', STR_PAD_LEFT);
                    $cpfCampoPersonalizadoDependentes = $userPrimario->parametros->cpf_campo_dependente . str_pad((1 + $i), '2', '0', STR_PAD_LEFT);
                    $nascimentoCampoPersonalizadoDependentes = $userPrimario->parametros->nascimento_campo_dependente . str_pad((1 + $i), '2', '0', STR_PAD_LEFT);

                    // ANALISANDO SE O CAMPO DE DEPENDENTE QUE ESTA SENDO PERCORRIDO EXISTE NO ARRAY DE CAMPOS EXTRAS API
                    if (in_array($nomeCampoPersonalizadoDependentes, array_column($campoExtras, 'tagName'))) {

                        // INICIALIZANDO VARIAVEIS
                        $indexNomeDependente = array_search($nomeCampoPersonalizadoDependentes, array_column($campoExtras, 'tagName'));
                        $indexCpfDependente = array_search($cpfCampoPersonalizadoDependentes, array_column($campoExtras, 'tagName'));
                        $indexNascimentoDependente = array_search($nascimentoCampoPersonalizadoDependentes, array_column($campoExtras, 'tagName'));

                        // ANALISANDO SE OS CAMPOS NÃO ESTÃO VAZIO, SENDO ASISM RETORNAR ERRO
                        if (empty($indexNomeDependente)) return view('components.messages.returnMessages', ['ERROR' => ['Erro: Campo dependente não preenchido na GalaxPay. [' . $nomeCampoPersonalizadoDependentes . ']']]);
                        if (empty($indexCpfDependente)) return view('components.messages.returnMessages', ['ERROR' => ['Erro: Campo dependente não preenchido na GalaxPay. [' . $cpfCampoPersonalizadoDependentes . ']']]);
                        if (empty($indexNascimentoDependente)) return view('components.messages.returnMessages', ['ERROR' => ['Erro: Campo dependente não preenchido na GalaxPay. [' . $nascimentoCampoPersonalizadoDependentes . ']']]);

                        // GERANDO NUMERO DE MATRICULA (CODIGO DO CLIENTE NA GALAXPAY + ANO ATUAL + 3 ULTIMOS NUMERO DO CPF)
                        $matriculaDependente = $codigoClienteGalaxpay . date('Y') . substr($campoExtras[$indexCpfDependente]->tagValue, -3);

                        // CRIANDO MODEL
                        $clientesDependentesGalaxpay = new clientes_dependentes_galaxpay();
                        // ATRIBUINDO VALORES
                        $clientesDependentesGalaxpay->nome_cliente_dependente = $campoExtras[$indexNomeDependente]->tagValue;
                        $clientesDependentesGalaxpay->cpf_cliente_dependente = $campoExtras[$indexCpfDependente]->tagValue;
                        $clientesDependentesGalaxpay->nascimento_cliente_dependente = $campoExtras[$indexNascimentoDependente]->tagValue;
                        $clientesDependentesGalaxpay->matricula_cliente_dependente = $matriculaDependente;
                        // SALVANDO NO BANCO
                        $clienteGalaxpay->clientesDependentesGalaxpay()->save($clientesDependentesGalaxpay);
                    };
                }

                // PERCORRENDO LAÇO
                foreach ($campoExtras as $campoExtras) {

                    // CRIANDO MODEL PARA INSERT
                    $campoPersonalizadoClienteGalaxpay[]  = new campo_personalizado_cliente_galaxpay([
                        'nome_campo_personalizado' => $campoExtras->tagName,
                        'valor_campo_personalizado' => $campoExtras->tagValue
                    ]);
                }
                // INSERIDNO CAMPOS EXTRAS NO BANCO
                $clienteGalaxpay->campoPersonalizadoClienteGalaxpay()->saveMany($campoPersonalizadoClienteGalaxpay);

                // ZERANDO VARIAVEL
                unset($campoPersonalizadoClienteGalaxpay);
            }

            //RETORNANDO RESPOSTA
            $retorno['statusRetorno'] = 'SUCCESS';
            $retorno['clienteGalaxpayCadastrado'] = $clienteGalaxpay;
            return $retorno;
        }
    }

    public function pesquisaClienteGalaxPay(Request $request)
    {
        // CAPTURANDO ACCESS TOKEN
        $generateAcessToken = $this->generateAcessToken($request);
        // ANALISANDO STATUS DE RETORNO DA FUNÇÃO
        if ($generateAcessToken['statusRetorno'] != 'SUCCESS') {
            // REDIRECIONANDO COM ERRO
            return redirect()->back()->withErrors(['Erro: ' . $generateAcessToken['msgErro']]);
        }
        // INICIALIZANDO VARIAVEIS 
        $search                     = $request->search;
        $searchOption               = $request->searchOption;
        $accessToken                = $generateAcessToken['access_token'];
        $permissaoUserLogado = $request->user()->role;
        $userPrimario = User::find($request->user()->id);
        switch ($permissaoUserLogado) {
            case 'empresaParceira':
                $userLinkedId = $request->user()->userPrimario->user_linked_id;
                $userPrimario = User::find($userLinkedId);
                break;
            case 'Funcionario':
                $userLinkedId = $request->user()->userPrimarioFuncionario->user_linked_id;
                $userPrimario = User::find($userLinkedId);
                break;
        }
        // CAPTURANDO MODEL galaxPayClientes
        $galaxPayClientes = $userPrimario->galaxPayClientes();

        // ANALISANDO TIPO DE PESQUISA 
        switch ($searchOption) {
            case 'myIds':
                // VERIFICANDO SE O CLIENTE JÁ ESTA CADASTRADO
                $clienteCadastrado = $galaxPayClientes->firstWhere('meu_id', $search);
                break;
            case 'galaxPayIds':
                // VERIFICANDO SE O CLIENTE JÁ ESTA CADASTRADO
                $clienteCadastrado = $galaxPayClientes->firstWhere('codigo_cliente_galaxpay', $search);
                break;
            case 'documents':
                // VERIFICANDO SE O CLIENTE JÁ ESTA CADASTRADO
                $clienteCadastrado = $galaxPayClientes->firstWhere('cpf_cnpj_cliente', $search);
                break;
        }

        // ANALISANDOS E O CLIENTE JÁ ESTA CADASTRADO NA BASE
        if (empty($clienteCadastrado)) {
            // MONTANDO CORPO PARA ENVIO DA API
            $response = Http::withHeaders([
                'Authorization' => "Bearer $accessToken",
                'Content-Type' => 'application/json'
            ])->get("https://api.sandbox.cloud.galaxpay.com.br/v2/customers?$searchOption=$search&startAt=0&limit=1");

            // CAPTURANDO RESPOSTA DA API
            $response = json_decode($response);

            if ($response->totalQtdFoundInPage <= 0) {
                return view('components.messages.returnMessages', ['WARNING' => ['Nenhum registro encontrado.']]);
            } else {
                // VERIFICANDO ERRO
                if (!empty($response->error)) {
                    // REDIRECIONANDO COM ERRO
                    return view('components.messages.returnMessages', ['ERROR' => ['Erro: [ ' . $response->error->message . "\n" .  json_encode($response->error->details) . ' ]']]);
                } else {
                    $importaClienteGalaxPay = $this->importaClienteGalaxPay($request, $response);
                    // ANALISANDO RESPOSTA DE ERRO
                    if ($importaClienteGalaxPay['statusRetorno'] != 'SUCCESS') {
                        return view('components.messages.returnMessages', ['ERROR' => ['Erro: [ OCORREU UM ERRO INESPERADO NA IMPORTAÇÃO. ]']]);
                    } else {
                        $retorno = $importaClienteGalaxPay['clienteGalaxpayCadastrado'];
                    }
                }
            }
        } else {
            // ATUALIZANDO DADOS DO CLIENTE CADASTRADO
            $this->atualizaClienteGalaxPay($request, $clienteCadastrado);
            // RETORNANDO CLIENTE CADASTRADO
            $retorno = $clienteCadastrado;
        }

        // RETORNANDO VIEW
        if ($request->user()->role == 'empresaParceira') {
            return view('clientes.infoClienteStatus', ['clienteGalaxpay' => $retorno]);
        } else {
            return view('components.listas.listGalaxPayClientes', ['clienteGalaxpay' => $retorno]);
        }
    }

    public function criarClienteGalaxPay(Request $request)
    {
        try {
            if (empty($request->nomeCliente)) {
                throw new Exception('O atributo "nome do cliente" é obrigatório.');
            } else if (empty($request->cpfCpnjCliente)) {
                throw new Exception('O atributo "cpf ou cnpj do cliente" é obrigatório.');
            } else if (empty($request->emailCliente1) && empty($request->emailCliente2)) {
                throw new Exception('O atributo "email do cliente" é obrigatório.');
            } else if (empty($request->cepEnderecoCliente)) {
                throw new Exception('O atributo "cep do cliente" é obrigatório.');
            } else if (empty($request->logradouroEnderecoCliente)) {
                throw new Exception('O atributo "rua do cliente" é obrigatório.');
            } else if (empty($request->numeroEnderecoCliente)) {
                throw new Exception('O atributo "número do cliente" é obrigatório.');
            } else if (empty($request->bairroEnderecoCliente)) {
                throw new Exception('O atributo "bairro do cliente" é obrigatório.');
            } else if (empty($request->cidadeEnderecoCliente)) {
                throw new Exception('O atributo "cidade do cliente" é obrigatório.');
            } else if (empty($request->estadoEnderecoCliente)) {
                throw new Exception('O atributo "estado do cliente" é obrigatório.');
            }


            // ANALISANDO TIPO DE USUARIO LOGADO
            $permissaoUserLogado = $request->user()->role;
            $userPrimario = User::find($request->user()->id);
            switch ($permissaoUserLogado) {
                case 'Funcionario':
                    $userLinkedId = $request->user()->userPrimarioFuncionario->user_linked_id;
                    $userPrimario = User::find($userLinkedId);
                    break;
            }

            // INICIALIZANDO VARIÁVEIS
            $nomeCliente                = $request->nomeCliente;
            $cpfCnpjCliente             = trim($request->cpfCpnjCliente);
            $cpfCnpjCliente             = str_replace(".", "", $cpfCnpjCliente);
            $cpfCnpjCliente             = str_replace(",", "", $cpfCnpjCliente);
            $cpfCnpjCliente             = str_replace("-", "", $cpfCnpjCliente);
            $cpfCnpjCliente             = str_replace("/", "", $cpfCnpjCliente);
            $emailCliente1              = $request->emailCliente1;
            $emailCliente2              = $request->emailCliente2;
            $telefoneCliente1           = $request->telefoneCliente1;
            $telefoneCliente2           = $request->telefoneCliente2;
            // ENDEREÇOS
            $cepEnderecoCliente         = str_replace("-", "", $request->cepEnderecoCliente);
            $logradouroEnderecoCliente  = $request->logradouroEnderecoCliente;
            $numeroEnderecoCliente      = $request->numeroEnderecoCliente;
            $complementoEnderecoCliente = $request->complementoEnderecoCliente;
            $bairroEnderecoCliente      = $request->bairroEnderecoCliente;
            $cidadeEnderecoCliente      = $request->cidadeEnderecoCliente;
            $estadoEnderecoCliente      = $request->estadoEnderecoCliente;

            $enderecoClienteGalaxpay    = array();
            $data                       = array();
            $qtdeDependentesLimite      = $userPrimario->parametros->quantidade_dependentes_galaxpay;
            // GERANDO NUMERO DE DE ID (ANO ATUAL + RANDOM 4 + 3 ULTIMOS NUMERO DO CPF)
            $myId = date('Y') . rand(0, 9999) . substr($cpfCnpjCliente, -3);

            // ARRAY DE EMAILS
            if (!empty($emailCliente2)) {
                $emails = [
                    $emailCliente1,
                    $emailCliente2
                ];
            } else {
                $emails = [
                    $emailCliente1,
                ];
            };

            // ARRAY DE TELEFONES
            $telefones = [
                $telefoneCliente1,
                $telefoneCliente2
            ];

            // ARRAY DE CAMPOS EXTRAS
            for ($i = 0; $i < count($request->nomeDependente); $i++) {
                // INICIALIZANDO VARIÁVEIS
                $nomeCampoPersonalizadoDependentes          = $userPrimario->parametros->nome_campo_dependente . str_pad((1 + $i), '2', '0', STR_PAD_LEFT);
                $cpfCampoPersonalizadoDependentes           = $userPrimario->parametros->cpf_campo_dependente . str_pad((1 + $i), '2', '0', STR_PAD_LEFT);
                $nascimentoCampoPersonalizadoDependentes    = $userPrimario->parametros->nascimento_campo_dependente . str_pad((1 + $i), '2', '0', STR_PAD_LEFT);

                // DEPENDENTES
                $nomeDependente            = $request->nomeDependente[$i];
                $cpfDependente             = $request->cpfDependente[$i];
                if (empty($cpfDependente)) throw new Exception('Campo "cpf" do dependente ' . ($i + 1) . '  não identificado.');
                $nascimentoDependente      = $request->nascimentoDependente[$i];
                if (empty($nascimentoDependente)) throw new Exception('Campo "data de nascimento" do dependente ' . ($i + 1) . '  não identificado.');
                $cpfDependente             = trim($cpfDependente);
                $cpfDependente             = str_replace(".", "", $cpfDependente);
                $cpfDependente             = str_replace("-", "", $cpfDependente);

                // ATRIBUINDO VALORES PARA ENVIO A API
                $camposExtras[] = [
                    'tagName' => $nomeCampoPersonalizadoDependentes,
                    'tagValue' => $nomeDependente
                ];
                $camposExtras[] = [
                    'tagName' => $cpfCampoPersonalizadoDependentes,
                    'tagValue' => $cpfDependente
                ];
                $camposExtras[] = [
                    'tagName' => $nascimentoCampoPersonalizadoDependentes,
                    'tagValue' => $nascimentoDependente
                ];
            }


            // MONTANDO ARRAY DE ENDERECO
            $enderecoClienteGalaxpay = [
                'zipCode' => $cepEnderecoCliente,
                'street' => $logradouroEnderecoCliente,
                'number' => $numeroEnderecoCliente,
                'complement' => $complementoEnderecoCliente,
                'neighborhood' => $bairroEnderecoCliente,
                'city' => $cidadeEnderecoCliente,
                'state' => $estadoEnderecoCliente
            ];

            // MONTANDO ARRAY DE DATA
            $data = [
                'myId' => $myId,
                'name' => $nomeCliente,
                'document' => $cpfCnpjCliente,
                'emails' => $emails,
                'phones' => $telefones,
                'Address' => $enderecoClienteGalaxpay,
                'ExtraFields' => $camposExtras
            ];

            // CAPTURANDO ACCESS TOKEN
            $generateAcessToken = galaxPayControllerAPI::generateAcessToken($request);
            // ANALISANDO STATUS DE RETORNO DA FUNÇÃO
            if ($generateAcessToken['statusRetorno'] != 'SUCCESS') {
                // RETORNANDO ERRO
                throw new Exception($generateAcessToken['msgErro']);
            }
            // INICIALIZANDO VARIAVEIS 
            $accessToken                = $generateAcessToken['access_token'];

            // MONTANDO CORPO PARA ENVIO DA API
            $response = Http::withHeaders([
                'Authorization' => "Bearer $accessToken",
                'Content-Type' => 'application/json'
            ])->post("https://api.sandbox.cloud.galaxpay.com.br/v2/customers/", $data);

            // CAPTURANDO RESPOSTA DA API
            $response = json_decode($response);
            if (!empty($response->error)) {
                // REDIRECIONANDO COM ERRO
                throw new Exception($response->error->message . " [ " .  json_encode($response) . " ]");
            }

            $response->Customer->status = 'active';
            $resposta = (object) [
                'totalQtdFoundInPage' => 1,
                'Customers' => [$response->Customer]
            ];

            // IMPORTANDO CLIENTE CADASTRADO
            $importaClienteGalaxPay = $this->importaClienteGalaxPay($request, $resposta);

            // ANALISANDO RESPOSTA DE ERRO
            if ($importaClienteGalaxPay['statusRetorno'] != 'SUCCESS') {
                throw new Exception('Erro na importação, cliente cadastrado na Galaxpay ' . $response->Customers[0]->galaxPayId);
            }

            return redirect()->route('clientes')->with(['SUCCESS' => ['Cliente criado com sucesso. Nº Matricula: ' . $importaClienteGalaxPay['clienteGalaxpayCadastrado']->matricula]]);
        } catch (Exception $e) {
            $retorno['statusRetorno'] = 'ERROR';
            return redirect()->back()->withInput()->withErrors(['Ocorreu um erro inesperado. Mensagem: ' . $e->getMessage()]);
        }
    }

    public function editarClienteGalaxPay(Request $request, clientes_galaxpay $clienteGalaxPay)
    {
        try {
            // INICIALIZANDO VARIÁVEIS
            $enderecoClienteGalaxpay = array();
            $data = array();
            if (!empty($clienteGalaxPay->email_cliente_2)) {
                $emails = [
                    $clienteGalaxPay->email_cliente_1,
                    $clienteGalaxPay->email_cliente_2
                ];
            } else {
                $emails = [
                    $clienteGalaxPay->email_cliente_1,
                ];
            };

            if (!empty($clienteGalaxPay->enderecoClienteGalaxpay)) {
                // MONTANDO ARRAY DE ENDERECO
                $enderecoClienteGalaxpay = [
                    'zipCode' => $clienteGalaxPay->enderecoClienteGalaxpay->cep,
                    'street' => $clienteGalaxPay->enderecoClienteGalaxpay->logradouro,
                    'number' => $clienteGalaxPay->enderecoClienteGalaxpay->numero,
                    'complement' => $clienteGalaxPay->enderecoClienteGalaxpay->complemento,
                    'neighborhood' => $clienteGalaxPay->enderecoClienteGalaxpay->bairro,
                    'city' => $clienteGalaxPay->enderecoClienteGalaxpay->cidade,
                    'state' => $clienteGalaxPay->enderecoClienteGalaxpay->estado
                ];
            }

            // MONTANDO ARRAY DE DATA
            $data = [
                'myId' => $clienteGalaxPay->meu_id,
                'name' => $clienteGalaxPay->nome_cliente,
                'document' => $clienteGalaxPay->cpf_cnpj_cliente,
                'emails' => $emails,
                'phones' => [
                    $clienteGalaxPay->telefone_cliente_1,
                    $clienteGalaxPay->telefone_cliente_2
                ],
                'Address' => $enderecoClienteGalaxpay,
                // 'ExtraFields' => []
            ];

            // CAPTURANDO ACCESS TOKEN
            $generateAcessToken = galaxPayControllerAPI::generateAcessToken($request);
            // ANALISANDO STATUS DE RETORNO DA FUNÇÃO
            if ($generateAcessToken['statusRetorno'] != 'SUCCESS') {
                // REDIRECIONANDO COM ERRO
                return redirect()->back()->withErrors(['Erro: ' . $generateAcessToken['msgErro']]);
            }
            // INICIALIZANDO VARIAVEIS 
            $accessToken                = $generateAcessToken['access_token'];

            // MONTANDO CORPO PARA ENVIO DA API
            $response = Http::withHeaders([
                'Authorization' => "Bearer $accessToken",
                'Content-Type' => 'application/json'
            ])->put("https://api.sandbox.cloud.galaxpay.com.br/v2/customers/" . $clienteGalaxPay->codigo_cliente_galaxpay . "/galaxPayId", $data);

            // CAPTURANDO RESPOSTA DA API
            $response = json_decode($response);
            if (!empty($response->error)) {
                // REDIRECIONANDO COM ERRO
                throw new Exception($response->error->message . " [ " .  json_encode($response) . " ]");
            } else {
                $retorno['statusRetorno'] = 'SUCCESS';
                $retorno['msgRetorno'] = 'Alterações realizadas com sucesso.';
            }
            return $retorno;
        } catch (Exception $e) {
            $retorno['statusRetorno'] = 'ERROR';
            $retorno['msgRetorno'] = $e->getMessage();
            return $retorno;
        }
    }

    public function atualizaClienteGalaxPay(Request $request, clientes_galaxpay $clienteGalaxpay)
    {
        // ANALISANDO VARIAVEL
        if (!isset($clienteGalaxpay) || empty($clienteGalaxpay)) {
            return view('components.messages.returnMessages', ['WARNING' => ['Nenhum registro encontrado. [Atualiza cliente galaxPay]']]);
        };

        // CAPTURANDO ACCESS TOKEN
        $generateAcessToken = galaxPayControllerAPI::generateAcessToken($request);
        // ANALISANDO STATUS DE RETORNO DA FUNÇÃO
        if ($generateAcessToken['statusRetorno'] != 'SUCCESS') {
            // REDIRECIONANDO COM ERRO
            return redirect()->back()->withErrors(['Erro: ' . $generateAcessToken['msgErro']]);
        }
        // INICIALIZANDO VARIAVEL
        $accessToken                = $generateAcessToken['access_token'];
        $galaxPayId                 = $clienteGalaxpay->codigo_cliente_galaxpay;
        $cadastraEnderecoCliente    = true;
        // ANALISANDO TIPO DE USUARIO LOGADO
        $permissaoUserLogado = $request->user()->role;
        $userPrimario = User::find($request->user()->id);
        switch ($permissaoUserLogado) {
            case 'empresaParceira':
                $userLinkedId = $request->user()->userPrimario->user_linked_id;
                $userPrimario = User::find($userLinkedId);
                break;
            case 'Funcionario':
                $userLinkedId = $request->user()->userPrimarioFuncionario->user_linked_id;
                $userPrimario = User::find($userLinkedId);
                break;
        }

        // DELETANDO CAMPOS PERSONALIZADOS
        campo_personalizado_cliente_galaxpay::where('cliente_galaxpay_id', $clienteGalaxpay->id)->delete();

        // CONSULTANDO API
        $response = Http::withHeaders([
            'Authorization' => "Bearer $accessToken",
            'Content-Type' => 'application/json'
        ])->get("https://api.sandbox.cloud.galaxpay.com.br/v2/customers?galaxPayIds=$galaxPayId&startAt=0&limit=1");

        // CAPTURANDO RESPOSTA DA API
        $response = json_decode($response);

        // VERIFICANDO ERRO
        if (!empty($response->error)) {
            // REDIRECIONANDO COM ERRO
            return view('components.messages.returnMessages', ['ERROR' => ['Erro: [ ' . $response->error->message . ' ]']]);
        }

        // PERCORRENDO LAÇO DE ENDEREÇOS
        foreach ($response->Customers[0]->Address as $keyAddress => $valueAddress) {
            // ANALISANDO SE O CAMPO COMPLEMENT É VAZIO
            if ($keyAddress == 'complement' && empty($valueAddress)) continue;

            // ANALISANDO SE EXISTE ALGUM CAMPO VAZIO NO ENDEREÇO
            if (empty($valueAddress)) {
                $cadastraEnderecoCliente = false;
            };
        }

        // ATUALIZANDO VALORES AO MODEL
        $clienteGalaxpay->codigo_cliente_galaxpay          = $response->Customers[0]->galaxPayId;
        $clienteGalaxpay->meu_id                           = $response->Customers[0]->myId;
        $clienteGalaxpay->nome_cliente                     = $response->Customers[0]->name;
        $clienteGalaxpay->cpf_cnpj_cliente                 = $response->Customers[0]->document;
        $clienteGalaxpay->email_cliente_1                  = empty($response->Customers[0]->emails[0]) ? '' : $response->Customers[0]->emails[0];
        $clienteGalaxpay->email_cliente_2                  = empty($response->Customers[0]->emails[1]) ? '' : $response->Customers[0]->emails[1];
        $clienteGalaxpay->telefone_cliente_1               = empty($response->Customers[0]->phones[0]) ? '' : $response->Customers[0]->phones[0];
        $clienteGalaxpay->telefone_cliente_2               = empty($response->Customers[0]->phones[1]) ? '' : $response->Customers[0]->phones[1];
        $clienteGalaxpay->iss_nf_cliente                   = ''; //CAMPO NÃO UTILIZADO ATÉ O MOMENTO
        $clienteGalaxpay->inscricao_municipal_cliente      = ''; //CAMPO NÃO UTILIZADO ATÉ O MOMENTO
        $clienteGalaxpay->status_cliente                   = $response->Customers[0]->status;
        $clienteGalaxpay->createdAt                        = $response->Customers[0]->createdAt;
        $clienteGalaxpay->updatedAt                        = $response->Customers[0]->updatedAt;
        // SALVANDO NO BANCO DE DADOS
        $clienteGalaxpay->save();

        // ANALISANDO ENDEREÇÕ DO CLIENTE
        if ($cadastraEnderecoCliente) {
            // ATUALIZANDO ENDEREÇO DO CLIENTE
            $clienteGalaxpay->enderecoClienteGalaxpay->cep               = $response->Customers[0]->Address->zipCode;
            $clienteGalaxpay->enderecoClienteGalaxpay->logradouro        = $response->Customers[0]->Address->street;
            $clienteGalaxpay->enderecoClienteGalaxpay->numero            = $response->Customers[0]->Address->number;
            $clienteGalaxpay->enderecoClienteGalaxpay->complemento       = $response->Customers[0]->Address->complement;
            $clienteGalaxpay->enderecoClienteGalaxpay->bairro            = $response->Customers[0]->Address->neighborhood;
            $clienteGalaxpay->enderecoClienteGalaxpay->cidade            = $response->Customers[0]->Address->city;
            $clienteGalaxpay->enderecoClienteGalaxpay->estado            = $response->Customers[0]->Address->state;
            // SALVANDO NO BANCO DE DADOS
            $clienteGalaxpay->enderecoClienteGalaxpay->save();
        }

        // CAPTURANDO CAMPOS EXTRAS
        $campoExtras = $response->Customers[0]->ExtraFields;

        // VERIFICANDO CAMPOS EXTRAS
        if (!empty($campoExtras)) {

            // ANALISANDO CAMPOS PERSONALIZADOS DEPENDENTES
            $qtdeDependentesLimite = $userPrimario->parametros->quantidade_dependentes_galaxpay;

            for ($i = 0; $i < $qtdeDependentesLimite; $i++) {
                // INCREMENTANDO VARIAVEL PARA PESQUISA
                $nomeCampoPersonalizadoDependentes = $userPrimario->parametros->nome_campo_dependente . str_pad((1 + $i), '2', '0', STR_PAD_LEFT);
                $cpfCampoPersonalizadoDependentes = $userPrimario->parametros->cpf_campo_dependente . str_pad((1 + $i), '2', '0', STR_PAD_LEFT);
                $nascimentoCampoPersonalizadoDependentes = $userPrimario->parametros->nascimento_campo_dependente . str_pad((1 + $i), '2', '0', STR_PAD_LEFT);

                // ANALISANDO SE O CAMPO DE DEPENDENTE QUE ESTA SENDO PERCORRIDO EXISTE NO ARRAY DE CAMPOS EXTRAS API
                if (in_array($nomeCampoPersonalizadoDependentes, array_column($campoExtras, 'tagName'))) {
                    // INICIALIZANDO VARIAVEIS
                    $indexNomeDependente = array_search($nomeCampoPersonalizadoDependentes, array_column($campoExtras, 'tagName'));
                    $indexCpfDependente = array_search($cpfCampoPersonalizadoDependentes, array_column($campoExtras, 'tagName'));
                    $indexNascimentoDependente = array_search($nascimentoCampoPersonalizadoDependentes, array_column($campoExtras, 'tagName'));

                    // ANALISANDO SE OS CAMPOS NÃO ESTÃO VAZIO, SENDO ASISM RETORNAR ERRO
                    if (empty($indexNomeDependente)) return view('components.messages.returnMessages', ['ERROR' => ['Erro: Campo dependente não preenchido na GalaxPay. [' . $nomeCampoPersonalizadoDependentes . ']']]);
                    if (empty($indexCpfDependente)) return view('components.messages.returnMessages', ['ERROR' => ['Erro: Campo dependente não preenchido na GalaxPay. [' . $cpfCampoPersonalizadoDependentes . ']']]);
                    if (empty($indexNascimentoDependente)) return view('components.messages.returnMessages', ['ERROR' => ['Erro: Campo dependente não preenchido na GalaxPay. [' . $nascimentoCampoPersonalizadoDependentes . ']']]);

                    // CAPTURANDO DEPENDENTE PELO CPF
                    $clientesDependentesGalaxpayCadastrado = clientes_dependentes_galaxpay::where('cpf_cliente_dependente', $campoExtras[$indexCpfDependente]->tagValue)->first();

                    // VERIFICANDO SE FOI ENCONTRADO DEPENDENTE PELO CPF
                    if ($clientesDependentesGalaxpayCadastrado) {
                        // ATRIBUINDO VALORES
                        $clientesDependentesGalaxpayCadastrado->nome_cliente_dependente = $campoExtras[$indexNomeDependente]->tagValue;
                        $clientesDependentesGalaxpayCadastrado->cpf_cliente_dependente = $campoExtras[$indexCpfDependente]->tagValue;
                        $clientesDependentesGalaxpayCadastrado->nascimento_cliente_dependente = $campoExtras[$indexNascimentoDependente]->tagValue;
                        // SALVANDO NO BANCO
                        $clientesDependentesGalaxpayCadastrado->save();
                    } else {
                        // CRIANDO MODEL
                        $clientesDependentesGalaxpay = new clientes_dependentes_galaxpay();

                        // GERANDO NUMERO DE MATRICULA (ANO ATUAL + CODIGO DO CLIENTE NA GALAXPAY + 3 ULTIMOS NUMERO DO CPF)
                        $matriculaDependente = date('Y') . $clienteGalaxpay->codigo_cliente_galaxpay . substr($campoExtras[$indexCpfDependente]->tagValue, -3);
                        // ATRIBUINDO VALORES
                        $clientesDependentesGalaxpay->nome_cliente_dependente = $campoExtras[$indexNomeDependente]->tagValue;
                        $clientesDependentesGalaxpay->cpf_cliente_dependente = $campoExtras[$indexCpfDependente]->tagValue;
                        $clientesDependentesGalaxpay->nascimento_cliente_dependente = $campoExtras[$indexNascimentoDependente]->tagValue;
                        $clientesDependentesGalaxpay->matricula_cliente_dependente = $matriculaDependente;
                        // SALVANDO NO BANCO
                        $clienteGalaxpay->clientesDependentesGalaxpay()->save($clientesDependentesGalaxpay);
                    }
                };
            }

            // PERCORRENDO LAÇO
            foreach ($campoExtras as $campoExtras) {
                // CRIANDO MODEL PARA INSERT
                $campoPersonalizadoClienteGalaxpay[]  = new campo_personalizado_cliente_galaxpay([
                    'nome_campo_personalizado' => $campoExtras->tagName,
                    'valor_campo_personalizado' => $campoExtras->tagValue
                ]);
            }
            // INSERIDNO CAMPOS EXTRAS NO BANCO
            $clienteGalaxpay->campoPersonalizadoClienteGalaxpay()->saveMany($campoPersonalizadoClienteGalaxpay);

            // ZERANDO VARIAVEL
            unset($campoPersonalizadoClienteGalaxpay);
        }

        return "SUCCESS";
    }

    /******************/
    /**** CONTRATO ****/
    /******************/
    public function importaContratoPorCliente(Request $request, clientes_galaxpay $clienteGalaxpay)
    {
        try {
            $generateAcessToken = $this->generateAcessToken($request);
            // ANALISANDO STATUS DE RETORNO DA FUNÇÃO
            if ($generateAcessToken['statusRetorno'] != 'SUCCESS') {
                throw new Exception($generateAcessToken['msgErro']);
            }
            $accessToken = $generateAcessToken['access_token'];

            // MONTANDO CORPO PARA ENVIO DA API
            $response = Http::withHeaders([
                'Authorization' => "Bearer $accessToken",
                'Content-Type' => 'application/json'
            ])->get("https://api.sandbox.cloud.galaxpay.com.br/v2/subscriptions?customerGalaxPayIds=" . $clienteGalaxpay->codigo_cliente_galaxpay . "&status=active&startAt=0&limit=100");

            // CAPTURANDO RESPOSTA DA API
            $response = json_decode($response);

            // VERIFICANDO ERRO
            if (!empty($response->error)) {
                throw new Exception($response->error->message . "\n" .  json_encode($response->error->details));
            }

            // ANALISANDO QUANTIDADE DE CONTRATOS
            if ($response->totalQtdFoundInPage <= 0) {
                return redirect()->back()->with(['WARNING' => ['Nenhum contrato encontrado para importação.']]);
            } else {
                // PERCORRENDO LAÇO DE CONTRATOS API
                for ($i = 0; $i < $response->totalQtdFoundInPage; $i++) {

                    // PESQUISANDO CONTRATO
                    $contratoCliente = $clienteGalaxpay->contratos()->where('codigo_contrato_galaxpay', $response->Subscriptions[$i]->galaxPayId)->first();

                    if (isset($contratoCliente)) {
                        $atualizaContratoCliente = $this->atualizaContratoPorContrato($request, $contratoCliente);
                        if ($atualizaContratoCliente['statusRetorno'] != 'SUCCESS') {
                            throw new Exception($atualizaContratoCliente['msgRetorno'] . "\n " . $atualizaContratoCliente['msgErro']);
                        }
                    } else {
                        // INICIALIZANDO VARIÁVEIS
                        $valorContrato = str_replace(',', '.', str_replace('.', '', $response->Subscriptions[$i]->value));
                        $decimal = substr($valorContrato, -2);
                        $inteiro = substr($valorContrato, 0, -2);
                        $valorContrato = $inteiro . '.' . $decimal;
                        if (empty($valorContrato)) $valorContrato = '0.00';
                        $valorContrato = number_format($valorContrato, 2, '.', ',');

                        // INICIALIZANDO MODEL
                        $contratos = new contratos;

                        // DEFININDO VARIÁVEIS
                        $contratos->cliente_galaxpay = $clienteGalaxpay->id;
                        $contratos->codigo_contrato_galaxpay = $response->Subscriptions[$i]->galaxPayId;
                        $contratos->plano_codigo_contrato = $response->Subscriptions[$i]->galaxPayId;
                        $contratos->valor_contrato = $valorContrato;
                        $contratos->duracao_contrato = empty($response->Subscriptions[$i]->quantity) ? NULL : $response->Subscriptions[$i]->quantity;
                        $contratos->periodicidade_pagamento = $response->Subscriptions[$i]->periodicity;
                        $contratos->primeira_data_pagamento = $response->Subscriptions[$i]->firstPayDayDate;
                        $contratos->forma_pagamento = $response->Subscriptions[$i]->mainPaymentMethodId;
                        $contratos->link_pagamento = $response->Subscriptions[$i]->paymentLink;
                        $contratos->informacao_adicional = $response->Subscriptions[$i]->additionalInfo;
                        $contratos->status = $response->Subscriptions[$i]->status;

                        // SALVANDO DADOS
                        $contratos->save();
                    }
                }
            }

            return redirect()->back()->with(['SUCCESS' => ['Sucesso ao importar contratos.']]);
        } catch (Exception $e) {
            $retorno['statusRetorno'] = 'ERROR';
            return redirect()->back()->withInput()->withErrors(['Ocorreu um erro inesperado. Mensagem: ' . $e->getMessage()]);
        }
    }

    public function atualizaContratoPorContrato(Request $request, contratos $contrato)
    {
        try {
            $generateAcessToken = $this->generateAcessToken($request);
            // ANALISANDO STATUS DE RETORNO DA FUNÇÃO
            if ($generateAcessToken['statusRetorno'] != 'SUCCESS') {
                throw new Exception($generateAcessToken['msgErro']);
            }
            $accessToken = $generateAcessToken['access_token'];

            // MONTANDO CORPO PARA ENVIO DA API
            $response = Http::withHeaders([
                'Authorization' => "Bearer $accessToken",
                'Content-Type' => 'application/json'
            ])->get("https://api.sandbox.cloud.galaxpay.com.br/v2/subscriptions?galaxPayIds=$contrato->codigo_contrato_galaxpay&startAt=0&limit=1");

            // CAPTURANDO RESPOSTA DA API
            $response = json_decode($response);

            // VERIFICANDO ERRO
            if (!empty($response->error)) {
                throw new Exception($response->error->message . "\n" .  json_encode($response->error->details));
            }

            // ANALISANDO QUANTIDADE DE CONTRATOS
            if ($response->totalQtdFoundInPage > 0) {
                // PERCORRENDO LAÇO DE CONTRATOS API
                for ($i = 0; $i < $response->totalQtdFoundInPage; $i++) {
                    // INICIALIZANDO VARIÁVEIS
                    $valorContrato = str_replace(',', '.', str_replace('.', '', $response->Subscriptions[$i]->value));
                    $decimal = substr($valorContrato, -2);
                    $inteiro = substr($valorContrato, 0, -2);
                    $valorContrato = $inteiro . '.' . $decimal;
                    if (empty($valorContrato)) $valorContrato = '0.00';
                    $valorContrato = number_format($valorContrato, 2, '.', ',');

                    // DEFININDO VARIÁVEIS
                    $contrato->plano_codigo_contrato = $response->Subscriptions[$i]->galaxPayId;
                    $contrato->valor_contrato = $valorContrato;
                    $contrato->duracao_contrato = empty($response->Subscriptions[$i]->quantity) ? NULL : $response->Subscriptions[$i]->quantity;
                    $contrato->periodicidade_pagamento = $response->Subscriptions[$i]->periodicity;
                    $contrato->primeira_data_pagamento = $response->Subscriptions[$i]->firstPayDayDate;
                    $contrato->forma_pagamento = $response->Subscriptions[$i]->mainPaymentMethodId;
                    $contrato->link_pagamento = $response->Subscriptions[$i]->paymentLink;
                    $contrato->informacao_adicional = $response->Subscriptions[$i]->additionalInfo;
                    $contrato->status = $response->Subscriptions[$i]->status;

                    // SALVANDO DADOS
                    $contrato->save();
                }
            }

            $retorno['statusRetorno'] = 'SUCCESS';
            $retorno['msgRetorno'] = 'Contrato atualizado com sucesso.';
            return $retorno;
        } catch (Exception $e) {
            $retorno['statusRetorno'] = 'ERROR';
            $retorno['msgRetorno'] = 'Erro ao atualizar contrato';
            $retorno['msgErro'] = $e->getMessage();
            return $retorno;
        }
    }

    /********************/
    /**** TRANSAÇÕES ****/
    /********************/

    public function importaTransacoesPorContrato(Request $request, contratos $contrato)
    {
        try {
            $generateAcessToken = $this->generateAcessToken($request);
            // ANALISANDO STATUS DE RETORNO DA FUNÇÃO
            if ($generateAcessToken['statusRetorno'] != 'SUCCESS') {
                throw new Exception($generateAcessToken['msgErro']);
            }
            $accessToken = $generateAcessToken['access_token'];

            // MONTANDO CORPO PARA ENVIO DA API
            $response = Http::withHeaders([
                'Authorization' => "Bearer $accessToken",
                'Content-Type' => 'application/json'
            ])->get("https://api.sandbox.cloud.galaxpay.com.br/v2/transactions?subscriptionGalaxPayIds=" . $contrato->codigo_contrato_galaxpay . "&status=pendingBoleto,pendingPix,notSend&startAt=0&limit=100&order=payday.asc");

            // CAPTURANDO RESPOSTA DA API
            $response = json_decode($response);

            // VERIFICANDO ERRO
            if (!empty($response->error)) {
                throw new Exception($response->error->message . "\n" .  json_encode($response->error->details));
            }

            // ANALISANDO QUANTIDADE DE CONTRATOS
            if ($response->totalQtdFoundInPage <= 0) {
                return redirect()->back()->with(['WARNING' => ['Nenhuma transação encontrada para importação.']]);
            } else {
                // PERCORRENDO LAÇO DE CONTRATOS API
                for ($i = 0; $i < $response->totalQtdFoundInPage; $i++) {

                    // PESQUISANDO CONTRATO
                    $transacaoContrato = $contrato->transacoes()->where('codigo_transacao_galaxpay', $response->Transactions[$i]->galaxPayId)->first();

                    // ANALISANDO SE JÁ EXISTE CADASTRO
                    if (isset($transacaoContrato)) {
                        $atualizaContratoCliente = $this->atualizaTransacoesPorTransacao($accessToken, $transacaoContrato);
                        if ($atualizaContratoCliente['statusRetorno'] != 'SUCCESS') {
                            throw new Exception($atualizaContratoCliente['msgRetorno'] . "\n " . $atualizaContratoCliente['msgErro']);
                        }
                    } else {
                        // INICIALIZANDO VARIÁVEIS
                        $valorTransacao = str_replace(',', '.', str_replace('.', '', $response->Transactions[$i]->value));
                        $decimal = substr($valorTransacao, -2);
                        $inteiro = substr($valorTransacao, 0, -2);
                        $valorTransacao = $inteiro . '.' . $decimal;
                        if (empty($valorTransacao)) $valorTransacao = '0.00';
                        $valorTransacao = number_format($valorTransacao, 2, '.', ',');

                        // INICIALIZANDO MODEL
                        $transacoesGalaxpay = new transacoes_galaxpay();

                        // DEFININDO VARIÁVEIS
                        $transacoesGalaxpay->id_contrato = $contrato->id;
                        $transacoesGalaxpay->cliente_galaxpay = $contrato->cliente_galaxpay;
                        $transacoesGalaxpay->codigo_transacao_galaxpay = $response->Transactions[$i]->galaxPayId;
                        $transacoesGalaxpay->valor_transacao = $valorTransacao;
                        $transacoesGalaxpay->data_pagamento_transacao = $response->Transactions[$i]->payday;
                        $transacoesGalaxpay->data_recebimento_transacao = $response->Transactions[$i]->paydayDate;
                        $transacoesGalaxpay->status_transacao = $response->Transactions[$i]->status;
                        $transacoesGalaxpay->descricao_status_transacao = $response->Transactions[$i]->statusDescription;
                        $transacoesGalaxpay->codigo_contrato_galaxpay = $response->Transactions[$i]->subscriptionGalaxPayId;
                        $transacoesGalaxpay->link_boleto_pagamento = $response->Transactions[$i]->Boleto->pdf;
                        $transacoesGalaxpay->link_pagamento = $response->Transactions[$i]->Subscription->paymentLink;

                        // SALVANDO DADOS
                        $transacoesGalaxpay->save();
                    }
                }
            }

            return redirect()->back()->with(['SUCCESS' => ['Sucesso ao importar transações Galax Pay.']]);
        } catch (Exception $e) {
            $retorno['statusRetorno'] = 'ERROR';
            return redirect()->back()->withInput()->withErrors(['Ocorreu um erro inesperado. Mensagem: ' . $e->getMessage()]);
        }
    }

    public function importaTransacoesPorCliente(Request $request, clientes_galaxpay $clienteGalaxpay)
    {
        try {

            // ANALISANDO CONTRATOS DO CLIENTE
            if (count($clienteGalaxpay->contratos) > 0) {
                // INICIALIZANDO VARIÁVEIS
                $codigoContratosGalaxpay = '';

                // PERCORRENDO LAÇO
                foreach ($clienteGalaxpay->contratos as $contratoCliente) {
                    // INICIALIZANDO VARIÁVEIS
                    $codigoContratosGalaxpay .= $contratoCliente->codigo_contrato_galaxpay . ',';
                }

                // CHAMANDO FUNÇÃO DE TOKEN
                $generateAcessToken = $this->generateAcessToken($request);
                // ANALISANDO STATUS DE RETORNO DA FUNÇÃO
                if ($generateAcessToken['statusRetorno'] != 'SUCCESS') {
                    throw new Exception($generateAcessToken['msgErro']);
                }
                $accessToken = $generateAcessToken['access_token'];

                // RETIRANDO A ULTIMA VIRGULA DA STRING
                $codigoContratosGalaxpay = substr($codigoContratosGalaxpay, 0, -1);

                // MONTANDO CORPO PARA ENVIO DA API
                $response = Http::withHeaders([
                    'Authorization' => "Bearer $accessToken",
                    'Content-Type' => 'application/json'
                ])->get("https://api.sandbox.cloud.galaxpay.com.br/v2/transactions?subscriptionGalaxPayIds=" . $codigoContratosGalaxpay . "&status=pendingBoleto,pendingPix,notSend&startAt=0&limit=100&order=payday.asc");

                // CAPTURANDO RESPOSTA DA API
                $response = json_decode($response);

                // VERIFICANDO ERRO
                if (!empty($response->error)) {
                    throw new Exception($response->error->message . "\n" .  json_encode($response->error->details));
                }
                // ANALISANDO QUANTIDADE DE CONTRATOS
                if ($response->totalQtdFoundInPage <= 0) {
                    return redirect()->back()->with(['WARNING' => ['Nenhuma transação encontrada para importação.']]);
                } else {
                    // PERCORRENDO LAÇO DE CONTRATOS API
                    for ($i = 0; $i < $response->totalQtdFoundInPage; $i++) {

                        // PESQUISANDO CONTRATO
                        $transacaoContrato = $clienteGalaxpay->transacoesAtivas()->where('codigo_transacao_galaxpay', $response->Transactions[$i]->galaxPayId)->first();

                        // ANALISANDO SE JÁ EXISTE CADASTRO
                        if (isset($transacaoContrato)) {
                            $atualizaContratoCliente = $this->atualizaTransacoesPorTransacao($accessToken, $transacaoContrato);
                            if ($atualizaContratoCliente['statusRetorno'] != 'SUCCESS') {
                                throw new Exception($atualizaContratoCliente['msgRetorno'] . "\n " . $atualizaContratoCliente['msgErro']);
                            }
                        } else {
                            // INICIALIZANDO VARIÁVEIS
                            $valorTransacao = str_replace(',', '.', str_replace('.', '', $response->Transactions[$i]->value));
                            $decimal = substr($valorTransacao, -2);
                            $inteiro = substr($valorTransacao, 0, -2);
                            $valorTransacao = $inteiro . '.' . $decimal;
                            if (empty($valorTransacao)) $valorTransacao = '0.00';
                            $valorTransacao = number_format($valorTransacao, 2, '.', ',');

                            // INICIALIZANDO MODEL
                            $transacoesGalaxpay = new transacoes_galaxpay();
                            $contrato = contratos::where('codigo_contrato_galaxpay', $response->Transactions[$i]->subscriptionGalaxPayId)->first();

                            // DEFININDO VARIÁVEIS
                            $transacoesGalaxpay->id_contrato = $contrato->id;
                            $transacoesGalaxpay->cliente_galaxpay = $clienteGalaxpay->id;
                            $transacoesGalaxpay->codigo_transacao_galaxpay = $response->Transactions[$i]->galaxPayId;
                            $transacoesGalaxpay->valor_transacao = $valorTransacao;
                            $transacoesGalaxpay->data_pagamento_transacao = $response->Transactions[$i]->payday;
                            $transacoesGalaxpay->status_transacao = $response->Transactions[$i]->status;
                            $transacoesGalaxpay->descricao_status_transacao = $response->Transactions[$i]->statusDescription;
                            $transacoesGalaxpay->codigo_contrato_galaxpay = $response->Transactions[$i]->subscriptionGalaxPayId;
                            $transacoesGalaxpay->link_boleto_pagamento = $response->Transactions[$i]->Boleto->pdf;
                            $transacoesGalaxpay->link_pagamento = $response->Transactions[$i]->Subscription->paymentLink;

                            // SALVANDO DADOS
                            $transacoesGalaxpay->save();
                        }
                    }
                }
                return redirect()->back()->with(['SUCCESS' => ['Sucesso ao importar transações GalaxPay.']]);
            } else {
                throw new Exception('Nenhum contrato encontrado.');
            };
        } catch (Exception $e) {
            $retorno['statusRetorno'] = 'ERROR';
            return redirect()->back()->withInput()->withErrors(['Ocorreu um erro inesperado. Mensagem: ' . $e->getMessage()]);
        }
    }

    public function atualizaTransacoesPorTransacao($accessToken, transacoes_galaxpay $transacao)
    {
        try {
            // MONTANDO CORPO PARA ENVIO DA API
            $response = Http::withHeaders([
                'Authorization' => "Bearer $accessToken",
                'Content-Type' => 'application/json'
            ])->get("https://api.sandbox.cloud.galaxpay.com.br/v2/subscriptions?galaxPayIds=$transacao->codigo_transacao_galaxpay&startAt=0&limit=1");

            // CAPTURANDO RESPOSTA DA API
            $response = json_decode($response);

            // VERIFICANDO ERRO
            if (!empty($response->error)) {
                throw new Exception($response->error->message . "\n" .  json_encode($response->error->details));
            }

            // ANALISANDO QUANTIDADE DE CONTRATOS
            if ($response->totalQtdFoundInPage > 0) {
                // PERCORRENDO LAÇO DE CONTRATOS API
                for ($i = 0; $i < $response->totalQtdFoundInPage; $i++) {
                    // INICIALIZANDO VARIÁVEIS
                    $valorTransacao = str_replace(',', '.', str_replace('.', '', $response->Transactions[$i]->value));
                    $decimal = substr($valorTransacao, -2);
                    $inteiro = substr($valorTransacao, 0, -2);
                    $valorTransacao = $inteiro . '.' . $decimal;
                    if (empty($valorTransacao)) $valorTransacao = '0.00';
                    $valorTransacao = number_format($valorTransacao, 2, '.', ',');

                    // DEFININDO VARIÁVEIS
                    $transacao->valor_transacao = $valorTransacao;
                    $transacao->data_pagamento_transacao = $response->Transactions[$i]->payday;
                    $transacao->status_transacao = $response->Transactions[$i]->status;
                    $transacao->descricao_status_transacao = $response->Transactions[$i]->statusDescription;
                    $transacao->link_boleto_pagamento = $response->Transactions[$i]->Boleto->pdf;
                    $transacao->link_pagamento = $response->Transactions[$i]->Subscription->paymentLink;

                    // SALVANDO DADOS
                    $transacao->save();
                }
            }

            $retorno['statusRetorno'] = 'SUCCESS';
            $retorno['msgRetorno'] = 'Contrato atualizado com sucesso.';
            return $retorno;
        } catch (Exception $e) {
            $retorno['statusRetorno'] = 'ERROR';
            $retorno['msgRetorno'] = 'Erro ao atualizar contrato';
            $retorno['msgErro'] = $e->getMessage();
            return $retorno;
        }
    }

    public function receberTransacoesPorTransacao(Request $request, transacoes_galaxpay $transacao)
    {
        try {
            $generateAcessToken = $this->generateAcessToken($request);
            // ANALISANDO STATUS DE RETORNO DA FUNÇÃO
            if ($generateAcessToken['statusRetorno'] != 'SUCCESS') {
                throw new Exception($generateAcessToken['msgErro']);
            }
            $accessToken = $generateAcessToken['access_token'];
            $data = array();

            // INICILIZANDO MODEL
            $caixaAberto = caixa_financeiro::where('status_caixa', 'A')->first();

            // ANALISANDO SE EXISTE CAIXA ABERTO
            if (empty($caixaAberto)) {
                throw new Exception('Não há caixa aberto para geração de financeiro.');
            }

            // MONTANDO DADOS PARA ENVIO
            $data = [
                // 'myId' => $transacao->id,
                'payedOutsideGalaxPay' => true,
                'additionalInfo' => 'Pago através do sistema SolicardSys.'
            ];

            // MONTANDO CORPO PARA ENVIO DA API
            $response = Http::withHeaders([
                'Authorization' => "Bearer $accessToken",
                'Content-Type' => 'application/json'
            ])->put("https://api.sandbox.cloud.galaxpay.com.br/v2/transactions/" . $transacao->codigo_transacao_galaxpay . "/galaxPayId", $data);

            // CAPTURANDO RESPOSTA DA API
            $response = json_decode($response);

            // VERIFICANDO ERRO
            if (!empty($response->error)) {
                $erroDetails = '';
                if (isset($response->error->details)) $erroDetails = json_encode($response->error->details);
                throw new Exception($response->error->message . "\n" . $erroDetails);
            }

            // INICIALIZANDO VARIÁVEIS
            $valorTransacao = str_replace(',', '.', str_replace('.', '', $response->Transaction->value));
            $decimal = substr($valorTransacao, -2);
            $inteiro = substr($valorTransacao, 0, -2);
            $valorTransacao = $inteiro . '.' . $decimal;
            if (empty($valorTransacao)) $valorTransacao = '0.00';
            $valorTransacao = number_format($valorTransacao, 2, '.', ',');

            // DEFININDO VARIÁVEIS
            $transacao->valor_transacao = $valorTransacao;
            $transacao->data_pagamento_transacao = $response->Transaction->payday;
            $transacao->status_transacao = $response->Transaction->status;
            $transacao->descricao_status_transacao = $response->Transaction->statusDescription;
            // SALVANDO DADOS
            $transacao->save();

            // DEFININDO ROTA PARA GERAMENTO DE RECEBIMENTO
            $rotaAdcionarRecebimento = redirect()->route('caixa.adicionarRecebimento.cartao', [
                'caixaFinanceiro' => $caixaAberto,
                'valorRecebimento' => str_replace(' ', ',', str_replace(',', '.', str_replace('.', ' ', $valorTransacao))),
                'observacaoRecebimento' => 'Recebimento transação Nº ' . $transacao->codigo_transacao_galaxpay,
                'galaxPayCliente' => $transacao->cliente_galaxpay,
                'contaRecebimento' => $request->user()->parametros->conta_recebimento_padrao
            ]);
            // RETORNANDO ROTA
            return $rotaAdcionarRecebimento;


            // RETORNANDO ROTA
            throw new Exception('Parametros não identificados.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['Ocorreu um erro inesperado. Mensagem: ' . $e->getMessage()]);
        }
    }
}
