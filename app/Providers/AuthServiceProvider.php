<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
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

        // Implicitly grant "Super-Admin" role all permission checks using can()
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('superadmin')) {
                return true;
            }
        });

        Gate::define('viewWebSocketsDashboard', function ($user) {
            if ($user->hasRole('superadmin')) {
                return true;
            }
        });

        // Gate::define('primary', function($user){
        //     return $user->hasAnyRoles(['admin','author','observer']);
        // });

        // Gate::define('secondary', function($user){
        //     return $user->hasAnyRoles(['author','admin']);
        // });

        // Gate::define('ultimate', function($user){
        //     return $user->hasAnyRoles(['admin']);
        // });
    }
}
