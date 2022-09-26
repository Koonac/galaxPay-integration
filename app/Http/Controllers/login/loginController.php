<?php

namespace App\Http\Controllers\login;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class loginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return view('login.login');
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function verificaLogin(Request $request)
    {
        $credenciais = [
            'login' => $request->userLogin,
            'password' => $request->senhaLogin
        ];

        if (Auth::attempt($credenciais)) {
            return redirect()->route('home');
        } else {
            return redirect()->back()->withInput()->withErrors(['Usuário/Senha Incorretos']);
        }
    }

    public function cadastraLogin()
    {
        return view('login.cadastraLogin');
    }

    public function registraLogin(Request $request)
    {
        // CRIANDO MODELO DE USUARIO
        $user = new User();

        // DEFININDO VALORES PARA CADASTRO
        $user->name = $request->nameLogin;
        $user->login = $request->userLogin;
        $user->email = $request->emailLogin;
        $user->password = Hash::make($request->passwordLogin);
        $user->role = 'Admin';

        // SALVANDO DADOS NO BANCO
        $user->save();

        return redirect()->route('login')->with('SUCCESS', ['Login cadastrado com sucesso.']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('SUCCESS', ['Você deslogou com sucesso.']);
    }
}
