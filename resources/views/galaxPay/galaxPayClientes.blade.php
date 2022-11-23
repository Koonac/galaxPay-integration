<x-layout.layoutNavBar>
    <script type="text/javascript" src="{{asset('js/galaxpay/galaxPayClientes.js')}}"></script>
    <div class="container-fluid">
        <div class="row bg-light shadow border rounded p-4">
            <div class="col-md-12">
                <div class="input-group">
                    <select class="form-select" id="searchOption">
                        <option value="myIds">Meu ID</option>
                        <option value="galaxPayIds">GalaxPay ID</option>
                        <option value="documents" selected>CPF ou CNPJ</option>
                      </select>
                    <input type="text" class="form-control w-50" id='inputPesquisaCliente' placeholder="Pesquisa por cliente">
                    <button type="button" id='btnImportarClienteGalaxPay' class="btn btn-info text-white fw-bold">Importar</button>
                    <button type="button" class="btn btn-info text-white fw-bold dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="visually-hidden">Opções extras</span>
                      </button>
                      <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a href="{{route('clientes.criarClienteGalaxPay')}}" class="dropdown-item"><i class="fa-solid fa-plus"></i> Criar cliente</a>
                        </li>
                      </ul>
                </div>
            </div>
        </div>
    </div>
    <div id='returnMessageImportacaoCliente'>

    </div>
    {{-- INCLUINDO MENSAGENS DE RETORNO --}}
    <x-messages.returnMessages>
    </x-messages.returnMessages>
    
    {{-- LISTANDO CLIENTES --}}
    <div id='listGalaxPayClientes'>

    </div>
</x-layout.layoutNavBar>