<?php

namespace App\Console\Commands;

use App\Models\AlpConfiguracion;
use App\Models\AlpProductos;
use Maatwebsite\Excel\Facades\Excel;

use Carbon\Carbon;




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


        $productos=array(1,2,3,4,5);
        foreach ($productos as $producto) {

            $borrado=AlpProductos::where('id','=', $producto)->delete();

            echo $producto.'-';



        }


        

        
    }
}
