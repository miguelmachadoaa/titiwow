<?php

namespace App\Console\Commands;

use App\User;
use App\Models\AlpAlmacenes;
use App\Models\AlpConsultaIcg;
use App\Models\AlpClientes;
use App\Models\AlpConfiguracion;
use App\Models\AlpDetalles;
use App\Models\AlpInventario;
use App\Models\AlpOrdenes;
use App\Models\AlpOrdenesHistory;
use App\Models\AlpOrdenesDescuento;
use App\Models\AlpOrdenesDescuentoIcg;
use App\Models\AlpPagos;
use App\Models\AlpFormasPago;
use App\Models\AlpDirecciones;


use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Mail;
use DB;
use Illuminate\Support\Facades\Log;

use MercadoPago;

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

       # $ordenes=AlpOrdenes::where('id', '11039')->get();

        foreach ($ordenes  as $orden) {

            $date = Carbon::parse($orden->created_at); 

            $now = Carbon::now();

            $diff = $date->diffInHours($now); 
           
            if ($diff>$configuracion->vence_ordenes) {
           #if (1) {
            
                # code...

                $ord=AlpOrdenes::where('id', $orden->id)->first();

                $arrayName = array('estatus' => 4, 'estatus_pago'=>3 );

                $ord->update($arrayName);

                $detalles=AlpDetalles::where('id_orden', $orden->id)->get();

                foreach ($detalles as $detalle) {

                      $data_inventario = array(
                        'id_almacen' => $ord->id_almacen, 
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


                 if ($orden->id_almacen=='1' || $orden->id_almacen=='32') {

                    $this->CancelarOrdenCompramas($orden->id);
                    # code...
                  }



                     $configuracion = AlpConfiguracion::where('id', '1')->first();

                     $almacen=AlpAlmacenes::where('id', $orden->id_almacen)->first();

                    MercadoPago::setClientId($almacen->id_mercadopago);
                    MercadoPago::setClientSecret($almacen->key_mercadopago);

                     if ($configuracion->mercadopago_sand=='1') {
                        
                      MercadoPago::setPublicKey($almacen->public_key_mercadopago_test);

                      }

                      if ($configuracion->mercadopago_sand=='2') {
                        
                        MercadoPago::setPublicKey($almacen->public_key_mercadopago);

                      }


                       $preference = MercadoPago::get("/v1/payments/search?external_reference=".$orden->referencia_mp);

                      # echo json_encode($preference);


                      if(isset($preference['body']['results'])){

                        foreach ($preference['body']['results'] as $r) {

                            if(isset($r['id'])){

                              $idpago=$r['id'];

                             # echo $idpago,

                            //Aqui se cancela mercadopago 
                            
                              #$preference_data_cancelar = '{"status": "cancelled"}';
                              $preference_data_cancelar = array("statuses"=>"cancelled");

                              MercadoPago::setClientId($almacen->id_mercadopago);
                              MercadoPago::setClientSecret($almacen->key_mercadopago);

                              $at=MercadoPago::getAccessToken();

                            $ch = curl_init();

                            curl_setopt($ch, CURLOPT_URL, 'https://api.mercadopago.com/v1/payments/'.$idpago);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');

                            curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"status\": \"cancelled\"}");

                            $headers = array();
                            $headers[] = 'Authorization: Bearer '.$at;
                            $headers[] = 'Content-Type: application/json';
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                            $result = curl_exec($ch);
                            if (curl_errno($ch)) {
                                echo 'Error:' . curl_error($ch);
                            }
                            curl_close($ch);

                            activity()->withProperties($result)->log('respuesta cancelar pago de orden   '.$orden->id);

                            $res=json_decode($result);

                            $data_cancelar = array(
                              'id_orden' => $orden->id, 
                              'id_forma_pago' => $orden->id_forma_pago, 
                              'id_estatus_pago' => 4, 
                              'monto_pago' => $orden->monto_total, 
                              'json' => json_encode($res), 
                              'id_user' => '1'
                            );

                            AlpPagos::create($data_cancelar);

                            $data_history_json = array(
                              'id_orden' => $orden->id, 
                              'id_status' =>'4', 
                              'notas' => 'Cancelacion de pago en Mercadopago', 
                              'json' => json_encode($res), 
                              'id_user' => '1' 
                          );

                          $history=AlpOrdenesHistory::create($data_history_json);






                           #   echo json_encode($pre);

                            }

                        }

                      }



            }

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

       $ReferenciaRegistroAnulado=$descuentosIcg->aplicado;

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
        'NumeroPedido' => $orden->referencia.'-'.time(), 
        'Fecha' => $fecha_cont, 
        'DocumentoEmpleado' => $c->doc_cliente, 
        'FormaPago' => 'CONTADO', 
        'ValorTransaccion' => '-'.$orden->monto_total, 
        'ValorDescuento' => '-'.$monto_descuentoicg,
        'ReferenciaRegistroAnulado' => $ReferenciaRegistroAnulado
      );

        // dd($data_consumo);

         $dataraw=json_encode($data_consumo);

activity()->withProperties($dataraw)->log('cancelar icg dataraw');

         $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, 'https://qacupo.alpina.com:8099/api/cupo/cupoAplicar');
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


