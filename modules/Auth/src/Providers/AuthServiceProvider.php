<?php

namespace Modules\Auth\Providers;

use App\Shared\Contracts\AuthContract;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Modules\Auth\Models\User;
use Modules\Auth\Repositories\Contracts\UserRepositoryContract;
use Modules\Auth\Repositories\EloquentUserRepository;
use Modules\Auth\Services\AuthService;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryContract::class,
            EloquentUserRepository::class
        );

        $this->app->bind(
            AuthContract::class,
            AuthService::class,
        );
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        // set default User module 
        Config::set('auth.providers.users.model', User::class);
    }
}