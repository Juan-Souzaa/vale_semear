<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Diretiva Blade para verificar permissão
        Blade::if('canPermission', function ($permission) {
            return Gate::allows($permission);
        });

        // Diretiva Blade para verificar role
        Blade::if('hasRole', function ($role) {
            $user = auth()->user();
            if (!$user instanceof User) {
                return false;
            }
            return $user->hasRole($role);
        });
    }
}
