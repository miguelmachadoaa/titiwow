<?php

namespace App\Providers;

use App\Models\AlpMenuDetalle;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

            view()->composer('layouts.header', function($view) {
                $view->with('menus', AlpMenuDetalle::menus(1));
            });

            view()->composer('layouts.sidebar', function($view) {
                $view->with('categorias', AlpMenuDetalle::menus(2));
            });

            view()->composer('layouts.sidebar', function($view) {
                $view->with('marcas', AlpMenuDetalle::menus(3));
            });
            
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
