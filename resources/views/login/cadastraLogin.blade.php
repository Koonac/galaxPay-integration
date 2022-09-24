<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Galax Pay</title>

    {{-- IMPORTANDO CSS --}}
    <link rel="stylesheet" href="{{asset('css/myBootstrap.css')}}">
    {{-- IMPORTANDO SCRIPTS --}}
    <script src="{{asset('js/bootstrap.js')}}"></script>
    <script src="{{asset('js/jquery.js')}}"></script>
</head>
<body>
    <div class="container-fluid vh-100 bg-light d-flex align-items-center p-5 m-0">
        <div class="row vw-100 justify-content-end">
            {{-- CAMPO DE LOGIN --}}
            <div class="col-md-5 border rounded bg-white shadow p-4">
                <div class="row py-2">
                    <div class="col-12">
                        <h3>Bem-vindo</h3>
                    </div>
                </div>
                <form action="{{Route('registraLogin')}}" method="POST">
                    @csrf
                    <div class="row py-2">
                        <div class="col-12">
                            <label class="form-label fw-bold" for="nameLogin">Nome</label>
                            <input class="form-control" type="text" name="nameLogin" id="nameLogin" placeholder="Digite seu Nome...">                  
                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-12">
                            <label class="form-label fw-bold" for="userLogin">Usuário</label>
                            <input class="form-control" type="text" name="userLogin" id="userLogin" placeholder="Digite seu usuário...">                  
                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-12">
                            <label class="form-label fw-bold" for="emailLogin">Email</label>
                            <input class="form-control" type="text" name="emailLogin" id="emailLogin" placeholder="Digite seu Email...">                  
                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-12">
                            <label class="form-label fw-bold" for="passwordLogin">Senha</label>
                            <input class="form-control" type="password" name="passwordLogin" id="passwordLogin" placeholder="Digite sua senha...">                  
                        </div>
                    </div>
                    <div class="row pt-4 text-center">
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