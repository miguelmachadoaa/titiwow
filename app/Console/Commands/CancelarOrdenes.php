<?php

namespace App\Console\Commands;

use App\User;
use App\Models\AlpOrdenes;
use App\Models\AlpOrdenesHistory;
use App\Models\AlpOrdenesDescuento;
use App\Models\AlpDetalles;
use App\Models\AlpInventario;
use App\Models\AlpConfiguracion;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Mail;
use DB;
use Illuminate\Support\Facades\Log;

use MP;

use Exception;


use Illuminate\Console\Command;

class CancelarOrdenes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cancelar:ordenes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancelar ordenes que esten vencidas segun tiempo establecido en configiracion ';

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

        $ordenes =  DB::table('alp_ordenes')->select('alp_ordenes.*') ->where('alp_ordenes.estatus','=', 8)->get();

        //$ordenes=AlpOrdenes::where('id', '5233')->get();

        foreach ($ordenes  as $orden) {

            $date = Carbon::parse($orden->created_at); 

            $now = Carbon::now();

            $diff = $date->diffInHours($now); 
           
            if ($diff>$configuracion->vence_ordenes) {
            
                # code...

                $ord=AlpOrdenes::where('id', $orden->id)->first();

                $arrayName = array('estatus' => 4, 'estatus_pago'=>3 );

                $ord->update($arrayName);

                $detalles=AlpDetalles::where('id_orden', $orden->id)->get();

                foreach ($detalles as $detalle) {

                      $data_inventario = array(
                        'id_producto' => $detalle->id_producto, 
                        'cantidad' =>$detalle->cantidad, 
                        'operacion' =>'1', 
                        'id_user' =>'1'
                      );

                      AlpInventario::create($data_inventario);
                    
                }

                $descuentos=AlpOrdenesDescuento::where('id_orden',$orden->id)->get();

                foreach ($descuentos as $desc) {
                    
                    $d=AlpOrdenesDescuento::where('id', $desc->id)->first();

                    $d->delete();

                }


                 if ($orden->id_almacen=='1') {

                    $this->sendcompramas($orden->id, 'rejected');
                    # code...
                  }



                     $configuracion = AlpConfiguracion::where('id', '1')->first();

                     if ($configuracion->mercadopago_sand=='1') {
                        
                        MP::sandbox_mode(TRUE);

                      }

                      if ($configuracion->mercadopago_sand=='2') {
                        
                        MP::sandbox_mode(FALSE);

                      }

                      MP::setCredenciales($configuracion->id_mercadopago, $configuracion->key_mercadopago);

                       $preference = MP::get("/v1/payments/search?external_reference=".$orden->referencia);

                      // dd($preference);

                        foreach ($preference['response']['results'] as $r) {

                            $idpago=$r['id'];

                           
                             $preference_data_cancelar = '{"status": "cancelled"}';

                             //dd($preference_data_cancelar);

                            $pre = MP::put("/v1/payments/".$idpago."", $preference_data_cancelar);

                            //dd($pre);
                               

                          }



                   
                          $descuentosIcg=AlpOrdenesDescuentoIcg::where('id_orden','=', $orden->id)->get();

                          $total_descuentos_icg=0;

                          foreach ($descuentosIcg as $pagoi) {

                            $total_descuentos_icg=$total_descuentos_icg+$pagoi->monto_descuento;

                          }

                          if ($total_descuentos_icg>0) {

                              $this->registroIcgCancelar($orden->id);

                            }



            }

        }

    }



     private function sendcompramas($id_orden, $estatus){


      $orden=AlpOrdenes::where('id', $id_orden)->first();

       $dataupdate = array(
          'ordenId' => $orden->referencia, 
          'status' => $estatus, 
        );


       $dataraw=json_encode($dataupdate);


        Log::useDailyFiles(storage_path().'/logs/compramas.log');
        
        Log::info('compramas dataraw '.json_encode($dataupdate));

         $configuracion = AlpConfiguracion::where('id','1')->first();

         $urls=$configuracion->compramas_url.'/updatedOrderReserved/'.$configuracion->compramas_hash;

           //   activity()->withProperties($urls)->log('compramas urls ');

               Log::info('compramas urls '.$urls);

                   // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $configuracion->compramas_url.'/updatedOrderReserved/'.$configuracion->compramas_hash);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $dataraw); 
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

                $headers = array();
                $headers[] = 'Content-Type: application/json';
                $headers[] = 'Woobsing-Token: '.$configuracion->compramas_token;
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
                curl_close($ch);


              

                  $res=json_decode($result);


                  $notas='Actualizar de orden en compramas.';

                  if (isset($res->mensaje)) {
                     $notas=$notas.$res->mensaje.' ';
                   }

                   if (isset($res->codigo)) {
                     $notas=$notas.$res->codigo.' ';
                   }


                   if (isset($res->message)) {
                     $notas=$notas.$res->message.' ';
                   }

                   if (isset($res->causa->message)) {
                     $notas=$notas.$res->causa->message.' ';
                   }

                   $notas=$notas.'Codigo: VF.';


                   Log::info('compramas result '.$result);

                    Log::info('compramas res '.json_encode($res));


                  if (isset($res->codigo)) {
                  
                  if ($res->codigo=='200') {

                    if ($estatus=='approved') {
                      

                       $dtt = array(
                        'json' => $result,
                        'estado_compramas' => $res->codigo,
                        'envio_compramas' => '2'
                      );


                    }


                    if ($estatus=='rejected') {
                      

                       $dtt = array(
                        'json' => $result,
                        'estado_compramas' => $res->codigo,
                        'envio_compramas' => '3'
                        
                      );

                       
                    }


                    

                      $orden->update($dtt);



                      

                      $texto=''.$res->mensaje.' Codigo Respuesta '.$res->codigo;

                         $data_history = array(
                          'id_orden' => $orden->id, 
                         'id_status' => '9', 
                          'notas' => $notas, 
                          'json' => json_encode($result), 
                         'id_user' => 1
                      );

                        $history=AlpOrdenesHistory::create($data_history);



                    Mail::to($configuracion->correo_sac)->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

                     Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));
                   
                  }else{

                     $dtt = array(
                        'json' => $result,
                        'estado_compramas' => $res->codigo
                      );

                      $orden->update($dtt);


                         $data_history = array(
                          'id_orden' => $orden->id, 
                         'id_status' => '9', 
                          'notas' => 'Error'.$notas, 
                          'json' => json_encode($result), 
                         'id_user' => 1
                      );

                        $history=AlpOrdenesHistory::create($data_history);


                    $texto=''.$res->mensaje.' Codigo Respuesta '.$res->codigo;

                    Mail::to($configuracion->correo_sac)->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

                     Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));


                  }


                }else{

                    $data_history = array(
                        'id_orden' => $orden->id, 
                       'id_status' => '9', 
                        'notas' => 'Respuesta '.$notas,
                        'json' => json_encode($result), 
                       'id_user' => 1
                    );

                    $history=AlpOrdenesHistory::create($data_history);


                      $texto='No se obtuvo respuesta de compramas. VP610';

                    Mail::to($configuracion->correo_sac)->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

                     Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

                     

                }



    }



     private function registroIcgCancelar($ordenId)
    {

      activity()->withProperties(1)
                        ->log('registro icg  ');

     //dd($id_orden);
      $configuracion=AlpConfiguracion::where('id', '=', 1)->first();

     
     $orden=AlpOrdenes::where('id', $ordenId)->first();

     $descuentosIcg=AlpOrdenesDescuentoIcg::where('id_orden', '=', $orden->id)->first();

     $monto_descuentoicg=0;

     if (isset($descuentosIcg->id)) {

       $monto_descuentoicg=$descuentosIcg->monto_descuento;

     }

      $c=AlpClientes::where('id_user_client', $orden->id_cliente)->first();



      
      $urls=$configuracion->endpoint_icg;

       Log::info('api icg urls '.$urls);


       $date = Carbon::now();

        $hoy=$date->format('YmdH:m:s');

        $fechad=$date->format('Ymd');
        $fechadt=$date->format('Y-m-d');
        $fechah=$date->format('H:m:s');
        $fecha=$fechad.' '.$fechah;
        $fecha_cont=$fechadt.'T'.$fechah;


         $data_consumo = array(
        'NumeroPedido' => $orden->referencia, 
        'Fecha' => $fecha_cont, 
        'DocumentoEmpleado' => $c->doc_cliente, 
        'FormaPago' => 'CONTADO', 
        'ValorTransaccion' => $orden->monto_total, 
        'ValorDescuento' => '-'.$monto_descuentoicg
      );

         $dataraw=json_encode($data_consumo);

activity()->withProperties($dataraw)->log('respuesta icg dataraw');

         $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, 'http://201.234.184.25:8099/api/cupo/cupoAplicar');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
      curl_setopt($ch, CURLOPT_POSTFIELDS, $dataraw); 
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

      $headers = array();
      $headers[] = 'Content-Type: application/json';
       $headers[] = 'apikeyalp2go: 1';
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

      $result = curl_exec($ch);
      if (curl_errno($ch)) {
          echo 'Error:' . curl_error($ch);
      }
      curl_close($ch);

      $res=json_decode($result);


activity()->withProperties($res)->log('registro consumo  icg res');

       

       $notas='Registro de orden en api icg res.';


      if (isset($res->CodigoRta)) {
        
        if ($res->CodigoRta=='OK') {

            $dataicg = array(
            'id_orden' => $carrito, 
            'doc_cliente' => $c->doc_cliente, 
            'monto_descuento' => 0, 
            'json' => json_encode($res), 
            'id_user' => $s_user, 
          );

          AlpConsultaIcg::create($dataicg);



          return $res;

           
        }else{

          $dataicg = array(
          'id_orden' => $carrito, 
          'doc_cliente' => $c->doc_cliente, 
          'monto_descuento' => 0, 
          'json' => json_encode($res), 
          'id_user' => $s_user, 
        );

        AlpConsultaIcg::create($dataicg);



          return $res;

        }


      }else{

        $dataicg = array(
          'id_orden' => $carrito, 
          'doc_cliente' => $c->doc_cliente, 
          'monto_descuento' => 0, 
          'json' => json_encode($res), 
          'id_user' => $s_user, 
        );

        AlpConsultaIcg::create($dataicg);

        return $res;

                       

      }

      
    }







}
