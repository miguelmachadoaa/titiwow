<?php

namespace App\Console\Commands;

use App\User;
use App\Models\AlpConfiguracion;
use App\Models\AlpDirecciones;
use App\Models\AlpFeriados;
use App\Models\AlpFormaCiudad;
use App\Models\AlpFormasenvio;
use App\Models\AlpImpuestos;

use App\Models\AlpProductos;
use App\Models\AlpAlmacenes;
use App\Models\AlpPagos;
use App\Models\AlpEnvios;
use App\Models\AlpEnviosHistory;
use App\Models\AlpOrdenes;
use App\Models\AlpDetalles;
use App\Models\AlpOrdenesHistory;
use App\Models\AlpOrdenesDescuento;
use App\Models\AlpClientes;

use App\Models\AlpLifeMiles;
use App\Models\AlpLifeMilesCodigos;
use App\Models\AlpLifeMilesOrden;

use App\Models\AlpOrdenesDescuentoIcg;
use App\Models\AlpConsultaIcg;
use App\Exports\CronNuevosUsuarios;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Mail;

use MercadoPago;
use DB;
use Exception;



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

      $date = Carbon::now();


      $d=$date->subDay(3)->format('Y-m-d');

      #  $ordenes_id=AlpOrdenes::select('alp_ordenes.id')->where('estatus_pago', '4')->where('countvp','<', '5')->whereDate('created_at','>=', $d)->get();

        $ordenes=AlpOrdenes::where('estatus_pago', '4')->where('countvp','<', '5')->whereDate('created_at','>=', $d)->get();

        #$ordenes=AlpOrdenes::where('id', '22357')->get();
        //
        
     # Log::info('ordenes a verficar  '.json_encode($ordenes_id));

        $configuracion = AlpConfiguracion::where('id', '1')->first();

        if (count($ordenes)) {
       
        foreach ($ordenes as $ord) {

          $almacen=AlpAlmacenes::where('id', $ord->id_almacen)->first();

          $orden=AlpOrdenes::where('id', $ord->id)->first();

          $orden->update(['countvp'=>$orden->countvp+1]);

          $user_cliente=User::where('id', $ord->id_user)->first();


          if($orden->id_forma_pago=='2'){

              if (isset($almacen->id)) {

                if (!is_null($almacen->id_mercadopago) &&  !is_null($almacen->key_mercadopago)) {
    
                  MercadoPago::setClientId($almacen->id_mercadopago);
                  MercadoPago::setClientSecret($almacen->key_mercadopago);
                  
              
              if ($almacen->mercadopago_sand=='1') {
    

                  MercadoPago::setPublicKey($almacen->public_key_mercadopago_test);

                  
                
                }
            
                if ($almacen->mercadopago_sand=='2') {
    
                  MercadoPago::setPublicKey($almacen->public_key_mercadopago);
                  
                }
    
                

                #Log::info('id ordenva verficar  '.json_encode($ord->id));
                #Log::info('id ordenva verficar  '.json_encode($ord->referencia_mp));
    
                  try {
    
                    $preference = Mercadopago::get("/v1/payments/search?external_reference=".$ord->referencia_mp);
    
                  } catch (MercadoPagoException $e) {
    
                    $preference = array('1');
                    
                  }
    
                }else{
    
                  $preference = array('2');
    
                }
                
            }else{
    
              $preference = array('3');
            }

            

           // dd(json_encode($preference));
    
            #Log::info('Respuesta mercadopago  '.json_encode($preference));

            //se procesa por mercadopago 
            //toda la logica se paso a esta funcion 
                
            $this->procesarMercadopago($preference, $ord->id);


          }

          if($orden->id_forma_pago=='4'){

            $this->procesarTarjeta($ord->id);


          }


          if($orden->id_forma_pago=='6'){

            $this->procesarEpayco($orden->id);

          }


        }//endforeach ordenes

      }//endifhay ordenes 

    }//endhadle










    private function procesarEpayco($id_orden){

     ## Log::info('procesar epayco   '.json_encode($id_orden));

      $configuracion = AlpConfiguracion::where('id', '1')->first();

           $orden=AlpOrdenes::where('id', $id_orden)->first();

           $user_cliente=User::where('id', $orden->id_user)->first();

           $almacen=AlpAlmacenes::where('id', $orden->id_almacen)->first();

            $pago=AlpPagos::where('id_orden', $id_orden)->first();


            Log::info('pago epayco   '.json_encode($pago));

            if (isset($pago->referencia)) {

                \Log::debug('pago a verificar' . $pago);

                $datos = array(
                'public_key' => $almacen->epayco_public_key, 
                'refPayco' => $pago->referencia 
                );

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, 'https://apiservices.epayco.co/consulta/transaccion?public_key='.$almacen->epayco_public_key.'&refPayco='.$pago->referencia);

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                curl_setopt($ch, CURLOPT_POST, 1);

                $headers = array();

                $headers[] = 'Accept: application/json';

                //$headers[] = 'Paypal-Request-Id: YL_PLAN_12';

                $headers[] = 'Prefer: return=representation';

                $headers[] = 'Content-Type: application/json';

                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result2 = curl_exec($ch);

                if (curl_errno($ch)) {

                    echo 'Error:' . curl_error($ch);

                }

                curl_close($ch);

                $datos2=json_decode($result2);

                \Log::debug('Orden '.$orden->id.'Json: ' . $result2);


                $cancel=0;
              $aproved=0;
              $pending=0;



                if (isset($datos2->success)) {

                  if ($datos2->success) {

                      foreach ($datos2->data->data as $r) {

                        if ($r->tipo_cod_respuesta=='2' || $r->tipo_cod_respuesta=='4' || $r->tipo_cod_respuesta=='9' || $r->tipo_cod_respuesta=='6' || $r->tipo_cod_respuesta=='10' || $r->tipo_cod_respuesta=='11' || $r->tipo_cod_respuesta=='12' || $r->tipo_cod_respuesta=='7') {

                          $cancel=1;

                        }

                        if ($r->tipo_cod_respuesta=='1') {

                          $aproved=1;

                        }

                        if ($r->tipo_cod_respuesta=='3' || $r->tipo_cod_respuesta=='8') {

                          $pending=1;

                        }

                      }


                      if ( $aproved )   {

                          $configuracion = AlpConfiguracion::where('id', '1')->first();

                          $direccion=AlpDirecciones::where('id', $orden->id_address)->withTrashed()->first();

                          //dd($direccion);

                          $feriados=AlpFeriados::feriados();

                          $ciudad_forma=AlpFormaCiudad::where('id_forma', $orden->id_forma_envio)->where('id_ciudad', $direccion->city_id)->first();

                          $date = Carbon::now();

                          $hora=$date->format('Hi');

                          $hora_base=str_replace(':', '', $ciudad_forma->hora);

                          if (intval($hora)>intval($hora_base)) {

                            $ciudad_forma->dias=$ciudad_forma->dias+1;

                          }

                          for ($i=1; $i <=$ciudad_forma->dias ; $i++) { 

                            $date2 = Carbon::now();

                            $date2->addDays($i);

                            if ($date2->isSunday()) {

                              $ciudad_forma->dias=$ciudad_forma->dias+1;
                            
                            }else{

                              if (isset($feriados[$date2->format('Y-m-d')])) {

                                  $ciudad_forma->dias=$ciudad_forma->dias+1;
                                
                              }

                            }

                            
                          }

                          $fecha_entrega=$date->addDays($ciudad_forma->dias)->format('d-m-Y');



                          $envio=$ciudad_forma->costo;

                          $valor_impuesto=AlpImpuestos::where('id', '1')->first();

                            if ($envio>0) {
                              
                                $envio_base=$envio/(1+$valor_impuesto->valor_impuesto);

                                $envio_impuesto=$envio_base*$valor_impuesto->valor_impuesto;


                            }else{

                                $envio_base=0;

                                $envio_impuesto=0;

                            }

                              $data_envio = array(
                                'id_orden' => $orden->id, 
                                'fecha_envio' => $fecha_entrega,
                                'costo' => $envio, 
                                'costo_base' => $envio_base, 
                                'costo_impuesto' => $envio_impuesto, 
                                'estatus' => 1, 
                                'id_user' =>1                   
                              );

                              $envio=AlpEnvios::create($data_envio);

                              $data_envio_history = array(
                                'id_envio' => $envio->id, 
                                'estatus_envio' => 1, 
                                'nota' => 'Envio Generado por Verificar Pagos', 
                                'id_user' =>1                 

                              );

                              AlpEnviosHistory::create($data_envio_history);


                                $data_update = array(
                                'estatus' =>1, 
                                'estatus_pago' =>2,
                                  );


                                $orden->update($data_update);


                                $data_pago = array(
                                  'id_orden' => $orden->id, 
                                  'id_forma_pago' => $orden->id_forma_pago, 
                                  'id_estatus_pago' => '2', 
                                  'monto_pago' => $orden->monto_total, 
                                  'json' => json_encode($datos2), 
                                  'id_user' => '1'
                                );


                                AlpPagos::create($data_pago);

                          if ($orden->id_almacen==1) {

                            try {
                              # $this->sendcompramas($orden->id, 'approved');

                              $this->registrarOrden($orden->id);
                              $this->registrarOrdenNuevo($orden->id);


                            } catch (\Exception $e) {

                              activity()->withProperties($orden)->log('error compramas vp l355');
                              
                            }

                            }


                            $compra =  DB::table('alp_ordenes')->select('alp_ordenes.*','users.first_name as first_name','users.last_name as last_name' ,'users.email as email','alp_formas_envios.nombre_forma_envios as nombre_forma_envios','alp_formas_envios.descripcion_forma_envios as descripcion_forma_envios','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago','alp_formas_pagos.descripcion_forma_pago as descripcion_forma_pago','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.doc_cliente as doc_cliente')
                            ->join('users','alp_ordenes.id_cliente' , '=', 'users.id')
                            ->join('alp_clientes','alp_ordenes.id_cliente' , '=', 'alp_clientes.id_user_client')
                          ->join('alp_formas_envios','alp_ordenes.id_forma_envio' , '=', 'alp_formas_envios.id')
                          ->join('alp_formas_pagos','alp_ordenes.id_forma_pago' , '=', 'alp_formas_pagos.id')
                          ->where('alp_ordenes.id', $orden->id)->first();


                            $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*',
                              'alp_productos.presentacion_producto as presentacion_producto',
                              'alp_productos.nombre_producto as nombre_producto',
                              'alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug')
                              ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
                              ->where('alp_ordenes_detalle.id_orden', $orden->id)
                              ->whereNull('alp_ordenes_detalle.deleted_at')
                              ->get();



                            $orden=AlpOrdenes::where('id', $orden->id)->first();

                            $detalles = AlpDetalles::select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.imagen_producto as imagen_producto','alp_productos.referencia_producto as referencia_producto','alp_productos.tipo_producto as tipo_producto')
                              ->join('alp_productos', 'alp_ordenes_detalle.id_producto', '=', 'alp_productos.id')
                              ->where('alp_ordenes_detalle.id_orden', $orden->id)
                              ->whereNull('alp_ordenes_detalle.deleted_at')
                              ->get();

                              $productos = array();

                              foreach ($detalles as $d) {
                                
                                  $dt = array(
                                    'sku' => $d->referencia_producto, 
                                    'name' => $d->nombre_producto, 
                                    'url_img' => $d->imagen_producto, 
                                    'value' => $d->precio_unitario, 
                                    'value_prom' => $d->precio_unitario, 
                                    'quantity' => $d->cantidad
                                  );

                                  $productos[]=$dt;
                              }



                          $cliente =  User::select('users.*','roles.name as name_role','alp_clientes.estado_masterfile as estado_masterfile','alp_clientes.estado_registro as estado_registro','alp_clientes.telefono_cliente as telefono_cliente','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.cod_alpinista as cod_alpinista','alp_clientes.doc_cliente as doc_cliente')
                            ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
                            ->join('role_users', 'users.id', '=', 'role_users.user_id')
                            ->join('roles', 'role_users.role_id', '=', 'roles.id')
                            ->where('users.id', '=', $orden->id_user)->first();


                          // dd($cliente);


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
                            'identificacionCliente' => $cliente->doc_cliente, 
                            'nombreCliente' => $cliente->first_name." ".$cliente->last_name, 
                            'direccionCliente' => $direccion->nombre_estructura." ".$direccion->principal_address." - ".$direccion->secundaria_address." ".$direccion->edificio_address." ".$direccion->detalle_address." ".$direccion->barrio_address, 
                            'observacionDomicilio' => "", 
                            'formaPago' => "Efectivo"
                          );

                          $o = array(
                            'tipoServicio' => 1, 
                            'retorno' => "false", 
                            'totalFactura' => $orden->monto_total, 
                            'subTotal' => $orden->monto_total-$orden->monto_impuesto, 
                            'iva' => $orden->monto_impuesto, 
                            'fechaPedido' => date("Ymd", strtotime($orden->created_at)), 
                            'horaMinPedido' => "00:00", 
                            'horaMaxPedido' => "00:00", 
                            'observaciones' => "", 
                            'paradas' => $dir, 
                            'products' => $productos, 
                          );


                          $dataraw=json_encode($o);




                          if ($compra->id_forma_envio!=1) {

                              try {

                                $formaenvio=AlpFormasenvio::where('id', $compra->id_forma_envio)->first();

                                Mail::to($formaenvio->email)->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega,1));

                                Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega,1));
                                
                                } catch (\Exception $e) {

                                  activity()->withProperties(1)->log('Error de correo vp354');
                            
                              
                                }

                              try {

                                # $this->ibmConfirmarCompra($user_cliente, $orden);

                                # $this->ibmConfirmarPago($user_cliente, $orden);

                                # $this->ibmConfirmarEnvio($user_cliente, $orden, $envio);
                                
                              } catch (\Exception $e) {

                                activity()->withProperties(1)->log('Error de ibm vp372');
                                
                              }

                                                    
                            }


                            

                            try {

                                Mail::to($user_cliente->email)->send(new \App\Mail\CompraRealizada($compra, $detalles, $fecha_entrega));

                                Mail::to($configuracion->correo_sac)->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega));


                                Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\CompraRealizada($compra, $detalles, $fecha_entrega));

                                Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega));
                              
                            } catch (\Exception $e) {

                              activity()->withProperties(1)->log('Error de correo vp408');
                              
                            }




                            foreach ($detalles as $d ) {

                              if ($d->tipo_producto=='4') {

                                $prod=AlpProductos::Where('id_producto', '=', $d->id_producto)->first();

                                  Mail::to($user_cliente->email)->send(new \App\Mail\NotificacionDigital($prod));
                                
                                  Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionDigital($prod));

                              }
                              # code...
                            }

                          
                      }elseif($pending){
                        

                      }elseif($cancel){

                          $configuracion = AlpConfiguracion::where('id', '1')->first();


                          $date = Carbon::parse($orden->created_at); 

                          $now = Carbon::now();

                          $diff = $date->diffInMinutes($now); 
                          

                          if ($diff>$configuracion->vence_ordenes_pago) {

                          //dd($diff);

                            $data_update = array(
                              'estatus' =>4, 
                              'estatus_pago' =>3,
                                );

                              $orden->update($data_update);

                              $data_history = array(
                                'id_orden' => $orden->id, 
                                'id_status' => '4', 
                                'notas' => 'Notificacion Mercadopago Cron',
                                'id_user' => 1
                              );

                              $history=AlpOrdenesHistory::create($data_history);

                              $descuentos=AlpOrdenesDescuento::where('id_orden', $orden->id)->get();

                                foreach ($descuentos as $desc) {
                                  
                                  $d=AlpOrdenesDescuento::where('id', $desc->id)->first();

                                  $d->delete();

                                }


                                /* try {

                                    if ($orden->id_almacen=='1') {

                                      // $this->sendcompramas($orden->id, 'rejected');

                                      $this->cancelarCompramas($orden->id);


                                      # code...
                                    }
                                  
                                } catch (\Exception $e) {

                                  activity()->withProperties(1)->log('Error de compramas vp477');
                                  
                                }*/


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
                  
                }
            
            }

    }



    private function procesarTarjeta($id_orden){

      $orden=AlpOrdenes::where('id', $id_orden)->first();

      $user_cliente=User::where('id', $orden->id_user)->first();

      $direccion=AlpDirecciones::where('id', $orden->id_address)->withTrashed()->first();

               //dd($direccion);

               $feriados=AlpFeriados::feriados();

               $ciudad_forma=AlpFormaCiudad::where('id_forma', $orden->id_forma_envio)->where('id_ciudad', $direccion->city_id)->first();

               $date = Carbon::now();

               $hora=$date->format('Hi');

               $hora_base=str_replace(':', '', $ciudad_forma->hora);

               if (intval($hora)>intval($hora_base)) {

                 $ciudad_forma->dias=$ciudad_forma->dias+1;

               }

               for ($i=1; $i <=$ciudad_forma->dias ; $i++) { 

                 $date2 = Carbon::now();

                 $date2->addDays($i);

                 if ($date2->isSunday()) {

                   $ciudad_forma->dias=$ciudad_forma->dias+1;
                 
                 }else{

                   if (isset($feriados[$date2->format('Y-m-d')])) {

                       $ciudad_forma->dias=$ciudad_forma->dias+1;
                    
                   }

                 }

                 
               }

               $fecha_entrega=$date->addDays($ciudad_forma->dias)->format('d-m-Y');



               $envio=$ciudad_forma->costo;

               $valor_impuesto=AlpImpuestos::where('id', '1')->first();

                 if ($envio>0) {
                  
                    $envio_base=$envio/(1+$valor_impuesto->valor_impuesto);

                     $envio_impuesto=$envio_base*$valor_impuesto->valor_impuesto;


                 }else{

                     $envio_base=0;

                     $envio_impuesto=0;

                 }

                   $data_envio = array(
                     'id_orden' => $orden->id, 
                     'fecha_envio' => $fecha_entrega,
                     'costo' => $envio, 
                     'costo_base' => $envio_base, 
                     'costo_impuesto' => $envio_impuesto, 
                     'estatus' => 1, 
                     'id_user' =>1                   
                   );

                   $envio=AlpEnvios::create($data_envio);

                   $data_envio_history = array(
                     'id_envio' => $envio->id, 
                     'estatus_envio' => 1, 
                     'nota' => 'Envio Generado por Verificar Pagos', 
                     'id_user' =>1                 

                   );

                   AlpEnviosHistory::create($data_envio_history);


                     $data_update = array(
                     'estatus' =>1, 
                     'estatus_pago' =>2,
                      );


                    $orden->update($data_update);


                     $data_pago = array(
                       'id_orden' => $orden->id, 
                       'id_forma_pago' => $orden->id_forma_pago, 
                       'id_estatus_pago' => '2', 
                       'monto_pago' => $orden->monto_total, 
                       'json' => json_encode(''), 
                       'id_user' => '1'
                     );


                    AlpPagos::create($data_pago);

              if ($orden->id_almacen==1) {

                try {
                  # $this->sendcompramas($orden->id, 'approved');

                  $this->registrarOrden($orden->id);
                  $this->registrarOrdenNuevo($orden->id);


                } catch (\Exception $e) {

                  activity()->withProperties($orden)->log('error compramas vp l355');
                  
                }

                }


                $compra =  DB::table('alp_ordenes')->select('alp_ordenes.*','users.first_name as first_name','users.last_name as last_name' ,'users.email as email','alp_formas_envios.nombre_forma_envios as nombre_forma_envios','alp_formas_envios.descripcion_forma_envios as descripcion_forma_envios','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago','alp_formas_pagos.descripcion_forma_pago as descripcion_forma_pago','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.doc_cliente as doc_cliente')
                ->join('users','alp_ordenes.id_cliente' , '=', 'users.id')
                ->join('alp_clientes','alp_ordenes.id_cliente' , '=', 'alp_clientes.id_user_client')
               ->join('alp_formas_envios','alp_ordenes.id_forma_envio' , '=', 'alp_formas_envios.id')
               ->join('alp_formas_pagos','alp_ordenes.id_forma_pago' , '=', 'alp_formas_pagos.id')
               ->where('alp_ordenes.id', $orden->id)->first();


                $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*',
                  'alp_productos.presentacion_producto as presentacion_producto',
                  'alp_productos.nombre_producto as nombre_producto',
                  'alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug')
                  ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
                  ->where('alp_ordenes_detalle.id_orden', $orden->id)
                  ->whereNull('alp_ordenes_detalle.deleted_at')
                  ->get();



                 $orden=AlpOrdenes::where('id', $orden->id)->first();

                 $detalles = AlpDetalles::select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.imagen_producto as imagen_producto','alp_productos.referencia_producto as referencia_producto','alp_productos.tipo_producto as tipo_producto')
                  ->join('alp_productos', 'alp_ordenes_detalle.id_producto', '=', 'alp_productos.id')
                  ->where('alp_ordenes_detalle.id_orden', $orden->id)
                  ->whereNull('alp_ordenes_detalle.deleted_at')
                  ->get();

                  $productos = array();

                  foreach ($detalles as $d) {
                    
                      $dt = array(
                        'sku' => $d->referencia_producto, 
                        'name' => $d->nombre_producto, 
                        'url_img' => $d->imagen_producto, 
                        'value' => $d->precio_unitario, 
                        'value_prom' => $d->precio_unitario, 
                        'quantity' => $d->cantidad
                      );

                      $productos[]=$dt;
                  }



              $cliente =  User::select('users.*','roles.name as name_role','alp_clientes.estado_masterfile as estado_masterfile','alp_clientes.estado_registro as estado_registro','alp_clientes.telefono_cliente as telefono_cliente','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.cod_alpinista as cod_alpinista','alp_clientes.doc_cliente as doc_cliente')
                ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
                ->join('role_users', 'users.id', '=', 'role_users.user_id')
                ->join('roles', 'role_users.role_id', '=', 'roles.id')
                ->where('users.id', '=', $orden->id_user)->first();


               // dd($cliente);


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
                'identificacionCliente' => $cliente->doc_cliente, 
                'nombreCliente' => $cliente->first_name." ".$cliente->last_name, 
                'direccionCliente' => $direccion->nombre_estructura." ".$direccion->principal_address." - ".$direccion->secundaria_address." ".$direccion->edificio_address." ".$direccion->detalle_address." ".$direccion->barrio_address, 
                'observacionDomicilio' => "", 
                'formaPago' => "Efectivo"
              );

              $o = array(
                'tipoServicio' => 1, 
                'retorno' => "false", 
                'totalFactura' => $orden->monto_total, 
                'subTotal' => $orden->monto_total-$orden->monto_impuesto, 
                'iva' => $orden->monto_impuesto, 
                'fechaPedido' => date("Ymd", strtotime($orden->created_at)), 
                'horaMinPedido' => "00:00", 
                'horaMaxPedido' => "00:00", 
                'observaciones' => "", 
                'paradas' => $dir, 
                'products' => $productos, 
              );


              $dataraw=json_encode($o);



              if ($orden->id_forma_envio!=1) {

                   try {

                     $formaenvio=AlpFormasenvio::where('id', $compra->id_forma_envio)->first();

                     Mail::to($formaenvio->email)->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega,1));

                     Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega,1));
                     
                     } catch (\Exception $e) {

                       activity()->withProperties(1)->log('Error de correo vp354');
                 
                   
                     }

                   try {

                     # $this->ibmConfirmarCompra($user_cliente, $orden);

                    # $this->ibmConfirmarPago($user_cliente, $orden);

                    # $this->ibmConfirmarEnvio($user_cliente, $orden, $envio);
                     
                   } catch (\Exception $e) {

                     activity()->withProperties(1)->log('Error de ibm vp372');
                     
                   }

                                        
                 }


                

                 try {

                    Mail::to($user_cliente->email)->send(new \App\Mail\CompraRealizada($compra, $detalles, $fecha_entrega));

                    Mail::to($configuracion->correo_sac)->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega));


                     Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\CompraRealizada($compra, $detalles, $fecha_entrega));

                    Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega));
                   
                 } catch (\Exception $e) {

                   activity()->withProperties(1)->log('Error de correo vp408');
                   
                 }


                 foreach ($detalles as $d ) {

                   if ($d->tipo_producto=='4') {

                     $prod=AlpProductos::Where('id_producto', '=', $d->id_producto)->first();

                       Mail::to($user_cliente->email)->send(new \App\Mail\NotificacionDigital($prod));
                     
                       Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionDigital($prod));

                   }
                   # code...
                 }




    }


    private function procesarMercadopago($preference, $id_orden){

      

        $configuracion = AlpConfiguracion::where('id', '1')->first();

      $orden=AlpOrdenes::where('id', $id_orden)->first();

      $user_cliente=User::where('id', $orden->id_user)->first();

      if (isset($preference['body']['results'])) {
        #if (isset($preference)) {

           $cantidad=count($preference['body']['results']);

           $aproved=0;

           $cancel=0;
           $pending=0;

           foreach ($preference['body']['results'] as $r) {

             $idpago=$r['id'];

            #d($idpago);

                   
                 if ($r['status']=='rejected' || $r['status']=='cancelled' || $r['status']=='refunded') {
                   $cancel=1;
                 }

                 if ($r['status']=='approved') {
                   $aproved=1;
                 }

                 if ($r['status']=='in_process' || $r['status']=='pending') {
                   $pending=1;
                 }

           }

           if ( $aproved ) 
             {

               $direccion=AlpDirecciones::where('id', $orden->id_address)->withTrashed()->first();

               //dd($direccion);

               $feriados=AlpFeriados::feriados();

               $ciudad_forma=AlpFormaCiudad::where('id_forma', $orden->id_forma_envio)->where('id_ciudad', $direccion->city_id)->first();

               $date = Carbon::now();

               $hora=$date->format('Hi');

               $hora_base=str_replace(':', '', $ciudad_forma->hora);

               if (intval($hora)>intval($hora_base)) {

                 $ciudad_forma->dias=$ciudad_forma->dias+1;

               }

               for ($i=1; $i <=$ciudad_forma->dias ; $i++) { 

                 $date2 = Carbon::now();

                 $date2->addDays($i);

                 if ($date2->isSunday()) {

                   $ciudad_forma->dias=$ciudad_forma->dias+1;
                 
                 }else{

                   if (isset($feriados[$date2->format('Y-m-d')])) {

                       $ciudad_forma->dias=$ciudad_forma->dias+1;
                    
                   }

                 }

                 
               }

               $fecha_entrega=$date->addDays($ciudad_forma->dias)->format('d-m-Y');



               $envio=$ciudad_forma->costo;

               $valor_impuesto=AlpImpuestos::where('id', '1')->first();

                 if ($envio>0) {
                  
                    $envio_base=$envio/(1+$valor_impuesto->valor_impuesto);

                     $envio_impuesto=$envio_base*$valor_impuesto->valor_impuesto;


                 }else{

                     $envio_base=0;

                     $envio_impuesto=0;

                 }

                   $data_envio = array(
                     'id_orden' => $orden->id, 
                     'fecha_envio' => $fecha_entrega,
                     'costo' => $envio, 
                     'costo_base' => $envio_base, 
                     'costo_impuesto' => $envio_impuesto, 
                     'estatus' => 1, 
                     'id_user' =>1                   
                   );

                   $envio=AlpEnvios::create($data_envio);

                   $data_envio_history = array(
                     'id_envio' => $envio->id, 
                     'estatus_envio' => 1, 
                     'nota' => 'Envio Generado por Verificar Pagos', 
                     'id_user' =>1                 

                   );

                   AlpEnviosHistory::create($data_envio_history);


                     $data_update = array(
                     'estatus' =>1, 
                     'estatus_pago' =>2,
                      );


                    $orden->update($data_update);



                    $data_json = array(
                      'id' => $r['id'], 
                      'operation_type' =>$r['operation_type'], 
                      'payment_method_id' =>$r['payment_method_id'], 
                      'payment_type_id' =>$r['payment_type_id'], 
                      'external_reference' => $r['external_reference'], 
                      'status' => $r['status'], 
                      'status_detail' =>$r['status_detail'], 
                      'transaction_amount' =>$r['transaction_amount'], 
                      'external_resource_url' =>$r['transaction_details']['external_resource_url'] 
                    );




                     $data_pago = array(
                       'id_orden' => $orden->id, 
                       'id_forma_pago' => $orden->id_forma_pago, 
                       'id_estatus_pago' => '2', 
                       'monto_pago' => $orden->monto_total,
                       'referencia' => $r['id'], 
                       'metodo' => $r['payment_method_id'], 
                       'tipo' => $r['payment_type_id'], 
                       'json' => json_encode($data_json), 
                       'id_user' => '1'
                     );


                    AlpPagos::create($data_pago);

              if ($orden->id_almacen==1) {

                try {
                  # $this->sendcompramas($orden->id, 'approved');

                  $this->registrarOrden($orden->id);
                  $this->registrarOrdenNuevo($orden->id);


                } catch (\Exception $e) {

                  activity()->withProperties($orden)->log('error compramas vp l355');
                  
                }

                }


                $compra =  DB::table('alp_ordenes')->select('alp_ordenes.*','users.first_name as first_name','users.last_name as last_name' ,'users.email as email','alp_formas_envios.nombre_forma_envios as nombre_forma_envios','alp_formas_envios.descripcion_forma_envios as descripcion_forma_envios','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago','alp_formas_pagos.descripcion_forma_pago as descripcion_forma_pago','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.doc_cliente as doc_cliente')
                ->join('users','alp_ordenes.id_cliente' , '=', 'users.id')
                ->join('alp_clientes','alp_ordenes.id_cliente' , '=', 'alp_clientes.id_user_client')
               ->join('alp_formas_envios','alp_ordenes.id_forma_envio' , '=', 'alp_formas_envios.id')
               ->join('alp_formas_pagos','alp_ordenes.id_forma_pago' , '=', 'alp_formas_pagos.id')
               ->where('alp_ordenes.id', $orden->id)->first();


                $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*',
                  'alp_productos.presentacion_producto as presentacion_producto',
                  'alp_productos.nombre_producto as nombre_producto',
                  'alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug')
                  ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
                  ->where('alp_ordenes_detalle.id_orden', $orden->id)
                  ->whereNull('alp_ordenes_detalle.deleted_at')
                  ->get();



                 $orden=AlpOrdenes::where('id', $orden->id)->first();

                 $detalles = AlpDetalles::select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.imagen_producto as imagen_producto','alp_productos.referencia_producto as referencia_producto','alp_productos.tipo_producto as tipo_producto')
                  ->join('alp_productos', 'alp_ordenes_detalle.id_producto', '=', 'alp_productos.id')
                  ->where('alp_ordenes_detalle.id_orden', $orden->id)
                  ->whereNull('alp_ordenes_detalle.deleted_at')
                  ->get();

                  $productos = array();

                  foreach ($detalles as $d) {
                    
                      $dt = array(
                        'sku' => $d->referencia_producto, 
                        'name' => $d->nombre_producto, 
                        'url_img' => $d->imagen_producto, 
                        'value' => $d->precio_unitario, 
                        'value_prom' => $d->precio_unitario, 
                        'quantity' => $d->cantidad
                      );

                      $productos[]=$dt;
                  }



              $cliente =  User::select('users.*','roles.name as name_role','alp_clientes.estado_masterfile as estado_masterfile','alp_clientes.estado_registro as estado_registro','alp_clientes.telefono_cliente as telefono_cliente','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.cod_alpinista as cod_alpinista','alp_clientes.doc_cliente as doc_cliente')
                ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
                ->join('role_users', 'users.id', '=', 'role_users.user_id')
                ->join('roles', 'role_users.role_id', '=', 'roles.id')
                ->where('users.id', '=', $orden->id_user)->first();


               // dd($cliente);


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
                'identificacionCliente' => $cliente->doc_cliente, 
                'nombreCliente' => $cliente->first_name." ".$cliente->last_name, 
                'direccionCliente' => $direccion->nombre_estructura." ".$direccion->principal_address." - ".$direccion->secundaria_address." ".$direccion->edificio_address." ".$direccion->detalle_address." ".$direccion->barrio_address, 
                'observacionDomicilio' => "", 
                'formaPago' => "Efectivo"
              );

              $o = array(
                'tipoServicio' => 1, 
                'retorno' => "false", 
                'totalFactura' => $orden->monto_total, 
                'subTotal' => $orden->monto_total-$orden->monto_impuesto, 
                'iva' => $orden->monto_impuesto, 
                'fechaPedido' => date("Ymd", strtotime($orden->created_at)), 
                'horaMinPedido' => "00:00", 
                'horaMaxPedido' => "00:00", 
                'observaciones' => "", 
                'paradas' => $dir, 
                'products' => $productos, 
              );


              $dataraw=json_encode($o);




              if ($compra->id_forma_envio!=1) {

                   try {

                     $formaenvio=AlpFormasenvio::where('id', $compra->id_forma_envio)->first();

                     Mail::to($formaenvio->email)->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega,1));

                     Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega,1));
                     
                     } catch (\Exception $e) {

                       activity()->withProperties(1)->log('Error de correo vp354');
                 
                   
                     }

                   try {

                     # $this->ibmConfirmarCompra($user_cliente, $orden);

                    # $this->ibmConfirmarPago($user_cliente, $orden);

                    # $this->ibmConfirmarEnvio($user_cliente, $orden, $envio);
                     
                   } catch (\Exception $e) {

                     activity()->withProperties(1)->log('Error de ibm vp372');
                     
                   }

                                        
                 }


                

                 try {

                    Mail::to($user_cliente->email)->send(new \App\Mail\CompraRealizada($compra, $detalles, $fecha_entrega));

                    Mail::to($configuracion->correo_sac)->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega));


                     Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\CompraRealizada($compra, $detalles, $fecha_entrega));

                    Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega));
                   
                 } catch (\Exception $e) {

                   activity()->withProperties(1)->log('Error de correo vp408');
                   
                 }




                 foreach ($detalles as $d ) {

                   if ($d->tipo_producto=='4') {

                     $prod=AlpProductos::Where('id_producto', '=', $d->id_producto)->first();

                       Mail::to($user_cliente->email)->send(new \App\Mail\NotificacionDigital($prod));
                     
                       Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionDigital($prod));

                   }
                   # code...
                 }


                 if($orden->lifemiles_id=='0'){

                 }else{

                  $codigo=AlpLifeMilesCodigos::where('id_lifemile', '=', $orden->lifemiles_id)->where('estado_registro','1')->first();
                  $fecha_lm = Carbon::now()->format('m/d/Y');

                    if(isset($codigo->id)){

                        $data_lifemiles = array(
                          'id_lifemile' => $codigo->id_lifemile, 
                          'id_codigo' => $codigo->id, 
                          'id_orden' => $orden->id,
                          'id_user' => $orden->id_user
                        );

                        AlpLifeMilesOrden::create($data_lifemiles);

                        $codigo->update(['estado_registro'=>'0']);

                        //envio Lifemiles a IBM

                        $this->addlifemiles($user_cliente, $codigo, $fecha_lm);


                        //Mail::to($user_cliente->email)->send(new \App\Mail\NotificacionLifemiles($codigo));

                        //Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionLifemiles($codigo));


                    }else{

                      $mensaje='Gracias por su compra en Alpina Go!, Por su compra usted recibira un Codigo LifeMiles, En estos momentos no tenemos disponible por favor contacte con Nuestra Area de Atencion al Cliente mendiante el Formulario pqr en Nuestra Web.';
                      
                      Mail::to($user_cliente->email)->send(new \App\Mail\NotificacionMensaje($mensaje));

                      Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionMensaje($mensaje));


                    }

                 }

              
           }elseif($pending){
             

           }elseif($cancel){

              $configuracion = AlpConfiguracion::where('id', '1')->first();


               $date = Carbon::parse($orden->created_at); 

               $now = Carbon::now();

               $diff = $date->diffInMinutes($now); 
               

              if ($diff>$configuracion->vence_ordenes_pago) {

               //dd($diff);

                 $data_update = array(
                   'estatus' =>4, 
                   'estatus_pago' =>3,
                    );

                  $orden->update($data_update);

                   $data_history = array(
                     'id_orden' => $orden->id, 
                     'id_status' => '4', 
                     'notas' => 'Notificacion Mercadopago Cron',
                     'id_user' => 1
                   );

                   $history=AlpOrdenesHistory::create($data_history);

                  $descuentos=AlpOrdenesDescuento::where('id_orden', $orden->id)->get();

                     foreach ($descuentos as $desc) {
                       
                       $d=AlpOrdenesDescuento::where('id', $desc->id)->first();

                       $d->delete();

                     }


                    /* try {

                        if ($orden->id_almacen=='1') {

                          // $this->sendcompramas($orden->id, 'rejected');

                           $this->cancelarCompramas($orden->id);


                           # code...
                         }
                       
                     } catch (\Exception $e) {

                       activity()->withProperties(1)->log('Error de compramas vp477');
                       
                     }*/


                     $descuentosIcg=AlpOrdenesDescuentoIcg::where('id_orden','=', $orden->id)->get();

                     $total_descuentos_icg=0;

                     foreach ($descuentosIcg as $pagoi) {

                       $total_descuentos_icg=$total_descuentos_icg+$pagoi->monto_descuento;

                     }

                     if ($total_descuentos_icg>0) {

                       $this->registroIcgCancelar($orden->id);

                     }

                     $this->cancelarMercadopago($orden->id);
              }

           }

         } //si hay resultados 

         if (isset($preference['response']['results'][0])) {
           # code...

             if ( $pending ) 
             {

             }else{

                 $data_pago = array(
               'id_orden' => $orden->id, 
               'id_forma_pago' => $orden->id_forma_pago, 
               'id_estatus_pago' => 4, 
               'monto_pago' => $orden->monto_total, 
               'json' => json_encode($preference['response']['results']), 
               'id_user' => '0' 
                 );

                #AlpPagos::create($data_pago);
             }

         }







    }



    private function registrarOrden($id_orden)
    {

      $configuracion=AlpConfiguracion::first();
      
       $orden=AlpOrdenes::where('id', $id_orden)->first();

        //Log::info('compramas orden '.json_encode($orden));

        $detalles = AlpDetalles::select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.imagen_producto as imagen_producto','alp_productos.referencia_producto as referencia_producto')
          ->join('alp_productos', 'alp_ordenes_detalle.id_producto', '=', 'alp_productos.id')
        ->where('alp_ordenes_detalle.id_orden', $orden->id)
        ->get();

                  $productos = array();

                  foreach ($detalles as $d) {

                    if ($d->precio_unitario>0) {

                      $dt = array(
                        'sku' => $d->referencia_producto, 
                        'name' => $d->nombre_producto, 
                        'url_img' => $d->imagen_producto, 
                        'value' => $d->precio_unitario, 
                        'value_prom' => $d->precio_unitario, 
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
                'identificacionCliente' => $cliente->doc_cliente, 
                'nombreCliente' => $cliente->first_name." ".$cliente->last_name, 
                'direccionCliente' => $direccion->nombre_estructura." ".$direccion->principal_address." - ".$direccion->secundaria_address." ".$direccion->edificio_address." ".$direccion->detalle_address." ".$direccion->barrio_address, 
                'observacionDomicilio' => "", 
                'formaPago' => "Efectivo"
              );

              $o = array(
                'tipoServicio' => 1, 
                'retorno' => "false", 
                'totalFactura' => $orden->monto_total, 
                'subTotal' => $orden->monto_total-$orden->monto_impuesto, 
                'iva' => $orden->monto_impuesto, 
                'fechaPedido' => date("Ymd", strtotime($orden->created_at)), 
                'horaMinPedido' => "00:00", 
                'horaMaxPedido' => "00:00", 
                'observaciones' => "", 
                'paradas' => $dir, 
                'products' => $productos, 
              );


        $dataraw=json_encode($o);

        $orden->update(['send_json_masc'=>$dataraw]);

        $urls=$configuracion->compramas_url.'/registerOrder/'.$configuracion->compramas_hash;

        Log::info('Datos enviados a compramas para registro de orden aprobada  '.$urls);

        Log::info($dataraw);

        activity()->withProperties($dataraw)->log('Datos enviados a registro  de orden aprobada en compramas orden id '.$orden->id.' .vp634');


             ## dd($dataraw);


      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, $configuracion->compramas_url.'/registerOrder/'.$configuracion->compramas_hash);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POST, 1);
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

       Log::info('Respuesta de compramas al registro de la orden '.json_encode($res));
       
       Log::info('Respuesta de compramas al registro de la orden. '.$result);

       activity()->withProperties($res)->log('Datos de respuesta  a registro  de orden aprobada en compramas orden id '.$orden->id.' .vp663');


       $notas='Registro de orden en compramas.';


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
                'estado_compramas' => $res->codigo
                
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
              'estado_compramas' => $res->codigo,
              'envio_compramas' => '3'
              
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





private function registrarOrdenNuevo($id_orden)
{

  $configuracion=AlpConfiguracion::first();
  
  $orden=AlpOrdenes::where('id', $id_orden)->first();


    //Log::info('compramas orden '.json_encode($orden));

  $detalles = AlpDetalles::select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.imagen_producto as imagen_producto','alp_productos.referencia_producto as referencia_producto','alp_productos.cantidad as cantidad_producto')
    ->join('alp_productos', 'alp_ordenes_detalle.id_producto', '=', 'alp_productos.id')
    ->where('alp_ordenes_detalle.id_orden', $orden->id)
    ->get();

              $productos = array();

              $peso=0;

              foreach ($detalles as $d) {

                $peso=$peso+$d->cantidad_producto;

                if ($d->precio_unitario>0) {

                  $dt = array(
                    'sku' => $d->referencia_producto, 
                    'name' => $d->nombre_producto, 
                    'url_img' => $d->imagen_producto, 
                    'value' => $d->precio_unitario, 
                    'value_prom' => $d->precio_unitario, 
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
            'formaPago' => "Efectivo"
          );



          $cupones=AlpOrdenesDescuento::where('id_orden', $orden->id)->get();

          $descuentoicg=AlpOrdenesDescuentoIcg::where('id_orden','=', $orden->id)->get();

          $descuento_total=0;

          foreach($descuentoicg as $di){

            $descuento_total=$descuento_total+$di->monto_descuento;
          }

          foreach($cupones as $co){

            $descuento_total=$descuento_total+$co->monto_descuento;
          }




          $o = array(
            'tipoServicio' => 1, 
            'retorno' => "false", 
            'totalFactura' => $orden->monto_total, 
            'subTotal' => $orden->monto_total-$orden->monto_impuesto, 
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


    $dataraw=json_encode($o);

    $orden->update(['send_json_masc'=>$dataraw]);

    $urls=$configuracion->compramas_url.'/registerOrder/'.$configuracion->compramas_hash;

    Log::info('Datos enviados a Velocity registro de orden aprobada  '.$urls);

    Log::info($dataraw);

    activity()->withProperties($dataraw)->log('Datos enviados a registro  de orden aprobada en Velocity nuevo orden id '.$orden->id.' .vp634');

  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, 'https://ff.logystix.co/api/v1/webhooks/alpinago');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
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

   Log::info('Respuesta de Velocity al registro de la orden '.json_encode($res));
   
   Log::info('Respuesta de Velocity al registro de la orden. '.$result);

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
            'estado_compramas' => $res->codigo
            
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
          'estado_compramas' => $res->codigo,
          'envio_compramas' => '3'
          
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




    private function cancelarCompramas($id_orden){


      $orden=AlpOrdenes::where('id', $id_orden)->first();

       $dataupdate = array(
          'ordenId' => $orden->referencia, 
          'status' => 'cancelled', 
          'messages' => 'orden cancelada por pagos'
        );


       $dataraw=json_encode($dataupdate);

      # dd($dataraw);

        Log::useDailyFiles(storage_path().'/logs/compramas.log');
        
        Log::info('datos enviados para cancelar orden en compramas '.json_encode($dataupdate));

         $configuracion = AlpConfiguracion::where('id','1')->first();

         $urls=$configuracion->compramas_url.'/cancelOrder/'.$configuracion->compramas_hash;

           //   activity()->withProperties($urls)->log('compramas urls ');

                   // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $configuracion->compramas_url.'/cancelOrder/'.$configuracion->compramas_hash);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
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


                  $notas='Cancelar de orden en compramas.';

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


                   Log::info('Respuesta de llamada para cancelar orden compramas '.$result);

                  Log::info('Respuesta de llamada para cancelar orden compramas '.json_encode($res));


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

                        try {

                          Mail::to($configuracion->correo_sac)->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

                          Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

                        } catch (\Exception $e) {

                          activity()->withProperties(1)->log('Error de correo vp653');
                          
                        }

                    
                   
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

                    try {

                      Mail::to($configuracion->correo_sac)->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

                     Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

                    } catch (\Exception $e) {
                      activity()->withProperties(1)->log('Error de correo vp689');
                    }


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

                      try {
                        Mail::to($configuracion->correo_sac)->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

                        Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));
                      } catch (\Exception $e) {

                        activity()->withProperties(1)->log('Error de correo vp717');
                        
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
        
        Log::info('datos enviados para cancelar orden en compramas '.json_encode($dataupdate));

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


                   Log::info('Respuesta de llamada para cancelar orden compramas '.$result);

                  Log::info('Respuesta de llamada para cancelar orden compramas '.json_encode($res));


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

                        try {

                         // Mail::to($configuracion->correo_sac)->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

                        //  Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

                        } catch (\Exception $e) {

                          activity()->withProperties(1)->log('Error de correo vp653');
                          
                        }

                    
                   
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

                    try {

                      Mail::to($configuracion->correo_sac)->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

                     Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

                    } catch (\Exception $e) {
                      activity()->withProperties(1)->log('Error de correo vp689');
                    }


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

                      try {
                        Mail::to($configuracion->correo_sac)->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

                        Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));
                      } catch (\Exception $e) {

                        activity()->withProperties(1)->log('Error de correo vp717');
                        
                      }

                    

                }



    }


    private function addibm($user)
    {


      $configuracion=AlpConfiguracion::where('id', '=', '1')->first();
        
        $pod = 0;
        $username = $configuracion->username_ibm;
        $password = $configuracion->password_ibm;

        $endpoint = $configuracion->endpoint_ibm;
        $jsessionid = null;

        $baseXml = '%s';
        $loginXml = '';
        $getListsXml = '%s%s';
        $logoutXml = '';

        try {

        $xml='<Envelope> <Body> <Login> <USERNAME>api_alpina@alpina.com</USERNAME> <PASSWORD>Alpina2020!</PASSWORD> </Login> </Body> </Envelope> ';

        $result = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml));

       // print_r($result);

        $jsessionid = $result['SESSIONID'];

      //  echo $jsessionid.'<br>';

            $xml='
            <Envelope>
               <Body>
                  <AddRecipient>
                     <LIST_ID>10491915  </LIST_ID>
                     <CREATED_FROM>1</CREATED_FROM>
                     <COLUMN>
                        <NAME>Customer Id</NAME>
                        <VALUE>'.$user->id_user.'</VALUE>
                     </COLUMN>
                     <COLUMN>
                        <NAME>EMAIL</NAME>
                        <VALUE>'.$user->email.'</VALUE>
                     </COLUMN>
                     <COLUMN>
                        <NAME>'.$user->first_name.'</NAME>
                        <VALUE>'.$user->last_name.'</VALUE>
                     </COLUMN>
                  </AddRecipient>
               </Body>
            </Envelope>
            ';



            $xml='<Envelope><Body><InsertUpdateRelationalTable><TABLE_ID>10843783</TABLE_ID><ROWS><ROW><COLUMN name="Correo"><![CDATA[axluis.gomez@gmail.com]]></COLUMN><COLUMN name="Referencia_Orden"><![CDATA[090393039303]]></COLUMN><COLUMN name="Fecha_Compra"><![CDATA[08/11/2020]]></COLUMN></ROW>  </ROWS></InsertUpdateRelationalTable></Body></Envelope>';


         activity()->withProperties($xml)->log('xml_ibm_add_recipiente');

        $result2 = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml));

        activity()->withProperties($result)->log('xml_ibm_add_result');

       // print_r($result);

       // echo "3<br>";

    //LOGOUT

        $xml = '<Envelope>
          <Body>
          <Logout/>
          </Body>
          </Envelope>';

              $result = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml, true));

           //   activity()->withProperties($result)->log('xml_ibm_add_result2');

             // print_r($result);

              return $result2['SUCCESS'];

              $jsessionid = null;

          } catch (\Exception $e) {

              die("\nException caught: {$e->getMessage()}\n\n");

              return 'FALSE';

          }
    }


    private function addlifemiles($user, $cupon, $fecha_lm)
    {
        
        $configuracion=AlpConfiguracion::where('id', '=', '1')->first();

        $pod = 0;
        $username = $configuracion->username_ibm;
        $password = $configuracion->password_ibm;
        $endpoint = $configuracion->endpoint_ibm;
        $jsessionid = null;

        $baseXml = '%s';
        $loginXml = '';
        $getListsXml = '%s%s';
        $logoutXml = '';

        try {

        $xml='<Envelope> <Body> <Login> <USERNAME>api_alpina@alpina.com</USERNAME> <PASSWORD>Alpina2020!</PASSWORD> </Login> </Body> </Envelope> ';

        $result = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml));

       // print_r($result);

        $jsessionid = $result['SESSIONID'];

      //  echo $jsessionid.'<br>';

            $xml='<Envelope><Body><AddRecipient><LIST_ID>8739683</LIST_ID><SYNC_FIELDS><SYNC_FIELD><NAME>EMAIL</NAME><VALUE>'.$user->email.'</VALUE></SYNC_FIELD></SYNC_FIELDS><UPDATE_IF_FOUND>true</UPDATE_IF_FOUND><COLUMN><NAME>Email</NAME><VALUE>'.$user->email.'</VALUE></COLUMN><COLUMN><NAME>Alpina_Go_Partner_Code</NAME><VALUE>ALPCO</VALUE></COLUMN><COLUMN><NAME>Alpina_Go_Gift_Code</NAME><VALUE>'.$cupon->code.'</VALUE></COLUMN><COLUMN><NAME>Alpina_Go_update_Gift_Code</NAME><VALUE>'.$fecha_lm.'</VALUE></COLUMN><COLUMN><NAME>Fuente</NAME><VALUE>Alpina Go</VALUE></COLUMN></AddRecipient></Body></Envelope>';
            //dd($xml);


           activity()->withProperties($xml)->log('ibm_lifemiles datos enviados ');

        $result2 = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml));

        activity()->withProperties($result)->log('ibm_lifemiles respuesta');

       // print_r($result);

       // echo "3<br>";

    //LOGOUT

        $xml = '<Envelope>
          <Body>
          <Logout/>
          </Body>
          </Envelope>';

              $result = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml, true));

             // activity()->withProperties($result)->log('codigo-descuento-xml_ibm_result2');

            // print_r($result);

              return $result2['SUCCESS'];

              $jsessionid = null;

          } catch (Exception $e) {

              die("\nException caught: {$e->getMessage()}\n\n");

              return 'FALSE';

          }
    }


     private function ibmConfirmarCompra($user, $orden)
    {
        
        $configuracion=AlpConfiguracion::where('id', '=', '1')->first();
        
        $pod = 0;
        $username = $configuracion->username_ibm;
        $password = $configuracion->password_ibm;

        $endpoint = $configuracion->endpoint_ibm;
        $jsessionid = null;

        $baseXml = '%s';
        $loginXml = '';
        $getListsXml = '%s%s';
        $logoutXml = '';

        try {

        $xml='<Envelope><Body><Login><USERNAME>api_alpina@alpina.com</USERNAME><PASSWORD>Alpina2020!</PASSWORD></Login></Body></Envelope> ';

        $result = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml));

        //print_r($result);

        $jsessionid = $result['SESSIONID'];

      //  echo $jsessionid.'<br>';


           $xml='<Envelope><Body><InsertUpdateRelationalTable><TABLE_ID>10843783</TABLE_ID><ROWS><ROW><COLUMN  name="Correo"><![CDATA['.$user->email.']]></COLUMN><COLUMN name="Referencia_Orden"><![CDATA['.$orden->referencia.']]></COLUMN><COLUMN name="Fecha_Compra"><![CDATA['.date("m/d/Y", strtotime($orden->created_at)).']]></COLUMN></ROW></ROWS></InsertUpdateRelationalTable></Body> </Envelope>';


            activity()->withProperties($xml)->log('compra-xml');

        $result2 = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml));

        activity()->withProperties($result2)->log('compra-resultado');

       // print_r($result);

       // echo "3<br>";

    //LOGOUT

        $xml = '<Envelope><Body><Logout/></Body></Envelope>';

              $result = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml, true));

              activity()->withProperties($result)->log('xml_ibm_add_result2-carrito');

             // print_r($result);

              return $result2['SUCCESS'];

              $jsessionid = null;

          } catch (\Exception $e) {

              die("\nException caught: {$e->getMessage()}\n\n");

              return 'FALSE';

          }
    }


   

 private function ibmConfirmarPago($user, $orden)
    {


        $configuracion=AlpConfiguracion::where('id', '=', '1')->first();
        
        $pod = 0;
        $username = $configuracion->username_ibm;
        $password = $configuracion->password_ibm;

        $endpoint = $configuracion->endpoint_ibm;
        $jsessionid = null;

        $baseXml = '%s';
        $loginXml = '';
        $getListsXml = '%s%s';
        $logoutXml = '';

        try {

        $xml='<Envelope> <Body> <Login> <USERNAME>api_alpina@alpina.com</USERNAME> <PASSWORD>Alpina2020!</PASSWORD> </Login> </Body> </Envelope> ';

        $result = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml));

       // print_r($result);

        $jsessionid = $result['SESSIONID'];

      //  echo $jsessionid.'<br>';


            $xml='<Envelope><Body><InsertUpdateRelationalTable><TABLE_ID>10843783</TABLE_ID><ROWS><ROW><COLUMN name="Correo"><![CDATA['.$user->email.']]></COLUMN><COLUMN name="Referencia_Orden"><![CDATA['.$orden->referencia.']]></COLUMN><COLUMN name="Fecha_Pago"><![CDATA['.date("m/d/Y",strtotime($orden->created_at)).']]></COLUMN></ROW></ROWS></InsertUpdateRelationalTable></Body></Envelope>';


            activity()->withProperties($xml)->log('pago-xml');

        $result2 = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml));

        activity()->withProperties($result2)->log('pago-result');

       // print_r($result);

       // echo "3<br>";

    //LOGOUT

        $xml = '<Envelope>
          <Body>
          <Logout/>
          </Body>
          </Envelope>';

              $result = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml, true));

           //   activity()->withProperties($result)->log('xml_ibm_add_result2');

             // print_r($result);

              return $result2['SUCCESS'];

              $jsessionid = null;

          } catch (\Exception $e) {

              die("\nException caught: {$e->getMessage()}\n\n");

              return 'FALSE';

          }
    }





 private function ibmConfirmarEnvio($user, $orden, $envio)
    {


        $configuracion=AlpConfiguracion::where('id', '=', '1')->first();
        
        $pod = 0;
        $username = $configuracion->username_ibm;
        $password = $configuracion->password_ibm;
        $endpoint = $configuracion->endpoint_ibm;
        
        $jsessionid = null;

        $baseXml = '%s';
        $loginXml = '';
        $getListsXml = '%s%s';
        $logoutXml = '';

        try {

        $xml='<Envelope> <Body> <Login> <USERNAME>api_alpina@alpina.com</USERNAME> <PASSWORD>Alpina2020!</PASSWORD> </Login> </Body> </Envelope> ';

        $result = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml));

       // print_r($result);

        $jsessionid = $result['SESSIONID'];

      //  echo $jsessionid.'<br>';


      $xml='<Envelope><Body><InsertUpdateRelationalTable><TABLE_ID>10843783</TABLE_ID><ROWS><ROW><COLUMN name="Correo"><![CDATA['.$user->email.']]></COLUMN><COLUMN name="Referencia_Orden"><![CDATA['.$orden->referencia.']]></COLUMN><COLUMN name="Fecha_Envio"><![CDATA['.date("m/d/Y", strtotime($envio->fecha_envio)).']]></COLUMN></ROW></ROWS></InsertUpdateRelationalTable></Body></Envelope>';

     //dd($xml);


            activity()->withProperties($xml)->log('envio-xml');

        $result2 = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml));

        activity()->withProperties($result)->log('envio-result');

       // print_r($result);

       // echo "3<br>";

    //LOGOUT

        $xml = '<Envelope>
          <Body>
          <Logout/>
          </Body>
          </Envelope>';

              $result = $this->xmlToArray($this->makeRequest($endpoint, $jsessionid, $xml, true));

           //   activity()->withProperties($result)->log('xml_ibm_add_result2');

             // print_r($result);

              return $result2['SUCCESS'];

              $jsessionid = null;

          } catch (\Exception $e) {

              die("\nException caught: {$e->getMessage()}\n\n");

              return 'FALSE';

          }
    }






