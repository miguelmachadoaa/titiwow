<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\loggerLink',
        Commands\GenerateSitemap::class,
        Commands\PedidosDelDia::class,
        Commands\NuevosUsuarios::class,
        Commands\VerificarPagos::class,
        Commands\ProductoB::class,
        Commands\ProductoC::class,
        Commands\TomaPedidos::class,
        Commands\NotificacionCarrito::class,
        Commands\VentaNomina::class,
        Commands\CancelarOrdenes::class,


    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        //  $schedule->command('usuarios:activar')->dailyAt('23:00');
       
        $schedule->command('pedidos:day')->mondays()->at('17:00');
        $schedule->command('toma:pedidos')->mondays()->at('17:00');
        $schedule->command('productos:venta')->mondays()->at('17:00');
        $schedule->command('productosc:venta')->mondays()->at('17:00');

        $schedule->command('pedidos:day')->tuesdays()->at('17:00');
        $schedule->command('toma:pedidos')->tuesdays()->at('17:00');
        $schedule->command('productos:venta')->tuesdays()->at('17:00');
        $schedule->command('productosc:venta')->tuesdays()->at('17:00');

        $schedule->command('pedidos:day')->wednesdays()->at('17:00');
        $schedule->command('toma:pedidos')->wednesdays()->at('17:00');
        $schedule->command('productos:venta')->wednesdays()->at('17:00');
        $schedule->command('productosc:venta')->wednesdays()->at('17:00');

        $schedule->command('pedidos:day')->thursdays()->at('17:00');
        $schedule->command('toma:pedidos')->thursdays()->at('17:00');
        $schedule->command('productos:venta')->thursdays()->at('17:00');
        $schedule->command('productosc:venta')->thursdays()->at('17:00');

        $schedule->command('pedidos:day')->fridays()->at('17:00');
        $schedule->command('toma:pedidos')->fridays()->at('17:00');
        $schedule->command('productos:venta')->fridays()->at('17:00');
        $schedule->command('productosc:venta')->fridays()->at('17:00');

        $schedule->command('pedidos:day')->saturdays()->at('14:00');
        $schedule->command('toma:pedidos')->saturdays()->at('14:00');
        $schedule->command('productos:venta')->saturdays()->at('14:00');
        $schedule->command('productosc:venta')->saturdays()->at('14:00');


        $schedule->command('nomina:venta')->dailyAt('16:00');
        $schedule->command('cancelar:ordenes')->dailyAt('07:00');
         $schedule->command('generate:sitemap')->weekly();
        //$schedule->command('usuarios:new')->dailyAt('08:00');
        //$schedule->command('usuarios:new')->dailyAt('15:00');
        $schedule->command('verificar:pagos')->everyFiveMinutes();
        $schedule->command('notificacion:carrito')->hourly();


    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
