<script type="text/javascript" src="{{asset('js/clientes/infoClienteStatus.js')}}"></script>

@if (empty($clienteGalaxpay))
    <div class="alert alert-warning shadow mt-2">
        Nenhum cliente encontrado.
    </div>
@else
    <div class="container-fluid mt-3">
        <div class="row bg-light shadow border rounded p-4">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-12 text-center">
                        <h2 class="fw-bold">{{$clienteGalaxpay->nome_cliente}}</h2>
                    </div>
                </div>
                <div class="row justify-content-center py-2">
                    <div class="col-md-4 text-center">
                        <label class="fw-bold">Nº Matricula</label>
                        <p>{{$clienteGalaxpay->matricula}}</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <label class="fw-bold">CPF/CNPJ</label>
                        <p class="cnpjMask">{{$clienteGalaxpay->cpf_cnpj_cliente}}</p>
                    </div>
                </div>
                <div class="row justify-content-center py-2">
                    <div class="col-md-3 text-center">
                        <label class="fw-bold">Emails</label>
                        <p class="m-0">{{$clienteGalaxpay->email_cliente_1}}</p>
                        <p class="m-0">{{$clienteGalaxpay->email_cliente_2}}</p>
                    </div>
                    <div class="col-md-3 text-center">
                        <label class="fw-bold">Telefones</label>
                        <p class="m-0 telefoneMask">{{$clienteGalaxpay->telefone_cliente_1}}</p>
                        <p class="m-0 telefoneMask">{{$clienteGalaxpay->telefone_cliente_2}}</p>
                    </div>
                    <div class="col-md-3 text-center">
                        <label class="fw-bold">Data cadastro</label>
                        {{date('d/m/Y H:i', strtotime($clienteGalaxpay->createdAt))}}
                    </div>
                </div>
                @if (count($clienteGalaxpay->clientesDependentesGalaxpay) > 0)
                <div class="row py-2">
                    <div class="col-md-12">
                        <h5>Dependentes:</h5>
                        <hr>
                            <div class="row pb-2 fw-bold">
                                <div class="col-md-2">
                                    Nº Matricula
                                </div>
                                <div class="col-md-6">
                                    Nome
                                </div>
                                <div class="col-md-2">
                                    CPF
                                </div>
                                <div class="col-md-2">
                                    Data de nascimento
                                </div>
                            </div>
                        @foreach ($clienteGalaxpay->clientesDependentesGalaxpay as $clienteDependenteGalaxpay)
                            <div class="row py-1">
                                <div class="col-md-2">
                                    {{$clienteDependenteGalaxpay->matricula_cliente_dependente}}
                                </div>
                                <div class="col-md-6">
                                    {{$clienteDependenteGalaxpay->nome_cliente_dependente}}
                                </div>
                                <div class="col-md-2">
                                    <p class="cnpjMask">{{$clienteDependenteGalaxpay->cpf_cliente_dependente}}</p>
                                </div>
                                <div class="col-md-2">
                                    {{date('d/m/Y', strtotime($clienteDependenteGalaxpay->nascimento_cliente_dependente))}}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
                <div class="row justify-content-center py-3">
                    @switch($clienteGalaxpay->status_cliente)
                        @case('active')
                            <div class="col-md-4 text-center rounded bg-success py-2">
                                <h3 class="fw-bold text-white">Ativo</h3>
                            </div>
                            @break
                        @case('delayed')
                            <div class="col-md-4 text-center rounded bg-warning py-2">
                                <h3 class="fw-bold text-white">Pagamento Pendente</h3>
                            </div>
                            @break
                        @case('inactive')
                            <div class="col-md-4 text-center rounded bg-danger py-2">
                                <h3 class="fw-bold text-white">Inativo</h3>
                            </div>
                            @break
                        @case('withoutSubscriptionOrCharge')
                            <div class="col-md-4 text-center rounded bg-secondary py-2">
                                <h3 class="fw-bold text-white">Sem assinatura</h3>
                            </div>
                            @break
                        @default
                    @endswitch
                </div>
            </div>
        </div>
    </div>
@endif

