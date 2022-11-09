<x-layout.layoutNavBar>
    <div class="container-fluid bg-light shadow border rounded p-4">
        <form action="{{route('editUser', $user)}}" method="POST">
            @method('PUT')
            @csrf
            <div class="row ">
                <div class="col-12">
                    <h4 class="fw-bold text-uppercase">Dados pessoais</h4>
                </div>
            </div>
            <hr>
            <div class="row py-2">
                <div class="col-md-4">
                    <label class="fw-bold" for="razaoSocial">Razão social</label>
                    <input class="form-control" name="razaoSocial" id="razaoSocial" type="text" value="{{$user->razao_social}}">
                </div>
                <div class="col-md-4">
                    <label class="fw-bold" for="nomeFantasia">Nome fantasia</label>
                    <input class="form-control" name="nomeFantasia" id="nomeFantasia" type="text" value="{{$user->nome_fantasia}}">
                </div>
                <div class="col-md-4">
                    <label class="fw-bold" for="cpfCnpj">CPF/CNPJ</label>
                    <input class="form-control cnpjMask" name="cpfCnpj" id="cpfCnpj" type="text" value="{{$user->cpf_cnpj}}">
                </div>
            </div>
            <div class="row py-2">
                <div class="col-md-4">
                    <label class="fw-bold" for="nomePerfil">Nome</label>
                    <input class="form-control" name="nomePerfil" id="nomePerfil" type="text" value="{{$user->name}}">
                </div>
                <div class="col-md-4">
                    <label class="fw-bold" for="telefone1">Telefone 1</label>
                    <input class="form-control telefoneMask2" name="telefone1" id="telefone1" type="text" value="{{$user->telefone_1}}">
                </div>
                <div class="col-md-4">
                    <label class="fw-bold" for="telefone2">Telefone 2</label>
                    <input class="form-control telefoneMask2" name="telefone2" id="telefone2" type="text" value="{{$user->telefone_2}}">
                </div>
            </div>
            <div class="row py-2">
                <div class="col-md-6">
                    <label class="fw-bold" for="usuarioPerfil">Usuario</label>
                    <input class="form-control" name="usuarioPerfil" id="usuarioPerfil" type="text" value="{{$user->login}}">
                </div>
                <div class="col-md-6">
                    <label class="fw-bold" for="emailPerfil">E-mail</label>
                    <input class="form-control" name="emailPerfil" id="emailPerfil" type="text" value="{{$user->email}}">
                </div>
            </div>
            @can('isAdmin')
                <div class="row pt-4">
                    <div class="col-12">
                        <h4 class="fw-bold text-uppercase">INFORMAÇÕES DE CONEXÃO</h4>
                    </div>
                </div>
                <hr>
                <div class="row py-2">
                    <div class="col-md-6">
                        <label class="fw-bold" for="galaxId">GalaxID</label>
                        <input class="form-control" name="galaxId" id="galaxId" type="text" aria-describedby="helpGalaxConection" value="{{isset($user->galaxPayParametros->galax_id) ? $user->galaxPayParametros->galax_id : ''}}">
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold" for="galaxHash">Galaxhash</label>
                        <input class="form-control" name="galaxHash" id="galaxHash" type="text" aria-describedby="helpGalaxConection" value="{{isset($user->galaxPayParametros->galax_hash) ? $user->galaxPayParametros->galax_hash : ''}}">
                    </div>
                    <div id='helpGalaxConection' class="form-text">
                        Galax Id e Galax Hash são fornecidos pelo suporte de integração da Galax Pay.
                        Caso ainda não tenha essas chaves. <a href="https://docs.galaxPay.com.br/suporte" target="_blank">Clique aqui.</a>
                    </div>
                </div>
            @endcan

            {{-- INCLUINDO COMPONENTE DE ALERT MENSAGENS --}}
            <x-messages.returnMessages>
            </x-messages.returnMessages>

            <div class="row pt-4 justify-content-end">
                <div class="col-md-3 text-end mb-2">
                    <button type="button" class="btn btn-warning fw-bold text-white w-100" data-bs-toggle="modal" data-bs-target="#modalEditPassword">Alterar senha</button>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-info text-white fw-bold w-100" type="submit">Salvar</button>
                </div>
            </div>
            
        </form>
    </div>

    {{-- INCLUINDO COMPONENTE DE MODAL --}}
   @include('components.modals.editPassword')

</x-layout.layoutNavBar>