public function makeRequest($endpoint, $jsessionid, $xml, $ignoreResult = false)
{

    $url = $this->getApiUrl($endpoint, $jsessionid);



    echo  $url.'<br>';

    

    $xmlObj = new \SimpleXmlElement($xml);



    $request = $xmlObj->asXml();



    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($curl, CURLOPT_POST, true);

    curl_setopt($curl, CURLOPT_POSTFIELDS, $request);

    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

    curl_setopt($curl, CURLINFO_HEADER_OUT, true);



    $headers = array(

        'Content-Type: text/xml; charset=UTF-8',

        'Content-Length: ' . strlen($request),

    );



    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60);

    curl_setopt($curl, CURLOPT_TIMEOUT, 60);



    $response = @curl_exec($curl);



    if (false === $response) {

        //throw new Exception('CURL error: ' . curl_error($curl));
        //
        return false;

    }



    curl_close($curl);



    if (true === $response || !trim($response)) {

       // throw new Exception('Empty response from WCA');
       // 
       $response=false;
       
       return false;

    }


    if ($response==false) {

      $xmlResponse = false;
     
    }else{

      $xmlResponse = simplexml_load_string($response);
    }
    



    if (false === $ignoreResult) {

        if (false === isset($xmlResponse->Body->RESULT)) {

           // var_dump($xmlResponse);

            //throw new Exception('Unexpected response from WCA');
            //
            return false;

        }else{

          return $xmlResponse->Body->RESULT;
        }



        

    }



    return $xmlResponse->Body;

}



