<x-layout.layoutNavBar>
    <script type="text/javascript" src="{{asset('js/clientes/clientes.js')}}"></script>
    <div class="container-fluid">
        <div class="row bg-light shadow border rounded p-4">
            <div class="col-md-12">
                <div class="alert alert-info" role="alert">
                    Empresas parceiras poderão logar-se atraves do link ('/empresasParceiras/login')
                </div>
            </div>
            <div class="col-md-12 text-end">
               <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCreateEmpresaParceira"><i class="fa-solid fa-plus"></i> Adicionar empresa</button>
            </div>
        </div>
    </div>

    <x-messages.returnMessages>
    </x-messages.returnMessages>
    
    <x-listas.listEmpresasParceiras>
    </x-listas.listEmpresasParceiras>
    
    <x-modals.createEmpresaParceira>
    </x-modals.createEmpresaParceira>
</x-layout.layoutNavBar>




