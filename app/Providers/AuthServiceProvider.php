<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\User;
use Mockery\Generator\StringManipulation\Pass\Pass;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
        Passport::tokensExpireIn(\Carbon::now()->addSeconds(config('oauth.token_ttl', 86400)));
        Passport::refreshTokensExpireIn(\Carbon::now()->addDays(30));

        //这个是适用于Laravel自带的@can指令之类，laravel-permission 这个包的hasPermissionTo()不会有用
        Gate::before(function($user, $ability) {
            $hasRole = $user->hasRole(User::ROLE_SUPER_ADMIN_NAME);
            \Log::info(sprintf("%s, user: %s has role 'super_admin': %s", __METHOD__, $user->name, (string)$hasRole));
            if ($user->hasRole(User::ROLE_SUPER_ADMIN_NAME))
            {
                return true;
            }
        });
    }
}
