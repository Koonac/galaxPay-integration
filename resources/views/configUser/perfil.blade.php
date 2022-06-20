<x-layout.layoutNavBar>
    <div class="container-fluid p-4">
        <form action="{{route('editUser', $user->id)}}" method="POST">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="col-12">
                    <h4 class="fw-bold text-uppercase">Dados pessoais</h4>
                </div>
            </div>
            <hr>
            <div class="row py-2">
                <div class="col-md-6">
                    <label class="fw-bold" for="empresaPerfil">Empresa</label>
                    <input class="form-control" name="empresaPerfil" id="empresaPerfil" type="text" readonly>
                </div>
                <div class="col-md-6">
                    <label class="fw-bold" for="cnpjPerfil">CNPJ</label>
                    <input class="form-control" name="cnpjPerfil" id="cnpjPerfil" type="text" readonly>
                </div>
            </div>
            <div class="row py-2">
                <div class="col-md-6">
                    <label class="fw-bold" for="nomePerfil">Nome</label>
                    <input class="form-control" name="nomePerfil" id="nomePerfil" type="text" value="{{$user->name}}">
                </div>
                <div class="col-md-6">
                    <label class="fw-bold" for="cpfPerfil">CPF</label>
                    <input class="form-control" name="cpfPerfil" id="cpfPerfil" type="text">
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
            <div class="row pt-4">
                <div class="col-12">
                    <h4 class="fw-bold text-uppercase">INFORMAÇÕES DE CONEXÃO</h4>
                </div>
            </div>
            <hr>
            <div class="row py-2">
                <div class="col-md-6">
                    <label class="fw-bold" for="galaxId">GalaxID</label>
                    <input class="form-control" name="galaxId" id="galaxId" type="text" aria-describedby="helpGalaxConection" value="">
                </div>
                <div class="col-md-6">
                    <label class="fw-bold" for="galaxHash">Galaxhash</label>
                    <input class="form-control" name="galaxHash" id="galaxHash" type="text" aria-describedby="helpGalaxConection" value="">
                </div>
                <div id='helpGalaxConection' class="form-text">
                    Galax Id e Galax Hash são fornecidos pelo suporte de integração da Galax Pay.
                    Caso ainda não tenha essas chaves. <a href="https://docs.galaxpay.com.br/suporte" target="_blank">Clique aqui.</a>
                </div>
            </div>

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
    <x-modals.formModal>
        {{-- DEFININDO ID DO MODAL PARA ABERTURA --}}
        <x-slot name="modalId">
            modalEditPassword
        </x-slot>
        {{-- DEFININDO TITULO DO MODAL --}}
        <x-slot name="title">
            Alterar senha
        </x-slot>
        {{-- DEFININDO URL DE ENVIO DO FORM --}}
        <x-slot name="route">
            {{route('editPassword', $user->id)}}
        </x-slot>
        
        <div class="row py-2">
            <div class="col-12">
                <label class="fw-bold" for="oldPassword">Senha antiga:</label>
                <input class="form-control" name="oldPassword" id="oldPassword" type="text" placeholder="Digite a senha antiga...">
            </div>
        </div>
        <hr>
        <div class="row py-2">
            <div class="col-12">
                <label class="fw-bold" for="newPassword">Nova senha:</label>
                <input class="form-control" name="newPassword" id="newPassword" type="text" placeholder="Digite sua nova senha...">
            </div>
        </div>
        <div class="row py-2">
            <div class="col-12">
                <label class="fw-bold" for="confirmNewPassword">Confirma nova senha:</label>
                <input class="form-control" name="confirmNewPassword" id="confirmNewPassword" type="text" placeholder="Digite a nova senha novamente...">
            </div>
        </div>
    </x-modals.formModal>

</x-layout.layoutNavBar>