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
                            <td width='80%'>Descrição conta</td>
                            <td width='10%' align="right">Valor</td>
                            <td width='5%' align="center"></td>
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
                                        {{str_replace(' ', ',', str_replace(',', '.', str_replace('.', ' ', $conta->valor_conta)))}}
                                    </td>
                                    <td align="center">
                                        <a href="{{route('financeiro.visualizarConta', $conta)}}" class="btn btn-warning text-white"><i class="fa-solid fa-eye"></i></a>
                                    </td>
                                </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
