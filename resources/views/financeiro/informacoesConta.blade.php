<x-layout.layoutNavBar>
    <script type="text/javascript" src="{{asset('js/financeiro/informacoesConta.js')}}"></script>
    <div class="container-fluid">
        <div class="row bg-light shadow border rounded p-4">
            <div class="col-md-12">
                <div class="row py-2">
                    <div class="col-md-1">
                        <label class="form-label fw-bold" for="idConta">ID conta</label>
                        <input class="form-control" type="text" name="idConta" id="idConta" value="{{$conta->id}}" readonly>                  
                    </div>
                    <div class="col-md-8">
                        <label class="form-label fw-bold" for="descricaoConta">Descrição da conta</label>
                        <input class="form-control" type="text" name="descricaoConta" id="descricaoConta" value="{{$conta->descricao_conta}}">                  
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold" for="valorConta">Valor em conta</label>
                        <input class="form-control text-end" type="text" name="valorConta" id="valorConta" value="{{str_replace(' ', ',', str_replace(',', '.', str_replace('.', ' ', $conta->valor_conta)))}}" readonly>                  
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row bg-light shadow border rounded mt-4 p-4">
            <div class="col-md-12">
                <table id="contasTable" class="table table-hover">
                    <thead class="fw-bold">
                        <tr>
                            <td width='15%'>Data/Hora</td>
                            <td width='45%'>Observação</td>
                            <td width='15%'>Usuário</td>
                            <td width='15%'>Cliente</td>
                            <td width='10%' class="text-end">Valor</td>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($conta->recebimentos))
                            @foreach($conta->recebimentos as $recebimento)
                                <tr class="text-success">
                                    <td>{{date('d/m/Y H:i', strtotime($recebimento->data_recebimento))}}</td>
                                    <td>{{$recebimento->observacao_recebimento}}</td>
                                    <td>{{$recebimento->user->name}}</td>
                                    <td>{{mb_substr($recebimento->galaxPayCliente->nome_cliente, 0, 25, 'utf-8')}}</td>
                                    <td class="text-end">+ {{str_replace(' ', ',', str_replace(',', '.', str_replace('.', ' ', $recebimento->valor_recebimento)))}}</td>
                                </tr>
                            @endforeach
                        @endif
                        @if (isset($conta->despesas))
                            @foreach($conta->despesas as $despesa)
                                <tr class="text-danger" >
                                    <td>{{date('d/m/Y H:i', strtotime($despesa->data_despesa))}}</td>
                                    <td>{{$despesa->observacao_despesa}}</td>
                                    <td>{{$despesa->user->name}}</td>
                                    <td></td>
                                    <td class="text-end">- {{str_replace(' ', ',', str_replace(',', '.', str_replace('.', ' ', $despesa->valor_despesa)))}}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layout.layoutNavBar>