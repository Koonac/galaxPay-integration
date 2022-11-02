<div class="modal fade" id="modalDespesa" tabindex="-1" aria-labelledby="formModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase" id="formModalTitle">Adicionar despesa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('financeiro.adicionarDespesa', $caixaFinanceiro)}}" method="POST" id="formModalDespesa">
                    @method('POST')
                    @csrf
                    {{-- CAMPOS DO MODAL --}}
                    <div class="row py-2">
                        <div class="col-12">
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" class="form-control text-end decimalBrasileiro2Digitos" name="valorDespesa" id="valorDespesa" required>
                            </div>
                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-12">
                            <label class="fw-bold" for="observacaoDespesa">Observação do despesa:</label>
                            <textarea class="form-control" name="observacaoDespesa" id="observacaoDespesa" placeholder="Escreva uma observação para o despesa..."></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="formModalDespesa" class="btn btn-info text-white fw-bold">Adicionar</button>
            </div>
        </div>
    </div>
</div>