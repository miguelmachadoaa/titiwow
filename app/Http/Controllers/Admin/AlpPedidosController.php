<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Models\AlpTDocumento;
use App\Models\AlpEstructuraAddress;
use App\Models\AlpAlmacenes;
use App\Models\AlpAlmacenProducto;
use App\Models\AlpAlmacenRol;
use App\Models\AlpDirecciones;
use App\Models\AlpAlmacenDespacho;
use App\Models\AlpAlmacenFormaEnvio;
use App\Models\AlpAlmacenFormaPago;
use App\Models\AlpFormaspago;
use App\Models\AlpFormasenvio;
use App\Models\AlpProductos;
use App\Models\AlpClientes;
use App\Models\AlpAmigos;
use App\Models\AlpPrecioGrupo;
use App\Models\AlpInventario;
use App\Models\AlpCategorias;
use App\Models\AlpMarcas;
use App\Models\AlpOrdenes;
use App\Models\AlpOrdenesHistory;
use App\Models\AlpDescuentos;
use App\Models\AlpDetalles;
use App\Models\AlpConfiguracion;
use App\Models\AlpEmpresas;
use App\Models\AlpClientesHistory;
use App\Models\AlpCombosProductos;
use App\Models\AlpOrdenesDescuento;
use App\Models\AlpPagos;
use App\Models\AlpFormaCiudad;
use App\Models\AlpImpuestos;
use App\User;
use App\State;
use App\City;
use App\Barrio;
use App\Country;
use App\RoleUser;

use App\Models\AlpAlmacenesUser;
use App\Http\Requests\AlmacenesRequest;
use App\Http\Requests\UploadRequest;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserModalRequest;
use App\Http\Requests\DireccionModalRequest;
use App\Http\Requests;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

use App\Imports\InvitacionesImport;
use App\Imports\AlmacenImport;
use Maatwebsite\Excel\Facades\Excel;

use Activation;
use Redirect;
use Sentinel;
use View;
use DB;

use MP;

use Carbon\Carbon;

class AlpPedidosController extends JoshController
{

   
    /**
     * Show a list of all the groups.
     *
     * @return View
     */
    public function index()
    {
        // Grab all the groups

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('almacenes/index ');

        }else{

          activity()
          ->log('almacenes/index');


        }


        if (!Sentinel::getUser()->hasAnyAccess(['almacenes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intento acceder');
        }

       $cart= \Session::get('cart');

          if (isset($cart['id_almacen'])) {
            # code...
          }else{

            $cart['id_almacen']='1';
          }



        $almacenes = AlpAlmacenes::all();

        $productos = AlpProductos::select('alp_productos.*', 'alp_categorias.nombre_categoria as nombre_categoria')
          ->join('alp_categorias', 'alp_productos.id_categoria_default', '=', 'alp_categorias.id')
          ->where('alp_productos.destacado', '1')
          ->orderBy('alp_productos.nombre_producto')
          ->limit(12)
          ->get();


          $productos=$this->addOferta($productos);

          $categorias=AlpCategorias::orderBy('nombre_categoria')->get();

          $marcas=AlpMarcas::orderBy('nombre_marca')->get();

          $total_venta=$this->totalcart($cart);

          $cart=$this->reloadCart();


          if (isset($cart['inventario'])) {
            # code...
          }else{

            $cart['inventario']=$this->inventario();
          }

          \Session::put('cart', $cart);


        // Show the page
        return view('admin.pedidos.index', compact('almacenes', 'productos', 'categorias', 'marcas', 'cart', 'total_venta'));
    }


    public function checkout()
    {
        // Grab all the groups

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('almacenes/checkout ');

        }else{

          activity()
          ->log('almacenes/checkout');


        }


        if (!Sentinel::getUser()->hasAnyAccess(['almacenes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intento acceder');
        }

        $cart= \Session::get('cart');

        //dd($cart);


        $id_almacen=$cart['id_almacen'];

        $almacen=AlpAlmacenes::where('id', $id_almacen)->first();

       // dd($almacen);

        $almacenes = AlpAlmacenes::all();

         $clientes =  User::select('users.*','roles.name as name_role','alp_clientes.estado_masterfile as estado_masterfile','alp_clientes.estado_registro as estado_registro','alp_clientes.telefono_cliente as telefono_cliente','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.cod_alpinista as cod_alpinista')
        ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
        ->join('role_users', 'users.id', '=', 'role_users.user_id')
        ->join('roles', 'role_users.role_id', '=', 'roles.id')
        ->where('role_users.role_id', '<>', 1)
        ->limit(50)
        ->get();

       
          

          $total_venta=$this->totalcart($cart);

          if ($total_venta<$almacen->minimo_compra) {

            return redirect('admin/tomapedidos/')->withInput()->with('error', trans('El monto de compra minimo para este almacen es de '.$almacen->minimo_compra));

            
          }


          $afe=AlpAlmacenFormaEnvio::where('id_almacen', $id_almacen)->first();

          if (isset($afe->id)) {

            $formasenvio = AlpFormasenvio::select('alp_formas_envios.*')
            ->join('alp_almacen_formas_envio', 'alp_formas_envios.id', '=', 'alp_almacen_formas_envio.id_forma_envio')
            ->where('alp_almacen_formas_envio.id_almacen', $id_almacen)
            ->whereNull('alp_almacen_formas_envio.deleted_at')
            ->groupBy('alp_formas_envios.id')->get();
            # code...
          }else{

             $formasenvio = AlpFormasenvio::select('alp_formas_envios.*')
            ->join('alp_rol_envio', 'alp_formas_envios.id', '=', 'alp_rol_envio.id_forma_envio')
            ->where('alp_rol_envio.id_rol', '9')->get();

          }



          $afe=AlpAlmacenFormaPago::where('id_almacen', $id_almacen)->first();


          if (isset($afe->id)) {

            $formaspago = AlpFormaspago::select('alp_formas_pagos.*')
              ->join('alp_almacen_formas_pago', 'alp_formas_pagos.id', '=', 'alp_almacen_formas_pago.id_forma_pago')
              ->where('alp_almacen_formas_pago.id_almacen', $id_almacen)
              ->whereNull('alp_almacen_formas_pago.deleted_at')
              ->groupBy('alp_formas_pagos.id')->get();
            # code...
          }else{

              $formaspago = AlpFormaspago::select('alp_formas_pagos.*')
              ->join('alp_rol_pago', 'alp_formas_pagos.id', '=', 'alp_rol_pago.id_forma_pago')
              ->where('alp_rol_pago.id_rol', '9')->get();

          }

          if (isset($cart['id_forma_pago'])) {
            
          }else{

            $i=0;  
            foreach ($formaspago as $fp) {

              if ($i==0) {
                
                $cart['id_forma_pago']=$fp->id;

                $i++;
              }
            }
          }


          if (isset($cart['id_forma_envio'])) {
            # code...
          }else{

            $i=0;  
            foreach ($formasenvio as $fe) {

              if ($i==0) {
                
                $cart['id_forma_envio']=$fe->id;
                $i++;
              }
            }
          }





          $t_documento = AlpTDocumento::where('estado_registro','=',1)->get();

            $estructura = AlpEstructuraAddress::where('estado_registro','=',1)->get();

            $countries = Country::all();
             $listabarrios=Barrio::get();

             $states=State::where('config_states.country_id', '47')->get();

             $cities=City::where('state_id', '47')->get();

             \Session::put('cart', $cart);


              $cart=$this->reloadCart();

              //dd($cart);
            
        // Show the page
        return view('admin.pedidos.checkout', compact('almacenes', 'cart', 'total_venta', 'clientes', 'formaspago', 'formasenvio', 't_documento', 'estructura', 'countries', 'listabarrios', 'states', 'cities'));


    }


    public function procesar(Request $request)
    {
        // Grab all the groups

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('almacenes/checkout ');

        }else{

          activity()
          ->log('almacenes/checkout');


        }


