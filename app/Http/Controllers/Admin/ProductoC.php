<?php

namespace App\Console\Commands;

use App\Models\AlpConfiguracion;
use App\Exports\CronLogisticaExport;
use Maatwebsite\Excel\Facades\Excel;

use Carbon\Carbon;
use Mail;



use Illuminate\Console\Command;

class ProductoC extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'productosc:venta';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envio de pedidos para ser aprobados';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $configuracion=AlpConfiguracion::where('id', '1')->first();

         $date = Carbon::now();

        $hoy=$date->format('Y-m-d');

        $archivo=$configuracion->base_url.'reportes/cronexportproductosc';


       // Excel::store(new CronLogisticaExport(), $archivo);

        $enlace=storage_path('/app/'.$archivo);

        Mail::to($configuracion->correo_cedi)->send(new \App\Mail\CronProductoB($archivo, $hoy));

    }
}
