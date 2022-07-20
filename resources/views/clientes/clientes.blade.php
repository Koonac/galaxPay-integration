<x-layout.layoutNavBar>
    <script type="text/javascript" src="{{asset('js/clientes/clientes.js')}}"></script>
    <div class="container-fluid">
        <div class="row bg-light shadow border rounded p-4">
            <div class="col-sm-7 col-md-8 col-lg-9">
                <div class="input-group">
                    <input type="text" class="form-control" id='inputPesquisaCliente' placeholder="Pesquise por cliente...">
                    <button type="button" id='btnPesquisarCliente' class="btn btn-info text-white fw-bold">Pesquisar</button>
                    <button type="button" class="btn btn-info text-white fw-bold d-block d-sm-none dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="visually-hidden">Opções</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Sincronizar lista</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-5 col-md-4 col-lg-3 d-none d-sm-block text-end">
                <a href="{{route('galaxpay.clientes')}}" class="btn btn-success text-white fw-bold w-100">Sincronizar lista</a>
            </div>
        </div>
    </div>
    {{-- INCLUINDO MENSAGENS DE RETORNO --}}
    <x-messages.returnMessages>
    </x-messages.returnMessages>
    
    <div id='listClientes'>
        @include('components.listas.listClientes')
    </div>
</x-layout.layoutNavBar>