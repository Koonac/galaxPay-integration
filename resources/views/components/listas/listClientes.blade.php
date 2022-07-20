<script type="text/javascript" src="{{asset('js/clientes/listClientes.js')}}"></script>

<div class="container-fluid mt-3">
    <div class="row bg-light shadow border rounded p-4">
        <div class="col-md-12">
            <div class="accordion accordion-flush bg-white p-4 vh-100 overflow-auto" id="accordionCollapse">
                <div class="px-2 pb-2 d-none d-md-block">
                    <div class="row fw-bold">
                        <div class="col-md-2 col-lg-1 col-xl-1 text-capitalize">
                            Código
                        </div>
                        <div class="col-md-3 col-lg-3 col-xl-2 text-capitalize">
                            Nome
                        </div>
                        <div class="col-md-2 col-lg-3 col-xl-3 text-capitalize">
                            Telefone
                        </div>
                        <div class="col-md-2 col-lg-3 col-xl-3 text-capitalize">
                            Email
                        </div>
                        <div class="col-md-3 col-lg-2 col-xl-3 text-capitalize">
                            Ações
                        </div>
                    </div>
                    <hr>
                </div>
                @foreach ($galaxPayClientes as $galaxPayCliente)
                    <div class="accordion-item">
                        <div class="row p-2" data-bs-toggle="collapse" data-bs-target="#collapse-{{$galaxPayCliente->codigo_cliente_galaxpay}}" aria-expanded="false" aria-controls="collapse-{{$galaxPayCliente->codigo_cliente_galaxpay}}">
                            <div class="col-md-2 col-lg-1 col-xl-1 d-none d-md-block">
                                {{$galaxPayCliente->codigo_cliente_galaxpay}}
                            </div>
                            <div class="col-md-3 col-lg-3 col-xl-2">
                                <label class="fw-bold d-block d-md-none">Nome</label>
                                {{$galaxPayCliente->nome_cliente}}
                            </div>
                            <label class="fw-bold d-block d-md-none">Telefone</label>
                            <div class="col-md-2 col-lg-3 col-xl-3 telefoneMask">
                                {{empty($galaxPayCliente->telefone_cliente_1) ? '00000000000' : $galaxPayCliente->telefone_cliente_1}}
                            </div>
                            <div class="col-md-2 col-lg-3 col-xl-3 text-truncate">
                                <label class="fw-bold d-block d-md-none">Email</label>
                                {{$galaxPayCliente->email_cliente_1}}
                            </div>
                            <div class="col-md-3 col-lg-2 col-xl-3 d-none d-md-block">
                                <a href="{{route('clientes.gerarCartao', $galaxPayCliente->id)}}" target="_blank" class="btn btn-warning text-white fw-bold">
                                    <i class="fa-solid fa-address-card"></i>
                                    <label class="d-none d-xl-inline">Imprimir</label>
                                </a>
                                {{-- <button type="button" class="btn btn-danger text-white fw-bold">
                                    <i class="fa-solid fa-trash"></i>
                                    <label class="d-none d-xl-inline">Excluir</label>
                                </button> --}}
                            </div>
                        </div>
                    </div>
                    <div class="accordion-collapse collapse bg-light" id="collapse-{{$galaxPayCliente->codigo_cliente_galaxpay}}" data-bs-parent="#accordionCollapse">
                        <div class="accordion-body">
                            <div class="row py-2">
                                <div class="col-sm-4 col-md-2">
                                    <label class="fw-bold d-block">Código</label>
                                    <span>{{$galaxPayCliente->codigo_cliente_galaxpay}}</span>
                                </div>
                                <div class="col-sm-8 col-md-4">
                                    <label class="fw-bold d-block">Nome</label>
                                    <span>{{$galaxPayCliente->nome_cliente}}</span>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <label class="fw-bold d-block">CPF/CNPJ</label>
                                    <span class="cnpjMask">{{$galaxPayCliente->cpf_cnpj_cliente}}</span>
                                </div>
                                <div class="col-sm-4 col-md-2">
                                    <label class="fw-bold d-block">Status</label>
                                    <span>
                                        <?php
                                            switch ($galaxPayCliente->status_cliente) {
                                                case 'active':
                                                    echo 'Ativo';
                                                    break;
                                                case 'delayed':
                                                   echo 'Pendente';
                                                    break;
                                                case 'inactive':
                                                    echo 'Inativo';
                                                    break;
                                                case 'withoutSubscriptionOrCharge':
                                                    echo 'Sem assinaturas';
                                                    break;
                                            }
                                        ?>
                                    </span>
                                </div>
                                <div class="col-sm-4 col-md-6 text-md-center">
                                    <label class="fw-bold d-block">Telefone</label>
                                    <span class="d-block telefoneMask">{{empty($galaxPayCliente->telefone_cliente_1) ? '00000000000' : $galaxPayCliente->telefone_cliente_1}}</span>
                                    <span class="d-block telefoneMask">{{empty($galaxPayCliente->telefone_cliente_2) ? '00000000000' : $galaxPayCliente->telefone_cliente_2}}</span>
                                </div>
                                <div class="col-sm-12 col-md-6 text-md-center">
                                    <label class="fw-bold d-block">E-mails</label>
                                    <span class="d-block text-truncate">{{empty($galaxPayCliente->email_cliente_1) ? '(vazio)' : $galaxPayCliente->email_cliente_1}}</span>
                                    <span class="d-block text-truncate">{{empty($galaxPayCliente->email_cliente_2) ? '(vazio)' : $galaxPayCliente->email_cliente_2}}</span>
                                </div>
                            </div>
                            @if (!empty($galaxPayCliente->enderecoClienteGalaxpay))
                                <hr>
                                <div class="row py-2">
                                    <div class="col-8 col-sm-5 col-lg-3">
                                        <label class="fw-bold d-block">Logradouro</label>
                                        <span class="d-block text-truncate">{{$galaxPayCliente->enderecoClienteGalaxpay->logradouro}}</span>
                                    </div>
                                    <div class="col-4 col-sm-2 col-lg-1">
                                        <label class="fw-bold d-block">Nº</label>
                                        <span class="d-block text-truncate">{{$galaxPayCliente->enderecoClienteGalaxpay->numero}}</span>
                                    </div>
                                    <div class="col-12 col-sm-5 col-lg-3">
                                        <label class="fw-bold d-block">Bairro</label>
                                        <span class="d-block text-truncate">{{$galaxPayCliente->enderecoClienteGalaxpay->bairro}}</span>
                                    </div>
                                    <div class="col-8 col-sm-5 col-lg-2">
                                        <label class="fw-bold d-block">Cidade</label>
                                        <span class="d-block text-truncate">{{$galaxPayCliente->enderecoClienteGalaxpay->cidade}}</span>
                                    </div>
                                    <div class="col-4 col-sm-2 col-lg-1">
                                        <label class="fw-bold d-block">Estado</label>
                                        <span class="d-block text-truncate">{{$galaxPayCliente->enderecoClienteGalaxpay->estado}}</span>
                                    </div>
                                    <div class="col-12 col-sm-5 col-lg-2">
                                        <label class="fw-bold d-block">CEP</label>
                                        <span class="d-block cepMask text-truncate">{{$galaxPayCliente->enderecoClienteGalaxpay->cep}}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-12">
                                        <label class="fw-bold d-block">Complemento</label>
                                        <span class="d-block text-truncate">{{$galaxPayCliente->enderecoClienteGalaxpay->complemento}}</span>
                                    </div>
                                </div>
                            @endif
                            <div class="d-block d-md-none py-2">
                                <a href="{{route('clientes.gerarCartao', $galaxPayCliente->id)}}" target="_blank" class="btn btn-warning text-white fw-bold">
                                    <label class="d-none d-xl-inline">Imprimir</label>
                                </a>
                                {{-- <button type="button" class="btn btn-danger text-white w-100 my-1 fw-bold">Excluir</button> --}}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>