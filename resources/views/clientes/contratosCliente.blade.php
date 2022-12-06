<x-clientes.informacoesCliente :galaxPayCliente="$galaxPayCliente">
    <ul class="nav nav-tabs mt-4">
        <li>
            <a class="nav-link link-info" href="{{route('clientes.dados', [$galaxPayCliente])}}">Dados</a>
        </li>
        <li>
            <a class="nav-link link-info active" href="{{route('clientes.contratos', [$galaxPayCliente])}}">Contratos</a>
        </li>
        <li>
            <a class="nav-link link-info" href="{{route('clientes.transacoes', [$galaxPayCliente])}}">Transações</a>
        </li>
    </ul>

@if (count($galaxPayCliente->contratos) <= 0)
    <div class="alert alert-warning shadow my-4">
        Nenhum contrato encontrado.
    </div>
@else
    <div class="row p-4">
        <div class="col-md-12 table-responsive">
            <h3>Contratos</h3>
            <hr>
            <table id="contratosTable" class="table table-striped">
                <thead>
                    <tr class="fw-bold">
                        <td width='5%'>#</td>
                        <td width='10%' align="right">Valor</td>
                        <td width='50%'>Periodicidade</td>
                        <td width='10%'>Duração</td>
                        <td width='10%'>Primeira transação</td>
                        <td width='10%'>Status</td>
                        <td width='5%'></td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($galaxPayCliente->contratos as $contrato)
                        <tr>
                            <td>{{$contrato->codigo_contrato_galaxpay}}</td>
                            <td align="right">R$ {{str_replace(' ', ',', str_replace(',', '.', str_replace('.', ' ', $contrato->valor_contrato)))}}</td>
                            <td>{{$contrato->periodicidade_pagamento}}</td>
                            <td>{{$contrato->duracao_contrato}}</td>
                            <td>{{date('d/m/Y', strtotime($contrato->primeira_data_pagamento))}}</td>
                            <td>{{$contrato->status}}</td>
                            <td>
                                <button class="btn btn-danger fw-bold" data-bs-toggle='modal' data-bs-target='#modalConfirmacaoExclusaoContrato{{$contrato->id}}'>Cancelar</button>
                            </td>
                            {{-- MODAL CONFIRMA EXCLUSÃO --}}
                            <div class="modal fade" id="modalConfirmacaoExclusaoContrato{{$contrato->id}}" tabindex="-1" aria-labelledby="formModalTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-uppercase" id="formModalTitle">Confirmação</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Deseja confirma o cancelamento do contrato Nº <strong>{{$contrato->codigo_contrato_galaxpay}}</strong> ?
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                                            <a class="btn btn-success" href="{{route('galaxPay.cancelarContrato', $contrato)}}">Confirmar</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
</x-clientes.informacoesCliente>