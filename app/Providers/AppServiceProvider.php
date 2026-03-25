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
        // automatically create the storage symlink if missing so
        // uploaded images are visible without requiring manual CLI work
        $link = public_path('storage');
        if (! file_exists($link) || ! is_link($link)) {
            try {
                \Illuminate\Support\Facades\Artisan::call('storage:link');
            } catch (\Exception $e) {
                // ignore; failure usually means permission issues on the host
            }
        }
    }
}
