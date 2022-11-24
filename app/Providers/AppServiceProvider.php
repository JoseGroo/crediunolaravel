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
        /*$this->app->bind('path.public',function(){
            return'/Users/josemanuelguerrerosanchez/LaravelProjects/crediuno/public';
        });*/
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Blade::directive('money_format', function ($money) {
            $symbol = '$';
            if($money < 0)
            {
                $money = $money * (-1);
                $symbol = '-$';
            }
            return "<?php echo '$symbol' . number_format($money, 2); ?>";
        });
    }
}
