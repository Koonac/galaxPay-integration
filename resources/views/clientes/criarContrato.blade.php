<x-layout.layoutNavBar>
    <script type="text/javascript" src="{{asset('js/clientes/contratos.js')}}"></script>

    <div class="container-fluid bg-light shadow border rounded p-4">
        {{-- INCLUINDO COMPONENTE DE ALERT MENSAGENS --}}
        <x-messages.returnMessages>
        </x-messages.returnMessages>
        <form action="{{route('clientes.criarContratoCliente', $clienteGalaxPay)}}" id="formCriarContratoGalaxPay" method="POST">
            @csrf
            <div class="row py-2">
                <div class="col-md-6">
                    <label class="fw-bold" for="planoCodigoContrato">Plano contrato</label>
                    <input class="form-control" name="planoCodigoContrato" id="planoCodigoContrato" type="text" value="{{old('planoCodigoContrato')}}" disabled required>
                </div>
                <div class="col-md-6">
                    <label class="fw-bold" for="valorContrato">Valor do contrato</label>
                    <input class="form-control decimalBrasileiro2Digitos" name="valorContrato" id="valorContrato" value="{{old('valorContrato')}}" type="text" required>
                </div>
            </div>
            <div class="row py-2">
                <div class="col-md-6">
                    <label class="fw-bold" for="duracaoContrato">Duração do contrato</label>
                    <input class="form-control inteiro mb-1" name="duracaoContrato" id="duracaoContrato" type="text">
                </div>
                <div class="col-md-6">
                    <label class="fw-bold" for="periodicidadePagamento">Periodicidade do pagamento</label>
                    <select class="form-select" name="periodicidadePagamento" id="periodicidadePagamento"  required>
                        <option value="weekly">Semanal</option>
                        <option value="biweekly">Quinzenal</option>
                        <option value="monthly" selected>Mensal</option>
                        <option value="bimonthly">Bimestral</option>
                        <option value="quarterly">Trimestral</option>
                        <option value="biannual">Semestral</option>
                        <option value="yearly">Anual</option>
                    </select>
                </div>
            </div>
            <div class="row py-2">
                <div class="col-md-6">
                    <label class="fw-bold" for="primeiraDataPagamento">Primeira data para pagamento</label>
                    <input class="form-control dataBrasileiraDDMMYYYY datePicker" name="primeiraDataPagamento" id="primeiraDataPagamento" value="{{old('primeiraDataPagamento')}}" type="text" required>
                </div>                    
                <div class="col-md-6">
                    <label class="fw-bold" for="formaPagamento">Forma de cobrança</label>
                    <select class="form-select" name="formaPagamento" id="formaPagamento" required>
                        <option value="creditcard">Cartão de credito</option>
                        <option value="boleto">Boleto/Pix</option>
                    </select>
                </div>                                      
            </div>
            <div class="row py-2">
                <div class="col-md-12">
                    <label class="fw-bold" for="informacaoAdicional">Observação contrato</label>
                    <textarea class="form-control" name="informacaoAdicional" id="informacaoAdicional" value="{{old('informacaoAdicional')}}" rows="2"></textarea>
                </div>                                                        
            </div>
            
            <div id='informacoesBoletoPix'>
                <h4>DESCONTO CONDICIONAL</h4>
                <div class="row py-2">
                    <div class="col-md-12">
                        <input class="form-check-input" type="checkbox" name="aplicarDesconto" id="aplicarDesconto" value="S" checked>
                        <label class="form-check-label" for="aplicarDesconto">Aplicar desconto condicional ?</label>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-md-6">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tipoDesconto" id="tipoDescontoPorcentagem" value="P">
                            <label class="form-check-label" for="tipoDescontoPorcentagem">Porcentagem (%)</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tipoDesconto" id="tipoDescontoFixo" value="F" checked>
                            <label class="form-check-label" for="tipoDescontoFixo">Valor Fixo (R$)</label>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="valorDescontoSpan">R$</span>
                            <input class="form-control decimalBrasileiro2Digitos" aria-label="valor de desconto" aria-describedby="Valor de desconto condicional" name="valorDesconto" id="valorDesconto" value={{old('valorDesconto')}}>
                          </div>
                    </div>
                    <div class="col-md-6">
                        <label for="qtdeDiasValidadeDesconto">Pago com antecedência ? (Dias)</label>
                        <input class="form-control inteiro" name="qtdeDiasValidadeDesconto" id="qtdeDiasValidadeDesconto" value={{old('qtdeDiasValidadeDesconto')}} >
                    </div>
                </div>
                <h4>MULTA E JUROS</h4>
                <div class="row py-2">
                    <div class="col-md-4">
                        <label for="percentualMulta">Multa por atraso(%)?</label>
                        <input class="form-control decimalBrasileiro2Digitos" name="percentualMulta" id="percentualMulta" value={{old('percentualMulta')}}>
                    </div>
                    <div class="col-md-4">
                        <label for="percentualJuros">Juros ao mês(%)?</label>
                        <input class="form-control decimalBrasileiro2Digitos" name="percentualJuros" id="percentualJuros" value={{old('percentualJuros')}}>
                    </div>
                    <div class="col-md-4">
                        <label for="qtdePagamentoPosVencimento">Prazo maximo para pagamento após vencimento ? (Dias)</label>
                        <input class="form-control inteiro" name="qtdePagamentoPosVencimento" id="qtdePagamentoPosVencimento" value={{old('qtdePagamentoPosVencimento')}}>
                    </div>
                    <div class="col-md-12">
                        <label for="observacaoBoleto">Observação do boleto</label>
                        <textarea class="form-control" name="observacaoBoleto" id="observacaoBoleto" value="{{old('observacaoBoleto')}}" rows="1"></textarea>
                    </div>
                </div>
            </div>
        </form>
        <div class="row pt-4 justify-content-end">
            <div class="col-md-3">
                <button type="submit" class="btn btn-info text-white fw-bold w-100" form="formCriarContratoGalaxPay" >Cadastrar contrato</button>
            </div>
        </div>
    </div>
</x-layout.layoutNavBar>