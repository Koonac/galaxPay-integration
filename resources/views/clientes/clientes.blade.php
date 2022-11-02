<x-layout.layoutNavBar>
    <script type="text/javascript" src="{{asset('js/clientes/clientes.js')}}"></script>

    {{-- INCLUINDO MENSAGENS DE RETORNO --}}
    <x-messages.returnMessages>
    </x-messages.returnMessages>

    {{-- LISTANDO CLIENTES --}}
    @include('components.listas.listClientes')
    
</x-layout.layoutNavBar>