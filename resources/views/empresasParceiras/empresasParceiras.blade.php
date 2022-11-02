<x-layout.layoutNavBar>
    <script type="text/javascript" src="{{asset('js/empresasParceiras/empresasParceiras.js')}}"></script>
    <div class="container-fluid">
        <div class="row bg-light shadow border rounded p-4">
            <div class="col-md-12">
                <div class="input-group mb-3">
                    <input type="text" class="form-control disabled" aria-label="Clique para copiar o link" id="inputEmpresaParceira" name="inputEmpresaParceira" aria-describedby="btnEmpresaParceira"
                    
                    
                    readonly>
                    <button class="btn btn-outline-info" type="button" id="btnCopyEmpresaParceira"><i class="fa-solid fa-copy"></i></button>
                  </div>
            </div>
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




