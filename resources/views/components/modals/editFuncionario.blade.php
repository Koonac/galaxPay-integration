<div class="modal fade" id="modalEditFuncionario{{$funcionario->id}}" tabindex="-1" aria-labelledby="formModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase" id="formModalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('funcionarios.edit', $funcionario)}}" method="POST" id="formModalEditFuncionario{{$funcionario->id}}">
                    @method('PUT')
                    @csrf
                    <div class="row py-2">
                        <div class="col-3">
                            <label class="form-label fw-bold" for="nomeFuncionario">Nome</label>
                            <input class="form-control" type="text" name="nomeFuncionario" id="nomeFuncionario" value="{{$funcionario->name}}" required>                  
                        </div>
                        <div class="col-3">
                            <label class="form-label fw-bold" for="cpfCnpj">CPF</label>
                            <input class="form-control cpfMask" type="text" name="cpfCnpj" id="cpfCnpj" value="{{$funcionario->cpf_cnpj}}" required>                  
                        </div>
                        <div class="col-3">
                            <label class="form-label fw-bold" for="telefone1Funcionario">Telefone 1</label>
                            <input class="form-control telefoneMask2" type="text" name="telefone1Funcionario" id="telefone1Funcionario" value="{{$funcionario->telefone_1}}">                  
                        </div>
                        <div class="col-3">
                            <label class="form-label fw-bold" for="telefone2Funcionario">Telefone 2</label>
                            <input class="form-control telefoneMask2" type="text" name="telefone2Funcionario" id="telefone2Funcionario" value="{{$funcionario->telefone_2}}">                  
                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-6">
                            <label class="form-label fw-bold" for="emailLogin">Email da funcionário</label>
                            <input class="form-control" type="text" name="emailLogin" id="emailLogin" value="{{$funcionario->email}}" required>                  
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold" for="userLogin">Login da Funcionario</label>
                            <input class="form-control" type="text" name="userLogin" id="userLogin" value="{{$funcionario->login}}" required>                  
                        </div>
                    </div>
                    <h6 class="mt-2">PERMISSÕES DO USUÁRIO</h6>
                    <hr class="px-2 m-0">
                    <div class="row py-2">
                        <div class="col-md-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="permitirClientes" name="permitirClientes" value='S' {{($funcionario->funcionarioPermissoes->acesso_clientes == 'S') ? 'Checked' : ''}}>
                                <label class="form-check-label" for="permitirClientes">Permitir acesso a clientes</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="permitirFinanceiro" name="permitirFinanceiro" value='S' {{($funcionario->funcionarioPermissoes->acesso_financeiro == 'S') ? 'Checked' : ''}}>
                                <label class="form-check-label" for="permitirFinanceiro">Permitir acesso ao financeiro</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="permitirEmpresas" name="permitirEmpresas" value='S' {{($funcionario->funcionarioPermissoes->acesso_empresas == 'S') ? 'Checked' : ''}}>
                                <label class="form-check-label" for="permitirEmpresas">Permitir acesso a empresas</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="permitirGalaxPay" name="permitirGalaxPay" value='S' {{($funcionario->funcionarioPermissoes->acesso_galaxpay == 'S') ? 'Checked' : ''}}>
                                <label class="form-check-label" for="permitirGalaxPay">Permitir acesso a galaxPay</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formModalEditFuncionario{{$funcionario->id}}" class="btn btn-info text-white fw-bold">Salvar</button>
            </div>
        </div>
    </div>
</div>