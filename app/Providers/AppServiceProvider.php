<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use App\Services\LinkService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */

public function boot(): void
{
    // Force HTTPS for all generated URLs (routes, assets, etc.) in production/staging
    // This ensures forms and links always use HTTPS in production
    // This fixes the security issue where credentials are transmitted over HTTP
    // CRITICAL: This prevents credentials from being sent in cleartext in production
    // Allow HTTP in local environment for development
    // if (!app()->environment('local')) {
    //     URL::forceScheme('https');
    // }
    
    App::setLocale(Session::get('locale', config('app.locale')));
    
    // Set custom pagination view
    Paginator::defaultView('vendor.pagination.bootstrap-5-custom');
    Paginator::defaultSimpleView('vendor.pagination.simple-bootstrap-5');
    
    // Register custom Blade directives for links
    Blade::directive('link', function ($expression) {
        return "<?php echo config('links.' . {$expression}); ?>";
    });
    
    Blade::directive('socialLink', function ($platform) {
        return "<?php echo config('links.social_media.' . {$platform}); ?>";
    });
    
    Blade::directive('heslbLink', function ($system) {
        return "<?php echo config('links.heslb_systems.' . {$system}); ?>";
    });
    
    Blade::directive('contactInfo', function ($type) {
        return "<?php echo config('links.contact.' . {$type}); ?>";
    });
    
    // Share flash messages with all views (using Laravel sessions)
    view()->composer('*', function ($view) {
        // Using Laravel's default session system
        if (session()->has('flash')) {
            $view->with('flash', session('flash'));
            session()->forget('flash');
        }
    });

    // Share current user with all views (using Laravel's Auth)
    view()->composer('*', function ($view) {
        $view->with('currentUser', Auth::user());
    });

}

}
