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
use App\Models\AlpFormaspago;
use App\Models\AlpOrdenes;
use App\Models\AlpDetalles;
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
       
    }

     /**
     * Display the specified resource.
     *
     * @param  Blog $blog
     * @return view
     */
    public function show()
    {
       $cart= \Session::get('cart');

       $total=$this->total();

      return view('frontend.cart', compact('cart', 'total'));
    }

    public function mercadopago()
    {

      $MP=new MP();

      $preference_data = [
      "items" => [
        [
          "id" => '1234',
          "title" => 'Titulo del articulo',
          "description" => 'Descripcion del articulo',
          "picture_url" => 'Imagen del articulo',
          "quantity" => 1,
          "currency_id" => 'COP',
          "unit_price" => 10.00
           ]
      ],
      "payer" => [
        "email" => 'miguelmachadoaa@gmail.com'
      ]
    ];
    $preference = MP::post("/checkout/preferences",$preference_data);
    return dd($preference);


    }


     

    public function orderDetail()
    {
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

            return view('frontend.order.detail', compact('cart', 'total', 'direcciones', 'formasenvio', 'formaspago', 'countries'));


         }

      }else{

        $url='order.detail';

          //return redirect('login');
          return view('frontend.order.login', compact('url'));


        }

    }



    public function orderProcesar(Request $request)
    {
       $cart= \Session::get('cart');

      $total=$this->total();

      if (Sentinel::check()) {

        $user_id = Sentinel::getUser()->id;

        $role=RoleUser::select('role_id')->where('user_id', $user_id)->first();


         $data_orden = array(
            'referencia ' => time(), 
            'id_cliente' => $user_id, 
            'id_forma_envio' =>$request->id_forma_envio, 
            'id_forma_pago' =>$request->id_forma_pago, 
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

          AlpDetalles::create($data_detalle);

         }

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


         //$cart= \Session::forget('cart');



      return view('frontend.order.procesar', compact('compra', 'detalles'));


         

      }else{

          return redirect('login');


    }



    }

    public function add( AlpProductos $producto)
    {
       $cart= \Session::get('cart');

       $producto->cantidad=1;


       $cart[$producto->slug]=$producto;

      // return $cart;

       \Session::put('cart', $cart);

       return redirect('cart/show');

      
    }

    public function addtocart( AlpProductos $producto)
    {
       $cart= \Session::get('cart');

       $producto->cantidad=1;


       $cart[$producto->slug]=$producto;

      // return $cart;

       \Session::put('cart', $cart);

       $cantidad=$this->cantidad();

       $data = array(
        'resultado' => 1, 
        'contenido' => $cantidad.' Items'
      );

       return $data;
      
    }

    public function delete( AlpProductos $producto)
    {
       $cart= \Session::get('cart');

      unset( $cart[$producto->slug]);
       

      // return $cart;

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


     public function storedir(Request $request)
    {

        $user_id = Sentinel::getUser()->id;

        $input = $request->all();

        //var_dump($input);

        $input['id_user']=$user_id;
        $input['id_client']=$user_id;
               
         
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
    
}
