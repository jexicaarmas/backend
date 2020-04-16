<?php

namespace App\Providers;

use App\Services\RouterExtended;
use Illuminate\Routing\ResourceRegistrar;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Agrega el método search en todos los controladores
        $register = new RouterExtended($this->app['router']);
        $this->app->bind(ResourceRegistrar::class, function () use ($register) {
            return $register;
        });

    }
}
