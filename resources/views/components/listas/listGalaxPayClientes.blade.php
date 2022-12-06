<script type="text/javascript" src="{{asset('js/galaxPay/listGalaxPayClientes.js')}}"></script>

<div class="container-fluid mt-3">
    <div class="row bg-light shadow border rounded p-4">
        <div class="col-md-12">
            <div class="accordion accordion-flush bg-white p-4 overflow-auto" id="accordionCollapse">
                <div class="px-2 pb-2 d-none d-md-block">
                    <div class="row fw-bold">
                        <div class="col-md-5 col-lg-5 col-xl-4 text-capitalize">
                            Nome
                        </div>
                        <div class="col-md-2 col-lg-2 col-xl-2 text-capitalize">
                            Telefone
                        </div>
                        <div class="col-md-2 col-lg-2 col-xl-3 text-capitalize">
                            Nº Matricula
                        </div>
                        <div class="col-md-2 col-lg-2 col-xl-2 text-capitalize">
                            CPF/CNPJ
                        </div>
                        <div class="col-md-1">
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="accordion-item">
                    <div class="row p-2">
                        <div class="col-md-5 col-lg-5 col-xl-4">
                            <label class="fw-bold d-block d-md-none cursorPointer" data-bs-toggle="collapse" data-bs-target="#collapse-{{$clienteGalaxpay->codigo_cliente_galaxpay}}" aria-expanded="false" aria-controls="collapse-{{$clienteGalaxpay->codigo_cliente_galaxpay}}">Nome</label>
                            <span class="px-2 cursorPointer" data-bs-toggle="collapse" data-bs-target="#collapse-{{$clienteGalaxpay->codigo_cliente_galaxpay}}" aria-expanded="false" aria-controls="collapse-{{$clienteGalaxpay->codigo_cliente_galaxpay}}">{{$clienteGalaxpay->nome_cliente}}</span>
                        </div>
                        <div class="col-md-2 col-lg-2 col-xl-2 cursorPointer" data-bs-toggle="collapse" data-bs-target="#collapse-{{$clienteGalaxpay->codigo_cliente_galaxpay}}" aria-expanded="false" aria-controls="collapse-{{$clienteGalaxpay->codigo_cliente_galaxpay}}">
                            <label class="fw-bold d-block d-md-none">Telefone</label>
                            <span class="telefoneMask">{{empty($clienteGalaxpay->telefone_cliente_1) ? '00000000000' : $clienteGalaxpay->telefone_cliente_1}}</span>
                        </div>
                        <div class="col-md-2 col-lg-2 col-xl-3 cursorPointer" data-bs-toggle="collapse" data-bs-target="#collapse-{{$clienteGalaxpay->codigo_cliente_galaxpay}}" aria-expanded="false" aria-controls="collapse-{{$clienteGalaxpay->codigo_cliente_galaxpay}}">
                            <label class="fw-bold d-block d-md-none">Nº Matricula</label>
                            <span>{{$clienteGalaxpay->matricula}}</span>
                        </div>
                        <div class="col-md-2 col-lg-2 col-xl-2 text-truncate cursorPointer" data-bs-toggle="collapse" data-bs-target="#collapse-{{$clienteGalaxpay->codigo_cliente_galaxpay}}" aria-expanded="false" aria-controls="collapse-{{$clienteGalaxpay->codigo_cliente_galaxpay}}">
                            <label class="fw-bold d-block d-md-none">CPF/CNPJ</label>
                            <span class="cnpjMask">{{$clienteGalaxpay->cpf_cnpj_cliente}}</span>
                        </div>
                        <div class="col-1">
                            <input class="btn-check" type="checkbox" onchange='campoExtraImprimir(this)' value="{{$clienteGalaxpay->codigo_cliente_galaxpay}}" name="checkboxCliente" id="checkboxCliente">
                            <label class="btn btn-outline-success" for="checkboxCliente"><i class="fa-solid fa-check fa-lg"></i></label>
                        </div>
                    </div>
                </div>
                <div class="accordion-collapse collapse bg-light" id="collapse-{{$clienteGalaxpay->codigo_cliente_galaxpay}}" data-bs-parent="#accordionCollapse">
                    <div class="accordion-body" id='divDependentes'>
                        @if (count($clienteGalaxpay->clientesDependentesGalaxpay) > 0)
                            <div class="row py-2">
                                <div class="col-12 ">
                                    <label class="fw-bold text-capitalize">Dependentes:</label>
                                </div>
                            </div>                        
                            @foreach ($clienteGalaxpay->clientesDependentesGalaxpay as $clientesDependentesGalaxpay)
                                <div class="row">
                                    <div class="col-md-5 ps-4" id="divDependente{{$clientesDependentesGalaxpay->id}}">
                                        <label class="form-check-label px-2" for=""> {{$clientesDependentesGalaxpay->nome_cliente_dependente}} </label>
                                    </div>
                                    <div class="col-md-2 d-none d-md-block">
                                        {{-- <label class="form-check-label px-2" for=""> CPF </label> --}}
                                        <label class="cnpjMask" for="" id="">{{$clientesDependentesGalaxpay->cpf_cliente_dependente}}</label>
                                    </div>
                                    <div class="col-md-2 d-none d-md-block">
                                        {{-- <label class="form-check-label px-2" for="">Matricula</label> --}}
                                        <label for="" id="">{{$clientesDependentesGalaxpay->matricula_cliente_dependente}}</label>
                                    </div>
                                    <div class="col-md-2 d-none d-md-block">
                                        {{-- <label class="form-check-label px-2" for="">Nascimento</label> --}}
                                        <label for="" id="">{{$clientesDependentesGalaxpay->nascimento_cliente_dependente}}</label>
                                    </div>
                                    <div class="col-1">
                                        <input class="btn-check" type="checkbox" onchange='campoExtraImprimir(this)' value="{{$clientesDependentesGalaxpay->id}}" name="checkboxClienteDependente{{$clientesDependentesGalaxpay->id}}" id="checkboxClienteDependente{{$clientesDependentesGalaxpay->id}}">
                                        <label class="btn btn-outline-success" for="checkboxClienteDependente{{$clientesDependentesGalaxpay->id}}"><i class="fa-solid fa-check fa-lg"></i></label>
                                    </div>
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
