<?php

namespace App\Console\Commands;

use App\Models\AlpAlmacenes;
use App\Models\AlpOrdenes;
use App\Models\AlpConfiguracion;
use App\Exports\ProductosRolExportB;
use App\Exports\NominaExport;
use App\Exports\UltimamillaExport;
use App\Exports\NominaExportAlmacen;
use App\Exports\FormatoSolicitudPedidoAlpinista;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Mail;


use Illuminate\Console\Command;

class VentasUltimaMilla extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reporte:ultimamilla';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envio de pedidos por almacen';

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

        


        $configuracion=AlpConfiguracion::where('id', '1')->first();

         $date = Carbon::now();

        $hoy=$date->format('Y-m-d');

        $archivo=$configuracion->base_url.'reportes/exporultimamilla';

        $documentos = array();

        $archivo_clientes='ventas_ultimamilla_'.time().'.xls';

        Excel::store(new UltimamillaExport(), $archivo_clientes, 'excel');
            
       //$documentos[]='C:\xampp\htdocs\alpina\storage\app\public/'.$archivo_clientes;
       $documentos[]='/home2/alpago/alpinago/storage/app/public/'.$archivo_clientes;
     
        $enlace=storage_path('/app/'.$archivo);

       // Mail::to($configuracion->correo_cedi)->send(new \App\Mail\CronNomina($archivo, $hoy, $documentos));
       // 
       // //alpina.vueltap@vueltap.com; okam1@mercadoasucasa.com; gerenciaoperaciones@mercadoasucasa.com; facturadormasc@gmail.com; jespinosa@vueltap.com; amonroy@vueltap.com


            $correos = explode(";", $configuracion->correo_ultimamilla);

            foreach ($correos as $key => $value) {

                Mail::to(trim($value))->send(new \App\Mail\CronUltimaMilla($archivo, $hoy, $documentos));
            }



    }
}