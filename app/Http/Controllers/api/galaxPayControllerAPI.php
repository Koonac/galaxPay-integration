<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\campo_personalizado_cliente_galaxpay;
use App\Models\clientes_galaxpay;
use App\Models\endereco_cliente_galaxpay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class galaxPayControllerAPI extends Controller
{
    public function generateAcessToken(Request $request){

        // PERMISSÕES PARA GERAR O TOKEN
        $permissoesApi = 'customers.read customers.write plans.read plans.write transactions.read transactions.write webhooks.write cards.read cards.write card-brands.read subscriptions.read subscriptions.write charges.read charges.write boletos.read';

        // CAPTURANDO PARAMETROS DO USUARIO
        $galaxPayParametros = $request->user()->galaxPayParametros;

        // INICIALIZANDO VARIAVEIS
        $galaxId = $galaxPayParametros->galax_id;
        $galaxHash = $galaxPayParametros->galax_hash;

        // MONTANDO CORPO PARA ENVIO API
        $response = Http::withHeaders([
            'Authorization' => 'Basic '. base64_encode("$galaxId:$galaxHash"),
            'Content-Type' => 'application/json'
        ])->withBody(json_encode([
            'grant_type' => 'authorization_code',
            'scope' => $permissoesApi
        ]), 'json')->post('https://api.sandbox.cloud.galaxpay.com.br/v2/token');

        // SALVANDO TOKEN NO BANCO DE DADOS 
        $galaxPayParametros->galax_token = $response['access_token']; 
        $galaxPayParametros->save();

        //RETORNANDO RESPOSTA
        return $response['access_token'];
    }

    public function importaClientesGalaxPay(Request $request){
        // CAPTURANDO ACCESS TOKEN
        $accessToken                = $this->generateAcessToken($request);
        $lacoCliente                = true;
        $registrosImportados        = 0;
        $totalRegistrosCapturados   = 0;
        $startAt                    = 0;

        while($lacoCliente){
                // MONTANDO CORPO PARA ENVIO DA API
            $response = Http::withHeaders([
                'Authorization' => "Bearer $accessToken",
                'Content-Type' => 'application/json'
            ])->get("https://api.sandbox.cloud.galaxpay.com.br/v2/customers?startAt=$startAt&limit=100");

            // CAPTURANDO RESPOSTA DA API
            $response = json_decode($response);

            // VERIFICANDO ERRO
            if(!empty($response->error)){
                // REDIRECIONANDO COM ERRO
                return redirect()->back()->withErrors(['Erro: ' . $response->error->message]);
            }else{
                // ANALISANDO TOTAL DE REGISTROS
                $totalRegistrosCapturados += $response->totalQtdFoundInPage;
                $listClientesGalaxpay['totalRegistrosCapturados'] = $totalRegistrosCapturados;

                // PERCORRENDO LAÇO
                foreach($response->Customers as $customer){
                    $listClientesGalaxpay['listClientesGalaxpay'][] = $customer;
                }

                // DEFININDO POR ONDE DEVE COMEÇAR A PESQUISAR NOVOS CLIENTES 
                $startAt = $totalRegistrosCapturados;

                // ANALISANDO SE JA CHEGOU NO LIMITE
                if($response->totalQtdFoundInPage < 100){
                    $lacoCliente = false;
                }
            }
        }

        if($listClientesGalaxpay['totalRegistrosCapturados'] <= 0){
            // REDIRECIONANDO COM ERRO
            return redirect()->back()->with(['WARNING' => ['Não há registros para sincronização']]);
        }else{
            // PERCORRENDO LAÇO 
            foreach($listClientesGalaxpay['listClientesGalaxpay'] as $cliente){
                // INICIALIZANDO VARIAVEIS
                $codigoClienteGalaxpay          = $cliente->galaxPayId;
                $nomeCliente                    = $cliente->name;
                $cpfCnpjCliente                 = $cliente->document;
                empty($cliente->emails[0]) ? $emailCliente1 = '' : $emailCliente1 = $cliente->emails[0];
                empty($cliente->emails[1]) ? $emailCliente2 = '' : $emailCliente2 = $cliente->emails[1];
                empty($cliente->phones[0]) ? $telefoneCliente1 = '' : $telefoneCliente1 = $cliente->phones[0];
                empty($cliente->phones[1]) ? $telefoneCliente2 = '' : $telefoneCliente2 = $cliente->phones[1];
                empty($cliente->xxxx) ? $issNfCliente = '' : $issNfCliente = $cliente->xxxx;
                empty($cliente->xxxx) ? $inscricaoMunicipalCliente = '' : $inscricaoMunicipalCliente = $cliente->xxxx;
                $statusCliente                  = $cliente->status;
                $createdAt                      = $cliente->createdAt;
                $updatedAt                      = $cliente->updatedAt;
                // ENDEREÇO
                $cep                            = $cliente->Address->zipCode;
                $logradouro                     = $cliente->Address->street;
                $numero                         = $cliente->Address->number;
                $complemento                    = $cliente->Address->complement;
                $bairro                         = $cliente->Address->neighborhood;
                $cidade                         = $cliente->Address->city;
                $estado                         = $cliente->Address->state;
                $cadastraEnderecoCliente        = true;
                // CAMPOS EXTRAS
                $campoExtras                    = $cliente->ExtraFields;
    
                // PERCORRENDO LAÇO DE ENDEREÇOS
                foreach($cliente->Address as $keyAddress => $valueAddress){
                    // ANALISANDO SE O CAMPO COMPLEMENT É VAZIO
                    if($keyAddress == 'complement' && empty($valueAddress)) continue;
    
                    // ANALISANDO SE EXISTE ALGUM CAMPO VAZIO NO ENDEREÇO
                    if(empty($valueAddress)){
                        $cadastraEnderecoCliente = false;
                    };
                }
                
                // CRIANDO MODEL 
                $galaxPayCLientesAssociado = $request->user()->galaxPayClientes();
    
                // VERIFICANDO SE O CLIENTE JA ESTA CADASTRADO
                $clienteCadastrado = $galaxPayCLientesAssociado->firstWhere('codigo_cliente_galaxpay', $codigoClienteGalaxpay);
    
                // CASO SEJA ENCONTRADO REFAZ O LAÇO
                if(!empty($clienteCadastrado)) continue;
    
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
                $galaxPayCLientesAssociado->save($clienteGalaxpay);
                if($cadastraEnderecoCliente) { $clienteGalaxpay->enderecoClienteGalaxpay()->save($enderecoClienteGalaxpay); };
    
                // VERIFICANDO CAMPOS EXTRAS
                if(!empty($campoExtras)){
                    // PERCORRENDO LAÇO
                    foreach($campoExtras as $campoExtras){
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

        //RETORNANDO RESPOSTA
        return redirect()->back()->with('SUCCESS', ["Foram importado(s) $registrosImportados novo(s) registros."]);
    }
}
