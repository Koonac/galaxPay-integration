@if (count($funcionarios) <= 0)
    <div class="alert alert-warning shadow mt-2">
        Nenhum funcionário cadastrado.
    </div>
@else
<script type="text/javascript" src="{{asset('js/funcionarios/listFuncionarios.js')}}"></script>
    <div class="container-fluid mt-4">
        <div class="row bg-light shadow border rounded p-4">
            <div class="col-md-12 table-responsive">
                <table id="funcionariosTable" class="table table-striped">
                    <thead>
                        <tr class="fw-bold">
                            <td width='10%'>#</td>
                            <td width='40%'>Nome</td>
                            <td width='15%'>CPF/CNPJ</td>
                            <td width='15%'>Telefone</td>
                            <td width='10%'>Criado</td>
                            <td width='10%'></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($funcionarios as $funcionario)
                            <tr>
                                <td class='border'>
                                    {{$funcionario->id}}
                                </td>
                                <td class='border'>
                                    {{$funcionario->name}}
                                </td>
                                <td class='border'>
                                    <p class="cpfMask"> {{$funcionario->cpf_cnpj}} </p>
                                </td>
                                <td class='border'>
                                    <p class="telefoneMask"> {{$funcionario->telefone_1}} </p>
                                </td>
                                <td class='border'>
                                    {{date('d/m/Y H:i', strtotime($funcionario->created_at))}}
                                </td>
                                <td>
                                    <table width='100%'>
                                        <tr>
                                            <td align="right">
                                                <button class="btn btn-warning fw-bold text-white" data-bs-toggle="modal" data-bs-target="#modalEditFuncionario{{$funcionario->id}}">Editar</button>
                                            </td>
                                            <td align="right">
                                                <button class="btn btn-danger fw-bold" data-bs-toggle='modal' data-bs-target='#modalConfirmacaoExclusaoFuncionario{{$funcionario->id}}'>Excluir</button>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            {{-- MODAL DE EDIT FUNCIONARIO --}}
                            @include('components.modals.editFuncionario')

                            {{-- MODAL CONFIRMA EXCLUSÃO --}}
                            <div class="modal fade" id="modalConfirmacaoExclusaoFuncionario{{$funcionario->id}}" tabindex="-1" aria-labelledby="formModalTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-uppercase" id="formModalTitle">Confirmação</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Deseja confirma a exclusão do funcionário <strong>{{$funcionario->name}}</strong> ?
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                                            <a class="btn btn-success" href="{{route('funcionarios.delete', $funcionario->id)}}">Confirmar</a>
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
