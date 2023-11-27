<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models'=>'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        Passport::tokensExpireIn(now()->addHours(23));

        Gate::define('access-Secretary', function (User $user) {
            return $user->isSecretary || $user->isManager;
        });

        Gate::define('access-SalesOfficer', function (User $user) {
            return $user->isSalesOfficer || $user->isManager;
        });
    }
}
