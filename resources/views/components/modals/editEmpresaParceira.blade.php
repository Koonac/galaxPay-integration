<div class="modal fade" id="modalEditEmpresaParceira{{$empresaParceira->id}}" tabindex="-1" aria-labelledby="formModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase" id="formModalTitle">ADICIONAR EMPRESA PARCEIRA</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('empresasParceiras.edit', $empresaParceira->id)}}" method="POST" id="formModalEditEmpresaParceira{{$empresaParceira->id}}">
                    @method('PUT')
                    @csrf
                    <div class="row py-2">
                        <div class="col-4">
                            <label class="form-label fw-bold" for="razaoSocial">Razão social</label>
                            <input class="form-control" type="text" name="razaoSocial" id="razaoSocial" value="{{$empresaParceira->razao_social}}" required>                  
                        </div>
                        <div class="col-4">
                            <label class="form-label fw-bold" for="nomeFantasia">Nome fantasia</label>
                            <input class="form-control" type="text" name="nomeFantasia" id="nomeFantasia" value="{{$empresaParceira->nome_fantasia}}">                  
                        </div>
                        <div class="col-4">
                            <label class="form-label fw-bold" for="cpfCnpj">CPF/CNPJ</label>
                            <input class="form-control cnpjMask" type="text" name="cpfCnpj" id="cpfCnpj" value="{{$empresaParceira->cpf_cnpj}}" required>                  
                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-6">
                            <label class="form-label fw-bold" for="telefone1Empresa">Telefone 1</label>
                            <input class="form-control telefoneMask2" type="text" name="telefone1Empresa" id="telefone1Empresa" value="{{$empresaParceira->telefone_1}}">                  
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold" for="telefone2Empresa">Telefone 2</label>
                            <input class="form-control telefoneMask2" type="text" name="telefone2Empresa" id="telefone2Empresa" value="{{$empresaParceira->telefone_2}}">                  
                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-6">
                            <label class="form-label fw-bold" for="emailLogin">Email da empresa</label>
                            <input class="form-control" type="text" name="emailLogin" id="emailLogin" value="{{$empresaParceira->email}}" required>                  
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold" for="userLogin">Login da Empresa</label>
                            <input class="form-control" type="text" name="userLogin" id="userLogin" value="{{$empresaParceira->login}}" required>                  
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formModalEditEmpresaParceira{{$empresaParceira->id}}" class="btn btn-warning text-white fw-bold">Salvar</button>
            </div>
        </div>
    </div>
</div>