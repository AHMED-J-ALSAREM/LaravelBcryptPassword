<?php

namespace AhmedJAlsarem\LaravelBcryptPassword;

use Illuminate\Support\ServiceProvider;

class BcryptPasswordServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/bcrypt-password.php' => config_path('bcrypt-password.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/bcrypt-password.php', 'bcrypt-password'
        );

        $this->app->singleton(PasswordHasher::class, function ($app) {
            return new PasswordHasher(config('bcrypt-password'));
        });
    }
}