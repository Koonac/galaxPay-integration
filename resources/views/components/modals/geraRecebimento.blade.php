<div class="modal fade" id="modalRecebimento" tabindex="-1" aria-labelledby="formModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase" id="formModalTitle">Adicionar recebimento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('caixa.adicionarRecebimento', $caixaFinanceiro)}}" method="POST" id="formModalRecebimento">
                    @method('POST')
                    @csrf
                    {{-- CAMPOS DO MODAL --}}
                    <div class="row py-2">
                        <div class="col-12">
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" class="form-control text-end decimalBrasileiro2Digitos" name="valorRecebimento" id="valorRecebimento" required>
                            </div>
                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-12">
                            <label class="fw-bold" for="observacaoRecebimento">Observação do recebimento:</label>
                            <textarea class="form-control" name="observacaoRecebimento" id="observacaoRecebimento" placeholder="Escreva uma observação para o recebimento..."></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formModalRecebimento" class="btn btn-info text-white fw-bold">Adicionar</button>
            </div>
        </div>
    </div>
</div>