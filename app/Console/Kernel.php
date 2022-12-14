<?php

namespace App\Console;

use App\Models\AlpAlmacenes;


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
        Commands\PedidosDelDia::class,
        Commands\NuevosUsuarios::class,
        Commands\VerificarPagos::class,
        Commands\VerificarPagosHora::class,
        Commands\ProductoB::class,
        Commands\ProductoC::class,
        Commands\TomaPedidos::class,
        Commands\NotificacionCarrito::class,
        Commands\VentasNomina::class,
        Commands\VerificarSaldo::class,
        Commands\CancelarOrdenes::class,
        Commands\VerificarExistenciaAlmacen::class,
        Commands\VentasUltimaMilla::class,
        Commands\BienvenidaIBM::class,
        Commands\CodigodescuentoIBM::class,
        Commands\Teextranamos2IBM::class,
        Commands\TeextranamosIBM::class,
        Commands\PedidosEnviados::class,
        Commands\PedidosEnviados::class,
        Commands\ImportAlmacenInventario::class,
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
       
        #$schedule->command('pedidos:day')->mondays()->at('17:00');
        #$schedule->command('toma:pedidos')->mondays()->at('17:00');
        #$schedule->command('productos:venta')->mondays()->at('17:00');
        #$schedule->command('productosc:venta')->mondays()->at('17:00');

        #$schedule->command('pedidos:day')->tuesdays()->at('17:00');
        #$schedule->command('toma:pedidos')->tuesdays()->at('17:00');
        #$schedule->command('productos:venta')->tuesdays()->at('17:00');
        #$schedule->command('productosc:venta')->tuesdays()->at('17:00');

        #$schedule->command('pedidos:day')->wednesdays()->at('17:00');
        #$schedule->command('toma:pedidos')->wednesdays()->at('17:00');
        #$schedule->command('productos:venta')->wednesdays()->at('17:00');
        #$schedule->command('productosc:venta')->wednesdays()->at('17:00');

        #$schedule->command('pedidos:day')->thursdays()->at('17:00');
        #$schedule->command('toma:pedidos')->thursdays()->at('17:00');
        #$schedule->command('productos:venta')->thursdays()->at('17:00');
        #$schedule->command('productosc:venta')->thursdays()->at('17:00');

        #$schedule->command('pedidos:day')->fridays()->at('17:00');
        #$schedule->command('toma:pedidos')->fridays()->at('17:00');
        #$schedule->command('productos:venta')->fridays()->at('17:00');
        #$schedule->command('productosc:venta')->fridays()->at('17:00');

        #$schedule->command('pedidos:day')->saturdays()->at('14:00');
        #$schedule->command('toma:pedidos')->saturdays()->at('14:00');
        #$schedule->command('productos:venta')->saturdays()->at('14:00');
        #$schedule->command('productosc:venta')->saturdays()->at('14:00');


        $schedule->command('verificar:saldo')->dailyAt('1:00');
        
        $schedule->command('cancelar:ordenes')->dailyAt('07:00');

        //reporte de ultima milla a las 5 pm
        $schedule->command('reporte:ultimamilla')->dailyAt('17:00');

        //llamadas ibm a las 5pm 

      #  $schedule->command('bienvenida:ibm')->dailyAt('07:00');
        $schedule->command('codigodescuento:ibm')->dailyAt('11:00');
        $schedule->command('teextranamos2:ibm')->dailyAt('09:00');
        $schedule->command('teextranamos:ibm')->dailyAt('07:00');


         
        $schedule->command('pedidos:enviados')->dailyAt('18:00');
        
        $schedule->command('verificar:pagos')->everyFifteenMinutes();

        $schedule->command('almacen:import')->everyFifteenMinutes();

        $schedule->command('verificar:pagoshora')->hourly()->between('6:00', '23:00');

        $schedule->command('notificacion:carrito')->hourly()->between('7:00', '22:00');
        
        #->twiceDaily(10, 16);

        #$schedule->command('verificar:almacen')->twiceDaily(10, 16);

        $schedule->command('activitylog:clean')->dailyAt('00:05');

        $almacenes=AlpAlmacenes::where('estado_registro', '1')->get();

        foreach ($almacenes as $alm) {

            if (isset($alm->id)) {

                $schedule->command('venta:almacenes '.$alm->id)->dailyAt($alm->hora);

                $schedule->command('nomina:venta '.$alm->id)->dailyAt($alm->hora);
                
            }
            
        }

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