public function getApiUrl($endpoint, $jsessionid)
{
    return $endpoint . ((null === $jsessionid)

        ? ''

        : ';jsessionid=' . urlencode($jsessionid));

}



   public function xmlToJson($xml)
  {

      return json_encode($xml);

  }



  public function xmlToArray($xml)

  {

      $json = $this->xmlToJson($xml);

      return json_decode($json, true);

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

      $orden=AlpOrdenes::where('id', $id_orden)->first();

      $configuracion = AlpConfiguracion::where('id', '1')->first();

      $almacen=AlpAlmacenes::where('id', $orden->id_almacen)->first();

      MercadoPago::setClientId($almacen->id_mercadopago);
      MercadoPago::setClientSecret($almacen->key_mercadopago);
                  
      if ($almacen->mercadopago_sand=='1') {

          MercadoPago::setPublicKey($almacen->public_key_mercadopago_test);
        
        }
    
        if ($almacen->mercadopago_sand=='2') {

          MercadoPago::setPublicKey($almacen->public_key_mercadopago);
          
        }

         $preference = MercadoPago::get("/v1/payments/search?external_reference=".$orden->referencia_mp);

          foreach ($preference['body']['results'] as $r) {

              if ($r['status']=='in_process' || $r['status']=='pending') {
                  
                $idpago=$r['id'];

                $preference_data_cancelar = '{"status": "cancelled"}';

                $pre = MercadoPago::put("/v1/payments/".$idpago."", $preference_data_cancelar);

                $data_cancelar = array(
                  'id_orden' => $orden->id, 
                  'id_forma_pago' => $orden->id_forma_pago, 
                  'id_estatus_pago' => 4, 
                  'monto_pago' => $orden->monto_total, 
                  'json' => json_encode($pre), 
                  'id_user' => '1'
                );

                AlpPagos::create($data_cancelar);

                $data_history_json = array(
                  'id_orden' => $orden->id, 
                  'id_status' =>'4', 
                  'notas' => 'Cancelacion de pago en Mercadopago', 
                  'json' => json_encode($pre), 
                  'id_user' => '1' 
              );

              $history=AlpOrdenesHistory::create($data_history_json);


            }

           

            }

    }












    





}//endclass