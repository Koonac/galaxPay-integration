@if (count($empresasParceiras) <= 0)
    <div class="alert alert-warning shadow mt-2">
        Nenhuma empresa cadastrada.
    </div>
@else
<script type="text/javascript" src="{{asset('js/empresasParceiras/listEmpresasParceiras.js')}}"></script>
    <div class="container-fluid mt-4">
        <div class="row bg-light shadow border rounded p-4">
            <div class="col-md-12 table-responsive">
                <table id="empresasParceirasTable" class="table table-striped">
                    <thead>
                        <tr class="fw-bold">
                            <td width='5%'>#</td>
                            <td width='30%'>Razão Social</td>
                            <td width='25%'>Nome Fantasia</td>
                            <td width='10%'>CPF/CNPJ</td>
                            <td width='10%'>Telefone</td>
                            <td width='10%'>Criado</td>
                            <td width='10%'></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($empresasParceiras as $empresaParceira)
                            <tr>
                                <td class='border'>
                                    {{$empresaParceira->id}}
                                </td>
                                <td class='border'>
                                    {{$empresaParceira->razao_social}}
                                </td>
                                <td class='border'>
                                    {{$empresaParceira->nome_fantasia}}
                                </td>
                                <td class='border'>
                                    <p class="cnpjMask"> {{$empresaParceira->cpf_cnpj}} </p>
                                </td>
                                <td class='border'>
                                    <p class="telefoneMask"> {{$empresaParceira->telefone_1}} </p>
                                </td>
                                <td class='border'>
                                    {{date('d/m/Y H:i', strtotime($empresaParceira->created_at))}}
                                </td>
                                <td>
                                    <table width='100%'>
                                        <tr>
                                            <td align="right">
                                                <button class="btn btn-warning text-white fw-bold" data-bs-toggle='modal' data-bs-target='#modalEditEmpresaParceira{{$empresaParceira->id}}'>Editar</button>
                                            </td>
                                            <td align="right">
                                                <button class="btn btn-danger fw-bold" data-bs-toggle='modal' data-bs-target='#modalConfirmacaoExclusaoEmpresa{{$empresaParceira->id}}'>Excluir</button>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            {{-- MODAL DE EDIT EMPRESA --}}
                            @include('components.modals.editEmpresaParceira')

                            {{-- MODAL CONFIRMA EXCLUSÃO --}}
                            <div class="modal fade" id="modalConfirmacaoExclusaoEmpresa{{$empresaParceira->id}}" tabindex="-1" aria-labelledby="formModalTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-uppercase" id="formModalTitle">Confirmação</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Deseja confirma a exclusão da empresa <strong>{{$empresaParceira->razao_social}}</strong> ?
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                                            <a class="btn btn-success" href="{{route('empresasParceiras.delete', $empresaParceira->id)}}">Confirmar</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
