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
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Log;


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


    //  dd(md5(time().time()));
        //
        $configuracion=AlpConfiguracion::where('id', '1')->first();

        $date = Carbon::now();

         $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $configuracion->compramas_url.'/checkinventory/'.$configuracion->compramas_hash);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Woobsing-Token: '.$configuracion->compramas_token;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error curl:' . curl_error($ch);
        }
        curl_close($ch);

        Log::useDailyFiles(storage_path().'/logs/compramas.log');

        Log::info($result);

     //   dd($result);


       $datos = json_decode($result);

       $inventario=$this->inventario();

       $almacen=1;

       if (is_null($datos)) {
           # code...
       }else{

       if (count($datos)) {

        AlpAlmacenProducto::where('id_almacen', '1')->delete();

            foreach ($datos as $dato ) {

              if ($dato->stock>0) {

                $p=AlpProductos::where('referencia_producto', $dato->sku)->first();

                    if (isset($p->id)) {

                        AlpInventario::where('id_almacen', '=', '1')->where('id_producto', $p->id)->delete();

                        $data = array(
                            'id_almacen' => $almacen, 
                            'id_producto' => $p->id, 
                            'id_user' => 1 
                        );

                        AlpAlmacenProducto::create($data);
                
                        $data_inventario_nuevo = array(
                            'id_almacen' => $almacen, 
                            'id_producto' => $p->id, 
                            'cantidad' => $dato->stock, 
                            'operacion' => 1, 
                            'notas' => 'Actualización de inventario por cron compramas', 
                            'id_user' => 1 
                        );

                        AlpInventario::create($data_inventario_nuevo);


                        # code...
                    }else{  //end fif $p->id

                        Log::info('Sku no encontrado:'.json_encode($dato));

                    } 
                } 
                
            } //end foreach datos
           
       } //(end if hay resspuessta)

       } 



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

                if (isset($inv[$row->id_producto])) {
                    $inv[$row->id_producto]= $inv[$row->id_producto]-$row->cantidad_total;
                }else{
                    $inv[$row->id_producto]= $row->cantidad_total;
                }
                
                

            }

            return $inv;
      
    }


}