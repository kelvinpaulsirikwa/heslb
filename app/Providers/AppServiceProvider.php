<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Response;
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
    
    // Log session configuration on boot (only in production for debugging)
    if (app()->environment('production')) {
        $this->logSessionBootConfig();
    }
    
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

}

/**
 * Log session configuration on application boot
 */
private function logSessionBootConfig(): void
{
    try {
        \Illuminate\Support\Facades\Log::channel('daily')->info('=== APPLICATION BOOT: SESSION CONFIG ===', [
            'session_driver' => config('session.driver'),
            'session_cookie_name' => config('session.cookie'),
            'session_domain' => config('session.domain'),
            'session_secure' => config('session.secure'),
            'session_http_only' => config('session.http_only'),
            'session_same_site' => config('session.same_site'),
            'session_path' => config('session.path'),
            'session_lifetime' => config('session.lifetime'),
            'session_files_path' => config('session.files'),
            'env_vars' => [
                'APP_ENV' => env('APP_ENV'),
                'APP_DEBUG' => env('APP_DEBUG'),
                'APP_URL' => env('APP_URL'),
                'SESSION_DRIVER' => env('SESSION_DRIVER'),
                'SESSION_DOMAIN' => env('SESSION_DOMAIN'),
                'SESSION_SECURE_COOKIE' => env('SESSION_SECURE_COOKIE'),
                'SESSION_LIFETIME' => env('SESSION_LIFETIME'),
            ],
            'php_session_status' => session_status(),
            'php_session_save_path' => session_save_path(),
            'php_session_name' => session_name(),
        ]);
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::channel('daily')->error('Error logging session boot config', [
            'error' => $e->getMessage(),
        ]);
    }
}

}
