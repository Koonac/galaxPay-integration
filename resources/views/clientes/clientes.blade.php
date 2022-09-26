<x-layout.layoutNavBar>
    <script type="text/javascript" src="{{asset('js/clientes/clientes.js')}}"></script>
    <div class="container-fluid">
        <div class="row bg-light shadow border rounded p-4">
            <div class="col-md-12">
                <div class="input-group">
                    <select class="form-select" id="searchOption">
                        <option value="myIds">Meu ID</option>
                        <option value="galaxPayIds">GalaxPay ID</option>
                        <option value="documents" selected>CPF ou CNPJ</option>
                      </select>
                    <input type="text" class="form-control w-50" id='inputPesquisaCliente' placeholder="Pesquisa por cliente" value="084.367.787-23">
                    <button type="button" id='btnPesquisarCliente' class="btn btn-info text-white fw-bold">Pesquisar</button>
                </div>
            </div>
        </div>
    </div>
    {{-- INCLUINDO MENSAGENS DE RETORNO --}}
    <x-messages.returnMessages>
    </x-messages.returnMessages>
    
    {{-- LISTANDO CLIENTES --}}
    <div id='listClientes'>
        @if (!isset($galaxPayClientes))
            @include('components.listas.listClientes')
        @else

        @endif
    </div>
</x-layout.layoutNavBar>