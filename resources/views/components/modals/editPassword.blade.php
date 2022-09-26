<div class="modal fade" id="modalEditPassword" tabindex="-1" aria-labelledby="formModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase" id="formModalTitle">Alterar senha</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @can('isAdmin')
                    <form action="{{route('editPassword', $user->id)}}" method="POST" id="IdFormModal">
                @elsecan('isPartner')
                   <form action="{{route('empresasParceiras.editPassword', $user->id)}}" method="POST" id="IdFormModal">
                @endcan
                    @method('PUT')
                    @csrf
                    {{-- CAMPOS DO MODAL --}}
                    <div class="row py-2">
                        <div class="col-12">
                            <label class="fw-bold" for="oldPassword">Senha antiga:</label>
                            <input class="form-control" name="oldPassword" id="oldPassword" type="password" required placeholder="Digite a senha antiga...">
                        </div>
                    </div>
                    <hr>
                    <div class="row py-2">
                        <div class="col-12">
                            <label class="fw-bold" for="newPassword">Nova senha:</label>
                            <input class="form-control" name="newPassword" id="newPassword" type="password" required placeholder="Digite sua nova senha...">
                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-12">
                            <label class="fw-bold" for="confirmNewPassword">Confirma nova senha:</label>
                            <input class="form-control" name="confirmNewPassword" id="confirmNewPassword" type="password" required placeholder="Digite a nova senha novamente...">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="IdFormModal" class="btn btn-info text-white fw-bold">Salvar alterações</button>
            </div>
        </div>
    </div>
</div>