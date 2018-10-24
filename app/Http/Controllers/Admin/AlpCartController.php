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
use App\Models\AlpPrecioGrupo;
use App\Models\AlpEnvios;
use App\Models\AlpEnviosHistory;
use App\Country;
use App\State;
use App\City;
use App\Roles;
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

      $cart=$this->reloadCart();

      $configuracion=AlpConfiguracion::where('id', '1')->first();


      // $cart= \Session::get('cart');

      $total=$this->total();

      return view('frontend.cart', compact('cart', 'total', 'configuracion'));
    }

    public function mercadopago()
    {

      $configuracion = AlpConfiguracion::where('id', '1')->first();


      //echo $configuracion->id_mercadopago;
      //echo "------------------------";
      //echo $configuracion->key_mercadopago;

      //$mp=new MP($configuracion->id_mercadopago, $configuracion->key_mercadopago);

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
    return dd($preference);


    }


    public function orderDetail()
    {
       //$cart= \Session::get('cart');

      $cart=$this->reloadCart();

      $total=$this->total();

      if (Sentinel::check()) {

        $user_id = Sentinel::getUser()->id;

        $configuracion=AlpConfiguracion::where('id', '1')->first();

        $role=RoleUser::select('role_id')->where('user_id', $user_id)->first();

        $direcciones = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_countries.country_name as country_name')
          ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
          ->where('alp_direcciones.id_client', $user_id)->get();

           $formasenvio = AlpFormasenvio::select('alp_formas_envios.*')
          ->join('alp_rol_envio', 'alp_formas_envios.id', '=', 'alp_rol_envio.id_forma_envio')
          ->where('alp_rol_envio.id_rol', $role->role_id)->get();


          $formaspago = AlpFormaspago::select('alp_formas_pagos.*')
          ->join('alp_rol_pago', 'alp_formas_pagos.id', '=', 'alp_rol_pago.id_forma_pago')
          ->where('alp_rol_pago.id_rol', $role->role_id)->get();

          $countries = Country::all();

         if(count($cart)<=0){

            return redirect('productos');

         }else{

          $items = array();

          $list=array();

            foreach ($cart as $row) {

              $items["id"]=$row->id;
              $items["title"]=$row->nombre_producto;
              $items["description"]=$row->descripcion_corta;
              $items["picture_url"]= url('/').'/uploads/productos/'.$row->imagen_producto;
              $items["quantity"]=intval($row->cantidad);
              $items["currency_id"]='COP';
              $items["unit_price"]=doubleval($row->precio_base);

              $list[]=$items;
              
            }

            $preference_data = [
              "items" => $list,
              "payer" => [
                "email" => 'correo@gmail.com'
              ],
              "back_urls" => [
                "success" => url('/order/success'),
                "failure" => url('/order/failure')
              ],
              "notification_url" =>url('/order/mercadopago'),
              "external_reference" =>'123456'
            ];

            return view('frontend.order.detail', compact('cart', 'total', 'direcciones', 'formasenvio', 'formaspago', 'countries', 'configuracion'));

         }


      }else{

        $url='order.detail';

          //return redirect('login');
          return view('frontend.order.login', compact('url'));


        }

    }

     public function failure(Request $request)
    {
       
       echo $request->collection_id;
       echo $request->collection_status;
       echo $request->preference_id;
       echo $request->external_reference;
       echo $request->payment_type;
       echo $request->merchant_order_id;

       if ($request->collection_status=='null') {
         

           $cart= \Session::get('cart');

      $total=$this->total();

      if (Sentinel::check()) {

        $user_id = Sentinel::getUser()->id;

        $role=RoleUser::select('role_id')->where('user_id', $user_id)->first();

        $direcciones = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_countries.country_name as country_name')
          ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
          ->where('alp_direcciones.id_client', $user_id)->get();

           $formasenvio = AlpFormasenvio::select('alp_formas_envios.*')
          ->join('alp_rol_envio', 'alp_formas_envios.id', '=', 'alp_rol_envio.id_forma_envio')
          ->where('alp_rol_envio.id_rol', $role->role_id)->get();


          $formaspago = AlpFormaspago::select('alp_formas_pagos.*')
          ->join('alp_rol_pago', 'alp_formas_pagos.id', '=', 'alp_rol_pago.id_forma_pago')
          ->where('alp_rol_pago.id_rol', $role->role_id)->get();

          $countries = Country::all();

         if(count($cart)<=0){

            return redirect('productos');

         }else{

          $items = array();

          $list=array();

            foreach ($cart as $row) {

              $items["id"]=$row->id;
              $items["title"]=$row->nombre_producto;
              $items["description"]=$row->descripcion_corta;
              $items["picture_url"]= url('/').'/uploads/productos/'.$row->imagen_producto;
              $items["quantity"]=intval($row->cantidad);
              $items["currency_id"]='COP';
              $items["unit_price"]=intval($row->precio_base);

              $list[]=$items;
              
            }

            $preference_data = [
              "items" => $list,
              "payer" => [
                "email" => 'correo@gmail.com'
              ],
              "back_urls" => [
                "success" => url('/order/success'),
                "failure" => url('/order/failure')
              ],
              "notification_url" =>url('/order/mercadopago'),
              "external_reference" =>'123456'
            ];

            //print_r($preference_data);
            
            //$preference = MP::post("/checkout/preferences",$preference_data);

            //$preference=null;

            //print_r($preference);

            return view('frontend.order.failure', compact('cart', 'total', 'direcciones', 'formasenvio', 'formaspago', 'countries'));


         }

      }


       }

    }



      public function success(Request $request)
    {
       
       dd($request);


    }



    public function orderProcesar(Request $request)
    {
       $cart= \Session::get('cart');

      $total=$this->total();

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
            'id_user' =>$user_id
          );

         $orden=AlpOrdenes::create($data_orden);


         foreach ($cart as $detalle) {

          $data_detalle = array(
            'id_orden' => $orden->id, 
            'id_producto' => $detalle->id, 
            'cantidad' =>$detalle->cantidad, 
            'precio_unitario' =>$detalle->precio_base, 
            'precio_total' =>$detalle->cantidad*$detalle->precio_base,
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

         $data_update = array('referencia' => 'ALP'.$orden->id );

         $orden->update($data_update);

       //  $datalles=AlpDetalles::where('id_orden', $orden->id)->get();

        $compra =  DB::table('alp_ordenes')->select('alp_ordenes.*','users.first_name as first_name','users.last_name as last_name' ,'users.email as email','alp_formas_envios.nombre_forma_envios as nombre_forma_envios','alp_formas_envios.descripcion_forma_envios as descripcion_forma_envios','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago','alp_formas_pagos.descripcion_forma_pago as descripcion_forma_pago')
            ->join('users','alp_ordenes.id_cliente' , '=', 'users.id')
            ->join('alp_formas_envios','alp_ordenes.id_forma_envio' , '=', 'alp_formas_envios.id')
            ->join('alp_formas_pagos','alp_ordenes.id_forma_pago' , '=', 'alp_formas_pagos.id')
            ->where('alp_ordenes.id', $orden->id)->first();

        $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.imagen_producto as imagen_producto')
          ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
          ->where('alp_ordenes_detalle.id_orden', $orden->id)->get();


         $cart= \Session::forget('cart');


          return view('frontend.order.procesar', compact('compra', 'detalles', 'fecha_entrega'));

         

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
       $cart= \Session::get('cart');

       $descuento='1'; 

       $precio = array();

        

       $producto->cantidad=1;

       if ($cart[$producto->slug]!==undefined) {

          $cart[$producto->slug]['cantidad']=$cart[$producto->slug]['cantidad']+1;
        

       }else{

       $cart[$producto->slug]=$producto;


       }

      $cart=$this->reloadCart();

      // return $cart;

      $producto=$cart[$producto->slug];

       \Session::put('cart', $cart);

       $cantidad=$this->cantidad();

       $total=$this->total();

       $view= View::make('frontend.order.cartdetail', compact('producto', 'cantidad', 'total'));

        $data=$view->render();

        $res = array('data' => $data);

        //  return json_encode($res);
        return $data;
      
    }

    public function delete( AlpProductos $producto)
    {
       $cart= \Session::get('cart');

      unset( $cart[$producto->slug]);
       

       \Session::put('cart', $cart);

       return redirect('cart/show');

      
    }

    public function update( AlpProductos $producto, $cantidad)
    {
       $cart= \Session::get('cart');

       $cart[$producto->slug]->cantidad=$cantidad;
       
      // return $cart;

       \Session::put('cart', $cart);

       return redirect('cart/show');

      
    }

    public function updatecart(Request $request)
    {
       $cart= \Session::get('cart');

       $cart[$request->slug]->cantidad=$request->cantidad;

       $configuracion=AlpConfiguracion::where('id', '1')->first();


      // return $cart;

       \Session::put('cart', $cart);



       $total=$this->total();


        $view= View::make('frontend.listcart', compact('cart', 'total', 'configuracion'));

        $data=$view->render();

        return $data;

      
    }

    public function vaciar( )
    {
       $cart= \Session::forget('cart');

      
       return redirect('cart/show');

      
    }

    private function total()
    {
       $cart= \Session::get('cart');

      $total=0;

      foreach($cart as $row) {

        $total=$total+($row->cantidad*$row->precio_base);

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


    private function reloadCart()
    {
       $cart= \Session::get('cart');

       $s_user= \Session::get('user');

      $total=0;

      $cambio=0;

      $inventario = AlpInventario::select('alp_inventarios.*', DB::raw( "SUM(cantidad) as disponible"))
            ->orderBy("id_producto")
            ->groupBy("id_producto")
            ->groupBy("operacion")
            ->where("operacion",'1')
            ->get();

      $inv_producto = array();

      foreach ($inventario as $row_inv) {

        $inv_producto[$row_inv->id_producto]=$row_inv->disponible;

      }

     

      $ventas = AlpInventario::select('alp_inventarios.*',DB::raw( "SUM(cantidad) as vendidas"))
            ->orderBy("id_producto")
            ->groupBy("id_producto")
            ->groupBy("operacion")
            ->where("operacion",'2')
            ->get();

      

      foreach ($ventas as $row_ventas) {
       
        $inv_producto[$row_ventas->id_producto]=$inv_producto[$row_ventas->id_producto]-$row_ventas->vendidas;

      }

      

      $descuento='1'; 

      $precio = array();

        if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

            if ($user_id!=$s_user) {

              $cambio=1;

              \Session::put('user', $user_id);

              $role=RoleUser::where('user_id', $user_id)->first();

            $cliente = AlpClientes::where('id_user_client', $user_id )->first();

            if (isset($cliente->id_empresa) ) {

                if ($cliente->id_empresa!=0) {
                    
                     $empresa=AlpEmpresas::find($cliente->id_empresa);

                    $cliente['nombre_empresa']=$empresa->nombre_empresa;

                    $descuento=(1-($empresa->descuento_empresa/100));
                }
               
            }

             if ($role->role_id) {
                    
                    
                foreach ($cart as $producto ) {

                  $pregiogrupo=AlpPrecioGrupo::where('id_producto', $producto->id)->where('id_role', $role->role_id)->first();

                  if (isset($pregiogrupo->id)) {
                     
                      $precio[$producto->id]['precio']=$pregiogrupo->precio;
                      $precio[$producto->id]['operacion']=$pregiogrupo->operacion;

                  }

                }
                
            }

            }

            

        } //end sentinel check


        $cart2 = array();

    //se verifica si hay modificacion en el perfil del usuario
      
    if ($cambio==1) {

      foreach ($cart as $producto) {

      if ($descuento=='1') {

        if (isset($precio[$producto->id])) {
          # code...
         
          switch ($precio[$producto->id]['operacion']) {
            case 1:

              $producto->precio_base=$producto->precio_base*$descuento;

              break;

            case 2:

              $producto->precio_base=$producto->precio_base*(1-($precio[$producto->id]['precio']/100));
              
              break;

            case 3:

              $producto->precio_base=$precio[$producto->id]['precio'];
              
              break;
            
            default:
            
             $producto->precio_base=$producto->precio_base*$descuento;
              # code...
              break;
          }

        }else{

          $producto->precio_base=$producto->precio_base*$descuento;

        }


       }else{

       $producto->precio_base=$producto->precio_base*$descuento;

       }


       $cart2[$producto->slug]=$producto;

       
      }


       return $cart2;


    }else{


      return $cart;

    }

      
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

     # print_r($direccion);

      $ciudad=AlpFormaCiudad::where('id_forma', $request->id_forma_envio)->where('id_ciudad', $direccion->city_id)->first();

      #echo '<br>'."ciudad: ".'<br>';

     # print_r($ciudad);


      if (isset($ciudad->id)) {

        return 'true';

      }else{

        return 'false';
      }

        
    }
    
}
