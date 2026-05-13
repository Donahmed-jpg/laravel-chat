<?php

use App\Providers\AppServiceProvider;
use App\Shared\Providers\SharedKernelServiceProvider;
use Modules\Auth\Infrastructure\Providers\AuthServiceProvider;


return [
    AppServiceProvider::class,

    // Shared Kernel (first — modules depend on it)
    SharedKernelServiceProvider::class,

    // Module Service Providers
    AuthServiceProvider::class,
];