        if (!Sentinel::getUser()->hasAnyAccess(['almacenes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intento acceder');
        }

       // dd($request->all());

         $clientIP = \Request::getClientIp(true);


        $id_almacen=1;

        $cart= \Session::get('cart');

        $total_venta=$this->totalcart($cart);

        $total=$total_venta;


        if (isset($cart['id_cliente'])) {

          $user_id=$cart['id_cliente'];

           $data_orden = array(
              'referencia ' => time(), 
              'id_cliente' => $cart['id_cliente'], 
              'id_forma_envio' =>$cart['id_forma_envio'], 
              'id_address' =>$cart['id_direccion'], 
              'id_forma_pago' =>$cart['id_forma_pago'],  
              'estatus' =>'8', 
              'estatus_pago' =>'4', 
              'monto_total' =>$total_venta,
              'monto_total_base' =>$total_venta,
              'base_impuesto' =>'0',
              'valor_impuesto' =>'0',
              'monto_impuesto' =>'0',
              'ip' =>$clientIP,
              'id_almacen' =>$id_almacen,
              'origen' =>'1',
              'token' =>substr(md5(time()), 0, 16),
              'id_user' =>$cart['id_cliente']
          );


          $orden=AlpOrdenes::create($data_orden);

          $resto=0;

          $total_descuentos=0;

          $base_imponible=0;

          $monto_total_base=0;

          $base_impuesto=0;

          $monto_impuesto=0;

          $valor_impuesto=0;

          foreach ($cart as $detalle) {

            if (isset($detalle->nombre_producto)) {

            $monto_total_base=$monto_total_base+($detalle->cantidad*$detalle->precio_base);

             $total_detalle=$detalle->precio_oferta*$detalle->cantidad;

              if ($detalle->valor_impuesto!=0) {

                $base_imponible_detalle=$total_detalle/(1+$detalle->valor_impuesto);

                $base_impuesto=$base_impuesto+$total_detalle;

                $valor_impuesto=$detalle->valor_impuesto;
              
              }else{

                $base_imponible_detalle=0;

                $base_impuesto=$base_impuesto+$total_detalle;

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
                'valor_impuesto' =>$valor_impuesto,
                'monto_impuesto' =>$base_imponible_detalle*$valor_impuesto,
                'id_user' =>$user_id 
              );

              $data_inventario = array(
                'id_producto' => $detalle->id, 
                'id_almacen' => $id_almacen, 
                'cantidad' =>$detalle->cantidad, 
                'operacion' =>'2', 
                'notas' =>'Orden '.$orden->id, 
                'id_user' =>$user_id 
              );

              AlpDetalles::create($data_detalle);

              AlpInventario::create($data_inventario);

              if ($detalle->tipo_producto=='2') {

                    $lista=AlpCombosProductos::where('id_combo', $detalle->id)->get();

                    foreach ($lista as $l) {

                        $data_detalle_l = array(
                          'id_orden' => $orden->id, 
                          'id_producto' => $l->id_producto, 
                          'cantidad' =>$l->cantidad*$detalle->cantidad, 
                          'precio_unitario' =>0, 
                          'precio_base' =>0, 
                          'precio_total' =>0,
                          'precio_total_base' =>0,
                          'valor_impuesto' =>0,
                          'monto_impuesto' =>0,
                          'id_combo' =>$detalle->id,
                          'id_user' =>$user_id 
                        );

                        $data_inventario_l = array(
                          'id_producto' => $l->id_producto, 
                          'id_almacen' => $id_almacen, 
                          'cantidad' =>$l->cantidad*$detalle->cantidad, 
                          'operacion' =>'2', 
                          'notas' =>'Orden '.$orden->id,
                          'id_user' =>$user_id 
                        );

                        AlpDetalles::create($data_detalle_l);

                        AlpInventario::create($data_inventario_l);
                  }

              }

            }//if es un producto 
          }//endfreach


          $total_descuentos=0;

            $descuentos=AlpOrdenesDescuento::where('id_orden', $orden->id)->get();

            foreach ($descuentos as $pago) {

              $total_descuentos=$total_descuentos+$pago->monto_descuento;

            }



             $resto=$total-$total_descuentos;

            if ($resto<$base_impuesto) {

              $base_impuesto=$resto;
              
            }


          $monto_impuesto=($base_impuesto/(1+$valor_impuesto))*$valor_impuesto;

          $base_imponible=($base_impuesto/(1+$valor_impuesto));




          $data_update = array(
              'referencia' => 'ALP'.$orden->id,
              'monto_total' =>$resto,
              'monto_descuento' =>$total_descuentos,
              'monto_total_base' => $monto_total_base,
              'base_impuesto' => $base_imponible,
              'monto_impuesto' => $monto_impuesto,
              'valor_impuesto' => $valor_impuesto

               );

             $orden->update($data_update);


              $data_history = array(
                'id_orden' => $orden->id, 
               'id_status' => '8', 
                'notas' => 'Orden Creada', 
               'id_user' => 1
              );

            $history=AlpOrdenesHistory::create($data_history);




            $compra =  DB::table('alp_ordenes')->select('alp_ordenes.*','users.first_name as first_name','users.last_name as last_name' ,'users.email as email','alp_formas_envios.nombre_forma_envios as nombre_forma_envios','alp_formas_envios.descripcion_forma_envios as descripcion_forma_envios','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago','alp_formas_pagos.descripcion_forma_pago as descripcion_forma_pago','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.doc_cliente as doc_cliente')
                  ->join('users','alp_ordenes.id_cliente' , '=', 'users.id')
                  ->join('alp_clientes','alp_ordenes.id_cliente' , '=', 'alp_clientes.id_user_client')
                  ->join('alp_formas_envios','alp_ordenes.id_forma_envio' , '=', 'alp_formas_envios.id')
                  ->join('alp_formas_pagos','alp_ordenes.id_forma_pago' , '=', 'alp_formas_pagos.id')
                  ->where('alp_ordenes.id', $orden->id)->first();


              $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug','alp_productos.presentacion_producto as presentacion_producto')
                ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
                ->where('alp_ordenes_detalle.id_orden', $orden->id)->get();



             \Session::forget('cart');

             $user_cliente=User::where('id', $user_id)->first();

             Mail::to($user_cliente->email)->send(new \App\Mail\NotificacionTomapedidos($compra, ''));

            return view('admin.pedidos.procesar', compact('compra', 'detalles'));

          # code...
        }else{


          return redirect('admin/tomapedidos/')->withInput()->with('error', trans('Hubo un error al procesar la compra'));


        }

   



          //dd($cart);

        // Show the page
        return view('admin.pedidos.checkout', compact('almacenes', 'cart', 'total_venta', 'clientes', 'formaspago', 'formasenvio', 't_documento', 'estructura', 'countries', 'listabarrios', 'states', 'cities'));
    }










 private function inventario()
    {

      if (isset($cart['id_almacen'])) {

        $id_almacen=$cart['id_almacen'];

      }else{

        $id_almacen='1';
      }
       
       

      $entradas = AlpInventario::groupBy('id_producto')
        ->select("alp_inventarios.*", DB::raw(  "SUM(alp_inventarios.cantidad) as cantidad_total"))
        ->where('alp_inventarios.operacion', '1')
        ->where('alp_inventarios.id_almacen', '=', $id_almacen)
        ->get();

        $inv = array();

        foreach ($entradas as $row) {
          
          $inv[$row->id_producto]=$row->cantidad_total;

        }


      $salidas = AlpInventario::select("alp_inventarios.*", DB::raw(  "SUM(alp_inventarios.cantidad) as cantidad_total"))
        ->groupBy('id_producto')
        ->where('operacion', '2')
        ->where('alp_inventarios.id_almacen', '=', $id_almacen)
        ->get();

        foreach ($salidas as $row) {

          if (isset($inv[$row->id_producto])) {
            # code...
          }else{

            $inv[$row->id_producto]=0;

          }
          
          $inv[$row->id_producto]= $inv[$row->id_producto]-$row->cantidad_total;

      }

      return $inv;
      
    }



   


