<?php

namespace Modules\Auth\Infrastructure\Providers;

use App\Shared\Contracts\AuthContract;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Modules\Auth\Application\Contracts\SessionManager;
use Modules\Auth\Domain\Contracts\PasswordChecker;
use Modules\Auth\Domain\Contracts\UserRepository;
use Modules\Auth\Domain\Contracts\PasswordHasher;
use Modules\Auth\Infrastructure\Models\EloquentUser;
use Modules\Auth\Infrastructure\Repositories\EloquentUserRepository;
use Modules\Auth\Infrastructure\Services\AuthService;
use Modules\Auth\Infrastructure\Services\BcryptPasswordChecker;
use Modules\Auth\Infrastructure\Services\BcryptPasswordHasher;
use Modules\Auth\Infrastructure\Services\LaravelSessionManager;

class AuthServiceProvider extends ServiceProvider
{
    
    public function register(): void
    {
        //application binds
        $this->app->bind(SessionManager::class, LaravelSessionManager::class);
        // domain binds
        $this->app->bind(PasswordChecker::class, BcryptPasswordChecker::class);
        $this->app->bind(PasswordHasher::class, BcryptPasswordHasher::class);
        $this->app->singleton(UserRepository::class, EloquentUserRepository::class);

        //shared binds
        $this->app->bind(AuthContract::class, AuthService::class);

    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../../../routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/../../../database/migrations');

        // Config::set('auth.providers.users.model', EloquentUser::class);
    }
}