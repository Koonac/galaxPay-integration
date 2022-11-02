<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class acessoFuncionario
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // VERIFICANDO PERMISSAO DO USUÁRIO
        switch ($request->user()->role) {
            case 'Admin':
                return $next($request);
                break;
            case 'Funcionario':
                // VERIFICANDO ROTAS PARA PERMISSOES DO USUARIO
                switch ($request->route()->getPrefix()) {
                    case '/clientes':
                        if ($request->user()->funcionarioPermissoes->acesso_clientes == 'S') return $next($request);
                        break;
                    case '/empresasParceiras':
                        if ($request->user()->funcionarioPermissoes->acesso_empresas == 'S') return $next($request);
                        break;
                    case '/financeiro':
                        if ($request->user()->funcionarioPermissoes->acesso_financeiro == 'S') return $next($request);
                        break;
                    case '/funcionarios':
                        if ($request->user()->funcionarioPermissoes->acesso_funcionarios == 'S') return $next($request);
                        break;
                    case '/galaxPay':
                        if ($request->user()->funcionarioPermissoes->acesso_galaxpay == 'S') return $next($request);
                        break;
                    default:
                        //RETORNANDO PARA HOME, POIS NÃO TEM PERMISSÃO 
                        return redirect()->route('home');
                        break;
                }
                //RETORNANDO PARA HOME, POIS NÃO TEM PERMISSÃO 
                return redirect()->route('home');
                break;

            default:
                //RETORNANDO PARA HOME, POIS NÃO TEM PERMISSÃO 
                return redirect()->route('home');
                break;
        }
    }
}