activity()->withProperties($res)->log('cancelar consumo  icg res');

       

       $notas='Cancelar de orden en api icg res.';


      if (isset($res->codigoRta)) {
        
        if ($res->codigoRta=='OK') {

            $dataicg = array(
            'id_orden' => $orden->id, 
            'doc_cliente' => $c->doc_cliente, 
            'monto_descuento' => 0, 
            'json' => json_encode($res), 
            'id_user' => '1', 
          );

          AlpConsultaIcg::create($dataicg);



          return $res;

           
        }else{

          $dataicg = array(
          'id_orden' => $orden->id, 
          'doc_cliente' => $c->doc_cliente, 
          'monto_descuento' => 0, 
          'json' => json_encode($res), 
          'id_user' => '1', 
        );

        AlpConsultaIcg::create($dataicg);



          return $res;

        }


      }else{

        $dataicg = array(
          'id_orden' => $orden->id, 
          'doc_cliente' => $c->doc_cliente, 
          'monto_descuento' => 0, 
          'json' => json_encode($res), 
          'id_user' => '1', 
        );  

        AlpConsultaIcg::create($dataicg);

        return $res;

                       

      }

      
    }







    public function cancelarMercadopago($id_orden){

      $user_id = Sentinel::getUser()->id;

      $orden=AlpOrdenes::where('id', $id_orden)->first();

      $configuracion = AlpConfiguracion::where('id', '1')->first();

       if ($configuracion->mercadopago_sand=='1') {
          
          MP::sandbox_mode(TRUE);

        }

        if ($configuracion->mercadopago_sand=='2') {
          
          MP::sandbox_mode(FALSE);

        }

        MP::setCredenciales($configuracion->id_mercadopago, $configuracion->key_mercadopago);

         $preference = MP::get("/v1/payments/search?external_reference=".$orden->referencia_mp);

          foreach ($preference['response']['results'] as $r) {

              $idpago=$r['id'];

               $preference_data_cancelar = '{"status": "cancelled"}';

              $pre = MP::put("/v1/payments/".$idpago."", $preference_data_cancelar);

              $data_cancelar = array(
                'id_orden' => $orden->id, 
                'id_forma_pago' => $orden->id_forma_pago, 
                'id_estatus_pago' => 4, 
                'monto_pago' => $orden->monto_total, 
                'json' => json_encode($pre), 
                'id_user' => $user_id
              );

              AlpPagos::create($data_cancelar);

               $data_history_json = array(
                'id_orden' => $orden->id, 
                'id_status' =>'4', 
                'notas' => 'Cancelacion de pago en Mercadopago', 
                'json' => json_encode($pre), 
                'id_user' => $user_id 
            );

            $history=AlpOrdenesHistory::create($data_history_json);

            }

    }






















       
       
public function CancelarOrdenCompramas()
{


  #echo 'proceso envio a velocity   / ';

    $id_orden=$this->idCancelar;

  $configuracion=AlpConfiguracion::first();
  
  $orden=AlpOrdenes::where('id', $id_orden)->first();

 # echo $orden->id.'   / ';

  $almacen_pedido=AlpAlmacenes::where('id', $orden->id_almacen)->first();
  

    //Log::info('compramas orden '.json_encode($orden));

           


    $dataraw=json_encode('');

  #  echo "data / ".$dataraw;

    $url= 'https://ff.logystix.co/api/v1/webhooks/alpinago/'.$orden->referencia.'/cancel';

   # echo $dataraw.' - ';

    //$orden->update(['send_json_masc'=>$dataraw]);

    Log::info($dataraw);

    activity()->withProperties($dataraw)->log('Envio Aprobado Velocity '.$orden->id.' .vp634');

  $ch = curl_init();

  #curl_setopt($ch, CURLOPT_URL, 'https://ff.logystix.co/api/v1/webhooks/alpinago?warehouse_id='.$almacen->codigo_almacen);
  #curl_setopt($ch, CURLOPT_URL, 'https://ff.startupexpansion.co/api/v1/webhooks/alpinago/'.$orden->referencia.'/cancel');
  curl_setopt($ch, CURLOPT_URL, 'https://ff.logystix.co/api/v1/webhooks/alpinago/'.$orden->referencia.'/cancel');

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  #curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
  
 // curl_setopt($ch, CURLOPT_POSTFIELDS, $dataraw); 
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

  $headers = array();
  $headers[] = 'Content-Type: application/json';
  $headers[] = 'Woobsing-Token: '.$configuracion->compramas_token;
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $result = curl_exec($ch);

  #$result='';
  
  if (curl_errno($ch)) {
      echo 'Error:' . curl_error($ch);
  }
  curl_close($ch);

  $res=json_decode($result);

   Log::info('Datos de respuesta  a registro  de orden cancelada  por tiempo en Velocity orden id '.$orden->id.' .el426'.json_encode($res));
   
   activity()->withProperties($res)->log('Datos de respuesta  a registro  de orden cancelada  por tiempo  en Velocity orden id '.$orden->id.' . el428');

   $notas='Cancelación de orden en Velocity por tiempo.';


   $notas=$notas.'Codigo: VP.554';

         $dtt = array(
            'json' => $result,
            'estado_compramas' => '3'
            
          );

        $orden->update($dtt);

        $texto='Orden Cancelada por tiempo';

        $data_history = array(
            'id_orden' => $orden->id, 
            'id_status' => '9', 
            'notas' => $notas, 
            'json' => json_encode($result), 
           'id_user' => 1
        );

        $history=AlpOrdenesHistory::create($data_history);


        $ord=AlpOrdenes::where('id', $orden->id)->first();

        $arrayName = array('estatus' => 4, 'estatus_pago'=>3 );

        $ord->update($arrayName);


        try {
          Mail::to($configuracion->correo_sac)->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

       Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));
          
        } catch (\Exception $e) {

          activity()->withProperties(1)
                    ->log('error envio de correo');
          
        }
      


}




















}
