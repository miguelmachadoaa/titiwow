<?php

namespace App\Console\Commands;

use App\Models\AlpConfiguracion;
use App\Models\AlpProductos;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\AlpOrdenes;

use Carbon\Carbon;

use DB;


use Illuminate\Console\Command;

class ProductosEliminar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'productos:eliminar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Borrado Masivo de Productos';

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
     
        $date = Carbon::now();

        $hoy=$date->format('Y-m-d');


        $productos=array(131,
        154,
        177,
        303,
        304,
        310,
        342,
        343,
        353,
        354,
        355,
        361,
        364,
        365,
        366,
        367,
        368,
        371,
        372,
        373,
        374,
        375,
        376,
        377,
        378,
        379,
        380,
        381,
        382,
        383,
        393,
        394,
        395,
        396,
        397,
        398,
        399,
        400,
        401,
        405,
        409,
        432,
        440,
        465,
        466,
        471,
        472,
        473,
        480,
        482,
        484,
        485,
        486,
        487,
        489,
        490,
        498,
        499,
        503,
        511,
        521,
        524,
        525,
        528,
        542,
        554,
        561,
        570,
        584,
        595,
        596,
        598,
        599,
        600,
        602,
        649,
        705,
        706,
        707,
        712,
        737,
        741,
        757,
        758,
        759,
        760,
        769,
        774,
        775,
        776,
        782,
        783,
        806,
        808,
        840,
        841,
        842,
        846,
        847,
        848,
        852,
        860,
        861,
        862,
        863,
        887,
        888,
        889,
        894,
        923,
        924,
        925,
        926,
        927,
        928,
        929,
        947
        );

        //$productos = AlpProductos::select('id')->get();

        foreach ($productos as $producto) {

            $borrado=AlpProductos::where('id','=', $producto)->whereNull('alp_productos.deleted_at')->delete();

            echo $producto.'-';

        }

 
        

        
    }
    
}
