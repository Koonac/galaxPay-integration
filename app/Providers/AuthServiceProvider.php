<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // PERMISSOES DE FUNCIONARIO
        Gate::define('acessoClientes', function ($user) {
            if (isset($user->funcionarioPermissoes)) {
                return $user->funcionarioPermissoes->acesso_clientes == 'S';
            } else {
                return $user->role == 'Admin';
            }
        });
        Gate::define('acessoEmpresas', function ($user) {
            if (isset($user->funcionarioPermissoes)) {
                return $user->funcionarioPermissoes->acesso_empresas == 'S';
            } else {
                return $user->role == 'Admin';
            }
        });
        Gate::define('acessoFinanceiro', function ($user) {
            if (isset($user->funcionarioPermissoes)) {
                return $user->funcionarioPermissoes->acesso_financeiro == 'S';
            } else {
                return $user->role == 'Admin';
            }
        });
        Gate::define('acessoGalaxpay', function ($user) {
            if (isset($user->funcionarioPermissoes)) {
                return $user->funcionarioPermissoes->acesso_galaxpay == 'S';
            } else {
                return $user->role == 'Admin';
            }
        });
        Gate::define('acessoFuncionarios', function ($user) {
            if (isset($user->funcionarioPermissoes)) {
                return $user->funcionarioPermissoes->acesso_funcionarios == 'S';
            } else {
                return $user->role == 'Admin';
            }
        });

        // ANALISANDO SE É FUNCIOANRIO
        Gate::define('isEmployee', function ($user) {
            return $user->role == 'Funcionario';
        });

        // ANALISANDO SE É ADMIN
        Gate::define('isAdmin', function ($user) {
            return $user->role == 'Admin';
        });

        // ANALISANDO SE É PARTNER
        Gate::define('isPartner', function ($user) {
            return $user->role == 'empresaParceira';
        });
    }
}
