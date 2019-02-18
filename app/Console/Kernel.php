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
        $schedule->command('generate:sitemap')->weekly();
        $schedule->command('pedidos:day')->dailyAt('17:00');
        $schedule->command('usuarios:activar')->dailyAt('23:00');
        $schedule->command('pedidos:venta')->dailyAt('17:00');
        $schedule->command('usuarios:new')->dailyAt('08:00');
        $schedule->command('usuarios:new')->dailyAt('15:00');
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
