<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Route Model Binding para planejamento -> Atividade
        Route::bind('planejamento', function ($value) {
            return \App\Models\Atividade::findOrFail($value);
        });

        // Route Model Binding para ata
        Route::bind('ata', function ($value) {
            return \App\Models\Ata::findOrFail($value);
        });

        // Route Model Binding para lembrete
        Route::bind('lembrete', function ($value) {
            return \App\Models\LembreteReuniao::findOrFail($value);
        });

        // Route Model Binding para reunio
        Route::bind('reunio', function ($value) {
            return \App\Models\Reuniao::findOrFail($value);
        });

        // Route Model Binding para decisao
        Route::bind('decisao', function ($value) {
            return \App\Models\Decisao::findOrFail($value);
        });

        // Route Model Binding para obrigaco
        Route::bind('obrigaco', function ($value) {
            return \App\Models\Obrigacao::findOrFail($value);
        });

        // Route Model Binding para role
        Route::bind('role', function ($value) {
            return \Spatie\Permission\Models\Role::findOrFail($value);
        });

        // Route Model Binding para permission
        Route::bind('permission', function ($value) {
            return \Spatie\Permission\Models\Permission::findOrFail($value);
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}