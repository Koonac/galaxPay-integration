<x-layout.layoutNavBar>
    <script type="text/javascript" src="{{asset('js/financeiro/financeiro.js')}}"></script>
    <div class="container-fluid">
        <div class="row bg-light shadow border rounded p-4">
            <div class="col-md-12 text-end">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCreateConta"><i class="fa-solid fa-plus"></i> Criar conta</button>
            </div>
        </div>
        
        {{-- INCLUINDO MENSAGENS DE RETORNO --}}
        <x-messages.returnMessages>
        </x-messages.returnMessages>

        <x-modals.createConta>
        </x-modals.createConta>
    </div>
    
    @include('components.listas.listContas')

</x-layout.layoutNavBar>