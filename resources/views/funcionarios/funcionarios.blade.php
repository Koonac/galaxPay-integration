<x-layout.layoutNavBar>
    <script type="text/javascript" src="{{asset('js/funcionarios/funcionarios.js')}}"></script>
    <div class="container-fluid">
        <div class="row bg-light shadow border rounded p-4">
            <div class="col-md-12 text-end">
               <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCreateFuncionario"><i class="fa-solid fa-plus"></i>Adicionar funcionário</button>
            </div>
        </div>
    </div>

    <x-messages.returnMessages>
    </x-messages.returnMessages>
    
    {{-- LISTANDO EMPRESAS --}}
    @include('components.listas.listFuncionarios')
    
    <x-modals.createFuncionario>
    </x-modals.createFuncionario>

</x-layout.layoutNavBar>




