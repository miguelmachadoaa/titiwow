<?php

namespace App\Console\Commands;

use App\User;
use App\Models\AlpConfiguracion;
use App\Models\AlpCarrito;
use App\Models\AlpCarritoDetalle;
use App\Exports\CronNuevosUsuarios;
use Maatwebsite\Excel\Facades\Excel;

use Carbon\Carbon;
use Mail;
use DB;



use Illuminate\Console\Command;

class NotificacionCarrito extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notificacion:carrito';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notificacion de usuarios que dejan carritos abandonados ';

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


        $carritos =  DB::table('alp_carrito')->select('alp_carrito.*','users.first_name as first_name','users.last_name as last_name','users.email as email')
          ->join('users','alp_carrito.id_user' , '=', 'users.id')
          ->where('alp_carrito.notificacion','=', 0)
          ->get();


        activity()->withProperties($carritos)->log('carritos');


      foreach ($carritos  as $car) {

        $date = Carbon::parse($car->created_at); 

        $now = Carbon::now();

        $diff = $date->diffInHours($now); 

        //dd($diff);

        if ($diff>0) {
            # code...


        $detalles =  DB::table('alp_carrito_detalle')->select('alp_carrito_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.descripcion_corta as descripcion_corta','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug','alp_productos.precio_base as precio_base')
          ->join('alp_productos','alp_carrito_detalle.id_producto' , '=', 'alp_productos.id')
          ->where('alp_carrito_detalle.id_carrito', $car->id)->get();

            Mail::to($car->email)->send(new \App\Mail\NotificacionCarrito($car, $detalles, $configuracion));

           Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionCarrito($car, $detalles, $configuracion));




            $arrayName = array('notificacion' => 1 );


            $ord=AlpCarrito::where('id', $car->id)->first();


            $ord->update($arrayName);

        }



      }



    }
}
