<?php

namespace App\Console\Commands;

use App\Models\AlpConfiguracion;
use App\Exports\ProductosRolExportC;
use Maatwebsite\Excel\Facades\Excel;

use Carbon\Carbon;
use Mail;



use Illuminate\Console\Command;

class ProductoRolC extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'productosrolc:venta';

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



         $documentos = array();

        $archivo_clientes='ventas_productosc_clientes_'.$hoy.'.xlsx';

        if (Excel::store(new ProductosRolExportC($hoy, $hoy, '9'), $archivo_clientes, 'excel')) {
            $documentos[]='/home2/alpago/alpinago/storage/app/public/'.$archivo_clientes;
        }


        $archivo_embajador='ventas_productosc_embajado_r'.$hoy.'.xlsx';

        if ( Excel::store(new ProductosRolExportC($hoy, $hoy, '10'), $archivo_embajador, 'excel')) {
            $documentos[]='/home2/alpago/alpinago/storage/app/public/'.$archivo_embajador;
        }


        $archivo_amigoalpina='ventas_productosc_amigoalpina_'.$hoy.'.xlsx';

           if ( Excel::store(new ProductosRolExportC($hoy, $hoy, '11'), $archivo_amigoalpina, 'excel')) {
            $documentos[]='/home2/alpago/alpinago/storage/app/public/'.$archivo_amigoalpina;
        }

       // Excel::store(new CronLogisticaExport(), $archivo);

        $enlace=storage_path('/app/'.$archivo);

        Mail::to($configuracion->correo_cedi)->send(new \App\Mail\CronProductoB($archivo, $hoy, $documentos));
Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\CronProductoB($archivo, $hoy, $documentos));
        

    }
}
