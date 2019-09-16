<?php

namespace App\Console\Commands;

use App\User;
use App\Models\AlpConfiguracion;
use App\Models\AlpDirecciones;
use App\Models\AlpFeriados;
use App\Models\AlpFormaCiudad;
use App\Models\AlpFormasenvio;

use App\Models\AlpPagos;
use App\Models\AlpEnvios;
use App\Models\AlpOrdenes;
use App\Models\AlpOrdenesHistory;
use App\Exports\CronNuevosUsuarios;
use Maatwebsite\Excel\Facades\Excel;

use Carbon\Carbon;
use Mail;
use MP;
use DB;



use Illuminate\Console\Command;

class VerificarPagos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verificar:pagos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consultar estatus de los pagos a mercadopago ';

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

        $ordenes=AlpOrdenes::where('estatus_pago', '4')->get();
      
      ///dd($ordenes);

      $configuracion = AlpConfiguracion::where('id', '1')->first();

       if ($configuracion->mercadopago_sand=='1') {
          
          MP::sandbox_mode(TRUE);

        }

        if ($configuracion->mercadopago_sand=='2') {
          
          MP::sandbox_mode(FALSE);

        }

        MP::setCredenciales($configuracion->id_mercadopago, $configuracion->key_mercadopago);

        foreach ($ordenes as $ord) {

          $orden=AlpOrdenes::where('id', $ord->id)->first();

          $user_cliente=User::where('id', $ord->id_user)->first();

          $preference = MP::get("/v1/payments/search?external_reference=".$ord->referencia);

          if (isset($preference['response']['results'][0])) {

            $cantidad=count($preference['response']['results']);

            $aproved=0;
            $cancel=0;
            $pending=0;

            foreach ($preference['response']['results'] as $r) {
                    
                  if ($r['status']=='rejected' || $r['status']=='cancelled' || $r['status']=='refunded') {
                    $cancel=1;
                 

                  }

                  if ($r['status']=='approved') {
                    $aproved=1;
                  }

                  if ($r['status']=='in_process' || $r['status']=='pending') {

                    $pending=1;
                  }

                  //echo $r['status'].'<br>';

            }

            if ( $aproved ) 
              {

                  $direccion=AlpDirecciones::where('id', $ord->id_address)->first();

                $feriados=AlpFeriados::feriados();

                $ciudad_forma=AlpFormaCiudad::where('id_forma', $ord->id_forma_envio)->where('id_ciudad', $direccion->city_id)->first();


                $date = Carbon::now();

                $hora=$date->format('Hi');

                $hora_base=str_replace(':', '', $ciudad_forma->hora);

                if (intval($hora)>intval($hora_base)) {

                  $ciudad_forma->dias=$ciudad_forma->dias+1;

                }

                for ($i=1; $i <=$ciudad_forma->dias ; $i++) { 

                  $date2 = Carbon::now();

                  $date2->addDays($i);

                  if ($date2->isWeekend()) {

                    $ciudad_forma->dias=$ciudad_forma->dias+1;
                  
                  }else{

                    if (isset($feriados[$date2->format('Y-m-d')])) {

                        $ciudad_forma->dias=$ciudad_forma->dias+1;
                     
                    }

                  }

                  
                }

                $fecha_entrega=$date->addDays($ciudad_forma->dias)->format('d-m-Y');


                $data_envio = array(
                  'id_orden' => $ord->id, 
                  'fecha_envio' => $date->addDays($ciudad_forma->dias)->format('Y-m-d'),
                  'estatus' => 1, 
                  'id_user' =>1                   

                );

                $envio=AlpEnvios::create($data_envio);

                     $data_history = array(
                          'id_orden' => $ord->id, 
                         'id_status' => '1', 
                          'notas' => 'Notificacion Mercadopago', 
                         'id_user' => 1
                      );

                      $history=AlpOrdenesHistory::create($data_history);


                      $data_update = array(
                      'estatus' =>1, 
                      'estatus_pago' =>2,
                       );

                     $orden->update($data_update);

               $compra =  DB::table('alp_ordenes')->select('alp_ordenes.*','users.first_name as first_name','users.last_name as last_name' ,'users.email as email','alp_formas_envios.nombre_forma_envios as nombre_forma_envios','alp_formas_envios.descripcion_forma_envios as descripcion_forma_envios','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago','alp_formas_pagos.descripcion_forma_pago as descripcion_forma_pago','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.doc_cliente as doc_cliente')
                ->join('users','alp_ordenes.id_cliente' , '=', 'users.id')
                ->join('alp_clientes','alp_ordenes.id_cliente' , '=', 'alp_clientes.id_user_client')
               ->join('alp_formas_envios','alp_ordenes.id_forma_envio' , '=', 'alp_formas_envios.id')
               ->join('alp_formas_pagos','alp_ordenes.id_forma_pago' , '=', 'alp_formas_pagos.id')
               ->where('alp_ordenes.id', $orden->id)->first();

                $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug')
                  ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
                  ->where('alp_ordenes_detalle.id_orden', $orden->id)->get();

                  if ($compra->id_forma_envio!=1) {

                    $formaenvio=AlpFormasenvio::where('id', $compra->id_forma_envio)->first();

                    Mail::to($formaenvio->email)->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega,1));
                      
                  }

                  Mail::to($user_cliente->email)->send(new \App\Mail\CompraRealizada($compra, $detalles, $fecha_entrega));

                 Mail::to($configuracion->correo_sac)->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega));
               
            }elseif($pending){


            }elseif($cancel){

                $data_update = array(
                      'estatus' =>4, 
                      'estatus_pago' =>3,
                       );

                     $orden->update($data_update);

                      $data_history = array(
                          'id_orden' => $orden->id, 
                         'id_status' => '4', 
                          'notas' => 'Notificacion Mercadopago', 
                         'id_user' => 1
                      );

                        $history=AlpOrdenesHistory::create($data_history);

            }


          } //si hay resultados 

          if (isset($preference['response']['results'][0])) {
            # code...

              if ( $pending ) 
              {

              }else{


                  $data_pago = array(
                'id_orden' => $ord->id, 
                'id_forma_pago' => $ord->id_forma_pago, 
                'id_estatus_pago' => 4, 
                'monto_pago' => $ord->monto_total, 
                'json' => json_encode($preference['response']['results']), 
                'id_user' => '0' 
                  );

                 AlpPagos::create($data_pago);
              }

          }


        }//endforeach ordenes



    }//endhadle

}//endclass
