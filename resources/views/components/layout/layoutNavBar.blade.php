<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Card GalaxPay</title>
    {{-- IMPORTANDO CSS --}}
    <link rel="stylesheet" href="{{asset('css/myBootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('css/myStyle.css')}}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/r-2.3.0/datatables.min.css"/>
      
    {{-- IMPORTANDO SCRIPTS --}}
    <script type="text/javascript" src="{{asset('js/jquery.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/bootstrap.js')}}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/r-2.3.0/datatables.min.js"></script>
    <script type="text/javascript" src="{{asset('js/jqueryMask.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/myScript.js')}}"></script>

    <script src="https://kit.fontawesome.com/dd928c7064.js" crossorigin="anonymous"></script>
</head>
<body>
  <nav class="navbar navbar-expand-md border-bottom shadow px-2 mb-4 bg-info">
    <div class="container-fluid">
      <a class="navbar-brand text-white fw-bold" href="{{route('home')}}"><img src="{{asset('assets/logoSimples.png')}}" alt="cartao-soli" width="50"></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navBarMenu" aria-controls="navBarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-center" id="navBarMenu">
            <ul class="nav nav-pills flex-column flex-md-row pt-4 py-md-1">
              <li class="nav-item flex-md-fill ">
                <a class="btn btn-outline-dark fw-bold text-white border border-info w-100" name="home" id="home" href="{{route('home')}}">Home</a>
              </li>
              @can('acessoClientes')
                <li class="nav-item flex-md-fill">
                  <a class="btn btn-outline-dark fw-bold text-white border border-info w-100" name="clientes" id="clientes" href="{{route('clientes')}}">Clientes</a>
                </li>
              @endcan
              @can('acessoEmpresas')
              <li class="nav-item flex-md-fill">
                <a class="btn btn-outline-dark fw-bold text-white border border-info w-100" name="empresasParceiras" id="empresasParceiras" href="{{route('empresasParceiras')}}">Empresas</a>
              </li>
              @endcan
              @can('acessoFinanceiro')
              <li class="nav-item flex-md-fill">
                <a class="btn btn-outline-dark fw-bold text-white border border-info w-100" name="financeiro" id="financeiro" href="{{route('financeiro')}}">Financeiro</a>
              </li>
              @endcan
              @can('acessoCaixa')
              <li class="nav-item flex-md-fill">
                <a class="btn btn-outline-dark fw-bold text-white border border-info w-100" name="caixa" id="caixa" href="{{route('caixa')}}">Caixa</a>
              </li>
              @endcan
              @can('acessoGalaxpay')
              <li class="nav-item flex-md-fill">
                <a class="btn btn-outline-dark fw-bold text-white border border-info w-100" name="galaxPay" id="galaxPay" href="{{route('galaxPay')}}">GalaxPay</a>
              </li>
              @endcan
              @can('acessoFuncionarios')
              <li class="nav-item flex-md-fill">
                <a class="btn btn-outline-dark fw-bold text-white border border-info w-100" name="funcionarios" id="funcionarios" href="{{route('funcionarios')}}">Funcionários</a>
              </li>
              @endcan
              @can('isPartner')
                <li class="nav-item flex-md-fill">
                  <a class="btn btn-outline-dark fw-bold text-white border border-info w-100" name="clientesStatus" id="clientesStatus" href="{{route('empresasParceiras.clientesStatus')}}">Clientes Status</a>
                </li>
              @endcan
              <li class="nav-item flex-md-fill">
                <a class="btn btn-outline-dark fw-bold text-white border border-info w-100 link-light" name="perfil" id="perfil" href="{{route('perfil')}}">Perfil</a>
              </li>
              <hr class="d-md-none">
              <li class="nav-item flex-md-fill">
                <a class="btn btn-outline-danger fw-bold text-white border border-info w-100 d-md-none" href="{{route('logout')}}">Sair</a>
              </li>
            </ul>
          </div>

            <p class="d-none d-md-block fw-bold colorNameLayout p-2 m-0 me-4">
              - {{Auth::user()->name}} 
            </p>          

          <a class="d-none d-md-block link-dark fw-bold" href="{{route('logout')}}">
              <i class="fa-solid text-white fa-arrow-right-from-bracket fa-xl"></i>
          </a>
    </div>
  </nav>
  <div class="container-fluid px-4">
    <div class="row">
      <div class="col-12">
        
        {{ $slot }}
      
      </div>
    </div>
  </div>
</body>
</html>