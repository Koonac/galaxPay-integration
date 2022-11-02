<x-layout.layoutNavBar>
    <script type="text/javascript" src="{{asset('js/clientes/clienteStatus.js')}}"></script>
        <div class="container-fluid">
            <div class="row bg-light shadow border rounded p-4">
                <div class="col-md-12">
                    <div class="input-group">
                        <select class="form-select" id="searchOption">
                            <option value="matricula" selected>Matricula</option>
                            <option value="cpfCnpj">CPF ou CNPJ</option>
                        </select>
                        <input type="text" class="form-control w-50" id='inputPesquisaCliente' placeholder="Pesquisa por cliente">
                        <button type="button" id='btnPesquisarCliente' class="btn btn-info text-white fw-bold">Pesquisar</button>
                    </div>
                </div>
            </div>
        </div>
    {{-- INCLUINDO MENSAGENS DE RETORNO --}}
    <x-messages.returnMessages>
    </x-messages.returnMessages>
    <div id='clienteStatus'>

    </div>
</x-layout.layoutNavBar>