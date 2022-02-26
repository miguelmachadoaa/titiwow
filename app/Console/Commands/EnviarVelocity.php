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

class EnviarVelocity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enviar:velocity';

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


        $ordenes=AlpOrdenes::where('id', '35270')->get();

     # Log::info('ordenes a verficar  '.json_encode($ordenes_id));

        $configuracion = AlpConfiguracion::where('id', '1')->first();

        if (count($ordenes)) {
       
        foreach ($ordenes as $ord) {

            $this->registrarOrdenNuevo($ord->id);
        

        }//endforeach ordenes

      }//endifhay ordenes 

    }//endhadle







private function registrarOrdenNuevo($id_orden)
{

  $configuracion=AlpConfiguracion::first();
  
  $orden=AlpOrdenes::where('id', $id_orden)->first();

  $almacen_pedido=AlpAlmacenes::where('id', $orden->id_almacen)->first();


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

         # dd($o);


    $dataraw=json_encode($o);

    $orden->update(['send_json_masc'=>$dataraw]);

    Log::info($dataraw);

    #dd($dataraw);

    activity()->withProperties($dataraw)->log('Datos enviados a registro  de orden aprobada en Velocity nuevo orden id '.$orden->id.' .vp634');

  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, 'https://ff.logystix.co/api/v1/webhooks/alpinago?warehouse_id='.$almacen_pedido->codigo_almacen);
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

   Log::info('Respuesta de Velocity al registro de la orden: '.$orden->id.' '.json_encode($res));
   
   activity()->withProperties($res)->log('Datos de respuesta a registro de orden aprobada en Velocity orden id '.$orden->id.' .vp663');

   $notas='Registro de orden en Velocity Manual.';

   print_r($res);

   echo "id de la orden: ".$id_orden.'-';

   echo "Almacen: ".$almacen_pedido->codigo_almacen;


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





}//endclass