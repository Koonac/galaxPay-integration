<x-layout.layoutNavBar>
    <script type="text/javascript" src="{{asset('js/empresasParceiras/empresasParceiras.js')}}"></script>
    <div class="container-fluid">
        <div class="row bg-light shadow border rounded p-4">
            <div class="col-md-12 text-end">
               <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCreateEmpresaParceira"><i class="fa-solid fa-plus"></i> Adicionar empresa</button>
            </div>
        </div>
    </div>

    <x-messages.returnMessages>
    </x-messages.returnMessages>

    {{-- LISTANDO EMPRESAS --}}
    @include('components.listas.listEmpresasParceiras')
    
    <x-modals.createEmpresaParceira>
    </x-modals.createEmpresaParceira>

</x-layout.layoutNavBar>




