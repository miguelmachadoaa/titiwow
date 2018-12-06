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

    // dd($configuracion);

     //MP::setCredenciales($configuracion->cliente_id_mercadopago, $configuracion->cliente_secret_mercadopago, $configuracion->access_token_mercadopago);

     MP::setCredenciales($configuracion->id_mercadopago, $configuracion->key_mercadopago);

    // MP::setCredenciales('1212121212', '123456');

    //dd(MP::getAccessToken());

     $preference_data = [
      "items" => [
        [
          "id" => '1234',
          "title" => 'Titulo del articulo',
          "description" => 'Descripcion del articulo',
          "picture_url" => 'uno.jpg',
          "quantity" => 1,
          "currency_id" => 'USD',
          "unit_price" => 10
        ]
      ],
      "payer" => [
        "email" => 'correo@gmail.com'
      ]
    ];
    $preference = MP::post("/checkout/preferences",$preference_data);

    dd($preference);
    //return dd($preference);

    }


   

    public function orderDetail()
    {

      $configuracion=AlpConfiguracion::where('id', '1')->first();


      
      $carrito= \Session::get('cr');

      $cart=$this->reloadCart();

      $total=$this->total();

     


      if ($total<$configuracion->minimo_compra) {
        
        return redirect('cart/show');

      }

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

           /* foreach ($cart as $row) {

              $items["id"]=$row->id;
              $items["title"]=$row->nombre_producto;
              $items["description"]=$row->descripcion_corta;
              $items["picture_url"]= url('/').'/uploads/productos/'.$row->imagen_producto;
              $items["quantity"]=intval($row->cantidad);
              $items["currency_id"]='COP';
              $items["unit_price"]=intval($row->precio_oferta);

              $list[]=$items;
              
            }*/


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

          /*$preference = array(            'response' => array(              'sandbox_init_point' => '#',
              'init_point' => '#',
              'id' => time(),
               ),
              );*/


          //dd($preference);

          $this->saveOrden($preference);

          //dd($preference);


          /*actualizamos la data del carrito */

          $carro=AlpCarrito::where('id', $carrito)->first();

         // dd($carrito);

          $data_carrito = array(
            'id_user' => $user_id );

          $carro->update($data_carrito);

          

         // echo $carrito;

          /*actualizamos la data del carrito */

          $states=State::where('config_states.country_id', '47')->get();

          return view('frontend.order.detail', compact('cart', 'total', 'direcciones', 'formasenvio', 'formaspago', 'countries', 'configuracion', 'states', 'preference', 'inv', 'pagos', 'total_pagos'));

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

      if ($request->collection_status=='null') {

      $cart= \Session::get('cart');

      $total=$this->total();

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
              "back_urls" => [
                  "success" => secure_url('/order/success'),
                "failure" => secure_url('/order/failure'),
                "pending" => secure_url('/order/pending')
              ],
              "notification_url" =>secure_url('/order/mercadopago'),
              "external_reference" =>time()
            ];

            //print_r($preference_data);

            MP::setCredenciales($configuracion->id_mercadopago, $configuracion->key_mercadopago);
            
            $preference = MP::post("/checkout/preferences",$preference_data);

             $this->saveOrden($preference);

            //$preference=null;

            ///print_r($preference);

            $states=State::where('config_states.country_id', '47')->get();

            return view('frontend.order.failure', compact('cart', 'total', 'direcciones', 'formasenvio', 'formaspago', 'countries','preference', 'states', 'configuracion', 'inv', 'pagos', 'total_pagos'));


         }

      }

    }

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
            'id_user' =>$user_id
        );

        $orden=AlpOrdenes::create($data_orden);

        $monto_total_base=0;

        foreach ($cart as $detalle) {

          $monto_total_base=$monto_total_base+($detalle->cantidad*$detalle->precio_base);

          $data_detalle = array(
            'id_orden' => $orden->id, 
            'id_producto' => $detalle->id, 
            'cantidad' =>$detalle->cantidad, 
            'precio_unitario' =>$detalle->precio_oferta, 
            'precio_base' =>$detalle->precio_base, 
            'precio_total' =>$detalle->cantidad*$detalle->precio_oferta,
            'precio_total_base' =>$detalle->cantidad*$detalle->precio_base,
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
          'monto_total_base' => $monto_total_base
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

         $aviso_pago="Hemos recibido su pago satifactoriamente según referencia: ".$request->preference_id.", Le llegará un email con la descripción de su pago. Muchas gracias por su Compra!";

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
            'id_user' =>$user_id
        );

        $orden=AlpOrdenes::create($data_orden);

        $monto_total_base=0;

        foreach ($cart as $detalle) {

          $monto_total_base=$monto_total_base+($detalle->cantidad*$detalle->precio_base);

          $data_detalle = array(
            'id_orden' => $orden->id, 
            'id_producto' => $detalle->id, 
            'cantidad' =>$detalle->cantidad, 
            'precio_unitario' =>$detalle->precio_oferta, 
            'precio_base' =>$detalle->precio_base, 
            'precio_total' =>$detalle->cantidad*$detalle->precio_oferta,
            'precio_total_base' =>$detalle->cantidad*$detalle->precio_base,
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
          'monto_total_base' => $monto_total_base
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

         $aviso_pago="Su pago esta siendo procesado segun referencia: ".$request->preference_id.", debera finalizar el proceso en 24 horas o su pedido sera cancelado.!";

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

          $texto='Se ha creado la siguiente orden '.$compra->id.' y esta a proceso de espera para aprobacion de pago, debera completar el pago en 24 horas o la orden sera cancelada';

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

      $cart=$this->reloadCart();

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

       $carrito= \Session::get('cr');

      $total=$this->total();

      $aviso_pago='0';

        $configuracion = AlpConfiguracion::where('id','1')->first();


      #$input=$request->all();

     ## dd($cart);

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
            'id_user' =>$user_id
          );

         $orden=AlpOrdenes::create($data_orden);

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


         /*---------------- */


       //  $datalles=AlpDetalles::where('id_orden', $orden->id)->get();

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

    public function addtocart( AlpProductos $producto)
    {

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

       if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

            $role=RoleUser::where('user_id', $user_id)->first();

            $cliente = AlpClientes::where('id_user_client', $user_id )->first();


            if (isset($cliente) ) {

                if ($cliente->id_empresa!=0) {
                    
                     /*$empresa=AlpEmpresas::find($cliente->id_empresa);

                    $cliente['nombre_empresa']=$empresa->nombre_empresa;

                    $descuento=(1-($empresa->descuento_empresa/100));*/

                    $role->role_id='E'.$cliente->id_empresa.'';
                }
               
            }



            if ($role->role_id) {
               
                foreach ($cart as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;

                    }

                }
                
            }

        }
          





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

      $producto->cantidad=1;

      
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

       /*guardar detalles en el carro */

       $cantidad=$this->cantidad();

       $total=$this->total();

       $view= View::make('frontend.order.cartdetail', compact('producto', 'cantidad', 'total', 'error'));

        $data=$view->render();

        $res = array('data' => $data);

        //  return json_encode($res);
        return $data;
      
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

      unset( $cart[$request->slug]);

       \Session::put('cart', $cart);

       return 'true';
      
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


      // return $cart;

       $cart=$this->reloadCart();
       

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
                      
                     /* $empresa=AlpEmpresas::find($cliente->id_empresa);

                      $cliente['nombre_empresa']=$empresa->nombre_empresa;

                      $descuento=(1-($empresa->descuento_empresa/100));*/

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


      //  $cart2 = array();

    //se verifica si hay modificacion en el perfil del usuario
      
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

       //dd($producto);


        //print_r($producto->id);

       $cart= \Session::get('cart');

       //print_r($cart);

        if (isset($producto->id)) {

          //return redirect('order/detail');

          $view= View::make('frontend.order.botones', compact('producto', 'cart'));

          $data=$view->render();

          $res = array('data' => $data);

          return $data;

        } else{

          echo "No se encontro del prodcuto";
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

        $cart=$this->reloadCart();


       \Session::put('cart', $cart);

      


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

      $cart=$this->reloadCart();

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

           /* foreach ($cart as $row) {

              $items["id"]=$row->id;
              $items["title"]=$row->nombre_producto;
              $items["description"]=$row->descripcion_corta;
              $items["picture_url"]= url('/').'/uploads/productos/'.$row->imagen_producto;
              $items["quantity"]=intval($row->cantidad);
              $items["currency_id"]='COP';
              $items["unit_price"]=intval($row->precio_oferta);

              $list[]=$items;
              
            }*/


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
              "back_urls" => [
                  "success" => secure_url('/order/success'),
                "failure" => secure_url('/order/failure'),
                "pending" => secure_url('/order/pending')
              ],
              "notification_url" =>secure_url('/order/mercadopago'),
              "external_reference" =>time()
            ];

          


          MP::setCredenciales($configuracion->id_mercadopago, $configuracion->key_mercadopago);

         $preference = MP::post("/checkout/preferences",$preference_data);

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

            return view('frontend.order.cupon', compact('cart', 'total', 'direcciones', 'formasenvio', 'formaspago', 'countries', 'configuracion', 'states', 'preference', 'inv', 'pagos', 'total_pagos'));

         }


      }else{

        $url='order.detail';

          //return redirect('login');
          return view('frontend.order.login', compact('url'));


        }

    }



    
}
