<?php

use App\Providers\AppServiceProvider;
use App\Shared\Providers\SharedKernelServiceProvider;
use Modules\Auth\Providers\AuthServiceProvider;
use Modules\Messaging\Providers\MessagingServiceProvider;
use Modules\Presence\Providers\PresenceServiceProvider;

return [
    AppServiceProvider::class,

    // Shared Kernel (first — modules depend on it)
    SharedKernelServiceProvider::class,

    // Module Service Providers
    AuthServiceProvider::class,
    MessagingServiceProvider::class,
    PresenceServiceProvider::class
];
