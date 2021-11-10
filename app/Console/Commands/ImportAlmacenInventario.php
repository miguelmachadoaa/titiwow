<?php

namespace App\Console\Commands;

use App\Models\AlpAlmacenes;
use App\Models\AlpOrdenes;
use App\Models\AlpConfiguracion;
use App\Models\AlpInventarioImport;


use App\Exports\ProductosRolExportB;
use App\Exports\NominaExport;
use App\Exports\UltimamillaExport;
use App\Exports\NominaExportAlmacen;

use App\Imports\AlmacenInventarioImport;

use App\Exports\FormatoSolicitudPedidoAlpinista;

use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Mail;

use Illuminate\Support\Facades\Storage;


use Illuminate\Console\Command;

class ImportAlmacenInventario extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'almacen:import';

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

        $imports=AlpInventarioImport::where('estado_registro', '=', '1')->get();

        foreach($imports as $i){

            echo 'Almacen - '.$i->id_almacen.'/ ';

            if (public_path() . '/uploads/inventario/'.$i->archivo) {

                Excel::import(new AlmacenInventarioImport($i->id_almacen), public_path() . '/uploads/inventario/'.$i->archivo);

                AlpInventarioImport::where('id', $i->id)->update(['estado_registro'=>'0']);
      
              }


        }

    }
}