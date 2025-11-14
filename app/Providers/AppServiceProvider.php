<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Blade;
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

}
