<?php

namespace App\Console\Commands;

use App\Models\AlpConfiguracion;
use App\Models\AlpProductos;
use App\Models\AlpAlmacenProducto;
use App\Models\AlpInventario;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Mail;
use DB;

use Illuminate\Console\Command;

class VerificarExistenciaAlmacen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verificar:almacen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualizar disponibles en almacen con api compra mas';

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

         $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://mercaas.com/api/checkinventory/YK7304PP34');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Woobsing-Token: f3f49185-4b8b-4918-b425-e6e3e9985349';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);


        \Log::debug('Respuesta compra mas' . $result);


       $datos = json_decode($result);

       $inventario=$this->inventario();

       $almacen=1;


       if (count($datos)) {

        AlpAlmacenProducto::where('id_almacen', '1')->delete();

            foreach ($datos as $dato ) {


                $p=AlpProductos::where('referencia_producto', $dato->sku)->first();

                if (isset($p->id)) {


                    $data = array(
                        'id_almacen' => $almacen, 
                        'id_producto' => $p->id, 
                        'id_user' => 1 
                    );

                    AlpAlmacenProducto::create($data);


                    if (isset($inventario[$p->id])) {
                    

                        $data_inventario = array(
                            'id_almacen' => $almacen, 
                            'id_producto' => $p->id, 
                            'cantidad' => abs($inventario[$p->id]), 
                            'operacion' => 2, 
                            'notas' => 'Actualización de inventario por cron compramas', 
                            'id_user' => 1 
                        );

                        //dd($data_inventario);   

                        AlpInventario::create($data_inventario);


                        $data_inventario_nuevo = array(
                            'id_almacen' => $almacen, 
                            'id_producto' => $p->id, 
                            'cantidad' => $dato->stock, 
                            'operacion' => 1, 
                            'notas' => 'Actualización de inventario por cron compramas', 
                            'id_user' => 1 
                        );

                        AlpInventario::create($data_inventario_nuevo);

                    }else{

                        $data_inventario_nuevo = array(
                            'id_almacen' => $almacen, 
                            'id_producto' => $p->id, 
                            'cantidad' => $row[1], 
                            'operacion' => 1, 
                            'notas' => 'Actualización de inventario por cron compramas', 
                            'id_user' => 1 
                        );

                        AlpInventario::create($data_inventario_nuevo);


                    }

                    # code...
                }else{  //end fif $p->id


                    \Log::debug('Sku no encontrado: ' . json_encode($dato));


                } 

                
            } //end foreach datos

           
       } //(end if hay resspuessta)


    }


     private function inventario()
    {
       
       $id_almacen=1;

      $entradas = AlpInventario::groupBy('id_producto')
              ->select("alp_inventarios.*", DB::raw(  "SUM(alp_inventarios.cantidad) as cantidad_total"))
              ->where('alp_inventarios.operacion', '1')
              ->where('alp_inventarios.id_almacen', '=', $id_almacen)
              ->get();

              $inv = array();

              foreach ($entradas as $row) {
                
                $inv[$row->id_producto]=$row->cantidad_total;

              }


            $salidas = AlpInventario::select("alp_inventarios.*", DB::raw(  "SUM(alp_inventarios.cantidad) as cantidad_total"))
              ->groupBy('id_producto')
              ->where('operacion', '2')
              ->where('alp_inventarios.id_almacen', '=', $id_almacen)
              ->get();

              foreach ($salidas as $row) {
                
                $inv[$row->id_producto]= $inv[$row->id_producto]-$row->cantidad_total;

            }

            return $inv;
      
    }








}
