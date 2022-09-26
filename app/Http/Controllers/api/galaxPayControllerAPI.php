<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\campo_personalizado_cliente_galaxpay;
use App\Models\clientes_galaxpay;
use App\Models\endereco_cliente_galaxpay;
use App\Models\User;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class galaxPayControllerAPI extends Controller
{
    public function generateAcessToken(Request $request)
    {

        // INICIALIZANDO VARIAVEIS
        $permissoesApi = 'customers.read customers.write plans.read plans.write transactions.read transactions.write webhooks.write cards.read cards.write card-brands.read subscriptions.read subscriptions.write charges.read charges.write boletos.read';
        $permissaoUserLogado = $request->user()->role;
        $userPrimario = User::find($request->user()->id);
        if ($permissaoUserLogado == 'empresaParceira') {
            $userLinkedId = $request->user()->userPrimario->user_linked_id;
            $userPrimario = User::find($userLinkedId);
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
            ]), 'json')->post('https://api.galaxpay.com.br/v2/token');

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
            ])->get("https://api.galaxpay.com.br/v2/customers?startAt=$startAt&limit=100");

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
            // GERANDO NUMERO DE MATRICULA
            $matricula = str_pad(date('Y') . $codigoClienteGalaxpay, 10, 0);

            // ANALISANDO TIPO DE USUARIO LOGADO
            $permissaoUserLogado = $request->user()->role;
            $userPrimario = User::find($request->user()->id);
            if ($permissaoUserLogado == 'empresaParceira') {
                $userLinkedId = $request->user()->userPrimario->user_linked_id;
                $userPrimario = User::find($userLinkedId);
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

    public function atualizaClienteGalaxPay(Request $request, clientes_galaxpay $clienteGalaxpay)
    {
        // ANALISANDO VARIAVEL
        if (!isset($clienteGalaxpay) || empty($clienteGalaxpay)) {
            return view('components.messages.returnMessages', ['WARNING' => ['Nenhum registro encontrado. [Atualiza cliente galaxPay]']]);
        };

        // CAPTURANDO ACCESS TOKEN
        $generateAcessToken = $this->generateAcessToken($request);
        // ANALISANDO STATUS DE RETORNO DA FUNÇÃO
        if ($generateAcessToken['statusRetorno'] != 'SUCCESS') {
            // REDIRECIONANDO COM ERRO
            return redirect()->back()->withErrors(['Erro: ' . $generateAcessToken['msgErro']]);
        }

        // INICIALIZANDO VARIAVEL
        $accessToken = $generateAcessToken['access_token'];
        $galaxPayId  = $clienteGalaxpay->codigo_cliente_galaxpay;

        // DELETANDO CAMPOS PERSONALIZADOS
        campo_personalizado_cliente_galaxpay::where('cliente_galaxpay_id', $clienteGalaxpay->id)->delete();

        // CONSULTANDO API
        $response = Http::withHeaders([
            'Authorization' => "Bearer $accessToken",
            'Content-Type' => 'application/json'
        ])->get("https://api.galaxpay.com.br/v2/customers?galaxPayIds=$galaxPayId&startAt=0&limit=1");

        // CAPTURANDO RESPOSTA DA API
        $response = json_decode($response);

        // VERIFICANDO ERRO
        if (!empty($response->error)) {
            // REDIRECIONANDO COM ERRO
            return view('components.messages.returnMessages', ['ERROR' => ['Erro: [ ' . $response->error->message . ' ]']]);
        }

        // GERANDO NUMERO DE MATRICULA
        $matricula = str_pad(date('Y') . $response->Customers[0]->galaxPayId, 10, 0);

        // ATUALIZANDO VALORES AO MODEL
        $clienteGalaxpay->codigo_cliente_galaxpay          = $response->Customers[0]->galaxPayId;
        $clienteGalaxpay->meu_id                           = $response->Customers[0]->myId;
        $clienteGalaxpay->matricula                        = $matricula;
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
        // ATUALIZANDO ENDEREÇO DO CLIENTE
        $clienteGalaxpay->enderecoClienteGalaxpay->cep               = $response->Customers[0]->Address->zipCode;
        $clienteGalaxpay->enderecoClienteGalaxpay->logradouro        = $response->Customers[0]->Address->street;
        $clienteGalaxpay->enderecoClienteGalaxpay->numero            = $response->Customers[0]->Address->number;
        $clienteGalaxpay->enderecoClienteGalaxpay->complemento       = $response->Customers[0]->Address->complement;
        $clienteGalaxpay->enderecoClienteGalaxpay->bairro            = $response->Customers[0]->Address->neighborhood;
        $clienteGalaxpay->enderecoClienteGalaxpay->cidade            = $response->Customers[0]->Address->city;
        $clienteGalaxpay->enderecoClienteGalaxpay->estado            = $response->Customers[0]->Address->state;

        // CAPTURANDO CAMPOS EXTRAS
        $campoExtras = $response->Customers[0]->ExtraFields;

        $clienteGalaxpay->save();
        $clienteGalaxpay->enderecoClienteGalaxpay->save();

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
        if ($permissaoUserLogado == 'empresaParceira') {
            $userLinkedId = $request->user()->userPrimario->user_linked_id;
            $userPrimario = User::find($userLinkedId);
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
            ])->get("https://api.galaxpay.com.br/v2/customers?$searchOption=$search&startAt=0&limit=1");

            // CAPTURANDO RESPOSTA DA API
            $response = json_decode($response);

            // VERIFICANDO ERRO
            if (!empty($response->error)) {
                // REDIRECIONANDO COM ERRO
                return view('components.messages.returnMessages', ['ERROR' => ['Erro: [ ' . $response->error->message . "\n" .  json_encode($response->error->details) . ' ]']]);
            } else {
                $importaClienteGalaxPay = $this->importaClienteGalaxPay($request, $response);
                // ANALISANDO RESPOSTA DE ERRO
                if ($importaClienteGalaxPay['statusRetorno'] != 'SUCCESS') {
                    return view('components.messages.returnMessages', ['ERROR' => ['Erro: [ OCORREU UM ERRO INESPERADO. ]']]);
                } else {
                    $retorno = $importaClienteGalaxPay['clienteGalaxpayCadastrado'];
                }
            }
        } else {
            // ATUALIZANDO DADOS DO CLIENTE CADASTRADO
            $this->atualizaClienteGalaxPay($request, $clienteCadastrado);
            // RETORNANDO CLIENTE CADASTRADO
            $retorno = $clienteCadastrado;
        }

        // DEFININDO QUANTIDADE DE DEPENDENTE EXISTEM
        $qtdDependentes = 7;
        $dependentesCliente = [];
        for ($i = 1; $i <= $qtdDependentes; $i++) {
            // INICIALIZANDO VARIAVEIS COM OS CAMPOS EXTRAS DE DEPENDENTES
            $nomeDependente = $retorno->campoPersonalizadoClienteGalaxpay()->where('nome_campo_personalizado', 'CP_NOME_DEPENDENTE_0' . $i)->first();
            $cpfDependente = $retorno->campoPersonalizadoClienteGalaxpay()->where('nome_campo_personalizado', 'CP_CPF_DEPENDENTES_0' . $i)->first();
            $nascimentoDependente = $retorno->campoPersonalizadoClienteGalaxpay()->where('nome_campo_personalizado', 'CP_NASCIMENTO_DEPENDENTE_0' . $i)->first();

            // ANISALISANDO SE EXISTE NOME DE DEPENDENTE
            if (isset($nomeDependente)) {
                // ADICIONANDO AO ARRAY
                $dependentesCliente[] = [
                    'nomeDependente' => $nomeDependente,
                    'cpfDependente' => $cpfDependente,
                    'nascimentoDependente' => $nascimentoDependente
                ];
            }

            // RESETANDO VARIAVEL
            unset($nomeDependente);
        }

        // RETORNANDO VIEW
        if ($request->user()->role == 'empresaParceira') {
            return view('clientes.infoClienteStatus', ['clienteGalaxpay' => $retorno, 'dependentesCliente' => $dependentesCliente]);
        } else {
            return view('components.listas.listClientes', ['clienteGalaxpay' => $retorno, 'dependentesCliente' => $dependentesCliente]);
        }
    }
}
