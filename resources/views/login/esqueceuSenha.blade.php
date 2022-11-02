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
            <div class="col-md-4 border rounded bg-light shadow p-4">
                <div class="row py-2">
                    <div class="col-12">
                        <h4>Esqueceu a senha ?</h4>
                    </div>
                </div>
                <form action="{{Route('esqueceuSenha.enviarEmailRecuperacao')}}" method="POST">
                    @csrf
                    <div class="row py-2">
                        <div class="col-12">
                            <label class="form-label fw-bold" for="email">Email</label>
                            <input class="form-control" type="text" name="email" id="email" placeholder="Digite seu email cadastrado..." value="{{old('email')}}" required>                  
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