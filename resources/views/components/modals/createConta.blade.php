<div class="modal fade" id="modalCreateConta" tabindex="-1" aria-labelledby="formModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase" id="formModalTitle">ADICIONAR CONTA</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('financeiro.criarConta')}}" method="POST" id="formModalCreateConta">
                    @csrf
                    <div class="row py-2">
                        <div class="col-12">
                            <label class="form-label fw-bold" for="descricaoConta">Descrição da conta</label>
                            <input class="form-control" type="text" name="descricaoConta" id="descricaoConta" placeholder="" required>                  
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formModalCreateConta" class="btn btn-info text-white fw-bold">Cadastrar</button>
            </div>
        </div>
    </div>
</div>