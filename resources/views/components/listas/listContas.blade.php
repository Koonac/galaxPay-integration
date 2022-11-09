@if (count($contas) <= 0)
    <div class="alert alert-warning shadow mt-2">
        Nenhuma conta cadastrada.
    </div>
@else
<script type="text/javascript" src="{{asset('js/contas/listContas.js')}}"></script>
    <div class="container-fluid mt-4">
        <div class="row bg-light shadow border rounded p-4">
            <div class="col-md-12 table-responsive">
                <table id="contasTable" class="table table-striped">
                    <thead>
                        <tr class="fw-bold">
                            <td width='5%'>#</td>
                            <td width='70%'>Descrição conta</td>
                            <td width='15%' align="right">Valor</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contas as $conta)
                            <tr>
                                <td class='border'>
                                    {{$conta->id}}
                                </td>
                                <td class='border'>
                                    <p class=""> {{$conta->descricao_conta}}</p>
                                </td>
                                <td class='border' align="right">
                                    {{$conta->valor_conta}}
                                </td>
                                <td>
                                    <table width='100%'>
                                        <tr>
                                            <td align="right">
                                                <button class="btn btn-warning text-white fw-bold" data-bs-toggle='modal' data-bs-target='#modalEditConta{{$conta->id}}'>Editar</button>
                                            </td>
                                            <td align="right">
                                                <button class="btn btn-danger fw-bold" data-bs-toggle='modal' data-bs-target='#modalConfirmacaoExclusaoConta{{$conta->id}}'>Excluir</button>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            
                            {{-- MODAL EDITAR --}}
                            <div class="modal fade" id="modalEditConta{{$conta->id}}" tabindex="-1" aria-labelledby="formModalTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-uppercase" id="formModalTitle">Confirmação</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{route('financeiro.editarConta', $conta)}}" method="POST" id="formModalEditarConta">
                                                @method('PUT')
                                                @csrf
                                                <div class="row py-2">
                                                    <div class="col-8">
                                                        <label class="form-label fw-bold" for="descricaoConta">Descrição da conta</label>
                                                        <input class="form-control" type="text" name="descricaoConta" id="descricaoConta" placeholder="" value="{{$conta->descricao_conta}}" required>                  
                                                    </div>
                                                    <div class="col-4">
                                                        <label class="form-label fw-bold" for="valorConta">Valor da conta</label>
                                                        <input class="form-control" type="text" name="valorConta" id="valorConta" value="{{$conta->valor_conta}}" placeholder="">                  
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-success" form="formModalEditarConta">Confirmar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- FIM MODAL EDITAR --}}
                           
                            {{-- MODAL CONFIRMA EXCLUSÃO --}}
                            <div class="modal fade" id="modalConfirmacaoExclusaoConta{{$conta->id}}" tabindex="-1" aria-labelledby="formModalTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-uppercase" id="formModalTitle">Confirmação</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Deseja confirma a exclusão da empresa <strong>{{$conta->razao_social}}</strong> ?
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                                            <a class="btn btn-success" href="{{route('financeiro.excluirConta', $conta->id)}}">Confirmar</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- FIM MODAL CONFIRMA EXCLUSÃO --}}

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
