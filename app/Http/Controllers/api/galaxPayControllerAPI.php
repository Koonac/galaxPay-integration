<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\campo_personalizado_cliente_galaxpay;
use App\Models\clientes_galaxpay;
use App\Models\endereco_cliente_galaxpay;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use stdClass;

class galaxPayControllerAPI extends Controller
{
    public function generateAcessToken(Request $request)
    {

        // PERMISSÕES PARA GERAR O TOKEN
        $permissoesApi = 'customers.read customers.write plans.read plans.write transactions.read transactions.write webhooks.write cards.read cards.write card-brands.read subscriptions.read subscriptions.write charges.read charges.write boletos.read';

        // CAPTURANDO PARAMETROS DO USUARIO
        $galaxPayParametros = $request->user()->galaxPayParametros;

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

    public function pesquisaClientesGalaxPay(Request $request)
    {
        // CAPTURANDO ACCESS TOKEN
        $generateAcessToken = $this->generateAcessToken($request);
        // ANALISANDO STATUS DE RETORNO DA FUNÇÃO
        if ($generateAcessToken['statusRetorno'] != 'SUCCESS') {
            // REDIRECIONANDO COM ERRO
            return redirect()->back()->withErrors(['Erro: ' . $generateAcessToken['msgErro']]);
        }
        $search                     = $request->search;
        $searchOption               = $request->searchOption;
        $accessToken                = $generateAcessToken['access_token'];
        $lacoCliente                = true;
        $totalRegistrosCapturados   = 0;
        $registrosImportados        = 0;
        $startAt                    = 0;
        $controleLaco               = 0;
        // CRIANDO MODEL 
        $galaxPayClientesAssociado = $request->user()->galaxPayClientes();

        switch ($searchOption) {
            case 'myIds':
                // VERIFICANDO SE O CLIENTE JA ESTA CADASTRADO
                $clienteCadastrado = $galaxPayClientesAssociado->firstWhere('meu_id', $search);
                break;
            case 'galaxPayIds':
                // VERIFICANDO SE O CLIENTE JA ESTA CADASTRADO
                $clienteCadastrado = $galaxPayClientesAssociado->firstWhere('codigo_cliente_galaxpay', $search);
                break;
            case 'documents':
                // VERIFICANDO SE O CLIENTE JA ESTA CADASTRADO
                $clienteCadastrado = $galaxPayClientesAssociado->firstWhere('cpf_cnpj_cliente', $search);
                break;
        }

        if (empty($clienteCadastrado)) {
            while ($lacoCliente) {
                // MONTANDO CORPO PARA ENVIO DA API
                $response = Http::withHeaders([
                    'Authorization' => "Bearer $accessToken",
                    'Content-Type' => 'application/json'
                ])->get("https://api.galaxpay.com.br/v2/customers?$searchOption=$search&startAt=$startAt&limit=100");

                // CAPTURANDO RESPOSTA DA API
                $response = json_decode($response);

                // VERIFICANDO ERRO
                if (!empty($response->error)) {
                    // REDIRECIONANDO COM ERRO
                    return view('components.messages.returnMessages', ['ERROR' => ['Erro: [ ' . $response->error->message . ' ]']]);
                } else {
                    // ANALISANDO TOTAL DE REGISTROS
                    $totalRegistrosCapturados += $response->totalQtdFoundInPage;

                    // ANALISANDO QUANTIDADE DE REGISTRO
                    if ($totalRegistrosCapturados <= 0) {
                        // REDIRECIONANDO COM WARNING
                        return view('components.messages.returnMessages', ['WARNING' => ['Nenhum registro encontrado.']]);
                    } else {
                        // PERCORRENDO LAÇO
                        foreach ($response->Customers as $customer) {
                            $listClientesGalaxpay['listClientesGalaxpay'][] = $customer;
                            // INICIALIZANDO VARIAVEIS
                            $meuId                          = $customer->myId;
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
                            // GERANDO NUMERO DE MATRICULA
                            $matricula = str_pad(date('Y') . $codigoClienteGalaxpay, 10, 0);

                            // PERCORRENDO LAÇO DE ENDEREÇOS
                            foreach ($customer->Address as $keyAddress => $valueAddress) {
                                // ANALISANDO SE O CAMPO COMPLEMENT É VAZIO
                                if ($keyAddress == 'complement' && empty($valueAddress)) continue;

                                // ANALISANDO SE EXISTE ALGUM CAMPO VAZIO NO ENDEREÇO
                                if (empty($valueAddress)) {
                                    $cadastraEnderecoCliente = false;
                                };
                            }

                            // VERIFICANDO SE O CLIENTE JA ESTA CADASTRADO
                            $clienteCadastrado = $galaxPayClientesAssociado->firstWhere('codigo_cliente_galaxpay', $codigoClienteGalaxpay);

                            // CASO SEJA ENCONTRADO REFAZ O LAÇO
                            if (!empty($clienteCadastrado)) continue;

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
                $retorno = $clienteGalaxpay;
                $controleLaco++;
            }
        } else {
            // RETORNO DA FUNÇÃO
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
        return view('components.listas.listClientes', ['clienteGalaxpay' => $retorno, 'dependentesCliente' => $dependentesCliente]);
    }
}
