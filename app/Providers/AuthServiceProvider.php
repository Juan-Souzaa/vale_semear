<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Atividade;
use App\Models\Obrigacao;
use App\Models\Reuniao;
use App\Models\Ata;
use App\Models\Decisao;
use App\Models\User;
use App\Policies\AtividadePolicy;
use App\Policies\ObrigacaoPolicy;
use App\Policies\ReuniaoPolicy;
use App\Policies\AtaPolicy;
use App\Policies\DecisaoPolicy;
use App\Policies\RelatorioPolicy;
use App\Policies\UserPolicy;
use App\Policies\RolePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Atividade::class => AtividadePolicy::class,
        Obrigacao::class => ObrigacaoPolicy::class,
        Reuniao::class => ReuniaoPolicy::class,
        Ata::class => AtaPolicy::class,
        Decisao::class => DecisaoPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Super Admin bypass - permite tudo
        Gate::before(function ($user, $ability) {
            if ($user->isSuperAdmin()) {
                return true;
            }
        });

        // Registrar policies para recursos sem model
        Gate::define('relatorios.view', [RelatorioPolicy::class, 'viewAny']);
        Gate::define('relatorios.export', [RelatorioPolicy::class, 'export']);
        Gate::define('permissoes.view', [RolePolicy::class, 'viewAny']);
        Gate::define('permissoes.update', [RolePolicy::class, 'update']);
        Gate::define('permissoes.delete', [RolePolicy::class, 'delete']);
    }
}
