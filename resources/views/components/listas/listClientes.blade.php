<script type="text/javascript" src="{{asset('js/clientes/listClientes.js')}}"></script>

<div class="container-fluid mt-3">
    <div class="row bg-light shadow border rounded p-4">
        <div class="col-md-12">
            <div class="accordion accordion-flush bg-white p-4 overflow-auto" id="accordionCollapse">
                <div class="px-2 pb-2 d-none d-md-block">
                    <div class="row fw-bold">
                        <div class="col-md-5 col-lg-5 col-xl-5 text-capitalize">
                            Nome
                        </div>
                        <div class="col-md-3 col-lg-3 col-xl-3 text-capitalize">
                            Telefone
                        </div>
                        <div class="col-md-3 col-lg-3 col-xl-3 text-capitalize">
                            CPF/CNPJ
                        </div>
                        {{-- <div class="col-md-2 col-lg-2 col-xl-3 text-capitalize">
                            Ações
                        </div> --}}
                    </div>
                    <hr>
                </div>
                <div class="accordion-item">
                    <div class="row p-2">
                        <div class="col-md-5 col-lg-5 col-xl-5 cursorPointer" data-bs-toggle="collapse" data-bs-target="#collapse-{{$clienteGalaxpay->codigo_cliente_galaxpay}}" aria-expanded="false" aria-controls="collapse-{{$clienteGalaxpay->codigo_cliente_galaxpay}}">
                            <label class="fw-bold d-block d-md-none">Nome</label>
                            <input class="form-check-input" type="checkbox" onchange='campoExtraImprimir(this)' value="{{$clienteGalaxpay->codigo_cliente_galaxpay}}" name="checkboxCliente" id="checkboxCliente">
                            <span class="px-2">{{$clienteGalaxpay->nome_cliente}}</span>
                        </div>
                        <div class="col-md-3 col-lg-3 col-xl-3 cursorPointer" data-bs-toggle="collapse" data-bs-target="#collapse-{{$clienteGalaxpay->codigo_cliente_galaxpay}}" aria-expanded="false" aria-controls="collapse-{{$clienteGalaxpay->codigo_cliente_galaxpay}}">
                            <label class="fw-bold d-block d-md-none">Telefone</label>
                            <span class="telefoneMask">{{empty($clienteGalaxpay->telefone_cliente_1) ? '00000000000' : $clienteGalaxpay->telefone_cliente_1}}</span>
                        </div>
                        <div class="col-md-3 col-lg-3 col-xl-3 text-truncate cursorPointer" data-bs-toggle="collapse" data-bs-target="#collapse-{{$clienteGalaxpay->codigo_cliente_galaxpay}}" aria-expanded="false" aria-controls="collapse-{{$clienteGalaxpay->codigo_cliente_galaxpay}}">
                            <label class="fw-bold d-block d-md-none">CPF/CNPJ</label>
                            <span class="cnpjMask">{{$clienteGalaxpay->cpf_cnpj_cliente}}</span>
                        </div>
                        {{-- <div class="col-md-2 col-lg-2 col-xl-2 d-none d-md-block cursorPointer" data-bs-toggle="collapse" data-bs-target="#collapse-{{$clienteGalaxpay->codigo_cliente_galaxpay}}" aria-expanded="false" aria-controls="collapse-{{$clienteGalaxpay->codigo_cliente_galaxpay}}">
                            <a href="{{route('clientes.gerarCartao', ['cliente' => $clienteGalaxpay->codigo_cliente_galaxpay, 'dependentesCliente' => ['38262', '38262']])}}" target="_blank" class="btn btn-warning text-white fw-bold">
                                <i class="fa-solid fa-address-card"></i>
                                <label class="d-none d-xl-inline">Imprimir</label>
                            </a>
                            <button type="button" class="btn btn-danger text-white fw-bold">
                                <i class="fa-solid fa-trash"></i>
                                <label class="d-none d-xl-inline">Excluir</label>
                            </button>
                        </div> --}}
                        <div class="col-1" data-bs-toggle="collapse" data-bs-target="#collapse-{{$clienteGalaxpay->codigo_cliente_galaxpay}}" aria-expanded="false" aria-controls="collapse-{{$clienteGalaxpay->codigo_cliente_galaxpay}}">
                            <i class="fa-solid fa-angle-right rotate"></i>
                        </div>
                    </div>
                </div>
                <div class="accordion-collapse collapse bg-light" id="collapse-{{$clienteGalaxpay->codigo_cliente_galaxpay}}" data-bs-parent="#accordionCollapse">
                    <div class="accordion-body" id='divDependentes'>
                        @if (count($dependentesCliente) > 0)
                            <div class="row py-2">
                                <div class="col-12 ">
                                    <label class="fw-bold text-capitalize">Dependentes:</label>
                                </div>
                            </div>                        
                            @foreach ($dependentesCliente as $dependenteCliente)
                                <div class="row">
                                    <div class="col-md-5 ps-4" id="divDependente{{$dependenteCliente['nomeDependente']->id}}">
                                        <input class="form-check-input" type="checkbox" onchange='campoExtraImprimir(this)' value="{{$dependenteCliente['nomeDependente']->id}}" name="{{$dependenteCliente['nomeDependente']->id}}" id="{{$dependenteCliente['nomeDependente']->id}}">
                                        @isset($dependenteCliente['cpfDependente'])
                                            <input hidden value="{{$dependenteCliente['cpfDependente']->id}}" name="{{$dependenteCliente['nomeDependente']->id}}" id="{{$dependenteCliente['nomeDependente']->id}}">
                                            <input hidden value="{{$dependenteCliente['nascimentoDependente']->id}}" name="{{$dependenteCliente['nomeDependente']->id}}" id="{{$dependenteCliente['nomeDependente']->id}}">
                                        @endisset
                                        <label class="form-check-label px-2" for="{{$dependenteCliente['nomeDependente']->id}}"> {{$dependenteCliente['nomeDependente']->valor_campo_personalizado}} </label>
                                    </div>
                                    @isset($dependenteCliente['cpfDependente'])

                                        <div class="col-md-4 d-none d-md-block">
                                            {{-- <label class="form-check-label px-2" for="{{$dependenteCliente['cpfDependente']->id}}"> CPF </label> --}}
                                            <label class="cnpjMask" for="{{$dependenteCliente['nomeDependente']->id}}" id="{{$dependenteCliente['cpfDependente']->id}}">{{$dependenteCliente['cpfDependente']->valor_campo_personalizado}}</label>
                                        </div>
                                        <div class="col-md-3 d-none d-md-block">
                                            {{-- <label class="form-check-label px-2" for="{{$dependenteCliente['cpfDependente']->id}}"> CPF </label> --}}
                                            <label for="{{$dependenteCliente['nomeDependente']->id}}" id="{{$dependenteCliente['nascimentoDependente']->id}}">{{date('d/m/Y', strtotime($dependenteCliente['cpfDependente']->valor_campo_personalizado))}}</label>
                                        </div>
                                    @endisset
                                </div>
                                <hr>
                            @endforeach
                        @else
                            <div class="ms-2">
                                <p class="font-italic font-weight-normal">Não há dependentes para impressão.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-offcanvas.menuBottom>
    <div class="row justify-content-end">
        <div class="d-none d-sm-block col-sm-7 col-md-8 col-lg-9 text-end fw-bold">
            <span id='textOffcanvas'>Selecionados para impressão</span>
        </div>
        <div class="col-sm-5 col-md-4 col-lg-3 text-center text-sm-end fw-bold">
            <button id="btnImprimirCard" class="btn btn-warning text-white fw-bold">
                <i class='fa-solid fa-address-card'></i>
                Imprimir selecionados
            </button>
        </div>
    </div>
</x-offcanvas.menuBottom>
