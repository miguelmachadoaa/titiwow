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


                 if ($orden->id_almacen=='1') {

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

                           #   echo json_encode($pre);

                            }

                        }

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


        #Log::useDailyFiles(storage_path().'/logs/compramas.log');
        
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























       
private function CancelarOrdenCompramas($id_orden)
{


  echo 'proceso envio a velocity   / ';


  $configuracion=AlpConfiguracion::first();
  
  $orden=AlpOrdenes::where('id', $id_orden)->first();


  echo $orden->id.'   / ';



  $almacen_pedido=AlpAlmacenes::where('id', $orden->id_almacen)->first();
  
  $formapago=AlpFormaspago::where('id', $orden->id_forma_pago)->first();

    //Log::info('compramas orden '.json_encode($orden));

  $detalles = AlpDetalles::select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.imagen_producto as imagen_producto','alp_productos.referencia_producto as referencia_producto','alp_productos.cantidad as cantidad_producto')
    ->join('alp_productos', 'alp_ordenes_detalle.id_producto', '=', 'alp_productos.id')
    ->where('alp_ordenes_detalle.id_orden', $orden->id)
    ->get();

              $productos = array();

              $peso=0;

              foreach ($detalles as $d) {

                $iva=0;

                if($d->monto_impuesto>0){

                  $iva=19;

                }

                $peso=$peso+$d->cantidad_producto;

                if ($d->precio_unitario>0) {

                  $dt = array(
                    'sku' => $d->referencia_producto, 
                    'name' => $d->nombre_producto, 
                    'url_img' => $d->imagen_producto, 
                    'value' => $d->precio_unitario, 
                    'value_prom' => $d->precio_unitario, 
                    'iva' => intval($iva), 
                    'quantity' => $d->cantidad
                  );

                  $productos[]=$dt;
                 
                }else{

                    if (substr($d->referencia_producto, 0,1)=='R') {
                       $dt = array(
                      'sku' => $d->referencia_producto, 
                      'name' => $d->nombre_producto, 
                      'url_img' => $d->imagen_producto, 
                      'value' => $d->precio_unitario, 
                      'value_prom' => $d->precio_unitario, 
                      'iva' => intval($iva),   
                      'quantity' => $d->cantidad
                    );

                    $productos[]=$dt;

                  }


                  $pc=AlpProductos::where('id', $d->id_combo)->first();

                  if (isset($pc->id)) {

                      if ($pc->tipo_producto=='3') {

                          
                        
                           $dt = array(
                          'sku' => $d->referencia_producto, 
                          'name' => $d->nombre_producto, 
                          'url_img' => $d->imagen_producto, 
                          'value' => $d->precio_unitario, 
                          'value_prom' => $d->precio_unitario, 
                          'iva' => intval($iva),  
                          'quantity' => $d->cantidad
                        );

                        $productos[]=$dt;

                      # code...
                    }

                  }

                }
            }

          $cliente =  User::select('users.*','roles.name as name_role','alp_clientes.estado_masterfile as estado_masterfile','alp_clientes.estado_registro as estado_registro','alp_clientes.telefono_cliente as telefono_cliente','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.cod_alpinista as cod_alpinista','alp_clientes.doc_cliente as doc_cliente')
            ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
            ->join('role_users', 'users.id', '=', 'role_users.user_id')
            ->join('roles', 'role_users.role_id', '=', 'roles.id')
            ->where('users.id', '=', $orden->id_user)->first();


          $direccion = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
          ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
          ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
          ->where('alp_direcciones.id', $orden->id_address)->withTrashed()->first();


          $dir = array(
            'ordenId' => $orden->referencia, 
            'ciudad' => $direccion->state_name, 
            'telefonoCliente' => $cliente->telefono_cliente, 
            'correoCliente' => $cliente->email, 
            'identificacionCliente' => $cliente->doc_cliente, 
            'nombreCliente' => $cliente->first_name." ".$cliente->last_name, 
            'direccionCliente' => $direccion->nombre_estructura." ".$direccion->principal_address." - ".$direccion->secundaria_address." ".$direccion->edificio_address." ".$direccion->detalle_address." ".$direccion->barrio_address, 
            'observacionDomicilio' => "", 
            'formaPago' => $formapago->nombre_forma_pago
          );

          $cupones=AlpOrdenesDescuento::where('id_orden', $orden->id)->get();

          $descuentoicg=AlpOrdenesDescuentoIcg::where('id_orden','=', $orden->id)->get();

          $descuento_total=0;

          echo 'Envio a velocity 1    / ';

          foreach($descuentoicg as $di){

            $descuento_total=$descuento_total+$di->monto_descuento;
          }

          foreach($cupones as $co){

            $descuento_total=$descuento_total+$co->monto_descuento;
          }

          $o = array(
            'tipoServicio' => 1, 
            'retorno' => "false", 
            'formaPago' => $formapago->nombre_forma_pago,
            'totalFactura' => $orden->monto_total, 
            'subTotal' => number_format($orden->monto_total-$orden->monto_impuesto, 2, '.', ''),
            'iva' => $orden->monto_impuesto, 
            'descuento' => $descuento_total, 
            'peso' => $peso, 
            'fechaPedido' => date("Ymd", strtotime($orden->created_at)), 
            'horaMinPedido' => "00:00", 
            'horaMaxPedido' => "00:00", 
            'observaciones' => "", 
            'paradas' => $dir, 
            'products' => $productos, 
          );

       #  dd($o);


    $dataraw=json_encode($o);

    echo "data / ".$dataraw;

    $url= 'https://ff.startupexpansion.co/api/v1/webhooks/alpinago/'.$orden->referencia.'/cancel';

    echo $dataraw.' - ';

    $orden->update(['send_json_masc'=>$dataraw]);

    Log::info($dataraw);

    activity()->withProperties($dataraw)->log('Envio Aprobado Velocity '.$orden->id.' .vp634');

  $ch = curl_init();

  #curl_setopt($ch, CURLOPT_URL, 'https://ff.logystix.co/api/v1/webhooks/alpinago?warehouse_id='.$almacen->codigo_almacen);
  curl_setopt($ch, CURLOPT_URL, 'https://ff.startupexpansion.co/api/v1/webhooks/alpinago/'.$orden->referencia.'/cancel');

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  #curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
  
  curl_setopt($ch, CURLOPT_POSTFIELDS, $dataraw); 
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

  $headers = array();
  $headers[] = 'Content-Type: application/json';
  $headers[] = 'Woobsing-Token: '.$configuracion->compramas_token;
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $result = curl_exec($ch);

  #$result=[];
  
  if (curl_errno($ch)) {
      echo 'Error:' . curl_error($ch);
  }
  curl_close($ch);

  $res=json_decode($result);

   Log::info('Respuesta de Velocity al registro de la orden '.json_encode($res));
   
   activity()->withProperties($res)->log('Datos de respuesta  a registro  de orden aprobada en Velocity orden id '.$orden->id.' .vp663');

   $notas='Registro de orden en Velocity.';


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


   $notas=$notas.'Codigo: VP.';


  if (isset($res->codigo)) {
    
    if ($res->codigo=='200') {

         $dtt = array(
            'json' => $result,
            'estado_compramas' => '3'
            
          );

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

        try {
          Mail::to($configuracion->correo_sac)->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

       Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));
          
        } catch (\Exception $e) {

          activity()->withProperties(1)
                    ->log('error envio de correo');
          
        }
      
     
    }else{

        $dtt = array(
          'json' => $result,
          'estado_compramas' => '5',
          'envio_compramas' => '0'
          
        );

        $orden->update($dtt);

      $texto=''.$res->mensaje.' Codigo Respuesta '.$res->codigo;

      $data_history = array(
          'id_orden' => $orden->id, 
          'id_status' => '9', 
          'notas' => 'Error '.$notas, 
          'json' => json_encode($result), 
         'id_user' => 1
      );

        $history=AlpOrdenesHistory::create($data_history);

        try {

          Mail::to($configuracion->correo_sac)->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

          Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));
          
        } catch (\Exception $e) {

          activity()->withProperties(1)
                    ->log('error envio de correo');
          
        }

    }

  }else{

    $notas='No hubo respuesta compramas';

    $data_history = array(
        'id_orden' => $orden->id, 
       'id_status' => '9', 
        'notas' => $notas,
        'json' => json_encode($result), 
       'id_user' => 1
    );

    $dtt = array(
      'json' => $result,
      'estado_compramas' => '5',
      'envio_compramas' => '0'
      
    );

    $orden->update($dtt);

    $history=AlpOrdenesHistory::create($data_history);

      $texto='No hubo respuesta compramas VP';

      try {
        
      Mail::to($configuracion->correo_sac)->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

       Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));
      } catch (\Exception $e) {

        activity()->withProperties(1)->log('error envio de correo');
        
      }
                 

  }


}






















}
