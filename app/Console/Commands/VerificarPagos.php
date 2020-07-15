<?php

namespace App\Console\Commands;

use App\User;
use App\Models\AlpConfiguracion;
use App\Models\AlpDirecciones;
use App\Models\AlpFeriados;
use App\Models\AlpFormaCiudad;
use App\Models\AlpFormasenvio;
use App\Models\AlpImpuestos;

use App\Models\AlpPagos;
use App\Models\AlpEnvios;
use App\Models\AlpEnviosHistory;
use App\Models\AlpOrdenes;
use App\Models\AlpDetalles;
use App\Models\AlpOrdenesHistory;
use App\Exports\CronNuevosUsuarios;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
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

      $date = Carbon::now();


      $d=$date->subDay(3)->format('Y-m-d');
      
        $ordenes=AlpOrdenes::where('estatus_pago', '4')->whereDate('created_at','>=', $d)->get();
        //$ordenes=AlpOrdenes::where('id','=', 5125)->get();

         //\Log::debug('1 listado' . $ordenes);

         activity()->withProperties($ordenes)->log('Listado de ordenes a consultar');

        // die;
      

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
//\Log::debug('preference ' . json_encode($preference));

          activity()->withProperties($preference)->log('preference');


          //if (isset($preference['response']['results'][0])) {
          if (isset($preference)) {

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

            }

            if ( $aproved ) 
              {

                $direccion=AlpDirecciones::where('id', $ord->id_address)->withTrashed()->first();

                activity()->withProperties($direccion)->log('detalle de direccion en verificar pagos ');
                activity()->withProperties($ord->id)->log('Orden aprobada verificar pagos ');

                // \Log::debug('1.3 direccion detalle direccion ' . $direccion);

                 //\Log::debug('1.4 orden  aprobada ' . $ord->id);


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
                      'id_orden' => $ord->id, 
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
                        'id_orden' => $ord->id, 
                        'id_forma_pago' => $ord->id_forma_pago, 
                        'id_estatus_pago' => '2', 
                        'monto_pago' => $ord->monto_total, 
                        'json' => json_encode([]), 
                        'id_user' => '1'
                      );


                     AlpPagos::create($data_pago);

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
                  ->where('alp_ordenes_detalle.id_orden', $orden->id)->get();



                  if ($compra->id_forma_envio!=1) {

                    $formaenvio=AlpFormasenvio::where('id', $compra->id_forma_envio)->first();

                    Mail::to($formaenvio->email)->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega,1));

                    Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega,1));

                      
                  }

                  Mail::to($user_cliente->email)->send(new \App\Mail\CompraRealizada($compra, $detalles, $fecha_entrega));

                 Mail::to($configuracion->correo_sac)->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega));


                  Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\CompraRealizada($compra, $detalles, $fecha_entrega));

                 Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega));



                 $orden=AlpOrdenes::where('id', $orden->id)->first();

                 $detalles = AlpDetalles::select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.imagen_producto as imagen_producto','alp_productos.referencia_producto as referencia_producto')
                  ->join('alp_productos', 'alp_ordenes_detalle.id_producto', '=', 'alp_productos.id')
                  ->where('alp_ordenes_detalle.id_orden', $orden->id)
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
                'subTotal' => $orden->base_impuesto, 
                'iva' => $orden->monto_impuesto, 
                'fechaPedido' => date("Ymd", strtotime($orden->created_at)), 
                'horaMinPedido' => "00:00", 
                'horaMaxPedido' => "00:00", 
                'observaciones' => "", 
                'paradas' => $dir, 
                'products' => $productos, 
              );


              $dataraw=json_encode($o);

             //dd($dataraw);
             //
             if ($orden->id_almacen==1) {

                    $this->sendcompramas($orden->id, 'approved');
                    # code...
               # code...
             }//if es almacen 1


             $this->addibm($user_cliente);

               
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
                      'notas' => 'Notificacion Mercadopago Cron', 
                     'id_user' => 1
                  );

                  $history=AlpOrdenesHistory::create($data_history);



                  if ($orden->id_almacen=='1') {

                    $this->sendcompramas($orden->id, 'rejected');
                    # code...
                  }



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



    private function sendcompramas($id_orden, $estatus){


      $orden=AlpOrdenes::where('id', $id_orden)->first();

       $dataupdate = array(
          'ordenId' => $orden->referencia, 
          'status' => $estatus, 
        );


       $dataraw=json_encode($dataupdate);

        

        Log::useDailyFiles(storage_path().'/logs/compramas.log');
        
        Log::info('compramas dataraw '.$dataupdate);

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


                   Log::info('compramas result '.$result);

                    Log::info('compramas res '.json_encode($res));


                  if (isset($res->codigo)) {
                  
                  if ($res->codigo=='200') {

                      $dtt = array('json' => $result );

                      $orden->update($dtt);

                      $texto=''.$res->mensaje.' Codigo Respuesta '.$res->codigo;

                         $data_history = array(
                          'id_orden' => $orden->id, 
                         'id_status' => '9', 
                          'notas' => 'Registro de orden en compramas. '.$res->mensaje, 
                          'json' => json_encode($result), 
                         'id_user' => 1
                      );

                        $history=AlpOrdenesHistory::create($data_history);



                    Mail::to($configuracion->correo_sac)->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

                     Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));
                   
                  }else{



                         $data_history = array(
                          'id_orden' => $orden->id, 
                         'id_status' => '9', 
                          'notas' => 'Registro de orden en compramas. '.$res->mensaje, 
                          'json' => json_encode($result), 
                         'id_user' => 1
                      );

                        $history=AlpOrdenesHistory::create($data_history);


                    $texto=''.$res->mensaje.' Codigo Respuesta '.$res->codigo;

                    Mail::to($configuracion->correo_sac)->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

                     Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));


                  }


                }



    }


    private function addibm($user)
    {
        
        $pod = 0;
        $username = 'api_alpina@alpina.com';
        $password = 'Alpina2020!';

        $endpoint = "https://api2.ibmmarketingcloud.com/XMLAPI";
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

              activity()->withProperties($result)->log('xml_ibm_add_result2');

             // print_r($result);

              return $result2['SUCCESS'];

              $jsessionid = null;

          } catch (Exception $e) {

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
        throw new Exception('CURL error: ' . curl_error($curl));
    }

    curl_close($curl);

    if (true === $response || !trim($response)) {
        throw new Exception('Empty response from WCA');
    }

    $xmlResponse = simplexml_load_string($response);

    if (false === $ignoreResult) {
        if (false === isset($xmlResponse->Body->RESULT)) {
            var_dump($xmlResponse);
            throw new Exception('Unexpected response from WCA');
        }

        return $xmlResponse->Body->RESULT;
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