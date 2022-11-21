<x-layout.layoutNavBar>
    <script type="text/javascript" src="{{asset('js/clientes/informacoesCliente.js')}}"></script>

    <div class="container-fluid bg-light shadow border rounded p-4">
        {{-- INCLUINDO COMPONENTE DE ALERT MENSAGENS --}}
        <x-messages.returnMessages>
        </x-messages.returnMessages>
        <div class="row">
            <div class="col-md-6">
                <h4 class="fw-bold text-uppercase">
                    Alterar cliente: {{$galaxPayCliente->nome_cliente}}
                </h4>
                <span>
                    @switch($galaxPayCliente->status_cliente)
                    @case('active')
                        <p class="bg-success px-2 py-2 m-0 text-white text-center fw-bold rounded" style="width: min-content">Ativo</p>
                        @break
                    @case('delayed')
                        <p class="bg-warning px-2 py-2 m-0 text-white fw-bold text-center rounded" style="width: 190px">Pagamento Pendente</p>
                        @break
                    @case('inactive')
                        <p class="bg-danger px-2 py-2 m-0 text-white fw-bold text-center rounded" style="width: min-content">Inativo</p>
                        @break
                    @case('withoutSubscriptionOrCharge')
                        <p class="bg-secondary px-2 py-2 m-0 text-white fw-bold text-center rounded" style="width: 140px">Sem assinatura</p>
                        @break
                    @default
                @endswitch
                </span> 
            </div>
            <div class="col-md-6 text-end">
                <a href="{{route('galaxPay.importaTransacoesPorCliente', $galaxPayCliente)}}" class="btn btn-success"><i class="fa-solid fa-plus"></i>Importar transações</a>
                <a href="{{route('galaxPay.importaContratoCliente', $galaxPayCliente)}}" class="btn btn-success"><i class="fa-solid fa-plus"></i>Importar contratos</a>
                <button type="button" class="btn btn-primary text-white fw-bold" data-bs-toggle="modal" data-bs-target="#modalHistoricoAtendimento">Histórico de atendimento</button>
            </div>
        </div>
        
        {{$slot}}
    </div>

    @include('components.modals.historicoAtendimento')
    
</x-layout.layoutNavBar>