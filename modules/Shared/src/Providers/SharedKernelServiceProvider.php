<?php

namespace App\Shared\Providers;

use Illuminate\Support\ServiceProvider;

class SharedKernelServiceProvider extends ServiceProvider
{
    /**
     * The Shared Kernel itself has nothing to register or boot.
     * It's pure PHP — no framework dependencies, no routes, no migrations.
     * 
     * This provider exists as an extension point.
     * If we ever need to register shared macros, validation rules,
     * or global middleware — they go here.
     */
    public function register(): void {}

    public function boot(): void {}
}