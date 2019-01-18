<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Models\AlpProductos;
use App\Models\AlpDirecciones;
use App\Models\AlpCategorias;
use App\Models\AlpCategoriasProductos;
use App\Models\AlpInventario;
use App\Models\AlpMarcas;
use App\Models\AlpFormasenvio;
use App\Models\AlpFormaCiudad;
use App\Models\AlpFormaspago;
use App\Models\AlpOrdenes;
use App\Models\AlpDetalles;
use App\Models\AlpConfiguracion;
use App\Models\AlpClientes;
use App\Models\AlpEmpresas;
use App\Models\AlpPuntos;
use App\Models\AlpPagos;
use App\Models\AlpPrecioGrupo;
use App\Models\AlpEnvios;
use App\Models\AlpEnviosHistory;
use App\Models\AlpCarrito;
use App\Models\AlpCarritoDetalle;
use App\Models\AlpCupones;
use App\Models\AlpPreOrdenes;
use App\Models\AlpPreDetalles;
use App\Country;
use App\State;
use App\City;
use App\Roles;
use App\User;
use App\RoleUser;
use App\Http\Requests;
use App\Http\Requests\ProductosRequest;
use Illuminate\Http\Request;
use Response;
use Sentinel;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use DOMDocument;
use DB;
use View;
use MP;
use Mail;

use Illuminate\Support\Facades\Storage;

class AlpCartController extends JoshController
{


    private $tags;

    public function __construct()
    {
        parent::__construct();

        if (!\Session::has('cart')) {
          \Session::put('cart', array());
        }

        if (!\Session::has('user')) {
          \Session::put('user', '0');
        }

       
    }

     /**
     * Display the specified resource.
     *
     * @param  Blog $blog
     * @return view
     */
    public function show()
    {

      $states=State::where('config_states.country_id', '47')->get();

      $cart=$this->reloadCart();

   

      $configuracion=AlpConfiguracion::where('id', '1')->first();

      $total=$this->total();

     $inv=$this->inventario();

      return view('frontend.cart', compact('cart', 'total', 'configuracion', 'states', 'inv'));
    }

    public function detalle()
    {

      $cart= \Session::get('cart');

      $view= View::make('frontend.order.detalle', compact('cart'));

      $data=$view->render();

      return $data;

    }

    public function detalle2()
    {

      $cart= \Session::get('cart');

      $view= View::make('frontend.order.detalle', compact('cart'));

      $data=$view->render();

      

      return $data;

    }

    public function mercadopago()
    {


    $configuracion = AlpConfiguracion::where('id', '1')->first();

     $date = Carbon::now();

      $hoy=$date->format('Y-m-d');

      //$enlace=secure_url('storage/'.$archivo);

     // dd(storage_path('app/logistica_desde_2018-12-01_hasta_2018-12-31.xlsx'));
     //return Storage::response("logistica_desde_2018-12-01_hasta_2018-12-31.xlsx");
    
     MP::setCredenciales($configuracion->id_mercadopago, $configuracion->key_mercadopago);


$payment_methods = MP::get("/v1/payment_methods");

         // dd($payment_methods);
   
    $preference_data = [
        "transaction_amount" => 10000,
        /*"net_amount" => 9000,
        "taxes" => [
          "value"=>1000,
          "type"=>"IVA"
        ],*/

        "description" => 'Pago de orden',
        "payer" => [
          "email"=>"miguel@gmail.com",
          "identification" => array(
            "type" => "CC",
            "number" => "76262349"
          ),
          "entity_type" => "individual"
        ],
        "transaction_details" => ["financial_institution"=>1007],
        "additional_info" => ["ip_address"=>"127.0.0.1"],
        "callback_url" => 'https://alpinago.com/public/orden/pse',
        "payment_method_id" => "pse",
        "back_urls" => [
                "success" => 'https://alpinago.com/public/orden/pse',
                "failure" => 'https://alpinago.com/public/orden/failure',
                "pending" => 'https://alpinago.com/public/orden/pending',
              ]

      ];

      

      $preference = MP::post("/v1/payments",$preference_data);


    dd($preference);

   

    }

    public function orderRapipago(Request $request)
    {
      
      $cart= \Session::get('cart');

      $total=$this->total();

      $impuesto=$this->impuesto();

    #  $input=$request->all();

     # dd($input);

      $configuracion = AlpConfiguracion::where('id', '1')->first();

      MP::setCredenciales($configuracion->id_mercadopago, $configuracion->key_mercadopago);

      MP::sandbox_mode(TRUE);

    // MP::setAccessToken('TEST-8070538380314059-092518-cdecd433d6d7253ead0520424d7c08e6-353183830');


       $preference_data = [
        "transaction_amount" => $total,
        "description" => 'Pago de orden',
        "payment_method_id" => "efecty",
        "payer" => [
          "email"=>"miguel@gmail.com"]
      ];


      $preference = MP::post("/v1/payments",$preference_data);

      dd($preference);




    }





