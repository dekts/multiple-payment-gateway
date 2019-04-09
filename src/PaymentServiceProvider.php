<?php

namespace Dekts\Payments;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__.'/routes.php';

        $this->publishes([
            __DIR__ . '/config/dekts.php' => base_path('config/dekts.php'),
            __DIR__.'/views/middleware.blade.php' => base_path('app/Http/Middleware/VerifyCsrfMiddleware.php'),
        ]);

        $this->loadViewsFrom(__DIR__.'/views', 'dekts');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $gateway = Config::get('dekts.gateway');

        $this->app->bind('indipay', 'Dekts\Payments\Dekts');

        $this->app->bind('Dekts\Payments\Gateways\PaymentGatewayInterface','Dekts\Payments\Gateways\\'.$gateway.'Gateway');
    }
}
