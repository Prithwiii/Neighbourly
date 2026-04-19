<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

use App\Models\MarketplaceItem;

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
        // Route model binding for marketplace
        RouteFacade::bind('marketplace', function ($value) {
            return MarketplaceItem::findOrFail($value);
        });

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
     
        View::composer('*', function ($view) {

            $unreadCount = 0;

            if (\Illuminate\Support\Facades\Auth::check()) {
              $unreadCount = \Illuminate\Support\Facades\Auth::user()
                ->unreadNotifications()
                ->count();
            }

             $view->with('unreadCount', $unreadCount);
        });    
    }   
    
}
