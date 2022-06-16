<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Galax Pay</title>

    {{-- IMPORTANDO CSS --}}
    <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}">
    {{-- IMPORTANDO SCRIPTS --}}
    <script src="{{asset('js/bootstrap.js')}}"></script>
    <script src="{{asset('js/jquery.js')}}"></script>
</head>
<body>
    <div class="container p-5 m-0">
        <div class="row bg-success p-4 border shadow">
            <div class="col-4">
                <h1>Olá mundo !</h1>
                <a href="{{route('galaxPay')}}">
                    <button class="btn btn-secondary">GalaxPay</button>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-8">
                @if (!empty($response))
                    @foreach ($response->Customers as $cliente)
                        <pre>
                            {{print_r($cliente->name)}}
                        </pre>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</body>
</html>