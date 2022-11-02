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
    
    @if (count($funcionarios) <= 0)
        <div class="alert alert-warning shadow mt-2">
            Nenhum registro encontrado.
        </div>
    @else
        <x-listas.listFuncionarios>
            @foreach ($funcionarios as $funcionario)
                <div class="row py-2 mb-1 rounded ">
                    <div class="col-md-1">
                        {{$funcionario->id}}
                    </div>
                    <div class="col-md-3">
                        {{$funcionario->name}}
                    </div>
                    <div class="col-md-2 cnpjMask">
                        {{$funcionario->cpf_cnpj}}
                    </div>
                    <div class="col-md-2">
                        {{$funcionario->email}}
                    </div>
                    <div class="col-md-2">
                        {{$funcionario->created_at}}
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-warning fw-bold text-white" data-bs-toggle="modal" data-bs-target="#modalPermissoesFuncionario">Permissoes  </button>
                        <a href="{{route('funcionarios.delete', $funcionario->id)}}" class="btn btn-danger fw-bold">Excluir</a>
                    </div>
                </div>
            @endforeach
        </x-listas.listFuncionarios>
    @endif
    
    <x-modals.createFuncionario>
    </x-modals.createFuncionario>

    <x-modals.permissoesFuncionario>
    </x-modals.permissoesFuncionario>

</x-layout.layoutNavBar>




