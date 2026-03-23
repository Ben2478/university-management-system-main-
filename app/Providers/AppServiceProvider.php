<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // Ensure required writable directories exist (important in fresh cloud deploys)
        $paths = [
            storage_path('framework/cache'),
            storage_path('framework/sessions'),
            storage_path('framework/views'),
            base_path('bootstrap/cache'),
        ];

        foreach ($paths as $path) {
            try {
                if (!is_dir($path)) {
                    @mkdir($path, 0775, true);
                }
            } catch (\Throwable $e) {
                // Avoid breaking app boot; deployment/runtime perms may be handled by the platform.
            }
        }
    }
}
