<?php

namespace Modules\Messaging\Providers;

use App\Shared\Contracts\MessagingContract;
use Illuminate\Support\ServiceProvider;
use Modules\Messaging\Repositories\Contracts\ConversationRepositoryContract;
use Modules\Messaging\Repositories\Contracts\MessageRepositoryContract;
use Modules\Messaging\Repositories\EloquentConversationRepository;
use Modules\Messaging\Repositories\EloquentMessageRepository;
use Modules\Messaging\Services\MessagingService;

class MessagingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Internal repository bindings
        $this->app->bind(
            ConversationRepositoryContract::class,
            EloquentConversationRepository::class,
        );

        $this->app->bind(
            MessageRepositoryContract::class,
            EloquentMessageRepository::class,
        );

        // Shared Kernel contract binding
        // Other modules inject MessagingContract → get MessagingService
        $this->app->bind(
            MessagingContract::class,
            MessagingService::class,
        );
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }
}