     public function show($id)
    {
        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('almacen/edit ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('almacen/edit');

        }


         if (!Sentinel::getUser()->hasAnyAccess(['almacenes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intento acceder');
        }



        $productos = AlpProductos::select('alp_productos.*')
        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->whereNull('alp_almacen_producto.deleted_at')
        ->where('alp_almacen_producto.id_almacen', '=', $id)
        ->groupBy('alp_productos.id')
        ->get();

        $almacen = AlpAlmacenes::where('id', $id)->first();

        $inventario=$this->inventario();

        $almacen_formas_pago=AlpAlmacenFormaPago::select('alp_almacen_formas_pago.*','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago')
        ->join('alp_formas_pagos', 'alp_almacen_formas_pago.id_forma_pago', '=' ,'alp_formas_pagos.id')
        ->where('alp_almacen_formas_pago.id_almacen', $id)->get();

        $almacen_formas_envio=AlpAlmacenFormaEnvio::select('alp_almacen_formas_envio.*', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios')
        ->join('alp_formas_envios', 'alp_almacen_formas_envio.id_forma_envio', '=', 'alp_formas_envios.id')
        ->where('alp_almacen_formas_envio.id_almacen', $id)->get();

        $despachos=AlpAlmacenDespacho::where('id_almacen', $id)->get();

        $listaestados=State::pluck('state_name', 'id');

        $listaestados[0]='Todos';

        $listaciudades=City::pluck('city_name', 'id');

        $listaciudades[0]='Todos';

        $listabarrios=Barrio::pluck('barrio_name', 'id');

         $listabarrios[0]='Todos';

       $formas_envio=AlpFormasenvio::get();

       $formas_pago=AlpFormaspago::get();

       $states=State::where('config_states.country_id', '47')->get();

        return view('admin.pedidos.show', compact('almacen', 'productos',  'inventario', 'formas_pago', 'formas_envio', 'listaestados', 'listaciudades', 'listabarrios','despachos', 'states', 'almacen_formas_pago', 'almacen_formas_envio'));
    }









      public function estatus(Request $request)
    {

       if (!Sentinel::getUser()->hasAnyAccess(['almacenes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intento acceder');
        }

        

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('almacenes/estatus ');

        }else{

          activity()
          ->withProperties($request->all())->log('almacenes/estatus');

        }

        $input = $request->all();

        $almacen=AlpAlmacenes::find($request->id);

        $data = array('estado_registro' => $request->estatus );

        $almacen->update($data);


        $view= View::make('admin.pedidos.estatus', compact('almacen'));

        $data=$view->render();
        
        return $data;
    }






    public function datacategorias($id)
    {

         $productos = AlpProductos::select('alp_productos.*', 'alp_categorias.nombre_categoria as nombre_categoria')
          ->join('alp_categorias', 'alp_productos.id_categoria_default', '=', 'alp_categorias.id')
          ->where('alp_productos.id_categoria_default', $id)
          ->orderBy('alp_productos.nombre_producto')
         // ->limit(12)
          ->get();


          $productos=$this->addOferta($productos);

          $cart= \Session::get('cart');



           $view= View::make('admin.pedidos.table', compact('productos', 'cart'));

            $data=$view->render();

            return $data;
          
    }


    public function datamarcas($id)
    {

         $productos = AlpProductos::select('alp_productos.*', 'alp_categorias.nombre_categoria as nombre_categoria')
          ->join('alp_categorias', 'alp_productos.id_categoria_default', '=', 'alp_categorias.id')
          ->where('alp_productos.id_marca', $id)
          ->orderBy('alp_productos.nombre_producto')
         // ->limit(12)
          ->get();


          $productos=$this->addOferta($productos);

          $cart= \Session::get('cart');

           $view= View::make('admin.pedidos.table', compact('productos', 'cart'));

            $data=$view->render();

            return $data;
          
    }


    public function databuscar($buscar)
    {

         $productos = AlpProductos::search($buscar)->select('alp_productos.*', 'alp_categorias.nombre_categoria as nombre_categoria')
          ->join('alp_categorias', 'alp_productos.id_categoria_default', '=', 'alp_categorias.id')
         // ->where('alp_productos.id_marca', $id)
          ->orderBy('alp_productos.nombre_producto')
          ->get();


          $productos=$this->addOferta($productos);


          $cart= \Session::get('cart');


           $view= View::make('admin.pedidos.table', compact('productos', 'cart'));

            $data=$view->render();

            return $data;
          
    }


     public function databuscarcliente($buscar)
    {

         $clientes = AlpClientes::select('alp_clientes.*', 'users.first_name as first_name', 'users.last_name as last_name', 'users.email as email',  'role_users.role_id  as role_id')
          ->join('users', 'alp_clientes.id_user_client', '=', 'users.id')
          ->join('role_users', 'users.id', '=', 'role_users.user_id')
        ->join('roles', 'role_users.role_id', '=', 'roles.id')
         // ->where('role_users.role_id', '<>', 1)
          ->orWhere('users.first_name','like',  '%'.$buscar.'%')
          ->orWhere('users.last_name','like',  '%'.$buscar.'%')
          ->orWhere('users.email','like',  '%'.$buscar.'%')
          ->orWhere('alp_clientes.doc_cliente','like',  '%'.$buscar.'%')
          ->orWhere('alp_clientes.telefono_cliente','like',  '%'.$buscar.'%')
          ->orderBy('users.first_name')
          ->get();

           $view= View::make('admin.pedidos.listaclientes', compact('clientes'));

            $data=$view->render();

            return $data;
          
    }



      public function addtocart($id)
    {

      if (!\Session::has('cart')) {
          \Session::put('cart', array());
        }

          $producto=AlpProductos::select('alp_productos.*', 'alp_impuestos.valor_impuesto as valor_impuesto')
          ->join('alp_impuestos', 'alp_productos.id_impuesto', '=', 'alp_impuestos.id')
          ->where('alp_productos.id', $id)
          ->first();

          

       $cart= \Session::get('cart');

       $descuento='1'; 

       $error='0'; 

       $precio = array();

       if (isset($cart['inventario'])) {

        $inv=$cart['inventario'];
         
       }else{

        $inv=$this->inventario();

       }


       if (isset($cart['id_almacen'])) {

        $almacen=$cart['id_almacen'];
         
       }else{

        $almacen='1';
        
       }

       

       

       //dd($almacen);

       if (isset($producto->id)) {

       // $producto->precio_oferta=$request->price;

        $producto->cantidad=1;

       // $producto->impuesto=$producto->precio_oferta*$producto->valor_impuesto;

        if (isset($inv[$producto->id])) {


          if($inv[$producto->id]>=$producto->cantidad){

            $cart[$producto->slug]=$producto;

          }else{

            $error="No hay existencia suficiente de este producto";

          }

          # code...
        }else{

            $error="No hay existencia suficiente de este producto, en su ubicacion";

        }

       }else{

        $error="No encontro el producto";

       }





      \Session::put('cart', $cart);

      $cart=$this->reloadCart();

      \Session::put('cart', $cart);

      $total_venta=$this->totalcart($cart);

      $view= View::make('admin.pedidos.listaorden', compact('producto', 'cart', 'error', 'total_venta'));

      $data=$view->render();

      $res = array('data' => $data);

      return $data;
      
    }



      public function updatecart($id, $cantidad)
    {

      if (!\Session::has('cart')) {
          \Session::put('cart', array());
        }

          $producto=AlpProductos::select('alp_productos.*', 'alp_impuestos.valor_impuesto as valor_impuesto')
          ->join('alp_impuestos', 'alp_productos.id_impuesto', '=', 'alp_impuestos.id')
          ->where('alp_productos.id', $id)
          ->first();

          //dd($producto);

       $cart= \Session::get('cart');

       $descuento='1'; 

       $error='0'; 

       $precio = array();

       $inv=$this->inventario();

       $almacen='1';

       //dd($almacen);

       if (isset($producto->id)) {

       // $producto->precio_oferta=$request->price;

        $producto->cantidad=$cantidad;

       // $producto->impuesto=$producto->precio_oferta*$producto->valor_impuesto;

        if (isset($inv[$producto->id])) {


          if($inv[$producto->id]>=$producto->cantidad){

            $cart[$producto->slug]=$producto;

          }else{

            $error="No hay existencia suficiente de este producto";

          }

          # code...
        }else{

            $error="No hay existencia suficiente de este producto, en su ubicacion";

        }

       }else{

        $error="No encontro el producto";

       }




      \Session::put('cart', $cart);

      $cart=$this->reloadCart();

      \Session::put('cart', $cart);

      $total_venta=$this->totalcart($cart);

      $view= View::make('admin.pedidos.listaorden', compact('producto', 'cart', 'error', 'total_venta'));

      $data=$view->render();

      $res = array('data' => $data);

      return $data;
      
    }



      public function deletecart($slug)
    {


          $producto=AlpProductos::select('alp_productos.*', 'alp_impuestos.valor_impuesto as valor_impuesto')
          ->join('alp_impuestos', 'alp_productos.id_impuesto', '=', 'alp_impuestos.id')
          ->where('alp_productos.slug', $slug)
          ->first();

          //dd($producto);

       $cart= \Session::get('cart');

      if (isset($cart[$slug])) {
       
       unset( $cart[$slug]);

       $error="Producto eliminado satisfacctoriamente ";

      }else{

        $error="No se encontro producto en el carrito";


      }


      \Session::put('cart', $cart);

      $total_venta=$this->totalcart($cart);

      $view= View::make('admin.pedidos.listaorden', compact('producto', 'cart', 'error', 'total_venta'));

      $data=$view->render();

      $res = array('data' => $data);

      return $data;
      
    }



      public function vaciarCarrito()
    {

        \Session::forget('cart');

        \Session::put('cart', array());

        $error='';

        $cart= \Session::get('cart');


      $total_venta=$this->totalcart($cart);

      $view= View::make('admin.pedidos.listaorden', compact('cart', 'error', 'total_venta'));

      $data=$view->render();

      $res = array('data' => $data);

      return $data;
      
    }








    private function totalcart($cart){

      $total=0;

      if (is_array($cart)) {
        # code...

        foreach ($cart as $c) {

          if (isset($c->id)) {
            $total=$total+($c->cantidad*$c->precio_base);
          }

          

          # code...
        }

      }


      return $total;
    }



    public function getdirecciones($id)
    {

      if (!\Session::has('cart')) {
          \Session::put('cart', array());
        }

          $direcciones = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
          ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
          ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
          ->where('alp_direcciones.id_client', $id)->get();


      $view= View::make('admin.pedidos.listadirecciones', compact('direcciones'));

      $data=$view->render();

      $res = array('data' => $data);

      return $data;
      
    }


     public function asignacliente($id)
    {

      if (!\Session::has('cart')) {
        \Session::put('cart', array());
      }


      $id_almacen=1;


      $cart=\Session::get('cart');

      $cliente =  User::select('users.*','roles.name as name_role','alp_clientes.estado_masterfile as estado_masterfile','alp_clientes.estado_registro as estado_registro','alp_clientes.telefono_cliente as telefono_cliente','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.cod_alpinista as cod_alpinista')
        ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
        ->join('role_users', 'users.id', '=', 'role_users.user_id')
        ->join('roles', 'role_users.role_id', '=', 'roles.id')
        ->where('role_users.role_id', '<>', 1)
        ->where('alp_clientes.id', '=', $id)
        ->first();


       $direcciones = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
          ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
          ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
          ->where('alp_direcciones.id_client', $cliente->id)->get();


        $cart['id_cliente']=$id;

        $cart['cliente']=$cliente;

        $cart['direcciones']=$direcciones;


        $i=0;

        foreach ($direcciones as $d) {

          if ($i==0){

            $cart['id_direccion']=$d->id;

            $i++;
            # code...
          }
        }


         \Session::put('cart', $cart);

           $afe=AlpAlmacenFormaEnvio::where('id_almacen', $id_almacen)->first();

          if (isset($afe->id)) {

            $formasenvio = AlpFormasenvio::select('alp_formas_envios.*')
            ->join('alp_almacen_formas_envio', 'alp_formas_envios.id', '=', 'alp_almacen_formas_envio.id_forma_envio')
            ->where('alp_almacen_formas_envio.id_almacen', $id_almacen)
            ->whereNull('alp_almacen_formas_envio.deleted_at')
            ->groupBy('alp_formas_envios.id')->get();
            # code...
          }else{

             $formasenvio = AlpFormasenvio::select('alp_formas_envios.*')
            ->join('alp_rol_envio', 'alp_formas_envios.id', '=', 'alp_rol_envio.id_forma_envio')
            ->where('alp_rol_envio.id_rol', '9')->get();

          }


          $afe=AlpAlmacenFormaPago::where('id_almacen', $id_almacen)->first();


          if (isset($afe->id)) {

            $formaspago = AlpFormaspago::select('alp_formas_pagos.*')
              ->join('alp_almacen_formas_pago', 'alp_formas_pagos.id', '=', 'alp_almacen_formas_pago.id_forma_pago')
              ->where('alp_almacen_formas_pago.id_almacen', $id_almacen)
              ->whereNull('alp_almacen_formas_pago.deleted_at')
              ->groupBy('alp_formas_pagos.id')->get();
            # code...
          }else{

              $formaspago = AlpFormaspago::select('alp_formas_pagos.*')
              ->join('alp_rol_pago', 'alp_formas_pagos.id', '=', 'alp_rol_pago.id_forma_pago')
              ->where('alp_rol_pago.id_rol', '9')->get();

          }



        
        \Session::put('cart', $cart);


      $view= View::make('admin.pedidos.clientecompra', compact('cart', 'formaspago', 'formasenvio'));

      $data=$view->render();

      $res = array('data' => $data);

      return $data;
      
    }




    public function storedir(Request $request)
    {


      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cartcontroller/storedir ');

        }else{

          activity()->withProperties($request->all())
                        ->log('cartcontroller/storedir');


        }


        $cart=\Session::get('cart');

        //$user_id = Sentinel::getUser()->id;

        $input = $request->all();


        $input['id_user']=$user_id;

        $input['id_client']=$user_id;

        $input['default_address']=1;


        if (isset($input['id_barrio'])) {
          
            if ($input['id_barrio']=='0') {
                  # code...
            }else{

              $b=Barrio::where('id', $input['id_barrio'])->first();

              if (isset($b->id)) {
                $input['barrio_address']=$b->barrio_name;
                
              }
            }

        }


               
        $direccion=AlpDirecciones::create($input);

         if (isset($direccion->id)) {

          \Session::put('direccion', $direccion->id);


          DB::table('alp_direcciones')->where('id_client', $user_id)->update(['default_address'=>0]);
          DB::table('alp_direcciones')->where('id', $direccion->id)->update(['default_address'=>1]);
          
        }


        $direcciones = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_countries.country_name as country_name')
          ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
          ->where('alp_direcciones.id_client', $user_id)->get();

          $cart['direcciones']=$direcciones;


          \Session::put('cart', $cart);


          $view= View::make('admin.pedidos.clientecompra', compact('cart', 'formaspago', 'formasenvio'));

          $data=$view->render();

          $res = array('data' => $data);

          return $data;



            

    }

























public function postdireccion(DireccionModalRequest $request)
    {

      $configuracion=AlpConfiguracion::where('id', '1')->first();

       $cart=\Session::get('cart');

         $user = Sentinel::getUser();


     //  dd($cart);

      $input=$request->all();

      dd($input);

      $request->principal_address_dir=strip_tags($request->principal_address_dir);
      $request->secundaria_address_dir=strip_tags($request->secundaria_address_dir);
      $request->edificio_address_dir=strip_tags($request->edificio_address_dir);
      $request->detalle_address_dir=strip_tags($request->detalle_address_dir);
      $request->barrio_address_dir=strip_tags($request->barrio_address_dir);


       $direccion = array(
            'id_client' => $cart['id_cliente'], 
            'city_id' => $request->city_id_dir, 
            'id_estructura_address' => $request->id_estructura_address_dir, 
            'principal_address' => $request->principal_address_dir,
            'secundaria_address' => $request->secundaria_address_dir,
            'edificio_address' => $request->edificio_address_dir,
            'detalle_address' => $request->detalle_address_dir,
            'barrio_address'=> $request->barrio_address_dir,             
            'id_barrio'=> $request->id_barrio_dir,             
            'id_user' => $user->id,               
        );

      // dd($direccion);

        $dir=AlpDirecciones::create($direccion);

        $cart['id_direccion']=$dir->id;


         $direcciones = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
          ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
          ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
          ->where('alp_direcciones.id_client', $cart['id_cliente'])->get();

          $cart['direcciones']=$direcciones;

          \Session::put('cart', $cart);

       if (isset($dir->id)) {

          return redirect('admin/tomapedidos/checkout')->with('success', trans('Dirección agregada satisfactoriamente'));
       }else{

        return redirect('admin/tomapedidos/checkout')->with('error', trans('Error al agregar la dirección intente nuevamente '));

       }




    }










 public function postregistro(Request $request)
    {

         $configuracion=AlpConfiguracion::where('id', '1')->first();

         $input=$request->all();

         dd($input);

         if($configuracion->user_activacion==0){

            $activate=true;

            $masterfi=1;

         }else{

            $activate=false;

            $masterfi=0;

         }

                  $request->first_name=strip_tags($request->first_name);
                  $request->last_name=strip_tags($request->last_name);
                  $request->email=strip_tags($request->email);
                  $request->password=strip_tags(substr(md5(time()), 0,8));
                  $request->doc_cliente=strip_tags($request->doc_cliente);
                  $request->cod_alpinista=strip_tags($request->cod_alpinista);
                  $request->principal_address=strip_tags($request->principal_address);
                  $request->secundaria_address=strip_tags($request->secundaria_address);
                  $request->edificio_address=strip_tags($request->edificio_address);
                  $request->detalle_address=strip_tags($request->detalle_address);
                  $request->barrio_address=strip_tags($request->barrio_address);


                  if ($request->email=='' || $request->email==null) {
                     $request->email=$request->first_name.$request->doc_cliente.'@alpinago.com';
                  }



        try {

            if($request->chkalpinista == 1) {

                $codalpin = AlpCodAlpinistas::where('documento_alpi', $request->doc_cliente)->where('codigo_alpi', $request->cod_alpinista)->where('estatus_alpinista',1)->first();

                if ($codalpin) {

                    $data_user = array(
                      'first_name' => $request->first_name, 
                      'last_name' => $request->last_name, 
                      'email' => $request->email, 
                      'password' => $request->password, 
                    );

                    $user = Sentinel::register($data_user, $activate);

                    $data = array(
                    'id_user_client' => $user->id, 
                    'id_type_doc' => $request->id_type_doc, 
                    'doc_cliente' =>$request->doc_cliente, 
                    'telefono_cliente' => $request->telefono_cliente,
                    'habeas_cliente' => '1',
                    'estado_masterfile' =>$masterfi,
                    'cod_alpinista'=> $request->cod_alpinista,
                    'cod_oracle_cliente'=> $codalpin->cod_oracle_cliente,
                    'id_empresa' =>'0',               
                    'id_embajador' =>'0',               
                    'origen' =>'1',               
                    'token' =>substr(md5(md5(time())), 0,12),               
                    'id_user' =>0,               
                    );

                    $cliente=AlpClientes::create($data);

                    $sialpin = array(
                        'id_usuario_creado' => $user->id, 
                        'estatus_alpinista' => 2    
                    );

                    AlpCodAlpinistas::where('id',$codalpin->id)->update($sialpin);

                    $masterfile = array(
                        'estado_masterfile' => 1 ,
                        'nota' => 'Alpinista Registrado automaticamente'
                    );

                    AlpClientes::where('id',$user->id)->update($masterfile);

                    //add user to 'Embajador' group
                    $role = Sentinel::findRoleById(10);

                    $role->users()->attach($user);
                    
                    /*$activation = Activation::exists($user);

                    if ($activation) {

                        Activation::complete($user, $activation->code);

                    }*/

                      $mensaje='Estamos procesando tu solicitud de registro, te notificaremos una vez haya finalizado el proceso, este proceso puede tomar hasta 24 horas.';


                      $roleusuario=RoleUser::where('user_id', $user->id)->first();

                      Mail::to($user->email)->send(new \App\Mail\WelcomeUser($user->first_name, $user->last_name, $configuracion->mensaje_bienvenida, $roleusuario ));

                      Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\WelcomeUser($user->first_name, $user->last_name, $configuracion->mensaje_bienvenida, $roleusuario ));


                      $cart= \Session::get('cart');

                      $cart['id_cliente']=$user->id;



















                     \Session::put('cart', $cart);


                    }else{

                        return redirect('admin/tomapedidos/checkout')->with('error', trans('auth/message.failure.error'))->withInput();

                    }

            }else{

              $data_user = array(
                    'first_name' => $request->first_name, 
                    'last_name' => $request->last_name, 
                    'email' => $request->email, 
                    'password' => $request->password, 
                  );



                  $user = Sentinel::register($data_user, $activate);

                  if ($request->convenio!='') {
                    
                    $empresa=AlpEmpresas::where('convenio', $request->convenio)->first();

                    if (isset($empresa->id)) {
                      # code...
                    }else{

                      return redirect('registro')->with('error', trans('El Código de Convenio no existe'))->withInput();

                    }

                    #dd($empresa);


                  }

                  if (isset($empresa->id)) {
                    
                  }else{

                    $dominio=explode('@', $request->email);

                    $empresa=AlpEmpresas::where('dominio',$dominio[1])->first();

                  }


                 // dd($empresa);

                  $id_empresa=0;

                  if (isset($empresa->id)) {

                      $id_empresa=$empresa->id;
                     
                  }

                    $data = array(
                    'id_user_client' => $user->id, 
                    'id_type_doc' => $request->id_type_doc, 
                    'doc_cliente' =>$request->doc_cliente, 
                    'telefono_cliente' => $request->telefono_cliente,
                    'habeas_cliente' => '1',
                    'cod_oracle_cliente' =>$request->telefono_cliente,
                    'estado_masterfile' =>'1',
                    'id_empresa' =>$id_empresa,               
                    'id_embajador' =>'0',  
                    'origen' =>'1',               
                    'token' =>substr(md5(md5(time())), 0,12),              
                    'id_user' =>0,               
                    );


                  $cliente=AlpClientes::create($data);

                   if ($id_empresa==0) {

                    $role = Sentinel::findRoleById(9);


                        $user_history = array(
                        'id_cliente' => $user->id,
                        'estatus_cliente' => "Activado",
                        'notas' => "Ha sido registrado satisfactoriamente",
                        'id_user'=>$user->id
                         );

                        AlpClientesHistory::create($user_history);


                    }else{

                      $role = Sentinel::findRoleById(12);

                       $user_history = array(
                        'id_cliente' => $user->id,
                        'estatus_cliente' => "Activado",
                        'notas' => "Ha sido registrado satisfactoriamente bajo la empresa ".$empresa->nombre_empresa,
                        'id_user'=>$user->id
                         );

                        AlpClientesHistory::create($user_history);

                    }

                    $role->users()->attach($user);


                    $roleusuario=RoleUser::where('user_id', $user->id)->first();

                     $mensaje='Estamos procesando tu solicitud de registro, te notificaremos una vez haya finalizado el proceso, este proceso puede tomar hasta 24 horas.';

                    Mail::to($user->email)->send(new \App\Mail\WelcomeUser($user->first_name, $user->last_name,  $configuracion->mensaje_bienvenida, $roleusuario));

                    Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\WelcomeUser($user->first_name, $user->last_name,  $configuracion->mensaje_bienvenida, $roleusuario));



                    $cart= \Session::get('cart');

                      $cart['id_cliente']=$user->id;



                     \Session::put('cart', $cart);




            }

            if ($request->id_barrio=='0') {
              # code...
            }else{

              $b=Barrio::where('id', $request->id_barrio)->first();

              if (isset($b->id)) {
                $request->barrio_address=$b->barrio_name;
              }
            }


            $direccion = array(
                'id_client' => $user->id, 
                'city_id' => $request->city_id, 
                'id_estructura_address' => $request->id_estructura_address, 
                'principal_address' => $request->principal_address,
                'secundaria_address' => $request->secundaria_address,
                'edificio_address' => $request->edificio_address,
                'detalle_address' => $request->detalle_address,
                'barrio_address'=> $request->barrio_address,             
                'id_barrio'=> $request->id_barrio,             
                'id_user' => 0,               
            );

            $dir=AlpDirecciones::create($direccion);

            $cart= \Session::get('cart');

            $cart['id_direccion']=$dir->id;


              $cliente =  User::select('users.*','roles.name as name_role','alp_clientes.estado_masterfile as estado_masterfile','alp_clientes.estado_registro as estado_registro','alp_clientes.telefono_cliente as telefono_cliente','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.cod_alpinista as cod_alpinista')
        ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
        ->join('role_users', 'users.id', '=', 'role_users.user_id')
        ->join('roles', 'role_users.role_id', '=', 'roles.id')
        ->where('role_users.role_id', '<>', 1)
        ->where('alp_clientes.id_user_client', '=', $user->id)
        ->first();


       $direcciones = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
          ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
          ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
          ->where('alp_direcciones.id_client', $cliente->id)->get();



          $cart['cliente']=$cliente;
          $cart['direcciones']=$direcciones;

           \Session::put('cart', $cart);


            //if you set $activate=false above then user will receive an activation mail
            if (!$activate) {
                // Data to be used on the email view
                $data=[
                    'user_name' => $user->first_name .' '. $user->last_name,
                    'activationUrl' => URL::route('activate', [$user->id, Activation::create($user)->code]),
                ];
                // Send the activation code through email
                //Mail::to($user->email)->send(new Register($data));
                //Redirect to login page

                if ($id_empresa==0) {
                   
                  //  return redirect('login')->with('success', trans('auth/message.signup.success'));

                }else{

                    $user_history = array(
                        'id_client' => $user->id,
                        'estatus_cliente' => "Activado",
                        'notas' => "Ha sido registrado satisfactoriamente bajo la empresa ".$empresa->nombre_empresa,
                        'id_user'=>$user->id
                         );

                    AlpClientesHistory::create($user_history);


                     $configuracion->mensaje_bienvenida="Ha sido registrado satisfactoriamente bajo la empresa ".$empresa->nombre_empresa.", debe esperar que su Usuario sea activado en un proceso interno, te notificaremos vía email su activación.";

                  //return redirect('login?registro='.$user->id)->with('success', trans($mensaje));

                }

            }

            // login user automatically
         //   Sentinel::login($user, false);
            //Activity log for new account
          //  activity($user->full_name)
          //      ->performedOn($user)
          //      ->causedBy($user)
          //      ->log('Nueva Cuenta Creada');


           $data_c = array(
                    'cod_oracle_cliente' =>$request->telefono_cliente,
                    'estado_masterfile' =>'1'
                );


            $cliente->update($data_c);


            return redirect('admin/tomapedidos/checkout')->with('success', trans('Usuario resitrado satisfactoriamente '))->withInput();





        } catch (UserExistsException $e) {

            $this->messageBag->add('email', trans('auth/message.account_already_exists'));
            
        }

