<x-clientes.informacoesCliente :galaxPayCliente="$galaxPayCliente">
    <ul class="nav nav-tabs mt-4">
        <li>
            <a class="nav-link link-info" href="{{route('clientes.dados', [$galaxPayCliente])}}">Dados</a>
        </li>
        <li>
            <a class="nav-link link-info" href="{{route('clientes.contratos', [$galaxPayCliente])}}">Contratos</a>
        </li>
        <li>
            <a class="nav-link link-info active" href="{{route('clientes.transacoes', [$galaxPayCliente])}}">Transações</a>
        </li>
    </ul>

@if (count($galaxPayCliente->transacoesAtivas) <= 0)
    <div class="alert alert-warning shadow my-4">
        Nenhuma transação pendente.
    </div>
@else
    <div class="row p-4">
        <div class="col-md-12 table-responsive">
            <h3>Transações</h3>
            <hr>
            <table id="transacoesTable" class="table table-striped">
                <thead>
                    <tr class="fw-bold">
                        <td width='10%'>#</td>
                        <td width='10%'>Nº Contrato</td>
                        <td width='15%'>Vencimento</td>
                        <td width='15%' class="text-end">Valor</td>
                        <td width='25%'>Status</td>
                        <td width='25%' class="text-end">Pagamento</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($galaxPayCliente->transacoesAtivas as $transacao)
                        <tr>
                            <td>
                                {{$transacao->codigo_transacao_galaxpay}}
                            </td>
                            <td>
                                {{$transacao->codigo_contrato_galaxpay}}
                            </td>
                            <td>
                                {{date('d/m/Y', strtotime($transacao->data_pagamento_transacao))}}
                            </td>
                            <td align="right">
                                {{str_replace(' ', ',', str_replace(',', '.', str_replace('.', ' ', $transacao->valor_transacao)))}}
                            </td>
                            <td >
                                {{$transacao->descricao_status_transacao}}
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    {{-- BOTÃO PARA CHAMAR MODAL PARA RECEBIMENTO DE TRANSAÇÃO --}}
                                    <button class="btn btn-warning fw-bold text-white" data-bs-toggle='modal' data-bs-target='#modalConfirmacaoRecebimentoTransacao{{$transacao->id}}'>Receber</button>

                                    {{-- MODAL CONFIRMA RECEBIMENTO DE TRANSAÇÃO --}}
                                    <div class="modal fade" id="modalConfirmacaoRecebimentoTransacao{{$transacao->id}}" tabindex="-1" aria-labelledby="formModalTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-uppercase" id="formModalTitle">Confirmação</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-start">
                                                    Deseja confirma a recebimento da transação: <br><strong>Cód. {{$transacao->codigo_transacao_galaxpay}} - Vencimento: {{date('d/m/Y', strtotime($transacao->data_pagamento_transacao))}} - Valor: {{str_replace(' ', ',', str_replace(',', '.', str_replace('.', ' ', $transacao->valor_transacao)))}}</strong> ?
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                                                    <a class="btn btn-success" href="{{route('galaxPay.receberTransacoesPorTransacao', $transacao)}}">Confirmar</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- MENU DROPDOWN --}}
                                    <button class="btn btn-warning text-white" type="button" data-bs-toggle="dropdown">
                                        <i class="fa-solid fa-bars"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{$transacao->link_boleto_pagamento}}" target="__blank">Boleto</a></li>
                                        <li><a class="dropdown-item" href="{{$transacao->link_pagamento}}" target="__blank">Pagamento</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

</x-clientes.informacoesCliente>