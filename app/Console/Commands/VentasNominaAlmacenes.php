<?php

namespace App\Console\Commands;

use App\Models\AlpAlmacenes;
use App\Models\AlpOrdenes;
use App\Models\AlpConfiguracion;
use App\Exports\ProductosRolExportB;
use App\Exports\NominaExport;
use App\Exports\NominaExportAlmacen;
use App\Exports\FormatoSolicitudPedidoAlpinista;
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

        $archivo_clientes='ventas_almacen_'.$almacen->nombre_almacen.'_'.$hoy.'.xlsx';

        Excel::store(new NominaExportAlmacen($hoy, $alm), $archivo_clientes, 'excel');
            
       $documentos[]='/home2/alpago/alpinago/storage/app/public/'.$archivo_clientes;
     

        $enlace=storage_path('/app/'.$archivo);

       // Mail::to($configuracion->correo_cedi)->send(new \App\Mail\CronNomina($archivo, $hoy, $documentos));

        if (isset($almacen->id)) {

            $correos = explode(";", $almacen->correos);

            foreach ($correos as $key => $value) {

                Mail::to(trim($value))->send(new \App\Mail\CronNomina($archivo, $hoy, $documentos));
            }

        }



        /*********Envio de formato*************/

        $almacen=AlpAlmacenes::where('id', $this->alm)->first();

        if ($almacen->formato=='1') {

        $date_desde = Carbon::parse($this->desde.' '.$almacen->hora.':00')->subDay()->toDateTimeString();

        $date_hasta = Carbon::parse($this->desde.' 23:59:59')->toDateTimeString(); 

          $ordenes=AlpOrdenes::where('alp_ordenes.id_almacen', $this->alm)
          ->where('alp_ordenes.created_at', '>=', $date_desde)
          ->where('alp_ordenes.created_at', '<=', $date_hasta)
          ->whereIn('alp_ordenes.estatus', ['1','2','3','5','6','7','8'])
          ->get();

          foreach ($ordenes as $orden) {

                $archivo=$configuracion->base_url;

                $documentos = array();

                $archivo_clientes='formato_solicitud_'.$orden->referencia.'_'.$almacen->nombre_almacen.'_'.$hoy.'.xlsx';

                Excel::store(new FormatoSolicitudPedidoAlpinista($orden->id, $archivo_clientes, 'excel'));
                    
               $documentos[]='/home2/alpago/alpinago/storage/app/public/'.$archivo_clientes;
             
                $enlace=storage_path('/app/'.$archivo);


                if (isset($almacen->id)) {

                    $correos = explode(";", $almacen->correos);

                    foreach ($correos as $key => $value) {

                        Mail::to(trim($value))->send(new \App\Mail\CronNominaFormato($archivo, $hoy, $documentos));
                    }

                }


          }

      }


    }
}
