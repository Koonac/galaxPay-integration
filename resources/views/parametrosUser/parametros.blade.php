<x-perfilCliente.perfil>
    <ul class="nav nav-tabs mt-4">
        <li>
            <a class="nav-link link-info" href="{{route('perfil')}}">Dados usuário</a>
        </li>
        @can('isAdmin')
            <li>
                <a class="nav-link link-info active" href="{{route('perfil.parametros')}}">Parametros</a>
            </li>
        @endcan
    </ul>
    
    <div class="row py-2">
        <div class="col-md-3">
            <label class="fw-bold" for="valorCard">Valor por cartão</label>
            <input class="form-control decimalBrasileiro2Digitos" name="valorCard" id="valorCard" type="text" value="{{str_replace(' ', ',', str_replace(',', '.', str_replace('.', ' ', $user->parametros->valor_card)))}}">
        </div>
        <div class="col-md-3">
            <label class="fw-bold" for="valorCancelamentoContrato">Valor de cancelamento</label>
            <input class="form-control decimalBrasileiro2Digitos" name="valorCancelamentoContrato" id="valorCancelamentoContrato" type="text" value="{{str_replace(' ', ',', str_replace(',', '.', str_replace('.', ' ', $user->parametros->valor_cancelamento_contrato)))}}">
        </div>
        <div class="col-md-3">
            <label class="fw-bold" for="cobrarCancelamentoMeses">Cobrar cancelamento a partir (meses)</label>
            <input class="form-control" name="cobrarCancelamentoMeses" id="cobrarCancelamentoMeses" type="text" value="{{$user->parametros->cobrar_cancelamento_meses}}">
        </div>
        <div class="col-md-3">
            <label class="fw-bold" for="cobrarCancelamentoMeses">Conta padrão</label>
            <select class="form-select" name="contaRecebimentoPadrao" id="contaRecebimentoPadrao">
                @if (Auth::user()->contas)
                    @foreach (Auth::user()->contas as $contaRecebimento)
                        @if ($contaRecebimento->id == $user->parametros->conta_recebimento_padrao )
                            <option value="{{$contaRecebimento->id}}" selected>{{$contaRecebimento->descricao_conta}}</option>
                        @else   
                            <option value="{{$contaRecebimento->id}}">{{$contaRecebimento->descricao_conta}}</option>
                        @endif
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    
    <div class="row py-2">
        <div class="col-md-2">
            <label class="fw-bold" for="quantidadeDependentesGalaxpay">Quantidade de dependentes</label>
            <input class="form-control" name="quantidadeDependentesGalaxpay" id="quantidadeDependentesGalaxpay" type="text" value="{{$user->parametros->quantidade_dependentes_galaxpay}}">
        </div>
        <div class="col-md-4">
            <label class="fw-bold" for="nomeCampoDependente">Campo nome do dependente</label>
            <input class="form-control" name="nomeCampoDependente" id="nomeCampoDependente" type="text" value="{{$user->parametros->nome_campo_dependente}}">
        </div>
        <div class="col-md-3">
            <label class="fw-bold" for="cpfCampoDependente">Campo cpf do dependente</label>
            <input class="form-control" name="cpfCampoDependente" id="cpfCampoDependente" type="text" value="{{$user->parametros->cpf_campo_dependente}}">
        </div>
        <div class="col-md-3">
            <label class="fw-bold" for="nascimentoCampoDependente">Campo nascimento do dependente</label>
            <input class="form-control" name="nascimentoCampoDependente" id="nascimentoCampoDependente" type="text" value="{{$user->parametros->nascimento_campo_dependente}}">
        </div>
    </div>
</x-perfilCliente.perfil>