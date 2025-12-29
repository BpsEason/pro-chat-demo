<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB; // 1. 必須引入 DB Facade
use Illuminate\Support\Facades\Log; // 2. 必須引入 Log Facade

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \DB::listen(fn($q) => \Log::info($q->sql, $q->bindings));
    }
}
