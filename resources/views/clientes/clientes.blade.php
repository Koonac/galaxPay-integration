<x-layout.layoutNavBar>
    <script type="text/javascript" src="{{asset('js/clientes/clientes.js')}}"></script>
    <div class="container-fluid mb-4">
        <div class="row bg-light shadow border rounded p-4">
            <div class="col-md-12 text-end">
               <a href="{{route('clientes.criarClienteGalaxPay')}}" class="btn btn-success"><i class="fa-solid fa-plus"></i>  Criar cliente</a>
            </div>
        </div>
    </div>

    {{-- INCLUINDO MENSAGENS DE RETORNO --}}
    <x-messages.returnMessages>
    </x-messages.returnMessages>

    {{-- LISTANDO CLIENTES --}}
    @include('components.listas.listClientes')
    
</x-layout.layoutNavBar>