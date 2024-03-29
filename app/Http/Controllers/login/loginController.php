<?php

namespace App\Http\Controllers\login;

use App\Http\Controllers\Controller;
use App\Models\galaxpay_parametros;
use App\Models\parametros_user;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

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

        // CRIANDO MODEL
        $galaxpayParametros = new galaxpay_parametros();
        $parametrosUser = new parametros_user();

        // DEFININDO VALORES DE PARAMETROS DO USUARIO
        $parametrosUser->valor_card = '9.99';
        $parametrosUser->valor_cancelamento_contrato = '59.99';
        $parametrosUser->cobrar_cancelamento_meses = '12';
        $parametrosUser->quantidade_dependentes_galaxpay = '7';

        // SALVANDO DADOS NO BANCO
        $user->save();
        $user->galaxPayParametros->save($galaxpayParametros);
        $user->parametros->save($parametrosUser);

        return redirect()->route('login')->with('SUCCESS', ['Login cadastrado com sucesso.']);
    }

    public function esqueceuSenha(Request $request)
    {
        return view('login.esqueceuSenha');
    }

    public function enviarEmailRecuperacao(Request $request)
    {
        try {
            // VALIDANDO EMAIL DO USUARIO
            $request->validate(['email' => 'required|email|exists:users']);

            // ENVIANDO EMAIL DE RESET DE SENHA
            $status = Password::sendResetLink(
                $request->only('email')
            );

            // RETORNANDO RESPOSTA DO ENVIO DE EMAIL
            return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);
        } catch (Exception $e) {
            // REDIRECIONANDO A PAGINA
            return redirect()->back()->withInput()->withErrors([$e->getMessage()]);
        }
    }

    public function redefinirSenhaToken(Request $request, $token)
    {
        return view('login.redefinirSenha', ['token' => $token]);
    }

    public function redefinirSenha(Request $request)
    {
        // VALIDANDO CAMPOS
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // REDEFININDO SENHA DO USUARIO
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        // RETORNANDO STATUS 
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function logout(Request $request)
    {
        // REALIZANDO LOGOUT
        Auth::logout();

        // REDIRECIONANDO
        return redirect()->route('login')->with('SUCCESS', ['Você deslogou com sucesso.']);
    }
}
