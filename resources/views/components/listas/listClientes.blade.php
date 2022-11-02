@if (count($galaxPayClientes) <= 0)
    <div class="alert alert-warning shadow mt-2">
        Nenhum cliente importado.
    </div>
@else
<script type="text/javascript" src="{{asset('js/clientes/listClientes.js')}}"></script>
    <div class="container-fluid">
        <div class="row bg-light shadow border rounded p-4">
            <div class="col-md-12 table-responsive">
                <table id="clientesTable" class="table table-striped">
                    <thead>
                        <tr class="fw-bold">
                            <td>Nº Matricula</td>
                            <td>Nome</td>
                            <td>CPF/CNPJ</td>
                            <td>Telefone</td>
                            <td>Status</td>
                            <td width='80px'></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($galaxPayClientes as $galaxPayCliente)
                            <tr>
                                <td class='border'>
                                    <p> {{$galaxPayCliente->matricula}} </p>
                                </td>
                                <td>
                                    <p> {{$galaxPayCliente->nome_cliente}} </p>
                                </td>
                                <td class='border'>
                                    <p class="cnpjMask"> {{$galaxPayCliente->cpf_cnpj_cliente}} </p>
                                </td>
                                <td class='border'>
                                    <p class="telefoneMask"> {{$galaxPayCliente->telefone_cliente_1}} </p>
                                </td>
                                <td class="fw-bold border">
                                    @switch($galaxPayCliente->status_cliente)
                                        @case('active')
                                            <p class="text-success text-center">Ativo</p>
                                            @break
                                        @case('delayed')
                                            <p class="text-warning text-center">Pagamento Pendente</p>
                                            @break
                                        @case('inactive')
                                            <p class="text-danger text-center">Inativo</p>
                                            @break
                                        @case('withoutSubscriptionOrCharge')
                                            <p class="text-secondary text-center">Sem assinatura</p>
                                            @break
                                        @default
                                    @endswitch
                                </td>
                                <td align="right" class="bg-purple">
                                    <a href="{{route('clientes.gerarCartaoCliente', ['idGalaxPayCliente' => $galaxPayCliente->id])}}" class="btn btn-info text-white" target="__blank"><i class="fa-solid fa-print"></i></i></a>
                                    <a href="{{route('clientes.informacoesCliente', [$galaxPayCliente->id])}}" class="btn btn-warning text-white"><i class="fa-solid fa-eye"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif