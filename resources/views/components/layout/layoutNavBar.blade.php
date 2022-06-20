<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Card GalaxPay</title>
    {{-- IMPORTANDO CSS --}}
    <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('css/myStyle.css')}}">
    {{-- IMPORTANDO SCRIPTS --}}
    <script src="{{asset('js/bootstrap.js')}}"></script>
    <script src="{{asset('js/jquery.js')}}"></script>
    <script src="{{asset('js/jqueryMask.js')}}"></script>
    <script src="{{asset('js/myScript.js')}}"></script>
    <script src="https://kit.fontawesome.com/dd928c7064.js" crossorigin="anonymous"></script>
</head>
<body>
  <nav class="navbar navbar-expand-md border-bottom shadow px-2 mb-4 bg-info">
      <div class="container-fluid">
          <a class="navbar-brand fw-bold" href="{{route('home')}}">(Logo)</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navBarMenu" aria-controls="navBarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-center" id="navBarMenu">
              <ul class="nav nav-pills flex-column flex-md-row pt-4 py-md-1">
                <li class="nav-item flex-md-fill ">
                  <a class="btn btn-outline-dark fw-bold text-white border border-info w-100" name="home" id="home" href="{{route('home')}}">Home</a>
                </li>
                <li class="nav-item flex-md-fill">
                  <a class="btn btn-outline-dark fw-bold text-white border border-info w-100" name="clientes" id="clientes" href="{{route('clientes')}}">Clientes</a>
                </li>
                <li class="nav-item flex-md-fill">
                  <a class="btn btn-outline-dark fw-bold text-white border border-info w-100 disabled" name="empresas" id="empresas" href="{{route('empresas')}}">Empresas</a>
                </li>
                <li class="nav-item flex-md-fill">
                  <a class="btn btn-outline-dark fw-bold text-white border border-info w-100 disabled" name="relatorios" id="relatorios" href="{{route('relatorios')}}">Relatórios</a>
                </li>
                <li class="nav-item flex-md-fill">
                  <a class="btn btn-outline-dark fw-bold text-white border border-info w-100 link-light" name="perfil" id="perfil" href="{{route('perfil')}}">Perfil</a>
                </li>
                <hr class="d-md-none">
                <li class="nav-item flex-md-fill">
                  <a class="btn btn-outline-danger fw-bold text-white border border-info w-100 d-md-none" href="{{route('logout')}}">Sair</a>
                </li>
              </ul>
          </div>
          <a class="d-none d-md-block link-dark fw-bold" href="{{route('logout')}}">
              <i class="fa-solid fa-arrow-right-from-bracket fa-xl"></i>
          </a>
      </div>
  </nav>
  <div class="container-fluid px-4">
    <div class="row bg-light shadow border rounded">
      <div class="col-12">
        
        {{ $slot }}
      
      </div>
    </div>
  </div>
</body>
</html>