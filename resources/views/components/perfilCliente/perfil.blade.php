<x-layout.layoutNavBar>

    {{-- INCLUINDO COMPONENTE DE ALERT MENSAGENS --}}
    <x-messages.returnMessages>
    </x-messages.returnMessages>

    <div class="container-fluid bg-light shadow border rounded p-4">
        <div class="row ">
            <div class="col-12">
                <h4 class="fw-bold text-uppercase">Perfil</h4>
            </div>
        </div>
        <hr>
        <form action="{{route('editUser', Auth::user())}}" method="POST">
            @method('PUT')
            @csrf

            {{$slot}}

            <div class="row pt-4 justify-content-end">
                <div class="col-md-3 text-end mb-2">
                    <button type="button" class="btn btn-warning fw-bold text-white w-100" data-bs-toggle="modal" data-bs-target="#modalEditPassword">Alterar senha</button>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-info text-white fw-bold w-100" type="submit">Salvar</button>
                </div>
            </div>
        </form>
        {{-- INCLUINDO COMPONENTE DE MODAL --}}
        @include('components.modals.editPassword')
    </div>


</x-layout.layoutNavBar>