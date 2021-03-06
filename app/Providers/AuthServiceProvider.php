<?php

namespace App\Providers;

use Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\ProjectExperience' => 'App\Policies\ProjectExperiencePolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::provider('dkf-eloquent', function ($app, $config) {
            return new DkfEloquentUserProvider($this->app['hash'], $config['model']);
        });

        Passport::routes();
    }
}
