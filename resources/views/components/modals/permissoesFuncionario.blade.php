<div class="modal fade" id="modalPermissoesFuncionario" tabindex="-1" aria-labelledby="formModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase" id="formModalTitle">PERMISSÕES DO USUÁRIO</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="formModalDespesa">
                    @method('POST')
                    @csrf
                    <div class="row py-2">
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="permitirClientes">
                                <label class="form-check-label" for="permitirClientes">Permitir acesso a clientes</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="permitirFinanceiro">
                                <label class="form-check-label" for="permitirFinanceiro">Permitir acesso ao financeiro</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="permitirEmpresas">
                                <label class="form-check-label" for="permitirEmpresas">Permitir acesso a empresas</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="permitirGalaxPay">
                                <label class="form-check-label" for="permitirGalaxPay">Permitir acesso a galaxPay</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="formModalDespesa" class="btn btn-info text-white fw-bold">Salvar</button>
            </div>
        </div>
    </div>
</div>