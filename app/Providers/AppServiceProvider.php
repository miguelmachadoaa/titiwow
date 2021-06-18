<?php
namespace App\Providers;
use App\Models\AlpMenuDetalle;
use App\Models\AlpConfiguracion;
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

            view()->composer('layouts.headerclub', function($view) {
                $view->with('menus', AlpMenuDetalle::menus(1));
            });

            view()->composer('layouts.sidebar', function($view) {
                $view->with('categorias', AlpMenuDetalle::menus(2));

                $categ=AlpMenuDetalle::menus(2);

            });

            view()->composer('layouts.sidebar', function($view) {
                $view->with('marcas', AlpMenuDetalle::menus(3));
            });

            view()->composer('*', function($view) {
                $config = AlpConfiguracion::where('alp_configuracion_general.id', '1')->first();
                $view->with('configuracion',$config);
            });
            
            view()->composer('layouts.footer', function($view) {
                $view->with('footermenu', AlpMenuDetalle::menus(5));

                $categ=AlpMenuDetalle::menus(5);

            });

            view()->composer('layouts.footerqlub', function($view) {
                $view->with('footerqlubmenu', AlpMenuDetalle::menus(6));

                $categ=AlpMenuDetalle::menus(6);

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
