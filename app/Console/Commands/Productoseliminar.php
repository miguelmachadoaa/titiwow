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


        $productos=array(314,
        315,
        316,
        317,
        318,
        305,
        140,
        141,
        139,
        220,
        217,
        221,
        219,
        223,
        133,
        134,
        135,
        136,
        132,
        66,
        201,
        194,
        168,
        191,
        204,
        79,
        171,
        164,
        188,
        160,
        186,
        196,
        199,
        296,
        294,
        292,
        290,
        103,
        124,
        2,
        4,
        203,
        202,
        211,
        13,
        24,
        26,
        29,
        30,
        128,
        129,
        130,
        330,
        210,
        31,
        225,
        226,
        228,
        340,
        169,
        170,
        37,
        297,
        50,
        52,
        344,
        309,
        307,
        362,
        407,
        274,
        311,
        308,
        322,
        320,
        321,
        414,
        333,
        385,
        347,
        336,
        348,
        328,
        329,
        363,
        352,
        349,
        427,
        415,
        423,
        426,
        430,
        431,
        429,
        428,
        433,
        583,
        585,
        751,
        752,
        370,
        715,
        858,
        341,
        324,
        325,
        326,
        384,
        327,
        445,
        434,
        435,
        436,
        360,
        323);

        //$productos = AlpProductos::select('id')->get();

        foreach ($productos as $producto) {

            $borrado=AlpProductos::where('id','=', $producto)->whereNull('alp_productos.deleted_at')->delete();

            echo $producto.'-';

        }

 
        

        
    }
    
}
