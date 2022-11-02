<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Galax Pay</title>

    {{-- IMPORTANDO CSS --}}
    <link rel="stylesheet" href="{{asset('css/myBootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('css/myStyle.css')}}">

    {{-- IMPORTANDO SCRIPTS --}}
    <script src="{{asset('js/bootstrap.js')}}"></script>
    <script src="{{asset('js/jquery.js')}}"></script>
</head>

<body>
    <div class="container-fluid float-start vh-100 d-flex align-items-center bg-info">
        <div class="row vw-100 justify-content-center bg-white shadow p-5">
            {{-- CAMPO DE LOGIN --}}
            <div class="col-md-4 border rounded bg-light shadow p-4">
                <div class="row py-2">
                    <div class="col-12">
                        <h4>Redefinir senha</h4>
                    </div>
                </div>
                <form action="{{Route('redefinirSenha')}}" method="POST">
                    @csrf

                    <input type="hidden" name="token" id="token" value="{{$token}}">
                    <input type="hidden" name="email" id="email" value="{{$_REQUEST['email']}}">
                    
                    <div class="row py-2">
                        <div class="col-12">
                            <label class="form-label fw-bold" for="password">Nova senha</label>
                            <input class="form-control" type="password" name="password" id="password" placeholder="Digite sua senha...">
                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-12">
                            <label class="form-label fw-bold" for="password_confirmation">Confirma nova senha</label>
                            <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" placeholder="Digite novamente sua senha...">
                        </div>
                    </div>
                    <div class="row pt-2 text-center">
                        <div class="col-12">
                            <button class="form-control fw-bold btn btn-info text-white" type="submit">Enviar</button>
                        </div>
                    </div>
                </form>

                {{-- COMPONENTE DE MENSAGEM DE ERROS --}}
                <x-messages.returnMessages>
                </x-messages.returnMessages>

            </div>
        </div>
    </div>
</body>

</html>