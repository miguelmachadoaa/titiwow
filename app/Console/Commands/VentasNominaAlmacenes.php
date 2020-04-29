<?php

namespace App\Console\Commands;

use App\Models\AlpAlmacenes;
use App\Models\AlpConfiguracion;
use App\Exports\ProductosRolExportB;
use App\Exports\NominaExport;
use App\Exports\NominaExportAlmacen;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Mail;


use Illuminate\Console\Command;

class VentasNominaAlmacenes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'venta:almacenes {alm}';

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

        $alm = $this->argument('alm');

        $almacen=AlpAlmacenes::where('id', $alm)->first();


       // dd($alm);

        //
        $configuracion=AlpConfiguracion::where('id', '1')->first();

         $date = Carbon::now();

        $hoy=$date->format('Y-m-d');

        $archivo=$configuracion->base_url.'reportes/exportnomina';


        $documentos = array();

        $archivo_clientes='listado_ventas_descuento_nomina_'.$hoy.'.xlsx';

        Excel::store(new NominaExportAlmacen($hoy, $alm), $archivo_clientes, 'excel');
            
       $documentos[]='/var/www/alpinago/storage/app/public/'.$archivo_clientes;
     

        $enlace=storage_path('/app/'.$archivo);

       // Mail::to($configuracion->correo_cedi)->send(new \App\Mail\CronNomina($archivo, $hoy, $documentos));

        if (isset($almacen->id)) {

            $correos = explode(";", $almacen->correos);

            foreach ($correos as $key => $value) {

                Mail::to(trim($value))->send(new \App\Mail\CronNomina($archivo, $hoy, $documentos));
            }

        }

      #  Mail::to('paula.fonseca@alpina.com')->send(new \App\Mail\CronNomina($archivo, $hoy, $documentos));
      #  Mail::to('julian.garzon@alpina.com')->send(new \App\Mail\CronNomina($archivo, $hoy, $documentos));
      #  Mail::to('claudia.archbold@alpina.com')->send(new \App\Mail\CronNomina($archivo, $hoy, $documentos));
       # Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\CronNomina($archivo, $hoy, $documentos));


    }
}
