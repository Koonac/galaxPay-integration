<div class="modal fade" id="modalCreateFuncionario" tabindex="-1" aria-labelledby="formModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase" id="formModalTitle">ADICIONAR FUNCIONÁRIO</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('funcionarios.cadastro')}}" method="POST" id="formModalCreateFuncionario">
                    @csrf
                    <div class="row py-2">
                        <div class="col-12">
                            <label class="form-label fw-bold" for="emailLogin">Email da funcionário</label>
                            <input class="form-control" type="text" name="emailLogin" id="emailLogin" placeholder="" required>                  
                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-md-4">
                            <label class="form-label fw-bold" for="nomeFuncionario">Nome funcionário</label>
                            <input class="form-control" type="text" name="nomeFuncionario" id="nomeFuncionario" placeholder="">                  
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold" for="cpfFuncionario">CPF funcionário</label>
                            <input class="form-control cpfMask" type="text" name="cpfFuncionario" id="cpfFuncionario" placeholder="" required>                  
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold" for="telefone1Funcionario">Telefone 1</label>
                            <input class="form-control cpfMask" type="text" name="telefone1Funcionario" id="telefone1Funcionario" placeholder="">                  
                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-6">
                            <label class="form-label fw-bold" for="userLogin">Login para acesso</label>
                            <input class="form-control" type="text" name="userLogin" id="userLogin" placeholder="" required>                  
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold" for="userPass">Senha para acesso</label>
                            <input class="form-control" type="password" name="userPass" id="userPass" placeholder="" required>                  
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formModalCreateFuncionario" class="btn btn-info text-white fw-bold">Cadastrar</button>
            </div>
        </div>
    </div>
</div>