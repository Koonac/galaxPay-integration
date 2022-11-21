<x-layout.layoutNavBar>
    <script type="text/javascript" src="{{asset('js/clientes/clientes.js')}}"></script>

    <div class="container-fluid bg-light shadow border rounded p-4">
        {{-- INCLUINDO COMPONENTE DE ALERT MENSAGENS --}}
        <x-messages.returnMessages>
        </x-messages.returnMessages>
        <form action="{{route('galaxPay.criarClienteGalaxPay')}}" id="formCriarClienteGalaxPay" method="POST">
            @csrf
            <div class="row py-2">
                <div class="col-md-6">
                    <label class="fw-bold" for="nomeCliente">Nome Cliente</label>
                    <input class="form-control" name="nomeCliente" id="nomeCliente" type="text" value="{{old('nomeCliente')}}">
                </div>
                <div class="col-md-6">
                    <label class="fw-bold" for="cpfCnpjCliente">CPF/CNPJ</label>
                    <input class="form-control cnpjMask" name="cpfCpnjCliente" id="cpfCnpjCliente" type="text" value="{{old('cpfCpnjCliente')}}">
                </div>
            </div>
            <div class="row py-2">
                <div class="col-md-6">
                    <label class="fw-bold" for="emailCliente1">Emails:</label>
                    <input class="form-control mb-1" name="emailCliente1" id="emailCliente1" type="text" value="{{old('emailCliente1')}}">
                    <input class="form-control" name="emailCliente2" id="emailCliente2" type="text" value="{{old('emailCliente2')}}">
                </div>
                <div class="col-md-6">
                    <label class="fw-bold" for="telefoneCliente1">Telefones:</label>
                    <input class="form-control telefoneMask2 mb-1" name="telefoneCliente1" id="telefoneCliente1" type="text" value="{{old('telefoneCliente1')}}">
                    <input class="form-control telefoneMask2" name="telefoneCliente2" id="telefoneCliente2" type="text" value="{{old('telefoneCliente2')}}">
                </div>
            </div>
                <h5>Endereço:</h5>
                <div class="row py-2">
                    <div class="col-md-5">
                        <label class="fw-bold" for="logradouroEnderecoCliente">Logradouro</label>
                        <input class="form-control" name="logradouroEnderecoCliente" id="logradouroEnderecoCliente" type="text" value="{{old('logradouroEnderecoCliente')}}">
                    </div>                    
                    <div class="col-md-2">
                        <label class="fw-bold" for="numeroEnderecoCliente">Nº</label>
                        <input class="form-control" name="numeroEnderecoCliente" id="numeroEnderecoCliente" type="text" value="{{old('numeroEnderecoCliente')}}">
                    </div>                    
                    <div class="col-md-5">
                        <label class="fw-bold" for="bairroEnderecoCliente">Bairro</label>
                        <input class="form-control" name="bairroEnderecoCliente" id="bairroEnderecoCliente" type="text" value="{{old('bairroEnderecoCliente')}}">
                    </div>                    
                </div>
                <div class="row py-2">
                    <div class="col-md-4">
                        <label class="fw-bold" for="cepEnderecoCliente">CEP</label>
                        <input class="form-control cepMask" name="cepEnderecoCliente" id="cepEnderecoCliente" type="text" value="{{old('cepEnderecoCliente')}}">
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold" for="cidadeEnderecoCliente">Cidade</label>
                        <input class="form-control" name="cidadeEnderecoCliente" id="cidadeEnderecoCliente" type="text" value="{{old('cidadeEnderecoCliente')}}">
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold" for="estadoEnderecoCliente">Estado</label>
                        <input class="form-control" name="estadoEnderecoCliente" id="estadoEnderecoCliente" type="text" value="{{old('estadoEnderecoCliente')}}">
                    </div>
                    <div class="col-md-12">
                        <label class="fw-bold" for="complementoEnderecoCliente">Complemento</label>
                        <input class="form-control" name="complementoEnderecoCliente" id="complementoEnderecoCliente" type="text" value="{{old('complementoEnderecoCliente')}}">
                    </div>
                </div>
                <div class="row py-2">
                    <h5>Dependentes:</h5>
                    <hr>
                    <div class="col-md-12" id="divDependentes">
                        <div class="row py-1 linhaDependente" id='linhaDependente'>
                            <div class="col-md-4">
                                <label class="fw-bold" for="nomeDependente">Nome dependente</label>
                                <input class="form-control" name="nomeDependente[]" id="nomeDependente" value="">
                            </div>
                            <div class="col-md-4">
                                <label class="fw-bold" for="cpfDependente">CPF dependente</label>
                                <input class="form-control cpfMask" name="cpfDependente[]" id="cpfDependente" value="">
                            </div>
                            <div class="col-md-3">
                                <label class="fw-bold" for="nascimentoDependente">Data de nascimento</label>
                                <input class="form-control dataBrasileiraDDMMYYYY" name="nascimentoDependente[]" id="nascimentoDependente" value="">
                            </div>
                            <div class="col-md-1 d-flex  align-items-end">
                                <button type="button" class="btn btn-success btn-lg fa-solid fa-plus" id="btnAdicionaNovoDependente"></button>                                
                            </div>
                        </div>
                    </div>
                </div>
        </form>
        <div class="row pt-4 justify-content-end">
            <div class="col-md-3">
                <button type="submit" class="btn btn-info text-white fw-bold w-100" form="formCriarClienteGalaxPay" >Cadastrar cliente</button>
            </div>
        </div>
    </div>
</x-layout.layoutNavBar>