<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Card GalaxPay</title>
    {{-- IMPORTANDO CSS --}}
    <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}">
    {{-- IMPORTANDO SCRIPTS --}}
    <script src="{{asset('js/bootstrap.js')}}"></script>
    <script src="{{asset('js/jquery.js')}}"></script>
</head>
<body>
    <div class="container-fluid vh-100 bg-light p-5 m-0">
        <div class="row bg-secondary">
            <div class="col-10">
                <h1>Olá, bem-vindo a home!</h1>
            </div>
            <div class="col-2">
                <a href="{{route('logout')}}">
                    <button class="btn btn-danger">Sair</button>
                </a>
            </div>
        </div>
    </div>
</body>
</html>