        // Ooops.. something went wrong
        return Redirect::back()->withInput()->withErrors($this->messageBag);
    }








 private function reloadCart()
    {
       
      $cart= \Session::get('cart');

      $s_user= \Session::get('user');

      if (isset($cart['id_almacen'])) {

         $id_almacen=$cart['id_almacen'];
        
      }else{

         $id_almacen='1';

          $cart['id_almacen']='1';
      }

    

      $total=0;

      $cambio=1;

      $descuento='1'; 

      $precio = array();

        if (isset($cart['id_cliente'])) {

            $user_id = Sentinel::getUser()->id;

            //if ($user_id!=$s_user) {
            if (1) {

              $cambio=1;

              \Session::put('user', $cart['id_cliente']);

              $role=RoleUser::where('user_id', $cart['id_cliente'])->first();

              $cliente = AlpClientes::where('id_user_client', $cart['id_cliente'] )->first();

              if (isset($cliente->id_empresa) ) {

                  if ($cliente->id_empresa!=0) {

                      $role->role_id='E'.$cliente->id_empresa.'';
                  }
                 
              }

             if ($role->role_id) {
                    
                    
                foreach ($cart as $producto ) {

                  if (isset($producto->nombre_producto)) {


                  $pregiogrupo=AlpPrecioGrupo::where('id_producto', $producto->id)->where('id_role', $role->role_id)->first();

                  if (isset($pregiogrupo->id)) {
                     
                      $precio[$producto->id]['precio']=$pregiogrupo->precio;

                      $precio[$producto->id]['operacion']=$pregiogrupo->operacion;
                      $precio[$producto->id]['pum']=$pregiogrupo->pum;

                  }

                }
                }
                
            }

          }

        }else{

          $role = array( );

            $r='9';
                foreach ($cart as  $producto) {

                  if (isset($producto->nombre_producto)) {
                    # code...
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $producto->id)->where('id_role', $r)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$producto->id]['precio']=$pregiogrupo->precio;
                        $precio[$producto->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$producto->id]['pum']=$pregiogrupo->pum;

                    }

                }
                }
                
        } //end sentinel check



    if ($cambio==1) {

      foreach ($cart as $producto) {

      if (isset($producto->nombre_producto)) {
        # code...

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

        $almp=AlpAlmacenProducto::where('id_almacen', $id_almacen)->where('id_producto', $producto->id)->first();

        //dd($almp);

        if (isset($almp->id)) {

          $producto->disponible=1;

        }else{

          $producto->disponible=0;

        }


       $cart[$producto->slug]=$producto;
       
      }
      }

     // dd($cart);

       return $cart;

      }else{

        return $cart;

      }
      
    }
















     private function addOferta($productos){


        $descuento='1'; 

        $precio = array();

        $cart= \Session::get('cart');

        $ciudad= \Session::get('ciudad');

        if (isset($cart['id_cliente'])) {



            $user_id = $cart['id_cliente'];

            $user=Sentinel::getUser();
             
            $role=RoleUser::where('user_id', $user_id)->first();

            $rol=$role->role_id;

            $d = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
              ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
              ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
              ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
              ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
              ->where('alp_direcciones.id_client', $user_id)
              ->where('alp_direcciones.default_address', '=', '1')
              ->first();

            if (isset($d->id)) {

            }else{

                  $d = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
                ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
                ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
                ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
                ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
                ->where('alp_direcciones.id_client', $user_id)
                ->first();
            }

            if (isset($d->id)) {
              $ciudad=$d->city_id;
            }



            $cliente = AlpClientes::where('id_user_client', $user_id )->first();

            if (isset($cliente) ) {

                if ($cliente->id_empresa!=0) {
                    
                    $role->role_id='E'.$role->role_id.'';
                }
               
            }

            if ($role->role_id) {

               
                foreach ($productos as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->where('city_id', $ciudad)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;
                        $precio[$row->id]['mostrar']=$pregiogrupo->mostrar_descuento;

                    }else{


                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->first();

                      if (isset($pregiogrupo->id)) {
                         
                          $precio[$row->id]['precio']=$pregiogrupo->precio;
                          $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                          $precio[$row->id]['pum']=$pregiogrupo->pum;
                          $precio[$row->id]['mostrar']=$pregiogrupo->mostrar_descuento;


                      }

                    

                    }

                }
                
            }

        }else{

        

          $role = array( );

            $r='9';

                foreach ($productos as  $row) {

                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $r)->where('city_id', $ciudad)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;
                        $precio[$row->id]['mostrar']=$pregiogrupo->mostrar_descuento;


                    }else{

                      $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $r)->first();

                      if (isset($pregiogrupo->id)) {
                       
                          $precio[$row->id]['precio']=$pregiogrupo->precio;
                          $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                          $precio[$row->id]['pum']=$pregiogrupo->pum;
                          $precio[$row->id]['mostrar']=$pregiogrupo->mostrar_descuento;


                      }

                      

                    }

                }
                
        }



      // dd($precio);

        $prods = array( );

        foreach ($productos as $producto) {

          if ($descuento=='1') {

            if (isset($precio[$producto->id])) {
              # code...
             
              switch ($precio[$producto->id]['operacion']) {

                case 1:

                  $producto->precio_oferta=$producto->precio_base*$descuento;
                  $producto->pum=$precio[$producto->id]['pum'];
                  $producto->mostrar=$precio[$producto->id]['mostrar'];

                  break;

                case 2:

                  $producto->precio_oferta=$producto->precio_base*(1-($precio[$producto->id]['precio']/100));
                  $producto->pum=$precio[$producto->id]['pum'];
                  $producto->mostrar=$precio[$producto->id]['mostrar'];
                  
                  break;

                case 3:

                  $producto->precio_oferta=$precio[$producto->id]['precio'];
                  $producto->pum=$precio[$producto->id]['pum'];
                  $producto->mostrar=$precio[$producto->id]['mostrar'];
                  
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


           $prods[]=$producto;
           
          }

        return $prods;


    }




    public function pedidopago($token)
    {
        // Grab all the groups

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('almacenes/checkout ');

        }else{

          activity()
          ->log('almacenes/checkout');


        }


        
        $configuracion=AlpConfiguracion::where('id', '=', '1')->first();

        $orden=AlpOrdenes::where('token', '=', $token)->first();


        \Session::put('orden', $orden->id);
        \Session::put('carrito', $orden->id);
        \Session::put('iduser', $orden->id_cliente);

         $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug','alp_productos.presentacion_producto as presentacion_producto')
            ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
            ->where('alp_ordenes_detalle.id_orden', $orden->id)->get();

            $cart = array();

        foreach ($detalles as $d) {

          $p=AlpProductos::where('slug', $d->slug)->first();

          $p->cantidad=$d->cantidad;

          $p->precio_oferta=$d->precio_unitario;

          $cart[$d->slug]=$p;


        }

        \Session::put('cart', $cart);

        $id_almacen=1;

        $almacenes = AlpAlmacenes::all();

         $clientes =  User::select('users.*','roles.name as name_role','alp_clientes.estado_masterfile as estado_masterfile','alp_clientes.estado_registro as estado_registro','alp_clientes.telefono_cliente as telefono_cliente','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.cod_alpinista as cod_alpinista')
        ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
        ->join('role_users', 'users.id', '=', 'role_users.user_id')
        ->join('roles', 'role_users.role_id', '=', 'roles.id')
        ->where('role_users.role_id', '<>', 1)
        ->limit(50)
        ->get();
       
         // $cart= \Session::get('cart');

          $total_venta=0;

          $total=0;

          $total_base=0;

          $impuesto=$this->impuesto($detalles, $orden);



          foreach ($detalles as $c) {

          if (isset($c->id)) {

            $total_base=$total_base+($c->cantidad*$c->precio_base);

            $total=$total+($c->cantidad*$c->precio_unitario);

            $total_venta=$total_venta+($c->cantidad*$c->precio_unitario);

            $total_venta=$total_venta+($c->cantidad*$c->precio_unitario);

          }

          # code...
        }














          $afe=AlpAlmacenFormaEnvio::where('id_almacen', $id_almacen)->first();

          if (isset($afe->id)) {

            $formasenvio = AlpFormasenvio::select('alp_formas_envios.*')
            ->join('alp_almacen_formas_envio', 'alp_formas_envios.id', '=', 'alp_almacen_formas_envio.id_forma_envio')
            ->where('alp_almacen_formas_envio.id_almacen', $id_almacen)
            ->whereNull('alp_almacen_formas_envio.deleted_at')
            ->groupBy('alp_formas_envios.id')->get();
            # code...
          }else{

             $formasenvio = AlpFormasenvio::select('alp_formas_envios.*')
            ->join('alp_rol_envio', 'alp_formas_envios.id', '=', 'alp_rol_envio.id_forma_envio')
            ->where('alp_rol_envio.id_rol', '9')->get();

          }

          $afe=AlpAlmacenFormaPago::where('id_almacen', $id_almacen)->first();

          if (isset($afe->id)) {

            $formaspago = AlpFormaspago::select('alp_formas_pagos.*')
              ->join('alp_almacen_formas_pago', 'alp_formas_pagos.id', '=', 'alp_almacen_formas_pago.id_forma_pago')
              ->where('alp_almacen_formas_pago.id_almacen', $id_almacen)
              ->whereNull('alp_almacen_formas_pago.deleted_at')
              ->groupBy('alp_formas_pagos.id')->get();
            # code...
          }else{

              $formaspago = AlpFormaspago::select('alp_formas_pagos.*')
              ->join('alp_rol_pago', 'alp_formas_pagos.id', '=', 'alp_rol_pago.id_forma_pago')
              ->where('alp_rol_pago.id_rol', '9')->get();

          }

          if (isset($cart['id_forma_pago'])) {
            
          }else{

            $i=0;  
            foreach ($formaspago as $fp) {

              if ($i==0) {
                
                $cart['id_forma_pago']=$fp->id;

                $i++;
              }
            }
          }


          if (isset($cart['id_forma_envio'])) {
            # code...
          }else{

            $i=0;  
            foreach ($formasenvio as $fe) {

              if ($i==0) {
                
                $cart['id_forma_envio']=$fe->id;
                $i++;
              }
            }
          }

          $t_documento = AlpTDocumento::where('estado_registro','=',1)->get();

            $estructura = AlpEstructuraAddress::where('estado_registro','=',1)->get();

            $countries = Country::all();
             $listabarrios=Barrio::get();

             $states=State::where('config_states.country_id', '47')->get();

             $cities=City::where('state_id', '47')->get();

             \Session::put('cart', $cart);


              $cart=$this->reloadCart();

              $url='';

$valor_impuesto=AlpImpuestos::where('id', '1')->first();

              $costo_envio=$this->enviopago($orden);

               if ($costo_envio>0) {
               
                 $envio_base=$costo_envio/(1+$valor_impuesto->valor_impuesto);

                $envio_impuesto=$envio_base*$valor_impuesto->valor_impuesto;

              }else{

               $envio_base=0;

                $envio_impuesto=0;

              }




          $pagos=AlpPagos::where('id_orden', $orden->id)->get();

          $total_pagos=0;

          foreach ($pagos as $pago) {

            $total_pagos=$total_pagos+$pago->monto_pago;

          }

          $total_descuentos=0;

         // dd($carrito);

            $descuentos=AlpOrdenesDescuento::where('id_orden','=', $orden->id)->get();

            foreach ($descuentos as $pago) {

              $total_pagos=$total_pagos+$pago->monto_descuento;

              $total_descuentos=$total_descuentos+$pago->monto_descuento;

            }


             $mp = new MP();

           if ($configuracion->mercadopago_sand=='1') {
          
              $mp::sandbox_mode(TRUE);

            }

            if ($configuracion->mercadopago_sand=='2') {
              
              $mp::sandbox_mode(FALSE);

            }



          MP::setCredenciales($configuracion->id_mercadopago, $configuracion->key_mercadopago);

            $payment_methods = MP::get("/v1/payment_methods");

            
        // Show the page
        return view('admin.pedidos.pago', compact('almacenes', 'cart', 'total', 'clientes', 'formaspago', 'formasenvio', 't_documento', 'estructura', 'countries', 'listabarrios', 'states', 'cities', 'url', 'impuesto', 'envio_base', 'envio_impuesto', 'costo_envio', 'total_pagos', 'total_base', 'total_descuentos', 'descuentos', 'orden', 'payment_methods', 'orden', 'detalles'));


    }





     private function impuesto($detalles, $orden)
    {
       

      $impuesto=0;

      $valor_impuesto=0;

      $base=0;

      $total=0;


       foreach ($detalles as $c) {

          if (isset($c->id)) {

            $total=$total+($c->cantidad*$c->precio_unitario);

          }

          # code...
        }




      $total_descuentos=0;


        $descuentos=AlpOrdenesDescuento::where('id_orden', $orden->id)->get();

        foreach ($descuentos as $pago) {

          $total_descuentos=$total_descuentos+$pago->monto_descuento;

        }

          foreach($detalles as $row) {

            if (isset($row->nombre_producto)) {
              
              if($row->valor_impuesto>0){

                $valor_impuesto=$row->valor_impuesto;

              }

              $impuesto=$impuesto+($row->monto_impuesto*$row->cantidad);

              $base=$base+($row->precio_unitario*$row->cantidad);
              }


          }


      $resto=$total-$total_descuentos;

       if ($resto<$base) {

        $impuesto=($resto/(1+$valor_impuesto))*$valor_impuesto;

      }

       return $impuesto;
      
    }


      public function enviopago($orden){

      $formasenvio= $orden->id_forma_envio;

      $direccion= $orden->id_address;

     //dd($formasenvio.' '.$direccion);

      $user = User::where('id',$orden->id_cliente)->first();
      
      $role=RoleUser::where('user_id', $user->id)->first();
      
      $dir=AlpDirecciones::where('id', $direccion)->first();

      if ($formasenvio==1) {

        if (isset($dir->id)) {

          $ciudad=AlpFormaCiudad::where('id_forma', $formasenvio)->where('id_ciudad', $dir->city_id)->first();

          if (isset($ciudad->id)) {

            $envio=$ciudad->costo;
            
          }else{

            $envio=-1;

          }

          
        }else{

          $envio=-1;
        }

      }else{


        if (isset($dir->id)) {

          $ciudad=AlpFormaCiudad::where('id_forma', $formasenvio)->where('id_ciudad', $dir->city_id)->where('id_rol', $role->role_id)->first();

          if (isset($ciudad->id)) {

            $envio=$ciudad->costo;
            
          }else{

            $envio=-1;

          }

          
        }else{

          $envio=-1;
        }



      }

      return $envio;

    } 








     public function envio(){

      $formasenvio= \Session::get('envio');

      $direccion= \Session::get('direccion');

     //dd($formasenvio.' '.$direccion);

      $user_id = Sentinel::getUser()->id;
      
      $role=RoleUser::where('user_id', $user_id)->first();
      
      $dir=AlpDirecciones::where('id', $direccion)->first();

      if ($formasenvio==1) {

        if (isset($dir->id)) {

          $ciudad=AlpFormaCiudad::where('id_forma', $formasenvio)->where('id_ciudad', $dir->city_id)->first();

          if (isset($ciudad->id)) {

            $envio=$ciudad->costo;
            
          }else{

            $envio=-1;

          }

          
        }else{

          $envio=-1;
        }

      }else{


        if (isset($dir->id)) {

          $ciudad=AlpFormaCiudad::where('id_forma', $formasenvio)->where('id_ciudad', $dir->city_id)->where('id_rol', $role->role_id)->first();

          if (isset($ciudad->id)) {

            $envio=$ciudad->costo;
            
          }else{

            $envio=-1;

          }

          
        }else{

          $envio=-1;
        }



      }

      return $envio;

    } 



     private function precio_base()
    {
       $cart= \Session::get('cart');

      $total=0;

      foreach($cart as $row) {

        if (isset($row->nombre_producto)) {

          $total=$total+($row->cantidad*$row->precio_base);# co

        }

      }

       return $total;
      
    }

















  public function orderCreditcard(Request $request)
    {

     // dd(1);


      

    
      


      $avisos = array(
        'cc_rejected_bad_filled_card_number' => 'No pudimos procesar su pago, Revisa el numero de tarjeta.', 
        'cc_rejected_bad_filled_date' => 'No pudimos procesar su pago, Revisa la fecha de vencimiento.', 
        'cc_rejected_bad_filled_other' => 'No pudimos procesar su pago, Revisa los datos.', 
        'cc_rejected_bad_filled_security_code' => 'No pudimos procesar su pago, Revisa el codigo de seguridad.', 
        'cc_rejected_blacklist' => 'No pudimos procesar su pago.', 
        'cc_rejected_call_for_authorize' => 'No pudimos procesar su pago, Debes autorizar ante el banco el pago a mercadopago.', 
        'cc_rejected_card_disabled' => 'No pudimos procesar su pago, Debes activar tu tarjeta.', 
        'cc_rejected_card_error' => 'No pudimos procesar su pago.', 
        'cc_rejected_duplicated_payment' => 'No pudimos procesar su pago, ya realizaste un pago por este monto', 
        'cc_rejected_high_risk' => 'No pudimos procesar su pago, su pago fue rechazado', 
        'cc_rejected_insufficient_amount' => 'No pudimos procesar su pago, no tiene fondos suficientes', 
        'cc_rejected_invalid_installments' => 'No pudimos procesar su pago, no puede procesar pagos por cuotas', 
        'cc_rejected_max_attempts' => 'No pudimos procesar su pago, llegaste al limite de intentos permitidos', 
        'cc_rejected_other_reason' => 'No pudimos procesar su pago, el banco rechazo el pago'
      );
      
      $cart= \Session::get('cart');

      $carrito= \Session::get('cr');


      $id_orden= \Session::get('orden');

      $orden=AlpOrdenes::where('id', $id_orden)->first();

      $input=$request->all();

     //dd($input);

      if (Sentinel::check()) {

        $user_id = Sentinel::getUser()->id;

       


      }else{

        $user_id= \Session::get('iduser');

       

      }

      if ($user_id) {
        # code...


       // $user_id = Sentinel::getUser()->id;

        $user_cliente=User::where('id', $user_id)->first();

        $datos_cliente=AlpClientes::where('id_user_client', $user_id)->first();

        $configuracion = AlpConfiguracion::where('id', '1')->first();

        MP::setCredenciales($configuracion->id_mercadopago, $configuracion->key_mercadopago);


        $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.descripcion_corta as descripcion_corta','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug','alp_productos.presentacion_producto as presentacion_producto')
          ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
          ->where('alp_ordenes_detalle.id_orden', $orden->id)->get();

          $envio=$this->enviopago($orden);


            $valor_impuesto=AlpImpuestos::where('id', '1')->first();

            if ($envio>0) {
             
               $envio_base=$envio/(1+$valor_impuesto->valor_impuesto);

            $envio_impuesto=$envio_base*$valor_impuesto->valor_impuesto;


            }else{

              $envio_base=0;

              $envio_impuesto=0;

            }


            $total=$orden->monto_total+$envio;

            $impuesto=$orden->monto_impuesto+$envio_impuesto;
            
            $net_amount=$total-$impuesto;

            $det_array = array();

            foreach ($detalles as $d ) {

              $det_array[]= array(

                    "id"          => $d->id_producto,
                    "title"       => $d->nombre_producto,
                    "description" => $d->descripcion_corta,
                    "quantity"    => (int)number_format($d->cantidad, 0, '.', ''),
                    "unit_price"  => intval($d->precio_unitario),
                    );
            }

            $phone= array(
              'area_code' =>'+57' , 
              'number' => $datos_cliente->telefono_cliente, 
            );


        $direccion = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
          ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
          ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
          ->where('alp_direcciones.id', $orden->id)->first();


      


          if (isset($direccion->id)) {
           
           $address = array( 
              'street_name' => $direccion->nombre_estructura.' '.$direccion->principal_address.' - '.$direccion->secundaria_address.' '.$direccion->edificio_address.' '.$direccion->detalle_address.' '.$direccion->barrio_address, 
              'street_number' => $direccion->principal_address
            );

          }else{

            $address = array( 
              'street_name' => '', 
              'street_number' => ''
            );

          }

        $fecha = Carbon::now()->format('c');

        $payer = array(
          'first_name' => $user_cliente->first_name, 
          'last_name' => $user_cliente->last_name, 
          'registration_date' => $fecha, 
          'phone' => $phone, 
          'address' => $address
        );

        $additional_info = array(
          'items' => $det_array, 
          'payer' => $payer, 
        );  

        $preference_data = [
        "transaction_amount" => doubleval($orden->monto_total+$envio),
        "net_amount"=>(float)number_format($net_amount, 2, '.', ''),
            "taxes"=>[[
              "value"=>(float)number_format($impuesto, 2, '.', ''),
              "type"=>"IVA"]],
          "token" => $request->token,
          "binary_mode" => true,
          "description" => 'Pago de orden: '.$orden->id,
          "installments" => intval($request->installments),
          "external_reference"=> "ALP".$orden->id."",
          "payment_method_id" => $request->payment_method_id,
          "additional_info" => $additional_info,
          
          "issuer_id" => $request->issuer_id,
          "payer" => [
            "email"=>$user_cliente->email]
        ];

        //dd($preference_data);

        $preference = MP::post("/v1/payments",$preference_data);

         

        if (isset($preference['response']['id'])) {


          if ($preference['response']['status']=='rejected' || $preference['response']['status']=='cancelled' || $preference['response']['status']=='cancelled/expired' )  {

              if (isset($avisos[$preference['response']['status_detail']])) {

                $aviso=$avisos[$preference['response']['status_detail']];
                
              }else{

                $aviso='No pudimos procesar su pago, por favor intente Nuevamente.';

              }
                $data_pago = array(
                'id_orden' => $orden->id, 
                'id_forma_pago' => $orden->id_forma_pago, 
                'id_estatus_pago' => '3', 
                'monto_pago' => 0, 
                'json' => json_encode($preference), 
                'id_user' => '1', 
                );

             AlpPagos::create($data_pago);

            return redirect('pedidos/'.$orden->token.'/pago')->with('aviso', $aviso);

          }

           /// $data=$this->generarPedido('1', '2', $preference, 'credit_card');
           $data=$this->generarPedido('8', '4', $preference, 'credit_card');

            $id_orden=$data['id_orden'];

            $fecha_entrega=$data['fecha_entrega'];

            //  $datalles=AlpDetalles::where('id_orden', $orden->id)->get();

            $compra =  DB::table('alp_ordenes')->select(
              'alp_ordenes.*',
              'users.first_name as first_name','users.last_name as last_name' ,'users.email as email','alp_formas_envios.nombre_forma_envios as nombre_forma_envios','alp_formas_envios.descripcion_forma_envios as descripcion_forma_envios','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago','alp_formas_pagos.descripcion_forma_pago as descripcion_forma_pago','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.doc_cliente as doc_cliente')
            ->join('users','alp_ordenes.id_cliente' , '=', 'users.id')
            ->join('alp_clientes','alp_ordenes.id_cliente' , '=', 'alp_clientes.id_user_client')
            ->join('alp_formas_envios','alp_ordenes.id_forma_envio' , '=', 'alp_formas_envios.id')
            ->join('alp_formas_pagos','alp_ordenes.id_forma_pago' , '=', 'alp_formas_pagos.id')
            ->where('alp_ordenes.id', $id_orden)->first();

           $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug','alp_productos.presentacion_producto as presentacion_producto')
          ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
          ->where('alp_ordenes_detalle.id_orden', $id_orden)->get();

            $cart= \Session::forget('cart');

           $states=State::where('config_states.country_id', '47')->get();

           $configuracion = AlpConfiguracion::where('id','1')->first();

           $envio=AlpEnvios::where('id_orden', $compra->id)->first();


           $texto='Se ha creado la siguiente orden '.$compra->id.' y esta a espera de aprobacion  ';

           $aviso_pago="Hemos recibido su pago satisfactoriamente, una vez sea confirmado, Le llegará un email con la descripción de su pago. ¡Muchas gracias por su Compra!";

            $aviso_pago = array(
            'tipo' => 'success', 
            'texto' => 'yellow', 
            'medio' => 'Tarjeta de Credito', 
            'mensaje' => 'Hemos recibido su pago satisfactoriamente, una vez sea confirmado, Le llegará un email con la descripción de su pago. ¡Muchas gracias por su Compra!', 
          );

            if ($compra->id_forma_envio!=1) {

              $formaenvio=AlpFormasenvio::where('id', $compra->id_forma_envio)->first();

            
            }

          
           $idc=$compra->id*1024;








           return redirect('cart/'.$idc.'/gracias?pago=aprobado');

         #  return view('frontend.order.procesar_completo', compact('compra', 'detalles', 'fecha_entrega', 'states', 'aviso_pago'));
        
        }else{

          return redirect('order/detail');

        }


      }else{

          return redirect('login'); //sino esta logueado
      }

    }



       public function asignaalmacen($id)
    {

      if (!\Session::has('cart')) {
        \Session::put('cart', array());
      }

      $cart=\Session::get('cart');

      $cart['id_almacen']=$id;

     // \Session::put('cart', $cart);

      if (isset($cart['inventario'])) {

          unset($cart['inventario']);

          $cart['inventario']=$this->inventario();
            # code...
          }else{

            $cart['inventario']=$this->inventario();
          }

    \Session::put('cart', $cart);

      return $cart;
      
    }






}