<x-layout.layoutNavBar>
    <script type="text/javascript" src="{{asset('js/financeiro/financeiro.js')}}"></script>

    <div class="container-fluid">
        <div class="row bg-light shadow border rounded p-4">
            <div class="col-md-12">
                @if (isset($caixaFinanceiro))
                    <form action="{{route('financeiro.fecharCaixa', $caixaFinanceiro)}}" method="post">
                        @csrf
                        <div class="row justify-content-end">
                            <div class="col-md-4">
                                <label for="valorTotalCaixa" class="form-label fw-bold">Valor em caixa</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="text" class="form-control text-end decimalBrasileiro2Digitos" name="valorTotalCaixa" id="valorTotalCaixa" value="" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="valorCaixaAbertura" class="form-label fw-bold">Valor de abertura</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="text" class="form-control text-end decimalBrasileiro2Digitos" name="valorCaixaAbertura" id="valorCaixaAbertura" value="{{$caixaFinanceiro->valor_abertura}}" readonly>
                                    <button type="submit" id='btnFecharFecharCaixa' class="btn btn-danger text-white fw-bold">Fechar caixa</button>
                                </div>
                            </div>
                        </div>
                    </form>
                @else
                    <form action="{{route('financeiro.abrirCaixa')}}" method="post">
                        @csrf
                        <div class="row justify-content-end">
                            <div class="col-md-4">
                                <label for="valorCaixaAbertura" class="form-label fw-bold">Valor de abertura</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="text" class="form-control text-end decimalBrasileiro2Digitos" name="valorCaixaAbertura" id="valorCaixaAbertura">
                                    <button type="submit" id='btnAbrirFecharCaixa' class="btn btn-success text-white fw-bold">Abrir caixa</button>
                                </div>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
        
        {{-- INCLUINDO MENSAGENS DE RETORNO --}}
        <x-messages.returnMessages>
        </x-messages.returnMessages>

        @if (isset($caixaFinanceiro))
            <div class="row bg-light shadow border rounded mt-4 p-4">
                <div class="col-md-12">
                    <button type="button" id='btnAdicionaRecebimento' class="btn btn-success text-white fw-bold" data-bs-toggle="modal" data-bs-target="#modalRecebimento">+ Receber</button>
                    <button type="button" id='btnAdicionaDespesa' class="btn btn-danger text-white fw-bold" data-bs-toggle="modal" data-bs-target="#modalDespesa">- Pagar</button>
                <table id="caixaFinanceiroTable" class="talbe table-striped">
                    <thead class="fw-bold">
                        <tr>
                            <td>Data/Hora</td>
                            <td>Observação</td>
                            <td align="right">Valor</td>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($caixaFinanceiro->recebimentos))
                            @foreach($caixaFinanceiro->recebimentos as $recebimento)
                                <tr class="text-success">
                                    <td>{{$recebimento->data_recebimento}}</td>
                                    <td>{{$recebimento->observacao_recebimento}}</td>
                                    <td align="right">+ {{$recebimento->valor_recebimento}}</td>
                                    <input type="hidden" class="valorRecebimento" id="valorRecebimento" name="valorRecebimento" value="{{$recebimento->valor_recebimento}}">
                                </tr>
                            @endforeach
                        @endif
                        @if (isset($caixaFinanceiro->despesas))
                            @foreach($caixaFinanceiro->despesas as $despesa)
                                <tr class="text-danger">
                                    <td>{{$despesa->data_despesa}}</td>
                                    <td>{{$despesa->observacao_despesa}}</td>
                                    <td align="right">- {{$despesa->valor_despesa}}</td>
                                    <input type="hidden" class="valorDespesa" id="valorDespesa" name="valorDespesa" value="{{$despesa->valor_despesa}}">
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    </table>
                </div>
            </div>
            @include('components.modals.geraRecebimento')
            @include('components.modals.geraDespesa')
        @endif   
    </div>
    

</x-layout.layoutNavBar>