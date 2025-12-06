<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        // Define gate for admin access
        Gate::define('viewAny', function (User $user, $model) {
            return $user->hasRole('admin');
        });

        Gate::define('admin-access', function (User $user) {
            return $user->hasRole('admin');
        });
    }
}
