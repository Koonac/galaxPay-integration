<div class="modal fade" id="modalGeraFinanceiroCartao{{$galaxPayCliente->id}}" tabindex="-1" aria-labelledby="formModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase" id="formModalTitle">Confirmação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('clientes.gerarCartaoCliente', $galaxPayCliente)}}" method="post" id='gerarFinanceiroSim{{$galaxPayCliente->id}}' target="__blank">
                    @csrf
                    <input type="hidden" name="gerarFinanceiro" id="gerarFinanceiro" value="SIM"> 
                </form>
                <form action="{{route('clientes.gerarCartaoCliente', $galaxPayCliente)}}" method="post" id='gerarFinanceiroNao{{$galaxPayCliente->id}}' target="__blank">
                    @csrf
                    <input type="hidden" name="gerarFinanceiro" id="gerarFinanceiro" value="NAO"> 
                </form>
                Deseja gerar financeiro deste cartão ?
            </div>
            <div class="modal-footer">
                <button type="submit" form="gerarFinanceiroSim{{$galaxPayCliente->id}}" class="btn btn-success" data-bs-dismiss="modal">Sim</button>
                <button type="submit" form="gerarFinanceiroNao{{$galaxPayCliente->id}}" class="btn btn-danger" data-bs-dismiss="modal">Não</button>
            </div>
        </div>
    </div>
</div>