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
    <div class="container-fluid float-start vh-100 d-flex align-items-center bg-primary">
        <div class="row vw-100 justify-content-center bg-white shadow p-5">
            {{-- CAMPO DE LOGIN --}}
            <div class="col-md-4 border rounded bg-light shadow p-4">
                <div class="row py-2">
                    <div class="col-12">
                        <h3>Bem-vindo</h3>
                        <h5>Acesso ao painel do colaborador</h5>
                    </div>
                </div>
                <form action="{{Route('empresasParceiras.verificaLogin')}}" method="POST">
                    @csrf
                    <div class="row py-2">
                        <div class="col-12">
                            <label class="form-label fw-bold" for="userLogin">Usuário</label>
                            <input class="form-control" type="text" name="userLogin" id="userLogin" placeholder="Digite seu usuário...">                  
                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-12">
                            <label class="form-label fw-bold" for="senhaLogin">Senha</label>
                            <input class="form-control" type="password" name="senhaLogin" id="senhaLogin" placeholder="Digite sua senha...">                  
                        </div>
                    </div>
                    <div class="row pt-4 text-center">
                        <div class="col-12">
                            <button class="form-control fw-bold btn btn-info text-white" type="submit">Entrar</button>              
                            <div class="text-end mt-1">
                                <a class="" href="{{route('esqueceuSenha')}}">Esqueceu a senha ?</a>
                            </div>
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