<?php

namespace App\Console\Commands;

use App\Models\AlpConfiguracion;
use App\Exports\CronLogisticaExport;
use App\Exports\TomaPedidosRolExport;
use Maatwebsite\Excel\Facades\Excel;

use Carbon\Carbon;
use Mail;



use Illuminate\Console\Command;

class TomaPedidos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'toma:pedidos';

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

        $archivo=$configuracion->base_url.'reportes/cronexporttomapedidos';


        $documentos = array();

        $archivo_clientes='toma_pedidos_clientes'.$hoy.'.xlsx';

       Excel::store(new TomaPedidosRolExport('9'), $archivo_clientes, 'excel');
            
        $documentos[]='/home2/alpago/alpinago/storage/app/public/'.$archivo_clientes;
     

        $archivo_embajador='toma_pedidos_embajador'.$hoy.'.xlsx';

        Excel::store(new TomaPedidosRolExport('10'), $archivo_embajador, 'excel');
            
        $documentos[]='/home2/alpago/alpinago/storage/app/public/'.$archivo_embajador;
      


        $archivo_amigoalpina='toma_pedidos_amigoalpina'.$hoy.'.xlsx';

         Excel::store(new TomaPedidosRolExport('11'), $archivo_amigoalpina, 'excel');

        $documentos[]='/home2/alpago/alpinago/storage/app/public/'.$archivo_amigoalpina;


        //dd($documentos);
       // Excel::store(new CronLogisticaExport(), $archivo);

         $enlace=storage_path('/app/'.$archivo);

        Mail::to($configuracion->correo_cedi)->send(new \App\Mail\CronTomaPedidos($archivo,$hoy,  $documentos));

        #$vacio = array();

        #$date = Carbon::now();

        #$hoy=$date->format('Y-m-d');

       # $archivo=$configuracion->base_url.'reportes/cronexporttomapedidos';


       // Excel::store(new CronLogisticaExport(), $archivo);

        #$enlace=storage_path('/app/'.$archivo);

       # Mail::to($configuracion->correo_cedi)->send(new \App\Mail\CronTomaPedidos($archivo, $hoy, $vacio));

    }
}
