<div class="modal fade" id="modalHistoricoAtendimento" tabindex="-1" aria-labelledby="formModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase" id="formModalTitle">Histórico de atendimento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped">
                    <thead>
                        <tr class="fw-bold">
                            <td>Usuario alteração</td>
                            <td>Observação</td>
                            <td>Data</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($galaxPayCliente->historicoAtendimentoCliente as $historicoAtendimento)
                            <tr>
                                <td>
                                    {{$historicoAtendimento->nome_usuario_alteracao}}
                                </td>
                                <td>
                                    {{$historicoAtendimento->observacao_alteracao}}
                                </td>
                                <td>
                                    {{date('d/m/Y H:i', strtotime($historicoAtendimento->created_at))}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>