     public function orderCreditcard(Request $request)
    {
      
      $cart= \Session::get('cart');

      $carrito= \Session::get('cr');

      $total=$this->total();

      $impuesto=$this->impuesto();

       $orden_data= \Session::get('orden');

     $input=$request->all();

     //dd($input);

     $configuracion = AlpConfiguracion::where('id', '1')->first();

     MP::setCredenciales($configuracion->id_mercadopago, $configuracion->key_mercadopago);

       $preference_data = [
        "transaction_amount" => $total,
        "token" => $request->token,
        "description" => 'Pago de orden'.$carrito,
        "installments" => intval($request->installments),
        "payment_method_id" => $request->payment_method_id,
        "issuer_id" => $request->issuer_id,
        "payer" => [
          "email"=>"miguel@gmail.com"]
      ];

      $preference = MP::post("/v1/payments",$preference_data);

     // dd($preference);

      if (isset($preference['response']['id'])) {

        if (Sentinel::check()) {

        $user_id = Sentinel::getUser()->id;

        $direccion=AlpDirecciones::where('id', $orden_data['id_direccion'])->first();

        $ciudad_forma=AlpFormaCiudad::where('id_forma', $orden_data['id_forma_envio'])->where('id_ciudad', $direccion->city_id)->first();


        $date = Carbon::now();

        $hora=$date->format('hi');

        $hora_base=str_replace(':', '', $ciudad_forma->hora);

        if (intval($hora)>intval($hora_base)) {

          $ciudad_forma->dias=$ciudad_forma->dias+1;

        }

        $fecha_entrega=$date->addDays($ciudad_forma->dias)->format('d-m-Y');

        $role=RoleUser::select('role_id')->where('user_id', $user_id)->first();

        $data_orden = array(
            'referencia ' => time(), 
            'id_cliente' => $user_id, 
            'id_forma_envio' =>$orden_data['id_forma_envio'], 
            'id_address' =>$orden_data['id_direccion'], 
            'id_forma_pago' =>$orden_data['id_forma_pago'], 
            'estatus' =>'1', 
            'estatus_pago' =>'2', 
            'monto_total' =>$total,
            'monto_total_base' =>$total,
            'base_impuesto' =>'0',
            'valor_impuesto' =>'0',
            'monto_impuesto' =>'0',
            'id_user' =>$user_id
        );

        $orden=AlpOrdenes::create($data_orden);

        $monto_total_base=0;
        $base_impuesto=0;
        $monto_impuesto=0;
        $valor_impuesto=0;

        foreach ($cart as $detalle) {

          $monto_total_base=$monto_total_base+($detalle->cantidad*$detalle->precio_base);

           $total_detalle=$detalle->precio_oferta*$detalle->cantidad;

            if ($detalle->valor_impuesto!=0) {

           

            $base_imponible_detalle=$total_detalle/(1+$detalle->valor_impuesto);

            $base_impuesto=$base_impuesto+$base_imponible_detalle;

            $valor_impuesto=$detalle->valor_impuesto;
            
          }

          $imp=$detalle->valor_impuesto+1;

          $monto_impuesto=$monto_impuesto+$detalle->valor_impuesto*($total_detalle/$imp);


          

          $data_detalle = array(
            'id_orden' => $orden->id, 
            'id_producto' => $detalle->id, 
            'cantidad' =>$detalle->cantidad, 
            'precio_unitario' =>$detalle->precio_oferta, 
            'precio_base' =>$detalle->precio_base, 
            'precio_total' =>$detalle->cantidad*$detalle->precio_oferta,
            'precio_total_base' =>$detalle->cantidad*$detalle->precio_base,
            'valor_impuesto' =>$detalle->valor_impuesto,
            'monto_impuesto' =>$detalle->valor_impuesto*$detalle->precio_oferta,
            'id_user' =>$user_id 
          );

          $data_inventario = array(
            'id_producto' => $detalle->id, 
            'cantidad' =>$detalle->cantidad, 
            'operacion' =>'2', 
            'id_user' =>$user_id 
          );

          AlpDetalles::create($data_detalle);

          AlpInventario::create($data_inventario);

        }//endfreach



        $cliente=AlpClientes::where('id_user_client', $user_id)->first();

        if (isset($cliente)) {
           
          if ($cliente->id_embajador!=0) {
             
              $data_puntos = array(
                  'id_orden' => $orden->id,
                  'id_cliente' => $cliente->id_embajador,
                  'tipo' => '1',//agregar
                  'cantidad' =>$total ,
                  'id_user' =>$user_id                   
              );

              AlpPuntos::create($data_puntos);

            }

         }

        $data_envio = array(
          'id_orden' => $orden->id, 
          'fecha_envio' => $date->addDays($ciudad_forma->dias)->format('Y-m-d'),
          'estatus' => 1, 
          'id_user' =>$user_id                   

        );

        $envio=AlpEnvios::create($data_envio);

        $data_envio_history = array(
          'id_envio' => $envio->id, 
          'estatus_envio' => 1, 
          'nota' => 'Envio recibido', 
          'id_user' =>$user_id                   

        );

        AlpEnviosHistory::create($data_envio_history);

         $data_update = array(
          'referencia' => 'ALP'.$orden->id,
          'monto_total_base' => $monto_total_base,
          'base_impuesto' => $base_impuesto,
          'monto_impuesto' => $monto_impuesto,
          'valor_impuesto' => $valor_impuesto

           );

         $orden->update($data_update);

         $data_pago = array(
          'id_orden' => $orden->id, 
          'id_forma_pago' => $orden_data['id_forma_pago'], 
          'id_estatus_pago' => 4, 
          'monto_pago' => $total, 
          'json' => json_encode($preference), 
          'id_user' => $user_id, 
        );

         AlpPagos::create($data_pago);

         $aviso_pago="Hemos recibido su pago satisfactoriamente, una vez sea confirmado, Le llegará un email con la descripción de su pago. ¡Muchas gracias por su Compra!";

       //  $datalles=AlpDetalles::where('id_orden', $orden->id)->get();

        $compra =  DB::table('alp_ordenes')->select('alp_ordenes.*','users.first_name as first_name','users.last_name as last_name' ,'users.email as email','alp_formas_envios.nombre_forma_envios as nombre_forma_envios','alp_formas_envios.descripcion_forma_envios as descripcion_forma_envios','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago','alp_formas_pagos.descripcion_forma_pago as descripcion_forma_pago','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.doc_cliente as doc_cliente')
            ->join('users','alp_ordenes.id_cliente' , '=', 'users.id')
            ->join('alp_clientes','alp_ordenes.id_cliente' , '=', 'alp_clientes.id_user_client')
            ->join('alp_formas_envios','alp_ordenes.id_forma_envio' , '=', 'alp_formas_envios.id')
            ->join('alp_formas_pagos','alp_ordenes.id_forma_pago' , '=', 'alp_formas_pagos.id')
            ->where('alp_ordenes.id', $orden->id)->first();


        $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug')
          ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
          ->where('alp_ordenes_detalle.id_orden', $orden->id)->get();


         $cart= \Session::forget('cart');

         $states=State::where('config_states.country_id', '47')->get();

         $configuracion = AlpConfiguracion::where('id','1')->first();

          $user_cliente=User::where('id', $user_id)->first();

          $texto='Se ha creado la siguiente orden '.$compra->id.' y esta a espera de aprobacion  ';


        Mail::to($user_cliente->email)->send(new \App\Mail\CompraRealizada($compra, $detalles, $fecha_entrega));

        Mail::to($configuracion->correo_sac)->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega));
          

          return view('frontend.order.procesar_completo', compact('compra', 'detalles', 'fecha_entrega', 'states', 'aviso_pago'));
        

      }else{

          return redirect('login');
      }




        
      }else{


        return redirect('order/detail');


      }

    }

   

    public function orderDetail()
    {

      $configuracion=AlpConfiguracion::where('id', '1')->first();
      
      $carrito= \Session::get('cr');

      $cart=$this->reloadCart();

      $total=$this->total();

      $impuesto=$this->impuesto();


      if ($total<$configuracion->minimo_compra) {
        
        return redirect('cart/show');

      }

      if (Sentinel::check()) {

        $user_id = Sentinel::getUser()->id;

        $usuario=User::where('id', $user_id)->first();

        $user_cliente=User::where('id', $user_id)->first();


        $role=RoleUser::select('role_id')->where('user_id', $user_id)->first();

       $direcciones = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
          ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
          ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
          ->where('alp_direcciones.id_client', $user_id)->first();

           $formasenvio = AlpFormasenvio::select('alp_formas_envios.*')
          ->join('alp_rol_envio', 'alp_formas_envios.id', '=', 'alp_rol_envio.id_forma_envio')
          ->where('alp_rol_envio.id_rol', $role->role_id)->get();


          $formaspago = AlpFormaspago::select('alp_formas_pagos.*')
          ->join('alp_rol_pago', 'alp_formas_pagos.id', '=', 'alp_rol_pago.id_forma_pago')
          ->where('alp_rol_pago.id_rol', $role->role_id)->get();

          $countries = Country::all();

           $inv = $this->inventario();


           $pagos=AlpPagos::where('id_orden', $carrito)->get();

          $total_pagos=0;

          foreach ($pagos as $pago) {

            $total_pagos=$total_pagos+$pago->monto_pago;

          }




         if(count($cart)<=0){

            return redirect('productos');

         }else{

          $items = array();

          $list=array();

         
              $items["id"]=$carrito;
              $items["title"]='Orden Alpina Nro. '.$carrito;
              $items["description"]='Orden Alpina Nro. '.$carrito;
              $items["picture_url"]= '#';
              $items["quantity"]=1;
              $items["currency_id"]='COP';
              $items["unit_price"]=intval($total-$total_pagos);

              $list[]=$items;


            $preference_data = [
              "items" => $list,
              "payer" => [
                "name" => $usuario->first_name,
                "surname" => $usuario->last_name,
                "email" => $usuario->email,
              ],
              "auto_return" => 'approved',
              "back_urls" => [
                "success" => secure_url('/order/success'),
                "failure" => secure_url('/order/failure'),
                "pending" => secure_url('/order/pending')
              ],
              "notification_url" =>secure_url('/order/mercadopago'),
              "external_reference" =>time()
            ];

           $mp = new MP();

            MP::setCredenciales($configuracion->id_mercadopago, $configuracion->key_mercadopago);

          $preference = MP::post("/checkout/preferences",$preference_data);

          $this->saveOrden($preference);



          $pse_data = [
            "transaction_amount" => $total,
           /* "net_amount" => $total-$impuesto,
            "taxes" =>[ 
                "value" => $impuesto,
                "type" => "IVA"
            ],*/
            "description" => 'Pago de orden Nro.'.$carrito,
            "payer" => [
              "email"=>$usuario->email,
              "identification" => array(
                "type" => "CC",
                "number" => "123123"
              ),
              "entity_type" => "individual"
            ],
            "transaction_details" => ["financial_institution"=>1007],
            "additional_info" => ["ip_address"=>"127.0.0.1"],
            "callback_url" => 'https://alpinago.com/public/orden/pse',
            "payment_method_id" => "pse"

          ];


          $pse = MP::post("/v1/payments",$pse_data);



          $payment_methods = MP::get("/v1/payment_methods");

         // dd($payment_methods);

          /*actualizamos la data del carrito */

          $carro=AlpCarrito::where('id', $carrito)->first();

          $data_carrito = array(
            'id_user' => $user_id );

          $carro->update($data_carrito);

          /*actualizamos la data del carrito */

          $states=State::where('config_states.country_id', '47')->get();

          return view('frontend.order.detail', compact('cart', 'total', 'direcciones', 'formasenvio', 'formaspago', 'countries', 'configuracion', 'states', 'preference', 'inv', 'pagos', 'total_pagos', 'impuesto', 'payment_methods', 'pse'));

         }


      }else{

        $url='order.detail';

          //return redirect('login');
        return view('frontend.order.login', compact('url'));


      }

    }

     public function failure(Request $request)
    {
       
      $configuracion=AlpConfiguracion::where('id', '1')->first();

      $carrito= \Session::get('cr');   

       $impuesto=$this->impuesto();   

        $cart= \Session::get('cart');

        $total=$this->total();

      if (!isset($request->collection_status) || $request->collection_status=='null') {

       

        if (Sentinel::check()) {

        $user_id = Sentinel::getUser()->id;

        $usuario=User::where('id', $user_id)->first();

        $role=RoleUser::select('role_id')->where('user_id', $user_id)->first();

        $direcciones = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
          ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
          ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
          ->where('alp_direcciones.id_client', $user_id)->first();

           $formasenvio = AlpFormasenvio::select('alp_formas_envios.*')
          ->join('alp_rol_envio', 'alp_formas_envios.id', '=', 'alp_rol_envio.id_forma_envio')
          ->where('alp_rol_envio.id_rol', $role->role_id)->get();


          $formaspago = AlpFormaspago::select('alp_formas_pagos.*')
          ->join('alp_rol_pago', 'alp_formas_pagos.id', '=', 'alp_rol_pago.id_forma_pago')
          ->where('alp_rol_pago.id_rol', $role->role_id)->get();

          $countries = Country::all();

          $inv = $this->inventario();

          $pagos=AlpPagos::where('id_orden', $carrito)->get();

          $total_pagos=0;

          foreach ($pagos as $pago) {

            $total_pagos=$total_pagos+$pago->monto_pago;

          }

         if(count($cart)<=0){

            return redirect('productos');

         }else{

          $items = array();

          $list=array();

         
              $items["id"]=$carrito;
              $items["title"]='Orden Alpina Nro. '.$carrito;
              $items["description"]='Orden Alpina Nro. '.$carrito;
              $items["picture_url"]= '#';
              $items["quantity"]=1;
              $items["currency_id"]='COP';
              $items["unit_price"]=intval($total-$total_pagos);

              $list[]=$items;


            $preference_data = [
              "items" => $list,
              "payer" => [
                "name" => $usuario->first_name,
                "surname" => $usuario->last_name,
                "email" => $usuario->email,
              ],
              "auto_return" => 'approved',
              "back_urls" => [
                "success" => secure_url('/order/success'),
                "failure" => secure_url('/order/failure'),
                "pending" => secure_url('/order/pending')
              ],
              "notification_url" =>secure_url('/order/mercadopago'),
              "external_reference" =>time()
            ];

           $mp = new MP();

            MP::setCredenciales($configuracion->id_mercadopago, $configuracion->key_mercadopago);

          $preference = MP::post("/checkout/preferences",$preference_data);

          $this->saveOrden($preference);



          $pse_data = [
            "transaction_amount" => $total,           
            "description" => 'Pago de orden Nro.'.$carrito,
            "payer" => [
              "email"=>$usuario->email,
              "identification" => array(
                "type" => "CC",
                "number" => "123123"
              ),
              "entity_type" => "individual"
            ],
            "transaction_details" => ["financial_institution"=>1007],
            "additional_info" => ["ip_address"=>"127.0.0.1"],
            "callback_url" => 'https://alpinago.com/public/orden/pse',
            "payment_method_id" => "pse",
            

          ];


          $pse = MP::post("/v1/payments",$pse_data);



          $payment_methods = MP::get("/v1/payment_methods");
            //$preference=null;

            ///print_r($preference);

            $states=State::where('config_states.country_id', '47')->get();

            return view('frontend.order.failure', compact('cart', 'total', 'impuesto', 'direcciones', 'formasenvio', 'formaspago', 'countries','preference', 'states', 'configuracion', 'inv', 'pagos', 'total_pagos', 'payment_methods', 'pse'));


         }

      }

    }

  }


  public function orderProcesarTicket(Request $request)
    {


    //  dd($preference);

   /* if ($request->collection_status=='approved') {*/
     
      $input=$request->all();

      $cart= \Session::get('cart');

      $carrito= \Session::get('cr');

      $orden_data= \Session::get('orden');

      $total=$this->total();

      $impuesto=$this->impuesto();

      if (Sentinel::check()) {

        $user_id = Sentinel::getUser()->id;

        $user_cliente=User::where('id', $user_id)->first();


         $configuracion = AlpConfiguracion::where('id', '1')->first();

          MP::setCredenciales($configuracion->id_mercadopago, $configuracion->key_mercadopago);

     


           $preference_data = [
            "transaction_amount" => $total,
            "description" => 'Pago de orden '.$carrito,
            "payment_method_id" => $request->idpago,
            "payer" => [
              "email"=>$user_cliente->email]
          ];


          $payment = MP::post("/v1/payments",$preference_data);


          if (isset($payment['response']['id'])) {
           
          
        $direccion=AlpDirecciones::where('id', $orden_data['id_direccion'])->first();

        $ciudad_forma=AlpFormaCiudad::where('id_forma', $orden_data['id_forma_envio'])->where('id_ciudad', $direccion->city_id)->first();

        $date = Carbon::now();

        $hora=$date->format('hi');

        $hora_base=str_replace(':', '', $ciudad_forma->hora);

        if (intval($hora)>intval($hora_base)) {

          $ciudad_forma->dias=$ciudad_forma->dias+1;

        }

        $fecha_entrega=$date->addDays($ciudad_forma->dias)->format('d-m-Y');

        $role=RoleUser::select('role_id')->where('user_id', $user_id)->first();

        $data_orden = array(
            'referencia ' => time(), 
            'id_cliente' => $user_id, 
            'id_forma_envio' =>$request->id_forma_envio, 
            'id_address' =>$request->id_direccion, 
            'id_forma_pago' =>$request->id_forma_pago,  
            'estatus' =>'8', 
            'estatus_pago' =>'4', 
            'monto_total' =>$total,
            'monto_total_base' =>$total,
            'base_impuesto' =>'0',
            'valor_impuesto' =>'0',
            'monto_impuesto' =>'0',
            'id_user' =>$user_id
        );

        $orden=AlpOrdenes::create($data_orden);

        $monto_total_base=0;
        $base_impuesto=0;
        $monto_impuesto=0;
        $valor_impuesto=0;

        foreach ($cart as $detalle) {

          $monto_total_base=$monto_total_base+($detalle->cantidad*$detalle->precio_base);

           $total_detalle=$detalle->precio_oferta*$detalle->cantidad;

            if ($detalle->valor_impuesto!=0) {

           

            $base_imponible_detalle=$total_detalle/(1+$detalle->valor_impuesto);

            $base_impuesto=$base_impuesto+$base_imponible_detalle;

            $valor_impuesto=$detalle->valor_impuesto;
            
          }

          $imp=$detalle->valor_impuesto+1;

          $monto_impuesto=$monto_impuesto+$detalle->valor_impuesto*($total_detalle/$imp);


          

          $data_detalle = array(
            'id_orden' => $orden->id, 
            'id_producto' => $detalle->id, 
            'cantidad' =>$detalle->cantidad, 
            'precio_unitario' =>$detalle->precio_oferta, 
            'precio_base' =>$detalle->precio_base, 
            'precio_total' =>$detalle->cantidad*$detalle->precio_oferta,
            'precio_total_base' =>$detalle->cantidad*$detalle->precio_base,
            'valor_impuesto' =>$detalle->valor_impuesto,
            'monto_impuesto' =>$detalle->valor_impuesto*$detalle->precio_oferta,
            'id_user' =>$user_id 
          );

          $data_inventario = array(
            'id_producto' => $detalle->id, 
            'cantidad' =>$detalle->cantidad, 
            'operacion' =>'2', 
            'id_user' =>$user_id 
          );

          AlpDetalles::create($data_detalle);

          AlpInventario::create($data_inventario);

        }//endfreach



        $cliente=AlpClientes::where('id_user_client', $user_id)->first();

        if (isset($cliente)) {
           
          if ($cliente->id_embajador!=0) {
             
                $data_puntos = array(
                    'id_orden' => $orden->id,
                    'id_cliente' => $cliente->id_embajador,
                    'tipo' => '1',//agregar
                    'cantidad' =>$total ,
                    'id_user' =>$user_id                   
                );

                AlpPuntos::create($data_puntos);

              }

           }

          $data_envio = array(
            'id_orden' => $orden->id, 
            'fecha_envio' => $date->addDays($ciudad_forma->dias)->format('Y-m-d'),
            'estatus' => 1, 
            'id_user' =>$user_id                   

          );

          $envio=AlpEnvios::create($data_envio);

          $data_envio_history = array(
            'id_envio' => $envio->id, 
            'estatus_envio' => 1, 
            'nota' => 'Envio recibido', 
            'id_user' =>$user_id                   

          );

          AlpEnviosHistory::create($data_envio_history);

           $data_update = array(
            'referencia' => 'ALP'.$orden->id,
            'monto_total_base' => $monto_total_base,
            'base_impuesto' => $base_impuesto,
            'monto_impuesto' => $monto_impuesto,
            'valor_impuesto' => $valor_impuesto

             );

           $orden->update($data_update);



           $data_pago = array(
            'id_orden' => $orden->id, 
            'id_forma_pago' => $request->id_forma_pago, 
            'id_estatus_pago' => 4, 
            'monto_pago' => $total, 
            'json' => json_encode($payment), 
            'id_user' => $user_id, 
          );

           AlpPagos::create($data_pago);

           $aviso_pago="Hemos procesado su orden satisfactoriamente, Su id para realizar el deposito en efectivo es <h4>".$payment['response']['id']."</h4>. Las indicaciones para finalizar su pago puede seguirlas en este enlace <a target='_blank' href='".$payment['response']['transaction_details']['external_resource_url']."' >Ticket</a>. Tiene 72 Horas para realizar el pago, o su orden sera cancelada. ¡Muchas gracias por su Compra!";

         //  $datalles=AlpDetalles::where('id_orden', $orden->id)->get();

          $compra =  DB::table('alp_ordenes')->select('alp_ordenes.*','users.first_name as first_name','users.last_name as last_name' ,'users.email as email','alp_formas_envios.nombre_forma_envios as nombre_forma_envios','alp_formas_envios.descripcion_forma_envios as descripcion_forma_envios','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago','alp_formas_pagos.descripcion_forma_pago as descripcion_forma_pago','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.doc_cliente as doc_cliente')
              ->join('users','alp_ordenes.id_cliente' , '=', 'users.id')
              ->join('alp_clientes','alp_ordenes.id_cliente' , '=', 'alp_clientes.id_user_client')
              ->join('alp_formas_envios','alp_ordenes.id_forma_envio' , '=', 'alp_formas_envios.id')
              ->join('alp_formas_pagos','alp_ordenes.id_forma_pago' , '=', 'alp_formas_pagos.id')
              ->where('alp_ordenes.id', $orden->id)->first();


          $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug')
            ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
            ->where('alp_ordenes_detalle.id_orden', $orden->id)->get();


           $cart= \Session::forget('cart');

           $states=State::where('config_states.country_id', '47')->get();

           $configuracion = AlpConfiguracion::where('id','1')->first();

            $user_cliente=User::where('id', $user_id)->first();

            $texto='Se ha creado la siguiente orden '.$compra->id.' y esta a espera de aprobacion  ';

            //Mail::to($user_cliente->email)->send(new \App\Mail\NotificacionOrden($compra->id, $texto));

           // Mail::to($configuracion->correo_cedi)->send(new \App\Mail\NotificacionOrden($compra->id, $texto));


          Mail::to($user_cliente->email)->send(new \App\Mail\CompraRealizada($compra, $detalles, $fecha_entrega));

          Mail::to($configuracion->correo_sac)->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega));
            

            return view('frontend.order.procesarticket', compact('compra', 'detalles', 'fecha_entrega', 'states', 'aviso_pago', 'payment'));

        }else{


          return redirect('order/detail');


        }
        

      }else{

          return redirect('login');
      }

    /*}else{

          return redirect('orden/failure');

    }*/

}





      public function orderpse(Request $request)
    {


   /* if ($request->collection_status=='approved') {*/
     
      $input=$request->all();

      $cart= \Session::get('cart');

      $orden_data= \Session::get('orden');

      $total=$this->total();

      if (Sentinel::check()) {

        $user_id = Sentinel::getUser()->id;

        $direccion=AlpDirecciones::where('id', $orden_data['id_direccion'])->first();

        $ciudad_forma=AlpFormaCiudad::where('id_forma', $orden_data['id_forma_envio'])->where('id_ciudad', $direccion->city_id)->first();


        $date = Carbon::now();

        $hora=$date->format('hi');

        $hora_base=str_replace(':', '', $ciudad_forma->hora);

        if (intval($hora)>intval($hora_base)) {

          $ciudad_forma->dias=$ciudad_forma->dias+1;

        }

        $fecha_entrega=$date->addDays($ciudad_forma->dias)->format('d-m-Y');

        $role=RoleUser::select('role_id')->where('user_id', $user_id)->first();

        $data_orden = array(
            'referencia ' => time(), 
            'id_cliente' => $user_id, 
            'id_forma_envio' =>$orden_data['id_forma_envio'], 
            'id_address' =>$orden_data['id_direccion'], 
            'id_forma_pago' =>$orden_data['id_forma_pago'], 
            'estatus' =>'1', 
            'estatus_pago' =>'2', 
            'monto_total' =>$total,
            'monto_total_base' =>$total,
            'base_impuesto' =>'0',
            'valor_impuesto' =>'0',
            'monto_impuesto' =>'0',
            'id_user' =>$user_id
        );

        $orden=AlpOrdenes::create($data_orden);

        $monto_total_base=0;
        $base_impuesto=0;
        $monto_impuesto=0;
        $valor_impuesto=0;

        foreach ($cart as $detalle) {

          $monto_total_base=$monto_total_base+($detalle->cantidad*$detalle->precio_base);

           $total_detalle=$detalle->precio_oferta*$detalle->cantidad;

            if ($detalle->valor_impuesto!=0) {

           

            $base_imponible_detalle=$total_detalle/(1+$detalle->valor_impuesto);

            $base_impuesto=$base_impuesto+$base_imponible_detalle;

            $valor_impuesto=$detalle->valor_impuesto;
            
          }

          $imp=$detalle->valor_impuesto+1;

          $monto_impuesto=$monto_impuesto+$detalle->valor_impuesto*($total_detalle/$imp);
          

          $data_detalle = array(
            'id_orden' => $orden->id, 
            'id_producto' => $detalle->id, 
            'cantidad' =>$detalle->cantidad, 
            'precio_unitario' =>$detalle->precio_oferta, 
            'precio_base' =>$detalle->precio_base, 
            'precio_total' =>$detalle->cantidad*$detalle->precio_oferta,
            'precio_total_base' =>$detalle->cantidad*$detalle->precio_base,
            'valor_impuesto' =>$detalle->valor_impuesto,
            'monto_impuesto' =>$detalle->valor_impuesto*$detalle->precio_oferta,
            'id_user' =>$user_id 
          );

          $data_inventario = array(
            'id_producto' => $detalle->id, 
            'cantidad' =>$detalle->cantidad, 
            'operacion' =>'2', 
            'id_user' =>$user_id 
          );

          AlpDetalles::create($data_detalle);

          AlpInventario::create($data_inventario);

        }//endfreach



        $cliente=AlpClientes::where('id_user_client', $user_id)->first();

        if (isset($cliente)) {
           
          if ($cliente->id_embajador!=0) {
             
              $data_puntos = array(
                  'id_orden' => $orden->id,
                  'id_cliente' => $cliente->id_embajador,
                  'tipo' => '1',//agregar
                  'cantidad' =>$total ,
                  'id_user' =>$user_id                   
              );

              AlpPuntos::create($data_puntos);

            }

         }

        $data_envio = array(
          'id_orden' => $orden->id, 
          'fecha_envio' => $date->addDays($ciudad_forma->dias)->format('Y-m-d'),
          'estatus' => 1, 
          'id_user' =>$user_id                   

        );

        $envio=AlpEnvios::create($data_envio);

        $data_envio_history = array(
          'id_envio' => $envio->id, 
          'estatus_envio' => 1, 
          'nota' => 'Envio recibido', 
          'id_user' =>$user_id                   

        );

        AlpEnviosHistory::create($data_envio_history);

         $data_update = array(
          'referencia' => 'ALP'.$orden->id,
          'monto_total_base' => $monto_total_base,
          'base_impuesto' => $base_impuesto,
          'monto_impuesto' => $monto_impuesto,
          'valor_impuesto' => $valor_impuesto

           );

         $orden->update($data_update);

         $data_pago = array(
          'id_orden' => $orden->id, 
          'id_forma_pago' => $orden_data['id_forma_pago'], 
          'id_estatus_pago' => 4, 
          'monto_pago' => $total, 
          'json' => json_encode($input), 
          'id_user' => $user_id, 
        );

         AlpPagos::create($data_pago);

         $aviso_pago="Hemos recibido su pago satisfactoriamente, una vez sea confirmado, Le llegará un email con la descripción de su pago. ¡Muchas gracias por su Compra!";

       //  $datalles=AlpDetalles::where('id_orden', $orden->id)->get();

        $compra =  DB::table('alp_ordenes')->select('alp_ordenes.*','users.first_name as first_name','users.last_name as last_name' ,'users.email as email','alp_formas_envios.nombre_forma_envios as nombre_forma_envios','alp_formas_envios.descripcion_forma_envios as descripcion_forma_envios','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago','alp_formas_pagos.descripcion_forma_pago as descripcion_forma_pago','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.doc_cliente as doc_cliente')
            ->join('users','alp_ordenes.id_cliente' , '=', 'users.id')
            ->join('alp_clientes','alp_ordenes.id_cliente' , '=', 'alp_clientes.id_user_client')
            ->join('alp_formas_envios','alp_ordenes.id_forma_envio' , '=', 'alp_formas_envios.id')
            ->join('alp_formas_pagos','alp_ordenes.id_forma_pago' , '=', 'alp_formas_pagos.id')
            ->where('alp_ordenes.id', $orden->id)->first();


        $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug')
          ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
          ->where('alp_ordenes_detalle.id_orden', $orden->id)->get();


         $cart= \Session::forget('cart');

         $states=State::where('config_states.country_id', '47')->get();

         $configuracion = AlpConfiguracion::where('id','1')->first();

          $user_cliente=User::where('id', $user_id)->first();

          $texto='Se ha creado la siguiente orden '.$compra->id.' y esta a espera de aprobacion  ';


        Mail::to($user_cliente->email)->send(new \App\Mail\CompraRealizada($compra, $detalles, $fecha_entrega));

        Mail::to($configuracion->correo_sac)->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega));
          

          return view('frontend.order.procesar', compact('compra', 'detalles', 'fecha_entrega', 'states', 'aviso_pago'));
        

      }else{

          return redirect('login');
      }

    /*}else{

          return redirect('orden/failure');

    }*/

}






      public function success(Request $request)
    {


   /* if ($request->collection_status=='approved') {*/
     
      $input=$request->all();

      $cart= \Session::get('cart');

      $orden_data= \Session::get('orden');

      $total=$this->total();

      if (Sentinel::check()) {

        $user_id = Sentinel::getUser()->id;

        $direccion=AlpDirecciones::where('id', $orden_data['id_direccion'])->first();

        $ciudad_forma=AlpFormaCiudad::where('id_forma', $orden_data['id_forma_envio'])->where('id_ciudad', $direccion->city_id)->first();


        $date = Carbon::now();

        $hora=$date->format('hi');

        $hora_base=str_replace(':', '', $ciudad_forma->hora);

        if (intval($hora)>intval($hora_base)) {

          $ciudad_forma->dias=$ciudad_forma->dias+1;

        }

        $fecha_entrega=$date->addDays($ciudad_forma->dias)->format('d-m-Y');

        $role=RoleUser::select('role_id')->where('user_id', $user_id)->first();

        $data_orden = array(
            'referencia ' => time(), 
            'id_cliente' => $user_id, 
            'id_forma_envio' =>$orden_data['id_forma_envio'], 
            'id_address' =>$orden_data['id_direccion'], 
            'id_forma_pago' =>$orden_data['id_forma_pago'], 
            'estatus' =>'1', 
            'estatus_pago' =>'2', 
            'monto_total' =>$total,
            'monto_total_base' =>$total,
            'base_impuesto' =>'0',
            'valor_impuesto' =>'0',
            'monto_impuesto' =>'0',
            'id_user' =>$user_id
        );

        $orden=AlpOrdenes::create($data_orden);

        $monto_total_base=0;
        $base_impuesto=0;
        $monto_impuesto=0;
        $valor_impuesto=0;

        foreach ($cart as $detalle) {

          $monto_total_base=$monto_total_base+($detalle->cantidad*$detalle->precio_base);

           $total_detalle=$detalle->precio_oferta*$detalle->cantidad;

            if ($detalle->valor_impuesto!=0) {

           

            $base_imponible_detalle=$total_detalle/(1+$detalle->valor_impuesto);

            $base_impuesto=$base_impuesto+$base_imponible_detalle;

            $valor_impuesto=$detalle->valor_impuesto;
            
          }

          $imp=$detalle->valor_impuesto+1;

          $monto_impuesto=$monto_impuesto+$detalle->valor_impuesto*($total_detalle/$imp);


          

          $data_detalle = array(
            'id_orden' => $orden->id, 
            'id_producto' => $detalle->id, 
            'cantidad' =>$detalle->cantidad, 
            'precio_unitario' =>$detalle->precio_oferta, 
            'precio_base' =>$detalle->precio_base, 
            'precio_total' =>$detalle->cantidad*$detalle->precio_oferta,
            'precio_total_base' =>$detalle->cantidad*$detalle->precio_base,
            'valor_impuesto' =>$detalle->valor_impuesto,
            'monto_impuesto' =>$detalle->valor_impuesto*$detalle->precio_oferta,
            'id_user' =>$user_id 
          );

          $data_inventario = array(
            'id_producto' => $detalle->id, 
            'cantidad' =>$detalle->cantidad, 
            'operacion' =>'2', 
            'id_user' =>$user_id 
          );

          AlpDetalles::create($data_detalle);

          AlpInventario::create($data_inventario);

        }//endfreach



        $cliente=AlpClientes::where('id_user_client', $user_id)->first();

        if (isset($cliente)) {
           
          if ($cliente->id_embajador!=0) {
             
              $data_puntos = array(
                  'id_orden' => $orden->id,
                  'id_cliente' => $cliente->id_embajador,
                  'tipo' => '1',//agregar
                  'cantidad' =>$total ,
                  'id_user' =>$user_id                   
              );

              AlpPuntos::create($data_puntos);

            }

         }

        $data_envio = array(
          'id_orden' => $orden->id, 
          'fecha_envio' => $date->addDays($ciudad_forma->dias)->format('Y-m-d'),
          'estatus' => 1, 
          'id_user' =>$user_id                   

        );

        $envio=AlpEnvios::create($data_envio);

        $data_envio_history = array(
          'id_envio' => $envio->id, 
          'estatus_envio' => 1, 
          'nota' => 'Envio recibido', 
          'id_user' =>$user_id                   

        );

        AlpEnviosHistory::create($data_envio_history);

         $data_update = array(
          'referencia' => 'ALP'.$orden->id,
          'monto_total_base' => $monto_total_base,
          'base_impuesto' => $base_impuesto,
          'monto_impuesto' => $monto_impuesto,
          'valor_impuesto' => $valor_impuesto

           );

         $orden->update($data_update);

         $data_pago = array(
          'id_orden' => $orden->id, 
          'id_forma_pago' => $orden_data['id_forma_pago'], 
          'id_estatus_pago' => 2, 
          'monto_pago' => $total, 
          'json' => json_encode($input), 
          'id_user' => $user_id, 
        );

         AlpPagos::create($data_pago);

         $aviso_pago="Hemos recibido su pago satisfactoriamente, una vez sea confirmado, Le llegará un email con la descripción de su pago. ¡Muchas gracias por su Compra!";

       //  $datalles=AlpDetalles::where('id_orden', $orden->id)->get();

        $compra =  DB::table('alp_ordenes')->select('alp_ordenes.*','users.first_name as first_name','users.last_name as last_name' ,'users.email as email','alp_formas_envios.nombre_forma_envios as nombre_forma_envios','alp_formas_envios.descripcion_forma_envios as descripcion_forma_envios','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago','alp_formas_pagos.descripcion_forma_pago as descripcion_forma_pago','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.doc_cliente as doc_cliente')
            ->join('users','alp_ordenes.id_cliente' , '=', 'users.id')
            ->join('alp_clientes','alp_ordenes.id_cliente' , '=', 'alp_clientes.id_user_client')
            ->join('alp_formas_envios','alp_ordenes.id_forma_envio' , '=', 'alp_formas_envios.id')
            ->join('alp_formas_pagos','alp_ordenes.id_forma_pago' , '=', 'alp_formas_pagos.id')
            ->where('alp_ordenes.id', $orden->id)->first();


        $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug')
          ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
          ->where('alp_ordenes_detalle.id_orden', $orden->id)->get();


         $cart= \Session::forget('cart');

         $states=State::where('config_states.country_id', '47')->get();

         $configuracion = AlpConfiguracion::where('id','1')->first();

          $user_cliente=User::where('id', $user_id)->first();

          $texto='Se ha creado la siguiente orden '.$compra->id.' y esta a espera de aprobacion  ';

          //Mail::to($user_cliente->email)->send(new \App\Mail\NotificacionOrden($compra->id, $texto));

         // Mail::to($configuracion->correo_cedi)->send(new \App\Mail\NotificacionOrden($compra->id, $texto));


        Mail::to($user_cliente->email)->send(new \App\Mail\CompraRealizada($compra, $detalles, $fecha_entrega));

        Mail::to($configuracion->correo_sac)->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega));
          

          return view('frontend.order.procesar', compact('compra', 'detalles', 'fecha_entrega', 'states', 'aviso_pago'));
        

      }else{

          return redirect('login');
      }

    /*}else{

          return redirect('orden/failure');

    }*/

}

      public function pending(Request $request)
    {

    //if ($request->collection_status=='approved') {
     
      $input=$request->all();

      $cart= \Session::get('cart');

      $orden_data= \Session::get('orden');

      $total=$this->total();

      if (Sentinel::check()) {

        $user_id = Sentinel::getUser()->id;

        $direccion=AlpDirecciones::where('id', $orden_data['id_direccion'])->first();

        $ciudad_forma=AlpFormaCiudad::where('id_forma', $orden_data['id_forma_envio'])->where('id_ciudad', $direccion->city_id)->first();


        $date = Carbon::now();

        $hora=$date->format('hi');

        $hora_base=str_replace(':', '', $ciudad_forma->hora);

        if (intval($hora)>intval($hora_base)) {

          $ciudad_forma->dias=$ciudad_forma->dias+1;

        }

        $fecha_entrega=$date->addDays($ciudad_forma->dias)->format('d-m-Y');

        $role=RoleUser::select('role_id')->where('user_id', $user_id)->first();

        $data_orden = array(
            'referencia ' => time(), 
            'id_cliente' => $user_id, 
            'id_forma_envio' =>$orden_data['id_forma_envio'], 
            'id_address' =>$orden_data['id_direccion'], 
            'id_forma_pago' =>$orden_data['id_forma_pago'], 
            'estatus' =>'8', 
            'estatus_pago' =>'4', 
            'monto_total' =>$total,
            'monto_total_base' =>$total,
            'base_impuesto' =>'0',
            'valor_impuesto' =>'0',
            'monto_impuesto' =>'0',
            'id_user' =>$user_id
        );

        $orden=AlpOrdenes::create($data_orden);

        $monto_total_base=0;

        $base_impuesto=0;
        $monto_impuesto=0;
        $valor_impuesto=0;

        foreach ($cart as $detalle) {

          $monto_total_base=$monto_total_base+($detalle->cantidad*$detalle->precio_base);

          $total_detalle=$detalle->precio_oferta*$detalle->cantidad;

            if ($detalle->valor_impuesto!=0) {

           

            $base_imponible_detalle=$total_detalle/(1+$detalle->valor_impuesto);

            $base_impuesto=$base_impuesto+$base_imponible_detalle;

            $valor_impuesto=$detalle->valor_impuesto;
            
          }

          $imp=$detalle->valor_impuesto+1;

          $monto_impuesto=$monto_impuesto+$detalle->valor_impuesto*($total_detalle/$imp);

          $data_detalle = array(
            'id_orden' => $orden->id, 
            'id_producto' => $detalle->id, 
            'cantidad' =>$detalle->cantidad, 
            'precio_unitario' =>$detalle->precio_oferta, 
            'precio_base' =>$detalle->precio_base, 
            'precio_total' =>$detalle->cantidad*$detalle->precio_oferta,
            'precio_total_base' =>$detalle->cantidad*$detalle->precio_base,
             'valor_impuesto' =>$detalle->valor_impuesto,
            'monto_impuesto' =>$detalle->valor_impuesto*$detalle->precio_oferta,
            'id_user' =>$user_id 
          );

          $data_inventario = array(
            'id_producto' => $detalle->id, 
            'cantidad' =>$detalle->cantidad, 
            'operacion' =>'2', 
            'id_user' =>$user_id 
          );

          AlpDetalles::create($data_detalle);

          AlpInventario::create($data_inventario);

        }//endfreach



        $cliente=AlpClientes::where('id_user_client', $user_id)->first();

        if (isset($cliente)) {
           
          if ($cliente->id_embajador!=0) {
             
              $data_puntos = array(
                  'id_orden' => $orden->id,
                  'id_cliente' => $cliente->id_embajador,
                  'tipo' => '1',//agregar
                  'cantidad' =>$total ,
                  'id_user' =>$user_id                   
              );


              AlpPuntos::create($data_puntos);

            }

         }

        $data_envio = array(
          'id_orden' => $orden->id, 
          'fecha_envio' => $date->addDays($ciudad_forma->dias)->format('Y-m-d'),
          'estatus' => 1, 
          'id_user' =>$user_id                   

        );

        $envio=AlpEnvios::create($data_envio);

        $data_envio_history = array(
          'id_envio' => $envio->id, 
          'estatus_envio' => 8, 
          'nota' => 'Envio recibido', 
          'id_user' =>$user_id                   

        );

        AlpEnviosHistory::create($data_envio_history);

         $data_update = array(
          'referencia' => 'ALP'.$orden->id,
          'monto_total_base' => $monto_total_base,

          'base_impuesto' => $base_impuesto,
          'monto_impuesto' => $monto_impuesto,
          'valor_impuesto' => $valor_impuesto
           );

         $orden->update($data_update);

         $data_pago = array(
          'id_orden' => $orden->id, 
          'id_forma_pago' => $orden_data['id_forma_pago'], 
          'id_estatus_pago' => 4, 
          'monto_pago' => $total, 
          'json' => json_encode($input), 
          'id_user' => $user_id, 
        );

         AlpPagos::create($data_pago);

         $aviso_pago="Su pago está siendo procesado , deberá finalizar el proceso en 24 horas o su pedido será cancelado.!";

       //  $datalles=AlpDetalles::where('id_orden', $orden->id)->get();

        $compra =  DB::table('alp_ordenes')->select('alp_ordenes.*','users.first_name as first_name','users.last_name as last_name' ,'users.email as email','alp_formas_envios.nombre_forma_envios as nombre_forma_envios','alp_formas_envios.descripcion_forma_envios as descripcion_forma_envios','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago','alp_formas_pagos.descripcion_forma_pago as descripcion_forma_pago','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.doc_cliente as doc_cliente')
            ->join('users','alp_ordenes.id_cliente' , '=', 'users.id')
            ->join('alp_clientes','alp_ordenes.id_cliente' , '=', 'alp_clientes.id_user_client')
            ->join('alp_formas_envios','alp_ordenes.id_forma_envio' , '=', 'alp_formas_envios.id')
            ->join('alp_formas_pagos','alp_ordenes.id_forma_pago' , '=', 'alp_formas_pagos.id')
            ->where('alp_ordenes.id', $orden->id)->first();


        $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.referencia_producto as referencia_producto','alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug')
          ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
          ->where('alp_ordenes_detalle.id_orden', $orden->id)->get();

         $cart= \Session::forget('cart');

         $states=State::where('config_states.country_id', '47')->get();

         $configuracion = AlpConfiguracion::where('id','1')->first();

          $user_cliente=User::where('id', $user_id)->first();

          $texto='Se ha creado la siguiente orden '.$compra->id.' y está en proceso de espera para aprobación de pago, deberá completar el pago en 24 horas o la orden será cancelada';

          //Mail::to($user_cliente->email)->send(new \App\Mail\NotificacionOrden($compra->id, $texto));

         // Mail::to($configuracion->correo_cedi)->send(new \App\Mail\NotificacionOrden($compra->id, $texto));


        Mail::to($user_cliente->email)->send(new \App\Mail\CompraRealizada($compra, $detalles, $fecha_entrega));

        Mail::to($configuracion->correo_sac)->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega));
          

          return view('frontend.order.procesar', compact('compra', 'detalles', 'fecha_entrega', 'states', 'aviso_pago'));

      }else{

          return redirect('login');
      }


   // }else{

   //       return redirect('orden/failure');

   // }


}




 public function saveOrden($preference){


      $carrito= \Session::get('cr');

      //$cart=$this->reloadCart();

      $cart= \Session::get('cart');


      $total=$this->total();

      $user_id = Sentinel::getUser()->id;


      $data_orden = array(
            'referencia ' => time(), 
            'id_cliente' => $user_id, 
            'estatus' =>'1', 
            'monto_total' =>$total,
            'monto_total_base' =>$total,
            'preferencia_id' => $preference['response']['id'],
            'json' => json_encode($preference),
            'id_user' =>$user_id
          );

      $orden=AlpPreOrdenes::create($data_orden);


       $monto_total_base=0;


         foreach ($cart as $detalle) {

          //dd($detalle);

            $monto_total_base=$monto_total_base+($detalle->cantidad*$detalle->precio_base);

            $data_detalle = array(
              'id_orden' => $orden->id, 
              'id_producto' => $detalle->id, 
              'cantidad' =>$detalle->cantidad, 
              'precio_base' =>$detalle->precio_base, 
              'precio_total_base' =>$detalle->cantidad*$detalle->precio_base, 
              'precio_unitario' =>$detalle->precio_oferta, 
              'precio_total' =>$detalle->cantidad*$detalle->precio_oferta,
              'id_user' =>$user_id 
            );

            AlpPreDetalles::create($data_detalle);

         

         }


         $data_update = array(
          'referencia' => 'PRE'.$orden->id,
          'monto_total_base' => $monto_total_base,
        );

         $orden->update($data_update);



    }






    public function orderProcesar(Request $request)
    {
       $cart= \Session::get('cart');

       if (count($cart)>0) {


       $carrito= \Session::get('cr');

      $total=$this->total();

      $aviso_pago='0';

        $configuracion = AlpConfiguracion::where('id','1')->first();


      if (Sentinel::check()) {

        $user_id = Sentinel::getUser()->id;

        $direccion=AlpDirecciones::where('id', $request->id_direccion)->first();

        $ciudad_forma=AlpFormaCiudad::where('id_forma', $request->id_forma_envio)->where('id_ciudad', $direccion->city_id)->first();


        $date = Carbon::now();

        $hora=$date->format('hi');

        $hora_base=str_replace(':', '', $ciudad_forma->hora);

        if (intval($hora)>intval($hora_base)) {
          $ciudad_forma->dias=$ciudad_forma->dias+1;
        }


       $fecha_entrega=$date->addDays($ciudad_forma->dias)->format('d-m-Y');


        $role=RoleUser::select('role_id')->where('user_id', $user_id)->first();


         $data_orden = array(
            'referencia ' => time(), 
            'id_cliente' => $user_id, 
            'id_forma_envio' =>$request->id_forma_envio, 
            'id_address' =>$request->id_direccion, 
            'id_forma_pago' =>$request->id_forma_pago, 
            'estatus' =>'1', 
            'estatus_pago' =>'1', 
            'monto_total' =>$total,
            'monto_total_base' =>$total,
            'base_impuesto' =>'0',
            'valor_impuesto' =>'0',
            'monto_impuesto' =>'0',
            'id_user' =>$user_id
          );

         $orden=AlpOrdenes::create($data_orden);

         $monto_total_base=0;
          $base_impuesto=0;
        $monto_impuesto=0;
        $valor_impuesto=0;


         foreach ($cart as $detalle) {

          //dd($detalle);

            $monto_total_base=$monto_total_base+($detalle->cantidad*$detalle->precio_base);

            $total_detalle=$detalle->precio_oferta*$detalle->cantidad;

            if ($detalle->valor_impuesto!=0) {

           

            $base_imponible_detalle=$total_detalle/(1+$detalle->valor_impuesto);

            $base_impuesto=$base_impuesto+$base_imponible_detalle;

            $valor_impuesto=$detalle->valor_impuesto;
            
          }

          $imp=$detalle->valor_impuesto+1;

          $monto_impuesto=$monto_impuesto+$detalle->valor_impuesto*($total_detalle/$imp);

            $data_detalle = array(
              'id_orden' => $orden->id, 
              'id_producto' => $detalle->id, 
              'cantidad' =>$detalle->cantidad, 
              'precio_base' =>$detalle->precio_base, 
              'precio_total_base' =>$detalle->cantidad*$detalle->precio_base, 
              'precio_unitario' =>$detalle->precio_oferta, 
              'precio_total' =>$detalle->cantidad*$detalle->precio_oferta,
            'valor_impuesto' =>$detalle->valor_impuesto,
            'monto_impuesto' =>$detalle->valor_impuesto*($detalle->precio_oferta*$detalle->cantidad),
              'id_user' =>$user_id 
            );

            $data_inventario = array(
              'id_producto' => $detalle->id, 
              'cantidad' =>$detalle->cantidad, 
              'operacion' =>'2', 
              'id_user' =>$user_id 
            );

            AlpDetalles::create($data_detalle);

            AlpInventario::create($data_inventario);

         }

         $cliente=AlpClientes::where('id_user_client', $user_id)->first();

         if (isset($cliente)) {
           
            if ($cliente->id_embajador!=0) {
             
                $data_puntos = array(
                  'id_orden' => $orden->id,
                  'id_cliente' => $cliente->id_embajador,
                  'tipo' => '1',//agregar
                  'cantidad' =>$total ,
                  'id_user' =>$user_id                   
                );

            }else{

              $data_puntos = array(
                  'id_orden' => $orden->id,
                  'id_cliente' => $user_id,
                  'tipo' => '1',//agregar
                  'cantidad' =>$total,
                  'id_user' =>$user_id                   
                );

            }

          AlpPuntos::create($data_puntos);


         }

        $data_envio = array(
          'id_orden' => $orden->id, 
          'fecha_envio' => $date->addDays($ciudad_forma->dias)->format('Y-m-d'),
          'estatus' => 1, 
          'id_user' =>$user_id                   

        );


        $envio=AlpEnvios::create($data_envio);

        $data_envio_history = array(
          'id_envio' => $envio->id, 
          'estatus_envio' => 1, 
          'nota' => 'Envio recibido', 
          'id_user' =>$user_id                   

        );


        AlpEnviosHistory::create($data_envio_history);

         $data_update = array(
          'referencia' => 'ALP'.$orden->id,
          'monto_total_base' => $monto_total_base,
          'base_impuesto' => $base_impuesto,
          'monto_impuesto' => $monto_impuesto,
          'valor_impuesto' => $valor_impuesto
        );

         $orden->update($data_update);


         /*eliminamos el carrito*/

         $carro=AlpCarrito::where('id', $carrito)->first();

         $carro->delete();

         $detalles_carrito=AlpCarritoDetalle::where('id_carrito', $carrito)->get();

         $ids = array();

         foreach ($detalles_carrito as $dc) {
           
          $ids[]=$dc->id;

         }

         AlpCarritoDetalle::destroy($ids);

        $carrito= \Session::forget('cr');

        $compra =  DB::table('alp_ordenes')->select('alp_ordenes.*','users.first_name as first_name','users.last_name as last_name' ,'users.email as email','alp_formas_envios.nombre_forma_envios as nombre_forma_envios','alp_formas_envios.descripcion_forma_envios as descripcion_forma_envios','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago','alp_formas_pagos.descripcion_forma_pago as descripcion_forma_pago','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.doc_cliente as doc_cliente')
            ->join('users','alp_ordenes.id_cliente' , '=', 'users.id')
            ->join('alp_clientes','alp_ordenes.id_cliente' , '=', 'alp_clientes.id_user_client')
            ->join('alp_formas_envios','alp_ordenes.id_forma_envio' , '=', 'alp_formas_envios.id')
            ->join('alp_formas_pagos','alp_ordenes.id_forma_pago' , '=', 'alp_formas_pagos.id')
            ->where('alp_ordenes.id', $orden->id)->first();

        $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.referencia_producto as referencia_producto','alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto' ,'alp_productos.slug as slug')
          ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
          ->where('alp_ordenes_detalle.id_orden', $orden->id)->get();


         $cart= \Session::forget('cart');

         $states=State::where('config_states.country_id', '47')->get();


          $configuracion = AlpConfiguracion::where('id','1')->first();

          $user_cliente=User::where('id', $user_id)->first();

          $texto='Se ha creado la siguiente orden '.$compra->id.' y esta a espera de aprobacion  ';

          Mail::to($user_cliente->email)->send(new \App\Mail\CompraRealizada($compra, $detalles, $fecha_entrega));

          Mail::to($configuracion->correo_sac)->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega));


          //Mail::to($configuracion->correo_cedi)->send(new \App\Mail\NotificacionOrden($compra->id, $texto));

          return view('frontend.order.procesar', compact('compra', 'detalles', 'fecha_entrega', 'states', 'aviso_pago'));

      }else{

          return redirect('login');

    }

    }else{

          return redirect('cart/show');

    }

    }

    public function add( AlpProductos $producto)
    {
       $cart= \Session::get('cart');

       $descuento='1'; 

        if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

            $cliente = AlpClientes::where('id_user_client', $user_id )->first();

            if (isset($cliente->id_empresa) ) {

                if ($cliente->id_empresa!=0) {
                    
                     $empresa=AlpEmpresas::find($cliente->id_empresa);

                    $cliente['nombre_empresa']=$empresa->nombre_empresa;

                    $descuento=(1-($empresa->descuento_empresa/100));
                }
               
            }

        }


       $producto->cantidad=1;

       $producto->precio_base=$producto->precio_base*$descuento;


       $cart[$producto->slug]=$producto;

      // return $cart;

       \Session::put('cart', $cart);

       return redirect('cart/show');

      
    }

    public function addtocart( Request $request)
    {


          $producto=AlpProductos::select('alp_productos.*', 'alp_impuestos.valor_impuesto as valor_impuesto')
          ->join('alp_impuestos', 'alp_productos.id_impuesto', '=', 'alp_impuestos.id')
          ->where('alp_productos.slug', $request->slug)
          ->first();


        if (!\Session::has('cr')) {

          \Session::put('cr', '0');

          $ciudad= \Session::get('ciudad');

          $data = array(
            'referencia' => time(), 
            'id_city' => $ciudad, 
            'id_user' => '0'
          );

          $carr=AlpCarrito::create($data);

          \Session::put('cr', $carr->id);
       
        }

       $cart= \Session::get('cart');


       $carrito= \Session::get('cr');

       $descuento='1'; 

       $error='0'; 

       $precio = array();

       $inv=$this->inventario();


      $producto->precio_oferta=$request->price;

      $producto->cantidad=1;
      $producto->impuesto=$producto->precio_oferta*$producto->valor_impuesto;


      
      if($inv[$producto->id]>=$producto->cantidad){

        $cart[$producto->slug]=$producto;

      }else{

        $error="No hay existencia suficiente de este producto";

      }


       \Session::put('cart', $cart);

       $data_detalle = array(
        'id_carrito' => $carrito, 
        'id_producto' => $producto->id, 
        'cantidad' => $producto->cantidad
      );


       AlpCarritoDetalle::create($data_detalle);

      


       $view= View::make('frontend.order.botones', compact('producto', 'cart'));

          $data=$view->render();

          $res = array('data' => $data);

          return $data;
       // return json_encode($cart);
      
    }

    public function delete( AlpProductos $producto)
    {
       $cart= \Session::get('cart');

       $carrito= \Session::get('cr');

      unset( $cart[$producto->slug]);

      $detalle=AlpCarritoDetalle::where('id_carrito', $carrito)->where('id_producto', $producto->id)->first();

      $detalle->delete();
       

       \Session::put('cart', $cart);

       return redirect('cart/show');

      
    }

    public function update( AlpProductos $producto, $cantidad)
    {
       $cart= \Session::get('cart');

       $carrito= \Session::get('cr');


       $cart[$producto->slug]->cantidad=$cantidad;

       
      // return $cart;

       \Session::put('cart', $cart);

       return redirect('cart/show');

      
    }



    public function updatecantidad(Request $request)
    {
      
      $cart= \Session::get('cart');

      $cart[$request->slug]->cantidad=$request->cantidad;

      \Session::put('cart', $cart);

      return 'true';

      
    }

    public function delproducto( Request $request)
    {
       $cart= \Session::get('cart');

       $producto=AlpProductos::where('slug', $request->slug)->first();
 

      unset( $cart[$request->slug]);

       \Session::put('cart', $cart);

       $view= View::make('frontend.order.botones', compact('producto', 'cart'));

        $data=$view->render();

        return $data;

       //return 'true';
      
    }



    public function updatecart(Request $request)
    {
       $cart= \Session::get('cart');


       $carrito= \Session::get('cr');

       $inv=$this->inventario();

       $error='0';


       if($inv[$request->id]>=$request->cantidad){

        $cart[$request->slug]->cantidad=$request->cantidad;

      }else{

        $error="No hay existencia suficiente de este producto";
      }

       $detalle=AlpCarritoDetalle::where('id_carrito', $carrito)->where('id_producto', $request->id)->first();

      $data = array(
        'cantidad' => $request->cantidad, 
      );

      $detalle->update($data);


       $configuracion=AlpConfiguracion::where('id', '1')->first();

//       $cart=$this->reloadCart();

       \Session::put('cart', $cart);

       $total=$this->total();

        $view= View::make('frontend.listcart', compact('cart', 'total', 'configuracion', 'error'));

        $data=$view->render();

        return $data;
      
    }

    public function vaciar( )
    {
       $cart= \Session::forget('cart');

       $carrito= \Session::forget('cr');
      
       return redirect('cart/show');
      
    }

    private function total()
    {
       $cart= \Session::get('cart');

      $total=0;

      foreach($cart as $row) {

        $total=$total+($row->cantidad*$row->precio_oferta);

      }

       return $total;

      
    }

  

    private function impuesto()
    {
       $cart= \Session::get('cart');

      $impuesto=0;

      foreach($cart as $row) {

        $impuesto=$impuesto+$row->impuesto;

      }

       return $impuesto;

      
    }


    private function cantidad()
    {
       $cart= \Session::get('cart');

      $cantidad=0;

      foreach($cart as $row) {

        $cantidad=$cantidad+($row->cantidad);

      }

       return $cantidad;

    }



    private function inventario()
    {

      $entradas = AlpInventario::groupBy('id_producto')
              ->select("alp_inventarios.*", DB::raw(  "SUM(alp_inventarios.cantidad) as cantidad_total"))
              ->where('alp_inventarios.operacion', '1')
              ->get();

              $inv = array();

              foreach ($entradas as $row) {
                
                $inv[$row->id_producto]=$row->cantidad_total;

              }

            $salidas = AlpInventario::select("alp_inventarios.*", DB::raw(  "SUM(alp_inventarios.cantidad) as cantidad_total"))
              ->groupBy('id_producto')
              ->where('operacion', '2')
              ->get();

              foreach ($salidas as $row) {
                
                $inv[$row->id_producto]= $inv[$row->id_producto]-$row->cantidad_total;

            }

            return $inv;
      
    }


    private function reloadCart()
    {
       $cart= \Session::get('cart');

       $s_user= \Session::get('user');

      $total=0;

      $cambio=0;

      $descuento='1'; 

      $precio = array();

        if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

            //if ($user_id!=$s_user) {
            if (1) {

              $cambio=1;

              \Session::put('user', $user_id);

              $role=RoleUser::where('user_id', $user_id)->first();

              $cliente = AlpClientes::where('id_user_client', $user_id )->first();

              if (isset($cliente->id_empresa) ) {

                  if ($cliente->id_empresa!=0) {

                      $role->role_id='E'.$cliente->id_empresa.'';
                  }
                 
              }

             if ($role->role_id) {
                    
                    
                foreach ($cart as $producto ) {

                  $pregiogrupo=AlpPrecioGrupo::where('id_producto', $producto->id)->where('id_role', $role->role_id)->first();

                  if (isset($pregiogrupo->id)) {
                     
                      $precio[$producto->id]['precio']=$pregiogrupo->precio;

                      $precio[$producto->id]['operacion']=$pregiogrupo->operacion;
                      $precio[$producto->id]['pum']=$pregiogrupo->pum;

                  }

                }
                
            }

          }

        } //end sentinel check


      
    if ($cambio==1) {

      foreach ($cart as $producto) {

      if ($descuento=='1') {

        if (isset($precio[$producto->id])) {
          # code...
         
          switch ($precio[$producto->id]['operacion']) {

            case 1:

              $producto->precio_oferta=$producto->precio_base*$descuento;

              break;

            case 2:

              $producto->precio_oferta=$producto->precio_base*(1-($precio[$producto->id]['precio']/100));
              
              break;

            case 3:

              $producto->precio_oferta=$precio[$producto->id]['precio'];
              
              break;
            
            default:
            
             $producto->precio_oferta=$producto->precio_base*$descuento;
              # code...
              break;
          }

        }else{

          $producto->precio_oferta=$producto->precio_base*$descuento;

        }


       }else{

       $producto->precio_oferta=$producto->precio_base*$descuento;


       }


        $producto->impuesto=$producto->precio_oferta*$producto->valor_impuesto;


       $cart[$producto->slug]=$producto;
       
      }

       return $cart;



    }else{

      return $cart;


    }

      
    }

    public function botones(Request $request)
    {

        $input = $request->all();

        $producto=AlpProductos::where('id', $request->id)->first();


       $cart= \Session::get('cart');

       //print_r($cart);

        if (isset($producto->id)) {

          //return redirect('order/detail');

          $view= View::make('frontend.order.botones', compact('producto', 'cart'));

          $data=$view->render();

          $res = array('data' => $data);

          return $data;

        } else{

          echo "No se encontro del producto";
        }   

    }


    public function updatecartbotones(Request $request)
    {
       $cart= \Session::get('cart');



       $carrito= \Session::get('cr');

       $inv=$this->inventario();

       $producto=AlpProductos::where('id', $request->id)->first();

       //dd(print_r($producto));

       $error='0';

       if ($request->cantidad>0) {
         

           if($inv[$request->id]>=$request->cantidad){

            $cart[$request->slug]->cantidad=$request->cantidad;

          }else{

            $error="No hay existencia suficiente de este producto";
          }

       }else{

        unset( $cart[$producto->slug]);


       }

       //dd($producto->slug.' - '.$producto->id.' - '.$request->id);



       $detalle=AlpCarritoDetalle::where('id_carrito', $carrito)->where('id_producto', $producto->id)->first();

       if (isset($detalle->id)) {

        $data = array(
        'cantidad' => $request->cantidad, 
        );

        $detalle->update($data);
         
       }

       // $cart=$this->reloadCart();


       \Session::put('cart', $cart);

      


        $view= View::make('frontend.order.botones', compact('producto', 'cart'));

        $data=$view->render();

        return $data;

      
    }


      public function getcartbotones(Request $request)
    {
       $cart= \Session::get('cart');

       

       $producto=AlpProductos::where('slug', $request->slug)->first();

       if (isset($cart[$producto->slug])) {
         
          unset( $cart[$producto->slug]);
       }
  
        $view= View::make('frontend.order.botones', compact('producto', 'cart'));

        $data=$view->render();

        return $data;
      
    }





     public function storedir(Request $request)
    {

        $user_id = Sentinel::getUser()->id;

        $input = $request->all();

        //var_dump($input);

        $input['id_user']=$user_id;
        $input['id_client']=$user_id;
        $input['default_address']=1;
               
         
        $direccion=AlpDirecciones::create($input);

        $direcciones = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_countries.country_name as country_name')
          ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
          ->where('alp_direcciones.id_client', $user_id)->get();


        if ($direccion->id) {

          //return redirect('order/detail');

          $view= View::make('frontend.order.direcciones', compact('direcciones'));

          $data=$view->render();

          $res = array('data' => $data);

        //  return json_encode($res);
          return $data;

        } else {

            return Redirect::route('order/detail')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
        }       

    }

    public function setdir( $id)
    {

      $user_id = Sentinel::getUser()->id;

      $direcciones=AlpDirecciones::where('id_client', $user_id)->get();

      $data = array('default_address' => '0' );

        foreach ($direcciones as $dir) {
          
          $dir_upd = AlpDirecciones::find($dir->id);

          $dir_upd->update($data);

        }

      $data = array('default_address' => '1' );


          $direccion= AlpDirecciones::find($id);

          $direccion->update($data);

        if ($direccion->id) {

          return redirect('order/detail');
            

        } else {

            return Redirect::route('order/detail')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
        }       

    }

    public function deldir( $id)
    {

      $user_id = Sentinel::getUser()->id;

          $direccion= AlpDirecciones::find($id);

          $direccion->delete();

          return redirect('order/detail');
    }

    public function verificarDireccion( Request $request)
    {

      $user_id = Sentinel::getUser()->id;

      $direccion=AlpDirecciones::where('id', $request->id_direccion)->first();


      $ciudad=AlpFormaCiudad::where('id_forma', $request->id_forma_envio)->where('id_ciudad', $direccion->city_id)->first();


      if (isset($ciudad->id)) {

        $data = array(
          'id_forma_envio' =>$request->id_forma_envio, 
          'id_direccion' =>$request->id_direccion, 
          'id_forma_pago' =>$request->id_forma_pago
        );


       \Session::put('orden', $data);



        return 'true';

      }else{

        return 'false';
      }

        
    }



 public function addcupon(Request $request)
    {
      
      $carrito= \Session::get('cr');

      //$cart=$this->reloadCart();

      $total=$this->total();

      if (Sentinel::check()) {

        $user_id = Sentinel::getUser()->id;

        $usuario=User::where('id', $user_id)->first();

        /*se busca el cupon */

        $cupon=AlpCupones::where('codigo_cupon', $request->codigo_cupon)->first();


        if (isset($cupon->id)) {

          $valor=0;

          if ($cupon->tipo_reduccion==1) {
            
            $valor=$cupon->valor_cupon;

          }else{

            $valor=($cupon->valor_cupon/100)*$total;
          }


          $data_pago = array(
            'id_orden' => $carrito, 
            'id_forma_pago' => '4', 
            'id_estatus_pago' => '2', 
            'monto_pago' => $valor, 
            'json' => json_encode($cupon), 
            'id_user' => $user_id 
          );


          $pago=AlpPagos::create($data_pago);

        }

        $pagos=AlpPagos::where('id_orden', $carrito)->get();

        $total_pagos=0;

          foreach ($pagos as $pago) {

            $total_pagos=$total_pagos+$pago->monto_pago;

          }


        $configuracion=AlpConfiguracion::where('id', '1')->first();


        $role=RoleUser::select('role_id')->where('user_id', $user_id)->first();


       $direcciones = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
          ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
          ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
          ->where('alp_direcciones.id_client', $user_id)->first();

           $formasenvio = AlpFormasenvio::select('alp_formas_envios.*')
          ->join('alp_rol_envio', 'alp_formas_envios.id', '=', 'alp_rol_envio.id_forma_envio')
          ->where('alp_rol_envio.id_rol', $role->role_id)->get();


          $formaspago = AlpFormaspago::select('alp_formas_pagos.*')
          ->join('alp_rol_pago', 'alp_formas_pagos.id', '=', 'alp_rol_pago.id_forma_pago')
          ->where('alp_rol_pago.id_rol', $role->role_id)->get();

          $countries = Country::all();

          $inv = $this->inventario();


         if(count($cart)<=0){

            return redirect('productos');

         }else{

          $items = array();

          $list=array();

         
              $items["id"]=$carrito;
              $items["title"]='Orden Alpina Nro. '.$carrito;
              $items["description"]='Orden Alpina Nro. '.$carrito;
              $items["picture_url"]= '#';
              $items["quantity"]=1;
              $items["currency_id"]='COP';
              $items["unit_price"]=intval($total-$total_pagos);

              $list[]=$items;


            $preference_data = [
              "items" => $list,
              "payer" => [
                "name" => $usuario->first_name,
                "surname" => $usuario->last_name,
                "email" => $usuario->email,
              ],
              "auto_return" => 'approved',
              "back_urls" => [
                "success" => secure_url('/order/success'),
                "failure" => secure_url('/order/failure'),
                "pending" => secure_url('/order/pending')
              ],
              "notification_url" =>secure_url('/order/mercadopago'),
              "external_reference" =>time()
            ];

           $mp = new MP();

            MP::setCredenciales($configuracion->id_mercadopago, $configuracion->key_mercadopago);

          $preference = MP::post("/checkout/preferences",$preference_data);

          $this->saveOrden($preference);



          $pse_data = [
            "transaction_amount" => $total,           
            "description" => 'Pago de orden Nro.'.$carrito,
            "payer" => [
              "email"=>$usuario->email,
              "identification" => array(
                "type" => "CC",
                "number" => "123123"
              ),
              "entity_type" => "individual"
            ],
            "transaction_details" => ["financial_institution"=>1007],
            "additional_info" => ["ip_address"=>"127.0.0.1"],
            "callback_url" => 'https://alpinago.com/public/orden/pse',
            "payment_method_id" => "pse",
            

          ];


          $pse = MP::post("/v1/payments",$pse_data);



          $payment_methods = MP::get("/v1/payment_methods");
            //$preference=null;

            ///print_r($preference);

          ///$preference = array('response' => array('sandbox_init_point' => '#', ), );

          /*actualizamos la data del carrito */

          $carro=AlpCarrito::where('id', $carrito)->first();

         // dd($carrito);

          $data_carrito = array(
            'id_user' => $user_id );

          $carro->update($data_carrito);

         // echo $carrito;

          /*actualizamos la data del carrito */

            $states=State::where('config_states.country_id', '47')->get();

            return view('frontend.order.cupon', compact('cart', 'total', 'direcciones', 'formasenvio', 'formaspago', 'countries', 'configuracion', 'states', 'preference', 'inv', 'pagos', 'total_pagos', 'pse', 'payment_methods'));

         }


      }else{

        $url='order.detail';

          //return redirect('login');
          return view('frontend.order.login', compact('url'));


        }

    }



    
}
