<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('path.public',function(){
            return'/Users/josemanuelguerrerosanchez/LaravelProjects/crediuno/public';
        });
    }
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Blade::directive('money_format', function ($money) {
            return "<?php echo '$'. number_format($money, 2); ?>";
        });
    }
}
