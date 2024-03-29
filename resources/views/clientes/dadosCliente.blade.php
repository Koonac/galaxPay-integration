<x-clientes.informacoesCliente :galaxPayCliente="$galaxPayCliente">
    <ul class="nav nav-tabs mt-4">
        <li>
            <a class="nav-link link-info active" href="{{route('clientes.dados', [$galaxPayCliente])}}">Dados</a>
        </li>
        <li>
            <a class="nav-link link-info" href="{{route('clientes.contratos', [$galaxPayCliente])}}">Contratos</a>
        </li>
        <li>
            <a class="nav-link link-info" href="{{route('clientes.transacoes', [$galaxPayCliente])}}">Transações</a>
        </li>
    </ul>

    <form action="{{route('clientes.editClienteGalaxPay', [$galaxPayCliente->id])}}" id="formEditClienteGalaxPay" method="POST">
        @method('PUT')
        @csrf
        <div class="row py-2">
            <div class="col-md-2">
                <label class="fw-bold" for="matriculaClienteGalaxPay">Matricula</label>
                <input class="form-control" name="matriculaClienteGalaxPay" id="matriculaClienteGalaxPay" type="text" value="{{$galaxPayCliente->matricula}}" readonly>
            </div>
            <div class="col-md-2">
                <label class="fw-bold" for="codigoClienteGalaxPay">Cód. GalaxPay</label>
                <input class="form-control" name="codigoClienteGalaxPay" id="codigoClienteGalaxPay" type="text" value="{{$galaxPayCliente->codigo_cliente_galaxpay}}" readonly>
            </div>
            <div class="col-md-8">
                <label class="fw-bold" for="nomeClienteGalaxPay">Nome Cliente</label>
                <input class="form-control" name="nomeClienteGalaxPay" id="nomeClienteGalaxPay" type="text" value="{{$galaxPayCliente->nome_cliente}}">
            </div>
        </div>
        <div class="row py-2">
            <div class="col-md-4">
                <label class="fw-bold" for="cpfCnpjClienteGalaxPay">CPF/CNPJ</label>
                <input class="form-control cnpjMask" name="cpfCnpjClienteGalaxPay" id="cpfCnpjClienteGalaxPay" type="text" value="{{$galaxPayCliente->cpf_cnpj_cliente}}">
            </div>
            <div class="col-md-4">
                <label class="fw-bold" for="emailClienteGalaxPay1">Emails:</label>
                <input class="form-control mb-1" name="emailClienteGalaxPay1" id="emailClienteGalaxPay1" type="text" value="{{$galaxPayCliente->email_cliente_1}}">
                <input class="form-control" name="emailClienteGalaxPay2" id="emailClienteGalaxPay2" type="text"value="{{$galaxPayCliente->email_cliente_2}}">
            </div>
            <div class="col-md-4">
                <label class="fw-bold" for="telefoneClienteGalaxPay1">Telefones:</label>
                <input class="form-control telefoneMask2 mb-1" name="telefoneClienteGalaxPay1" id="telefoneClienteGalaxPay1" type="text" value="{{$galaxPayCliente->telefone_cliente_1}}">
                <input class="form-control telefoneMask2" name="telefoneClienteGalaxPay2" id="telefoneClienteGalaxPay2" type="text" value="{{$galaxPayCliente->telefone_cliente_2}}">
            </div>
        </div>
            @if (empty($galaxPayCliente->enderecoClienteGalaxpay))
                <h5>Endereço:</h5>
                <div class="row py-2">
                    <div class="col-md-5">
                        <label class="fw-bold" for="logradouroClienteGalaxPay">Logradouro</label>
                        <input class="form-control" name="logradouroClienteGalaxPay" id="logradouroClienteGalaxPay" type="text">
                    </div>                    
                    <div class="col-md-2">
                        <label class="fw-bold" for="numeroClienteGalaxPay">Nº</label>
                        <input class="form-control" name="numeroClienteGalaxPay" id="numeroClienteGalaxPay" type="text">
                    </div>                    
                    <div class="col-md-5">
                        <label class="fw-bold" for="bairroClienteGalaxPay">Bairro</label>
                        <input class="form-control" name="bairroClienteGalaxPay" id="bairroClienteGalaxPay" type="text">
                    </div>                    
                </div>
                <div class="row py-2">
                    <div class="col-md-4">
                        <label class="fw-bold" for="cepClienteGalaxPay">CEP</label>
                        <input class="form-control cepMask" name="cepClienteGalaxPay" id="cepClienteGalaxPay" type="text">
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold" for="cidadeClienteGalaxPay">Cidade</label>
                        <input class="form-control" name="cidadeClienteGalaxPay" id="cidadeClienteGalaxPay" type="text">
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold" for="estadoClienteGalaxPay">Estado</label>
                        <input class="form-control" name="estadoClienteGalaxPay" id="estadoClienteGalaxPay" type="text">
                    </div>
                    <div class="col-md-12">
                        <label class="fw-bold" for="complementoClienteGalaxPay">Complemento</label>
                        <input class="form-control" name="complementoClienteGalaxPay" id="complementoClienteGalaxPay" type="text">
                    </div>
                </div>
            @else
                <h5>Endereço:</h5>
                <div class="row py-2">
                    <div class="col-md-5">
                        <label class="fw-bold" for="logradouroClienteGalaxPay">Logradouro</label>
                        <input class="form-control" name="logradouroClienteGalaxPay" id="logradouroClienteGalaxPay" type="text" value="{{$galaxPayCliente->enderecoClienteGalaxpay->logradouro}}">
                    </div>                    
                    <div class="col-md-2">
                        <label class="fw-bold" for="numeroClienteGalaxPay">Nº</label>
                        <input class="form-control" name="numeroClienteGalaxPay" id="numeroClienteGalaxPay" type="text" value="{{$galaxPayCliente->enderecoClienteGalaxpay->numero}}">
                    </div>                    
                    <div class="col-md-5">
                        <label class="fw-bold" for="bairroClienteGalaxPay">Bairro</label>
                        <input class="form-control" name="bairroClienteGalaxPay" id="bairroClienteGalaxPay" type="text" value="{{$galaxPayCliente->enderecoClienteGalaxpay->bairro}}">
                    </div>                    
                </div>
                <div class="row py-2">
                    <div class="col-md-4">
                        <label class="fw-bold" for="cepClienteGalaxPay">CEP</label>
                        <input class="form-control" name="cepClienteGalaxPay" id="cepClienteGalaxPay" type="text" value="{{$galaxPayCliente->enderecoClienteGalaxpay->cep}}">
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold" for="cidadeClienteGalaxPay">Cidade</label>
                        <input class="form-control" name="cidadeClienteGalaxPay" id="cidadeClienteGalaxPay" type="text" value="{{$galaxPayCliente->enderecoClienteGalaxpay->cidade}}">
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold" for="estadoClienteGalaxPay">Estado</label>
                        <input class="form-control" name="estadoClienteGalaxPay" id="estadoClienteGalaxPay" type="text" value="{{$galaxPayCliente->enderecoClienteGalaxpay->estado}}">
                    </div>
                    <div class="col-md-12">
                        <label class="fw-bold" for="complementoClienteGalaxPay">Complemento</label>
                        <input class="form-control" name="complementoClienteGalaxPay" id="complementoClienteGalaxPay" type="text" value="{{$galaxPayCliente->enderecoClienteGalaxpay->complemento}}">
                    </div>
                </div>
            @endif
        <div class="row py-2">
            <div class="col-md-12">
                <label class="fw-bold" for="comentarioEdit">Comentário(Obrigatório):</label>
                <textarea class="form-control" name="comentarioEdit" id="comentarioEdit" rows="3" placeholder="Escreva o motivo da alteração..." value='{{old('comentarioEdit')}}' required></textarea>
            </div>
        </div>
        @if (count($galaxPayCliente->clientesDependentesGalaxpay) > 0)
            <div class="row py-2">
                <div class="col-md-12">
                    <h5>Dependentes:</h5>
                    <hr>
                        <div class="row pb-2 fw-bold">
                            <div class="col-md-2">
                                Matricula dependente
                            </div>
                            <div class="col-md-6">
                                Nome dependente
                            </div>
                            <div class="col-md-2">
                                CPF dependente
                            </div>
                            <div class="col-md-2">
                                Data de nascimento
                            </div>
                        </div>
                    @foreach ($galaxPayCliente->clientesDependentesGalaxpay as $clienteDependenteGalaxpay)
                        <div class="row py-1">
                            <div class="col-md-2">
                                <input class="form-control" name="matriculaDependenteGalaxpay{{$clienteDependenteGalaxpay->id}}" id="matriculaDependenteGalaxpay{{$clienteDependenteGalaxpay->id}}" value="{{$clienteDependenteGalaxpay->matricula_cliente_dependente}}" readonly>
                            </div>
                            <div class="col-md-6">
                                <input class="form-control" name="nomeDependenteGalaxpay{{$clienteDependenteGalaxpay->id}}" id="nomeDependenteGalaxpay{{$clienteDependenteGalaxpay->id}}" value="{{$clienteDependenteGalaxpay->nome_cliente_dependente}}" readonly>
                            </div>
                            <div class="col-md-2">
                                <input class="form-control" name="cpfDependenteGalaxpay{{$clienteDependenteGalaxpay->id}}" id="cpfDependenteGalaxpay{{$clienteDependenteGalaxpay->id}}" value="{{$clienteDependenteGalaxpay->cpf_cliente_dependente}}" readonly>
                            </div>
                            <div class="col-md-2">
                                <input class="form-control" name="nascimentoDependenteGalaxpay{{$clienteDependenteGalaxpay->id}}" id="nascimentoDependenteGalaxpay{{$clienteDependenteGalaxpay->id}}" value="{{$clienteDependenteGalaxpay->nascimento_cliente_dependente}}" readonly>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </form>
    <div class="row pt-4 justify-content-end">
        <div class="col-md-3">
            <button class="btn btn-info text-white fw-bold w-100" data-bs-toggle="modal" data-bs-target="#modalConfirmacao">Salvar Alterações</button>
        </div>
    </div>
    <input type="hidden" value="{{$idFormulario = 'formEditClienteGalaxPay'}}">
    <x-modals.confirmacao :idFormulario="$idFormulario">
        <div class="row">
            Confirmar está operação implica em alterações na plataforma da Galaxpay, deseja confirmar ?
        </div>
    </x-modals.confirmacao>
</x-clientes.informacoesCliente>