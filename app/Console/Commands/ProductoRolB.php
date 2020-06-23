<?php

namespace App\Console\Commands;

use App\Models\AlpConfiguracion;
use App\Exports\ProductosRolExportB;
use Maatwebsite\Excel\Facades\Excel;

use Carbon\Carbon;
use Mail;



use Illuminate\Console\Command;

class ProductoRolB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'productosrol:venta';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envio de pedidos para ser facturados';

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

        $archivo=$configuracion->base_url.'reportes/cronexportproductosb';


        $documentos = array();

        $archivo_clientes='ventas_productos_clientes'.$hoy.'.xlsx';

       Excel::store(new ProductosRolExportB($hoy, $hoy, '9'), $archivo_clientes, 'excel');
            
        $documentos[]='/home2/alpago/alpinago/storage/app/public/'.$archivo_clientes;
     

        $archivo_embajador='ventas_productos_embajador'.$hoy.'.xlsx';

        Excel::store(new ProductosRolExportB($hoy, $hoy, '10'), $archivo_embajador, 'excel');
            
        $documentos[]='/home2/alpago/alpinago/storage/app/public/'.$archivo_embajador;
      


        $archivo_amigoalpina='ventas_productos_amigoalpina'.$hoy.'.xlsx';

         Excel::store(new ProductosRolExportB($hoy, $hoy, '11'), $archivo_amigoalpina, 'excel');

        $documentos[]='/home2/alpago/alpinago/storage/app/public/'.$archivo_amigoalpina;


        $enlace=storage_path('/app/'.$archivo);

        Mail::to($configuracion->correo_cedi)->send(new \App\Mail\CronProductoB($archivo, $hoy, $documentos));

Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\CronProductoB($archivo, $hoy, $documentos));
    }
}
