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
use App\Models\AlpRolenvio;
use App\Models\AlpEnvios;
use App\Models\AlpEnviosHistory;



use App\User;
use App\State;
use App\City;
use App\Barrio;
use App\Country;
use App\RoleUser;



use App\Models\AlpAlmacenesUser;

use App\Models\AlpCupones;
use App\Models\AlpCuponesUser;
use App\Models\AlpCuponesCategorias;
use App\Models\AlpCuponesAlmacen;
use App\Models\AlpCuponesMarcas;
use App\Models\AlpCuponesProducto;
use App\Models\AlpCuponesEmpresa;
use App\Models\AlpCuponesRol;

use App\Models\AlpCategoriasProductos;



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

use MercadoPago;

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
                        ->log('pedidos/index ');

        }else{

          activity()
          ->log('pedidos/index');


        }


        if (!Sentinel::getUser()->hasAnyAccess(['tomapedidos.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intento acceder');
        }

       $cart= \Session::get('cart');

          if (isset($cart['id_almacen'])) {
            # code...
            }else{

            $cart['id_almacen']=null;
          }

        $almacenes = AlpAlmacenes::where('estado_registro', '=', '1')->get();

        $id_almacen=$cart['id_almacen'];

        $almacen=AlpAlmacenes::where('id', $cart['id_almacen'])->first();

        $productos = AlpProductos::select('alp_productos.*', 'alp_categorias.nombre_categoria as nombre_categoria')
          ->join('alp_categorias', 'alp_productos.id_categoria_default', '=', 'alp_categorias.id')
           ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
          ->where('alp_almacen_producto.id_almacen', '=', $cart['id_almacen'])
          ->whereNull('alp_almacen_producto.deleted_at')
          ->where('alp_productos.destacado', '1')
          ->orderBy('alp_productos.nombre_producto')
          ->groupBy('alp_productos.id')
          ->limit(12)
          ->get();

         // $productos=AlpProductos::where('id', '=', -1)->get();

          $productos=$this->addOferta($productos);

          //dd($productos);

          $categorias=AlpCategorias::orderBy('nombre_categoria')->get();

          $marcas=AlpMarcas::orderBy('nombre_marca')->get();

          $total_venta=$this->totalcart($cart);

          $cart=$this->reloadCart();

          $descripcion='Lista de productos destacados';


          if (isset($cart['inventario'])) {
            # code...
          }else{

            $cart['inventario']=$this->inventario();
          }


          $clientes =  User::select('users.*','roles.name as name_role','alp_clientes.estado_masterfile as estado_masterfile','alp_clientes.estado_registro as estado_registro','alp_clientes.telefono_cliente as telefono_cliente','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.cod_alpinista as cod_alpinista')
          ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
          ->join('role_users', 'users.id', '=', 'role_users.user_id')
          ->join('roles', 'role_users.role_id', '=', 'roles.id')
          ->where('role_users.role_id', '<>', 1)
          ->limit(50)
          ->get();


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


        // Show the page
        return view('admin.pedidos.index', compact('almacenes', 'productos', 'categorias', 'marcas', 'cart', 'total_venta', 'almacen', 'descripcion','formaspago', 'formasenvio', 't_documento', 'estructura', 'countries', 'listabarrios', 'states', 'cities'));
    }


    public function checkout()
    {
        // Grab all the groups

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('pedidos/checkout ');

        }else{

          activity()
          ->log('pedidos/checkout');


        }


        if (!Sentinel::getUser()->hasAnyAccess(['tomapedidos.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intento acceder');
        }

        $cart= \Session::get('cart');

        
         if (isset($cart['id_almacen'])) {

            $id_almacen=$cart['id_almacen'];

          }else{

            $id_almacen='1';
          }
      

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

          if ($total_venta<7000) {

            return redirect('admin/tomapedidos/')->withInput()->with('error', trans('El monto de compra minimo para este almacen es de 7.000'));

            
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
                        ->log('pedidos/checkout ');

        }else{

          activity()
          ->log('pedidos/checkout');

        }

        $vd=$this->verificarDireccion();

        if ($vd=='true') {
          
        }else{

          return redirect('admin/tomapedidos/')->withInput()->with('error', trans('Su dirección no esta disponible para despacho en este almacen.'));

        }
        
        

        $clientIP = \Request::getClientIp(true);

        $id_almacen=1;

        $almacen=AlpAlmacenes::where('id', $id_almacen)->first();

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

          if (isset($cart['notas_orden'])) {
            $data_notas = array('notas' => $cart['notas_orden'] );

            $orden->update($data_notas);

          }

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

               # $base_impuesto=$base_impuesto+$total_detalle;

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
              'referencia' => 'SC'.$orden->id,
              'referencia_mp' => 'SC'.$orden->id.'_'.str_slug($almacen->nombre_almacen),
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


                $direccion = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
          ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
          ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
          ->where('alp_direcciones.id', $compra->id_address)->first();



          


             \Session::forget('cart');

             $user_cliente=User::where('id', $user_id)->first();

             try {


            # Mail::to($user_cliente->email)->send(new \App\Mail\NotificacionTomapedidos($compra, ''));
               
             } catch (Exception $e) {

              activity()->withProperties(1)
                        ->log('error corrreo pedido  l607');
               
             }


             if ($orden->id_almacen==1) {

              try {
               # $compramas=$this->reservarOrden($orden->id);
              } catch (Exception $e) {
                activity()->withProperties(1)
                        ->log('error reserva orden pedidos l615');
              }
              
            }


            return view('admin.pedidos.procesar', compact('compra', 'detalles', 'direccion'));

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


         if (!Sentinel::getUser()->hasAnyAccess(['tomapedidos.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intento acceder');
        }



        $productos = AlpProductos::select('alp_productos.*')
        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->whereNull('alp_almacen_producto.deleted_at')
        ->where('alp_almacen_producto.id_almacen', '=', $id)
        ->groupBy('alp_productos.id')
        ->get();

        $productos=$this->addOferta($productos);



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

       if (!Sentinel::getUser()->hasAnyAccess(['tomapedidos.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intento acceder');
        }

        

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('pedidos/estatus ');

        }else{

          activity()
          ->withProperties($request->all())->log('pedidos/estatus');

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
        $cart= \Session::get('cart');

          $productos =  DB::table('alp_productos')->select('alp_productos.*', 'alp_marcas.order as order', 'alp_marcas.nombre_marca as nombre_marca', 'alp_categorias.nombre_categoria as nombre_categoria')
          ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')
          ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
          ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
         ->where('alp_almacen_producto.id_almacen', '=', $cart['id_almacen'])
         ->whereNull('alp_almacen_producto.deleted_at')
         ->whereNull('alp_productos.deleted_at')
          ->join('alp_marcas','alp_productos.id_marca' , '=', 'alp_marcas.id')
          ->join('alp_categorias','alp_productos.id_categoria_default' , '=', 'alp_categorias.id')
          ->where('alp_productos_category.id_categoria','=', $id)
          ->whereNull('alp_productos_category.deleted_at')
          ->where('alp_productos.estado_registro','=',1)
          ->where('alp_productos.mostrar','=',1)
          ->groupBy('alp_productos.id')
          ->orderBy('alp_marcas.order') 
          ->orderBy('alp_productos.updated_at', 'desc')
          ->get(); 


          $productos=$this->addOferta($productos);

          $almacen=AlpAlmacenes::where('id', $cart['id_almacen'])->first();

          $categoria=AlpCategorias::where('id', $id)->first();

          $descripcion='Listado de productos de la Categoria: '.$categoria->nombre_categoria;

          $combos=$this->combos();

          $view= View::make('admin.pedidos.table', compact('productos', 'cart', 'almacen', 'descripcion', 'combos'));

          $data=$view->render();

          return $data;
          
    }


    public function datamarcas($id)
    {

      $cart= \Session::get('cart');

         $productos = AlpProductos::select('alp_productos.*', 'alp_categorias.nombre_categoria as nombre_categoria')
          ->join('alp_categorias', 'alp_productos.id_categoria_default', '=', 'alp_categorias.id')
          ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
          ->where('alp_almacen_producto.id_almacen', '=', $cart['id_almacen'])
          ->where('alp_productos.id_marca', $id)
           ->whereNull('alp_almacen_producto.deleted_at')
          ->orderBy('alp_productos.nombre_producto')
          ->groupBy('alp_productos.id')
         // ->limit(12)
          ->get();


          $productos=$this->addOferta($productos);

          $cart= \Session::get('cart');

           if (isset($cart['id_almacen'])) {
            # code...
          }else{

            $cart['id_almacen']='1';

          }



           $almacen=AlpAlmacenes::where('id', $cart['id_almacen'])->first();

          $marca=AlpMarcas::where('id', $id)->first();

          $descripcion='Listado de productos de la Marca: '.$marca->nombre_marca;

           $combos=$this->combos();

           $view= View::make('admin.pedidos.table', compact('productos', 'cart', 'almacen', 'descripcion', 'combos'));
            $data=$view->render();

            return $data;
          
    }


    public function databuscar($buscar)
    {

       $cart= \Session::get('cart');

       if (isset($cart['id_almacen'])) {
         # code...
       }else{

         $cart['id_almacen']='1';
       }

        /* $productos = AlpProductos::search($buscar)->select('alp_productos.*', 'alp_categorias.nombre_categoria as nombre_categoria')
          ->join('alp_categorias', 'alp_productos.id_categoria_default', '=', 'alp_categorias.id')
          ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
          ->where('alp_almacen_producto.id_almacen', '=', $cart['id_almacen'])
          ->whereNull('alp_almacen_producto.deleted_at')
          ->orderBy('alp_productos.nombre_producto')
          ->groupBy('alp_productos.id')
          ->get();*/

         /* $productos = AlpProductos::search($buscar)->select('alp_productos.*', 'alp_categorias.nombre_categoria as nombre_categoria')
          ->join('alp_categorias', 'alp_productos.id_categoria_default', '=', 'alp_categorias.id')
          ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
          ->where('alp_almacen_producto.id_almacen', '=', $cart['id_almacen'])
          ->whereNull('alp_almacen_producto.deleted_at')
          ->whereNull('alp_productos.deleted_at')
          ->where('alp_productos.estado_registro','=', 1)
          ->where('alp_productos.mostrar','=',1)
          ->orderBy('alp_productos.nombre_producto')
          ->groupBy('alp_productos.id')
          ->get();*/


          $productos = AlpProductos::
        search($buscar)->select('alp_productos.*', 'alp_marcas.order as order')
        ->join('alp_marcas','alp_productos.id_marca' , '=', 'alp_marcas.id')
        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
       ->where('alp_almacen_producto.id_almacen', '=', $cart['id_almacen'])
       ->whereNull('alp_almacen_producto.deleted_at')
       ->whereNull('alp_productos.deleted_at')
        ->where('alp_productos.estado_registro','=', 1)
        ->where('alp_productos.mostrar','=',1)
       # ->groupBy('alp_productos.id')
        ->orderBy('alp_marcas.order', 'asc')
        ->orderBy('alp_productos.updated_at', 'desc')
        ->get();


        #dd

          $productos=$this->addOferta($productos);


          $cart= \Session::get('cart');

           if (isset($cart['id_almacen'])) {
            # code...
          }else{

            $cart['id_almacen']='1';

          }


           $almacen=AlpAlmacenes::where('id', $cart['id_almacen'])->first();


          $descripcion='Listado de productos de la Busqueda: '.$buscar;

          $combos=$this->combos();

           $view= View::make('admin.pedidos.table', compact('productos', 'cart', 'almacen', 'descripcion', 'combos'));

            $data=$view->render();

            return $data;
          
    }


     public function databuscarcliente($buscar)
    {

         $clientes = AlpClientes::select('alp_clientes.*', 'users.first_name as first_name', 'users.last_name as last_name', 'users.email as email',  'role_users.role_id  as role_id',  'config_cities.city_name  as city_name')
          ->join('users', 'alp_clientes.id_user_client', '=', 'users.id')
          ->join('role_users', 'users.id', '=', 'role_users.user_id')
          ->join('alp_direcciones', 'users.id', '=', 'alp_direcciones.id_client')
          ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
        ->join('roles', 'role_users.role_id', '=', 'roles.id')
         // ->where('role_users.role_id', '<>', 1)
          ->orWhere('users.first_name','like',  '%'.$buscar.'%')
          ->orWhere('users.last_name','like',  '%'.$buscar.'%')
          ->orWhere('users.email','like',  '%'.$buscar.'%')
          ->orWhere('alp_clientes.doc_cliente','like',  '%'.$buscar.'%')
          ->orWhere('alp_clientes.telefono_cliente','like',  '%'.$buscar.'%')
          ->orderBy('users.first_name')
          ->limit(10)
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

          $producto=$this->addOfertaUn($producto);
          

       $cart= \Session::get('cart');

       $descuento='1'; 



       $ban_disponible=0;

       $error='0'; 

       $precio = array();

       if (isset($cart['inventario'])) {

        $inv=$cart['inventario'];
         
       }else{

        $inv=$this->inventario();

       }


     //  if (isset($cart['id_almacen'])) {

        $almacen=$cart['id_almacen'];
         
      // }else{

      //  $almacen='1';
        
    //   }

       

       

       //dd($almacen);

       if (isset($producto->id)) {

       // $producto->precio_oferta=$request->price;

        $producto->cantidad=1;

       // $producto->impuesto=$producto->precio_oferta*$producto->valor_impuesto;

        if (isset($inv[$producto->id])) {


          if($inv[$producto->id]>=$producto->cantidad){


             if ($producto->tipo_producto=='2') {

                    $lista=AlpCombosProductos::where('id_combo', $producto->id)->get();

                    foreach ($lista as $l) {

                         if (isset($inv[$l->id_producto])) {

                            if($inv[$l->id_producto]>=($l->cantidad*$producto->cantidad)){


                            }else{

                              $ban_disponible=1;

                              $error="No hay existencia suficiente de este producto, en su ubicacion";
                            }

                        }else{

                          $ban_disponible=1;

                          $error="No hay existencia suficiente de este producto, en su ubicacion";

                        }

                  }

              }

              

              if ($ban_disponible==0) {

                    $cart[$producto->slug]=$producto;
              }

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

   #   dd($cart);

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

       $cart= \Session::get('cart');

       $descuento='1'; 

       $error='0'; 

       $precio = array();

       $inv=$this->inventario();

       $almacen='1';

       //dd($almacen);

       if (isset($producto->id)) {

        $producto->cantidad=$cantidad;

        if (isset($inv[$producto->id])) {

          if($inv[$producto->id]>=$producto->cantidad){

            $cart[$producto->slug]->cantidad=$cantidad;

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

       if($cantidad==0){

          if(isset($cart[$producto->slug])){

            unset($cart[$producto->slug]);

          }

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

       # \Session::forget('cart');

      #  \Session::put('cart', array());

        $error='';

        $cart= \Session::get('cart');

        foreach($cart as $c){

          if(isset($c->slug)){

            unset($cart[$c->slug]);
            
          }
        }


        \Session::put('cart', $cart);

      $total_venta=$this->totalcart($cart);

      $view= View::make('admin.pedidos.listaorden', compact('cart', 'error', 'total_venta'));

      $data=$view->render();

      $res = array('data' => $data);

      return $data;
      
    }






    public function cancelarpedido()
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
            $total=$total+($c->cantidad*$c->precio_oferta);
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


        $cart['id_cliente']=$cliente->id;

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


          $id_almacen=$this->getAlmacen();

          $cart['id_almacen']=$id_almacen;



        
        \Session::put('cart', $cart);


      $view= View::make('admin.pedidos.clientecompra', compact('cart', 'formaspago', 'formasenvio'));

      $data=$view->render();

      $res = array('data' => $data);

      return $data;
      
    }




     public function asignaformadepago($id)
    {

      if (!\Session::has('cart')) {
        \Session::put('cart', array());
      }


      

      $cart=\Session::get('cart');

         if (isset($cart['id_forma_pago'])) {

          $cart['id_forma_pago']=$id;
            
          }else{

           $cart['id_forma_pago']=$id;
          }

      return '1';
      
    }




     public function asignaformadeenvio($id)
    {

      if (!\Session::has('cart')) {
        \Session::put('cart', array());
      }


       $cart=\Session::get('cart');

         if (isset($cart['id_forma_envio'])) {

          $cart['id_forma_envio']=$id;
            
          }else{

           $cart['id_forma_envio']=$id;
          }


      return '1';
      
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

      //dd($input);

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









 public function postregistro(UserModalRequest $request)
    {

         $configuracion=AlpConfiguracion::where('id', '1')->first();

         $input=$request->all();

         //dd($input)

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
                     $request->email='sc'.$request->doc_cliente.'@alpinago.com';
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

            $user_id = $cart['id_cliente'];

            $d = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
                ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
                ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
                ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
                ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
                ->where('alp_direcciones.id_client', $user_id)
                ->first();

                $ciudad= null;

                if (isset($d->id)) {

                    $ciudad=$d->city_id;

                }

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

                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $producto->id)->where('id_role', $role->role_id)->where('city_id', $ciudad)->first();

                  
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
                  
                    $ciudad= null;

                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $producto->id)->where('id_role', $r)->where('city_id', '=', $ciudad)->first();

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


       return $cart;

      }else{

        return $cart;

      }
      
    }









 private function addOfertaUn($producto){


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

               
               
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $producto->id)->where('id_role', $role->role_id)->where('city_id', $ciudad)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$producto->id]['precio']=$pregiogrupo->precio;
                        $precio[$producto->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$producto->id]['pum']=$pregiogrupo->pum;
                        $precio[$producto->id]['mostrar']=$pregiogrupo->mostrar_descuento;

                    }else{


                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $producto->id)->where('id_role', $role->role_id)->first();

                      if (isset($pregiogrupo->id)) {
                         
                          $precio[$producto->id]['precio']=$pregiogrupo->precio;
                          $precio[$producto->id]['operacion']=$pregiogrupo->operacion;
                          $precio[$producto->id]['pum']=$pregiogrupo->pum;
                          $precio[$producto->id]['mostrar']=$pregiogrupo->mostrar_descuento;


                      }

                    

                    }

                
                
            }

        }else{

        

          $role = array( );

            $r='9';

                

                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $producto->id)->where('id_role', $r)->where('city_id', $ciudad)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$producto->id]['precio']=$pregiogrupo->precio;
                        $precio[$producto->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$producto->id]['pum']=$pregiogrupo->pum;
                        $precio[$producto->id]['mostrar']=$pregiogrupo->mostrar_descuento;


                    }else{

                     /* $pregiogrupo=AlpPrecioGrupo::where('id_producto', $producto->id)->where('id_role', $r)->first();

                      if (isset($pregiogrupo->id)) {
                       
                          $precio[$producto->id]['precio']=$pregiogrupo->precio;
                          $precio[$producto->id]['operacion']=$pregiogrupo->operacion;
                          $precio[$producto->id]['pum']=$pregiogrupo->pum;
                          $precio[$producto->id]['mostrar']=$pregiogrupo->mostrar_descuento;


                      }*/

                      

                    }

                
                
        }



      // dd($precio);

        $prods = array( );

       

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


           
          

        return $producto;


    }







     private function addOferta($productos){


        $descuento='1'; 

        $precio = array();

        $cart= \Session::get('cart');

        $ciudad= null;

        if (isset($cart['id_cliente'])) {

            $user_id = $cart['id_cliente'];

            $role=RoleUser::where('user_id', $user_id)->first();

            $rol=$role->role_id;


              $d = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
              ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
              ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
              ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
              ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
              ->where('alp_direcciones.id', $cart['id_direccion'])
              ->first();

             

         

            if (isset($d->id)) {
              $ciudad=$d->city_id;
            }

            #dd($ciudad);

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

                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->where('city_id', '=', 0)->first();

                      if (isset($pregiogrupo->id)) {
                         
                          $precio[$row->id]['precio']=$pregiogrupo->precio;
                          $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                          $precio[$row->id]['pum']=$pregiogrupo->pum;
                          $precio[$row->id]['mostrar']=$pregiogrupo->mostrar_descuento;


                      }

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
                        ->log('pedidos/checkout ');

        }else{

          activity()
          ->log('pedidos/checkout');


        }

        if (Sentinel::check()) {

          Sentinel::logout();
        }


        $configuracion=AlpConfiguracion::where('id', '=', '1')->first();

        $orden=AlpOrdenes::where('token', '=', $token)->first();

        $almacen=AlpAlmacenes::where('id','=', $orden->id_almacen)->first();

        if (isset($orden->id)) {
          
        }else{

          abort('404');
        }

        \Session::put('orden', $orden->id);
        \Session::put('cr', $orden->id);
        \Session::put('iduser', $orden->id_cliente);

        $user=User::where('id', $orden->id_cliente)->first();

        //Sentinel::login($user, false);


         $detalles =  DB::table('alp_ordenes_detalle')->select(
           'alp_ordenes_detalle.*',
           'alp_productos.nombre_producto as nombre_producto',
           'alp_productos.referencia_producto as referencia_producto' ,
           'alp_productos.referencia_producto_sap as referencia_producto_sap' ,
           'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug',
           'alp_productos.presentacion_producto as presentacion_producto',
           'alp_impuestos.valor_impuesto as valor_impuesto')
            ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
            ->join('alp_impuestos', 'alp_productos.id_impuesto', '=', 'alp_impuestos.id')
            ->whereNull('alp_ordenes_detalle.deleted_at')
            ->where('alp_ordenes_detalle.id_orden', $orden->id)->get();


            $cart = array();

        foreach ($detalles as $d) {

          $p=AlpProductos::where('slug', $d->slug)->first();

          $p->cantidad=$d->cantidad;

          $p->precio_oferta=$d->precio_unitario;

          $p->precio_base=$d->precio_base;

          $p->valor_impuesto=$d->valor_impuesto;

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




          $pagos=AlpPagos::where('id_orden', $orden->id)->where('id_estatus_pago', '=', 2)->get();

          $total_pagos=0;

          foreach ($pagos as $pago) {

            $total_pagos=$total_pagos+$pago->monto_pago;

          }

          $total_descuentos=0;

         // dd($carrito);

            $descuentos=AlpOrdenesDescuento::where('id_orden','=', $orden->id)->whereNull('alp_ordenes_descuento.deleted_at')->get();


            foreach ($descuentos as $pago) {

             # $total_pagos=$total_pagos+$pago->monto_descuento;

              $total_descuentos=$total_descuentos+$pago->monto_descuento;

            }

           // dd($total_descuentos);


          #   $mp = new MP();

             MercadoPago::setClientId($almacen->id_mercadopago);
             MercadoPago::setClientSecret($almacen->key_mercadopago);
             MercadoPago::setPublicKey($almacen->public_key_mercadopago);
    
            $payment_methods = MercadoPago::get("/v1/payment_methods");

          //  echo json_encode($payment_methods);

        // Show the page
        return view('admin.pedidos.pago', compact('almacenes', 'cart', 'total', 'clientes', 'formaspago', 'formasenvio', 't_documento', 'estructura', 'countries', 'listabarrios', 'states', 'cities', 'url', 'impuesto', 'envio_base', 'envio_impuesto', 'costo_envio', 'total_pagos', 'total_base', 'total_descuentos', 'descuentos', 'orden', 'payment_methods', 'orden', 'detalles', 'user', 'almacen'));


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

                $impuesto=$impuesto+($row->monto_impuesto*$row->cantidad);

                $base=$base+($row->precio_unitario*$row->cantidad);

              }

             

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

        $almacen=AlpAlmacenes::where('id', $orden->id_almacen)->first();

        MercadoPago::setClientId($almacen->id_mercadopago);
        MercadoPago::setClientSecret($almacen->key_mercadopago);
        MercadoPago::setPublicKey($almacen->public_key_mercadopago);


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
          "external_reference"=> $orden->referencia_mp,
          "payment_method_id" => $request->payment_method_id,
          "additional_info" => $additional_info,
          
          "issuer_id" => $request->issuer_id,
          "payer" => [
            "email"=>$user_cliente->email]
        ];


        MercadoPago::setClientId($almacen->id_mercadopago);
        MercadoPago::setClientSecret($almacen->key_mercadopago);
        MercadoPago::setPublicKey($almacen->public_key_mercadopago);


        $ip=\Request::ip();

        $payment = new MercadoPago\Payment();
        $payment->transaction_amount = (float)$orden->monto_total+$envio;
        $payment->net_amount =(float)number_format($net_amount, 2, '.', '');
        $payment->taxes = [[
          "value"=>(float)number_format($impuesto, 2, '.', ''),
          "type"=>"IVA"]
        ];

        $payment->token = $request['MPHiddenInputToken'];
        $payment->description = 'Pago de Orden '. $orden->id;
        $payment->installments = (int)$request['installments'];
        $payment->payment_method_id = $request['MPHiddenInputPaymentMethod'];
        //$payment->issuer_id = (int)$request['issuer'];
        $payment->external_reference = $orden->referencia_mp;
        $payment->binary_mode = true;
        $payment->additional_info = array(
          "ip_address"=>$ip,
          "items"=>$det_array,
          "payer"=>$payer,
        );

        $payer = new MercadoPago\Payer();
        $payer->email = $request['cardholderEmail'];
        $payer->identification = array(
            "type" => $request['identificationType'],
            "number" => $request['123123123']
        );
        $payment->payer = $payer;

        $payment->save();

        $response = array(
            'status' => $payment->status,
            'status_detail' => $payment->status_detail,
            'id' => $payment->id
        );


        //dd($preference_data);

       # $preference = MP::post("/v1/payments",$preference_data);


         $data_pago = array(
                'id_orden' => $orden->id, 
                'id_forma_pago' => $orden->id_forma_pago, 
                'id_estatus_pago' => '3', 
                'monto_pago' => 0, 
                'json' => json_encode($preference), 
                'id_user' => '1', 
                );

             AlpPagos::create($data_pago);

         

             if (isset($payment->id)) {


              if ($payment->status=='rejected' || $payment->status=='cancelled' || $payment->status=='cancelled/expired' )  {

                if (isset($avisos[$payment->status_detail])) {

                  $aviso=$avisos[$payment->status_detail];
                
              }else{

                $aviso='No pudimos procesar su pago, por favor intente Nuevamente.';

              }

              $data_payment = array(
                'id' => $payment->id, 
                'operation_type' => $payment->operation_type, 
                'payment_method_id' => $payment->payment_method_id, 
                'payment_type_id' => $payment->payment_type_id, 
                'external_reference' => $payment->external_reference, 
                'status' => $payment->status, 
                'status_detail' => $payment->status_detail, 
                'transaction_amount' => $payment->transaction_amount, 
                'external_resource_url' => $payment->transaction_details->external_resource_url, 
              );

                $data_pago = array(
                'id_orden' => $orden->id, 
                'id_forma_pago' => $orden->id_forma_pago, 
                'id_estatus_pago' => '3', 
                'monto_pago' => 0, 
                'referencia' => $payment->id, 
                'metodo' => $payment->payment_method_id, 
                'tipo' => $payment->payment_type_id, 
                'json' => json_encode($data_payment), 
                'id_user' => '1', 
                );

                
             AlpPagos::create($data_pago);

            return redirect('pedidos/'.$orden->token.'/pago')->with('aviso', $aviso);

          }



           /// $data=$this->generarPedido('1', '2', $preference, 'credit_card');
           $data=$this->generarPedido('8', '4', $payment, 'credit_card');

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

          foreach ($cart as $c) {

            if (isset($c->nombre_producto)) {
              unset($cart[$c->slug]);
            }
            # code...
          }

    \Session::put('cart', $cart);

      return $cart;
      
    }



 public function asignadireccion($id)
    {

      if (!\Session::has('cart')) {
        \Session::put('cart', array());
      }

      $cart=\Session::get('cart');

      $cart['id_direccion']=$id;

      \Session::put('cart', $cart);


      $id_almacen=$this->getAlmacen();

      $cart['id_almacen']=$id_almacen;

     // \Session::put('cart', $cart);

     \Session::put('cart', $cart);

      return $cart;
      
    }
















    public function verificarDireccion()
    {


        /*\Session::put('orden', $orden->id);
        \Session::put('cr', $orden->id);
        \Session::put('iduser', $orden->id_cliente);*/

      $cart=\Session::get('cart');


      if (isset($cart['id_direccion'])) {
        # code...
      }else{

        $cr=\Session::get('cr');

        $orden=AlpOrdenes::where('id', $cr)->first();

        if (isset($orden->id)) {
          
          $cart['id_direccion']=$orden->id_address;

        }



      }

       $validado=0;


      if (isset($cart['id_direccion'])) {
        
        $direccion=AlpDirecciones::where('id', $cart['id_direccion'])->first();

        if ($direccion->id_barrio!=0) {

           
          $ciudad=AlpFormaCiudad::where('id_forma', $cart['id_forma_envio'])->where('id_barrio', $direccion->id_barrio)->first();

            if(isset($ciudad->id)){

            }else{

              $ciudad=AlpFormaCiudad::where('id_forma', $cart['id_forma_envio'])->where('id_ciudad', $direccion->city_id)->where('id_barrio', '=', 0)->first();

            }

        }else{

           $ciudad=AlpFormaCiudad::where('id_forma', $cart['id_forma_envio'])->where('id_ciudad', $direccion->city_id)->first();

        }

          $role=RoleUser::select('role_id')->where('user_id', $cart['id_cliente'])->first();

          $re=AlpRolenvio::where('id_rol', $role->role_id)->get();

          $re_u=AlpRolenvio::where('id_rol', $role->role_id)->first();

          $id_almacen=$cart['id_almacen'];

        $ad=AlpAlmacenDespacho::where('id_almacen', '=', $cart['id_almacen'])->where('id_city', '=', $direccion->city_id)->first();

        if (isset($ad->id)) {
          
        }else{

          return false;
        }

        if (count($re)==1) {
            
            if ($re_u->id_forma_envio=='4') {

                $validado='1';
                
            }

        }

      }//if existe id_direccion
      

      if (isset($ciudad->id)  ||  $validado=='1'){

          return 'true';

      }else{

        return 'false';
      }

        
    }
    



 private function asignaCupon($codigo){

      if (Sentinel::check()) {

      $user = Sentinel::getUser();

       activity($user->full_name)
                    ->performedOn($user)
                    ->causedBy($user)
                    ->withProperties(['codigo'=>$codigo])
                    ->log('cartcontroller/asignaCupon ');

      }else{

      activity()->withProperties(['codigo'=>$codigo])
                    ->log('cartcontroller/asignaCupon');

      }



       $ordenId=\Session::get('orden');

      $orden=AlpOrdenes::where('id', $ordenId)->first();

      $id_almacen=$orden->id_almacen;

     $configuracion=AlpConfiguracion::where('id', '1')->first();

      $cart= \Session::get('cart');





       


      $cart=$this->reloadCart($cart);

      $total=$this->totalcart($cart);


        $orden=AlpOrdenes::where('id', '=', $ordenId)->first();
        
        $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug','alp_productos.presentacion_producto as presentacion_producto')
            ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
            ->where('alp_ordenes_detalle.id_orden', $orden->id)->get();



      $total_base=$this->precio_base();

      $impuesto=$this->impuesto($detalles, $orden);



        $date = Carbon::now();

        $hoy=$date->format('Y-m-d');

        $user_id = $orden->id_cliente;

        $usuario=User::where('id', $user_id)->first();

        $user_cliente=User::where('id', $user_id)->first();

        $cliente=AlpClientes::where('id_user_client', $user_id)->first();

      $cupon=AlpCupones::where('codigo_cupon', $codigo)
          ->whereDate('fecha_inicio','<=',$hoy)
          ->whereDate('fecha_final','>=',$hoy)
          ->first();

      $usados=AlpOrdenesDescuento::where('codigo_cupon', $codigo)->where('aplicado', '1')->get();

      $usados_persona=AlpOrdenesDescuento::where('codigo_cupon', $codigo)->where('id_user', $user_id)->where('aplicado', '1')->get();

      $usados_orden=AlpOrdenesDescuento::where('id_orden', $ordenId)->where('id_user', $user_id)->get();


          $mensaje_user='';
          
          $mensaje_producto = '';
          
          $pago = '';

          $clase='info';


           $b_empresa=0;
            $b_rol=0;
            $b_user=0;


            $b_marca=0;
            $b_categoria=0;
            $b_producto=0;
            $b_almacen=0;

            $b_cantidad=0;

            $b_user_valido=0;


            $b_producto_valido=0;


      if (isset($cupon->id)) {


            $c_user=AlpCuponesUser::where('id_cupon', $cupon->id)->first();

            $c_rol=AlpCuponesRol::where('id_cupon', $cupon->id)->first();

            $c_empresa=AlpCuponesEmpresa::where('id_cupon', $cupon->id)->first();

            $c_producto=AlpCuponesProducto::where('id_cupon', $cupon->id)->first();

            $c_marca=AlpCuponesMarcas::where('id_cupon', $cupon->id)->first();

            $c_categoria=AlpCuponesCategorias::where('id_cupon', $cupon->id)->first();

            $c_almacen=AlpCuponesAlmacen::where('id_cupon', $cupon->id)->first();


            if (isset($c_empresa->id)) {  $b_empresa=1;    }

            if (isset($c_rol->id)) {  $b_rol=1;    }

            if (isset($c_user->id)) {  $b_user=1;    }


            if (isset($c_producto->id)) {  $b_producto=1;    }

            if (isset($c_marca->id)) {  $b_marca=1;    }

            if (isset($c_categoria->id)) {  $b_categoria=1;    }

            if (isset($c_almacen->id)) {  $b_almacen=1;    }


            if(count($usados_orden)>0){

               $b_user_valido=1;

              $mensaje_user=$mensaje_user.'Ya el usuario aplico un cupón en la orden. ';
               $clase='info';

            }

            if($cupon->primeracompra=='1'){

              $orden=AlpOrdenes::where('id_cliente', $user_id)->first();

              if (isset($orden->id)) {
                
                $b_user_valido=1;

                $mensaje_user=$mensaje_user.'Este cupón solo puede ser usado en la primera compra de cada cliente. ';
                
                $clase='danger';

              }

            }

            if($cupon->registrado!= NULL){

             $f=Carbon::parse($cupon->registrado);

             $fecha=Carbon::parse($cupon->registrado)->timestamp;



             $fecha_cliente=Carbon::parse($usuario->created_at)->timestamp;

             if ($fecha_cliente>=$fecha) {
               
             }else{

              $b_user_valido=1;

                $mensaje_user=$mensaje_user.'Este Cupón solo esta disponible para clientes que se registraron después del '.$f->format('d/m/Y').'. ';
                
                $clase='danger';


             }
              

            }




            if($cupon->limite_uso<=count($usados)){

               $b_user_valido=1;

                $mensaje_user=$mensaje_user.'Ya se usaron los cupones disponibles. ';
               $clase='info';


            }


             if($cupon->limite_uso_persona<=count($usados_persona)){

               $b_user_valido=1;

                $mensaje_user=$mensaje_user.'Ya el usuario aplico el máximo de cupones disponibles.  ';
               $clase='info';


            }


              if(intval($cupon->monto_minimo)<intval($total)){ }else{

               $b_user_valido=1;

                $mensaje_user=$mensaje_user.'Para usar el cupón debe tener mínimo $'.intval($cupon->monto_minimo).' en el carrito. ';

               $clase='info';


            }


            if($b_almacen==1){

              $cc=AlpCuponesAlmacen::where('id_cupon', $cupon->id)->where('id_almacen', $id_almacen)->first();

              if(isset($cc->id)){


                          if ($cc->condicion==0) {
                              
                             $b_user_valido=1;

                              $mensaje_user=$mensaje_user.' Descuento no disponible para tu ubicación ';

                             $clase='danger';

                          }

                }else{

                  $cb=AlpCuponesAlmacen::where('id_cupon', $cupon->id)->where('condicion','=', '1')->first();

                    if (isset($cb->id)) {


                      $b_user_valido=1;

                      $mensaje_user=$mensaje_user.' Descuento no disponible para tu ubicación ';

                      $clase='danger';
                      
                    }

                 

              }

            }



            if($b_empresa==1){

              $cc=AlpCuponesEmpresa::where('id_cupon', $cupon->id)->where('id_empresa', $cliente->id_empresa)->first();

              if(isset($cc->id)){


                          if ($cc->condicion==0) {
                              
                             $b_user_valido=1;

                              $mensaje_user=$mensaje_user.' No aplicable por filtro empresa. ';

                             $clase='danger';

                          }

                }else{

                  $b_user_valido=1;

                  $mensaje_user=$mensaje_user.' No aplicable por filtro empresa. ';

               $clase='danger';

              }

            }

            if($b_rol==1){

              $role=RoleUser::select('role_id')->where('user_id', $user_id)->first();

              $cc=AlpCuponesRol::where('id_cupon', $cupon->id)->where('id_rol', $role->role_id)->first();

              if(isset($cc->id)){


                if ($cc->condicion==0) {
                              
                             $b_user_valido=1;

                                $mensaje_user=$mensaje_user.' No aplicable por filtro rol. ';

                               $clase='danger';

                          }

              }else{

                $b_user_valido=1;

                $mensaje_user=$mensaje_user.' No aplicable por filtro rol. ';

               $clase='danger';

              }

            }

            if($b_user==1){

              $cc=AlpCuponesUser::where('id_cupon', $cupon->id)->where('id_cliente', $user_id)->first();

              if(isset($cc->id)){

                       if ($cc->condicion==0) {
                              
                             $b_user_valido=1;

                                $mensaje_user=$mensaje_user.' No aplicable por filtro usuario. ';

                               $clase='danger';

                          }



              }else{

                $b_user_valido=1;
                $mensaje_user=$mensaje_user.' No aplicable por filtro usuario. ';

               $clase='danger';

              }

            }

            $base_descuento=0;


            if($b_user_valido==0){


              foreach ($cart as $detalle) {

                if(isset($detalle->nombre_producto)){

                  $b_producto_valido=0;

                  if($cupon->maximo_productos<$detalle->cantidad){

                      $b_cantidad=1;

                      $mensaje_user='La cantidad de: '.$detalle->nombre_producto.', en el carrito excede lo permitido para comprar con este cupón. El Máximo permitido es de: '.$cupon->maximo_productos.' Unidades. ';

                  }


                  if($b_categoria==1){

                      $cc=AlpCuponesCategorias::where('id_cupon', $cupon->id)->where('id_categoria', $detalle->id_categoria_default)->first();

                      if(isset($cc->id)){

                        //dd($cc);

                          if ($cc->condicion==0) {
                              
                            $b_producto_valido=1;

                            $mensaje_producto=' No aplicable por filtro categoria. ';

                            $clase='info';

                          }


                        }else{

                           $cb=AlpCuponesCategorias::where('id_cupon', $cupon->id)->where('condicion','=', '1')->first();

                              if (isset($cb->id)) {

                                $b_producto_valido=1;

                                $mensaje_producto=' No aplicable por filtro categoria. ';

                                $clase='info';
                                
                              }

                        }


                        if ($b_producto_valido=='0') {
                          # code...


                      $cas=AlpCategoriasProductos::where('id_producto', $detalle->id)->get();

                      foreach ($cas as $ca) {

                         $cc=AlpCuponesCategorias::where('id_cupon', $cupon->id)->where('id_categoria', $ca->id_categoria)->first();

                      if(isset($cc->id)){

                        //dd($cc);

                          if ($cc->condicion==0) {
                              
                            $b_producto_valido=1;

                            $mensaje_producto=' No aplicable por filtro categoria. ';

                            $clase='info';

                          }


                        }else{

                           $cb=AlpCuponesCategorias::where('id_cupon', $cupon->id)->where('condicion','=', '1')->first();

                              if (isset($cb->id)) {

                                $b_producto_valido=1;

                                $mensaje_producto=' No aplicable por filtro categoria. ';

                                $clase='info';
                                
                              }

                        }

                      }//endforeach

                        }//si se venrifica




                    }



                  if($b_marca==1){

                      $cc=AlpCuponesMarcas::where('id_cupon', $cupon->id)->where('id_marca', $detalle->id_marca)->first();


                      if(isset($cc->id)){

                       // dd($cc->condicion);

                          if ($cc->condicion=='0') {
                            
                            $b_producto_valido=1;
                            $mensaje_producto=' No aplicable por filtro marca. ';
                            $clase='info';

                          }


                        }else{


                           $cb=AlpCuponesMarcas::where('id_cupon', $cupon->id)->where('condicion','=', '1')->first();

                            if (isset($cb->id)) {

                              $b_producto_valido=1;
                              $mensaje_producto=' No aplicable por filtro marca. ';
                              $clase='info';
                              
                            }

                      }

                    }



                    if($b_producto==1){

                      $cc=AlpCuponesProducto::where('id_cupon', $cupon->id)->where('id_producto', $detalle->id)->first();

                      if(isset($cc->id)){

                        if ($cc->condicion==0) {
                              
                             $b_producto_valido=1;

                                $mensaje_user=$mensaje_user.' No aplicable por filtro producto. ';

                               $clase='danger';

                          }

                        }else{

                          


                           $cb=AlpCuponesProducto::where('id_cupon', $cupon->id)->where('condicion','=', '1')->first();

                            if (isset($cb->id)) {

                              $b_producto_valido=1;

                              $mensaje_producto=' No aplicable por filtro producto. ';

                              $clase='info';
                              
                            }

                      }

                    }


                    if ($b_producto_valido==0) {

                     // dd($detalle);

                      $base_descuento=$base_descuento+($detalle->precio_oferta*$detalle->cantidad);

                      

                    }
                    }

                
              }//endforeach detalles

            //  dd($base_descuento);

            
            if ($b_cantidad==0) {
              

              if ($base_descuento>0) {

                  $mensaje_producto='';

                   $valor=0;

                if ($cupon->tipo_reduccion==1) {
                  
                  $valor=$cupon->valor_cupon;

                  if ($valor>$base_descuento) {

                    $valor=$base_descuento;

                  }

                }else{

                  $valor=($cupon->valor_cupon/100)*$base_descuento;
                }

                $cupones=AlpOrdenesDescuento::where('id_orden', $ordenId)->get();

                foreach ($cupones as $cupon) {
                  
                  $c=AlpOrdenesDescuento::where('id', $cupon->id)->first();

                  if (isset($c->id)) {

                    $c->delete();
                    # code...
                  }

                }

                $valor_int=intval($valor);

                if ($valor>$valor_int) {

                  $valor_int=$valor_int+1;
                  
                }


                $data_pago = array(
                  'id_orden' => $ordenId, 
                  'codigo_cupon' => $codigo, 
                  'monto_descuento' => $valor_int, 
                  'json' => json_encode($cupon), 
                  'id_user' => $user_id 
                );
              
              $pago=AlpOrdenesDescuento::create($data_pago);


              $mensaje_producto='Cupón aplicado satisfactoriamente ';

              $clase='info';


            }

          }

          } //en if usuario paso


      }else{//end if hay cupon 

            $b_user_valido=1;

              $mensaje_user=$mensaje_user.'Lo sentimos, es código no esta disponible';
              $clase='danger';
      }

      return array(
        'codigo' => $codigo, 
        'cupon' => $cupon, 
        'pago' => $pago, 
        'user_valido' => $b_user_valido, 
        'producto_valido' => $b_producto_valido, 
        'mensaje_user' => $mensaje_user, 
        'mensaje_producto' => $mensaje_producto, 
        'clase' => $clase
      );

    }

public function addcupon(Request $request)
    {


       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cartcontroller/addcupon ');

        }else{

          activity()->withProperties($request->all())
                        ->log('cartcontroller/addcupon');


        }

      $configuracion=AlpConfiguracion::where('id', '1')->first();


      $ordenId= \Session::get('orden');

      $cart= \Session::get('cart');

      $cart=$this->reloadCart($cart);

      $total=$this->totalcart($cart);


        $orden=AlpOrdenes::where('id', '=', $ordenId)->first();
        
        $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug','alp_productos.presentacion_producto as presentacion_producto')
            ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
            ->where('alp_ordenes_detalle.id_orden', $orden->id)->get();



      $total_base=$this->precio_base();

      $impuesto=$this->impuesto($detalles, $orden);

      $aviso='';


      if ($total<7000) {

            $aviso='El monto mínimo de compra es de $'.number_format($configuracion->minimo_compra,0,",",".");

            $cart=$this->reloadCart();


          $configuracion=AlpConfiguracion::where('id', '1')->first();

          $total=$this->total();

          $inv=$this->inventario();

         return Redirect::back()->withInput()->withErrors($aviso);
      }

      

        $user_id = $orden->id_cliente;

        $usuario=User::where('id', $user_id)->first();

        $user_cliente=User::where('id', $user_id)->first();

        $request->codigo_cupon=strip_tags($request->codigo_cupon);

        $mensaje_cupon=$this->asignaCupon($request->codigo_cupon);

        if ($mensaje_cupon['mensaje_user']=='') {
          
        }else{

          $aviso=$aviso.$mensaje_cupon['mensaje_user'];

        }

        if ($mensaje_cupon['mensaje_producto']=='') {
          
        }else{

          $aviso=$aviso.$mensaje_cupon['mensaje_producto'];

        }

        $texto="<div class='alert alert-".$mensaje_cupon['clase']."'>".$aviso."</div>";


        return $texto;



    }

    public function addcuponform(Request $request)
    {


       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cartcontroller/addcuponform ');

        }else{

          activity()->withProperties($request->all())
                        ->log('cartcontroller/addcuponform');


        }
;

      $configuracion=AlpConfiguracion::where('id', '1')->first();
      
      $carrito= \Session::get('cr');

      $cart=$this->reloadCart();

      $total=$this->total();

      $total_base=$this->precio_base();

      $orden=AlpOrdenes::where('id', '=', $carrito)->first();
 $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug','alp_productos.presentacion_producto as presentacion_producto')
            ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
            ->whereNull('alp_ordenes_detalle.deleted_at')
            ->where('alp_ordenes_detalle.id_orden', $orden->id)->get();



      $impuesto=$this->impuesto($detalles, $orden);

      $aviso='';


      if ($total<7000) {

            $aviso='El monto mínimo de compra es de $'.number_format(7000,0,",",".");


            $cart=$this->reloadCart();


          $configuracion=AlpConfiguracion::where('id', '1')->first();

          $total=$this->total();

          $inv=$this->inventario();

          return redirect('pedidos/'.$orden->token.'/pago')->with('aviso', $aviso);
      }


      $user_id = $orden->id_cliente;

       // $user_id = Sentinel::getUser()->id;

        $usuario=User::where('id', $user_id)->first();

        $user_cliente=User::where('id', $user_id)->first();

        $request->codigo_cupon=strip_tags($request->codigo_cupon);

        $mensaje_cupon=$this->asignaCupon($request->codigo_cupon);

        if ($mensaje_cupon['mensaje_user']=='') {
          
        }else{

          $aviso=$aviso.$mensaje_cupon['mensaje_user'];

        }

        if ($mensaje_cupon['mensaje_producto']=='') {
          
        }else{

          $aviso=$aviso.$mensaje_cupon['mensaje_producto'];

        }

        return redirect('pedidos/'.$orden->token.'/pago')->with('aviso', $aviso);

     

    }




    public function delcupon(Request $request)
    {


       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cartcontroller/delcupon ');

        }else{

          activity()->withProperties($request->all())
                        ->log('cartcontroller/delcupon');


        }

        

      $configuracion=AlpConfiguracion::where('id', '1')->first();
      
      $carrito= \Session::get('orden');

      $cart= \Session::get('cart');

    //  $cart=$this->reloadCart();

     // $total=$this->total();

    //  $total_base=$this->precio_base();

    //  $impuesto=$this->impuesto();

      $texto="<div class='alert alert-danger'>El cupón ha sido eliminado</div>";

      $o=AlpOrdenesDescuento::where('id', $request->id)->first();

      if (isset($o->id)) {

      $o->delete();
        # code...
      }


      return $texto;

    }













    public function terminoscliente()
    {


      $iduser=\Session::get('iduser');

      if ($iduser) {

      $id_cliente=$iduser;

      $data = array('tomapedidos_termino' => 1);

      $c=AlpClientes::where('id_user_client', $id_cliente)->first();

      $c->update($data);


      $data_history = array(
        'id_cliente' => $c->id_user_client, 
        'estatus_cliente' => 'activado', 
        'notas' => 'El Cliente ha aceptado terminos y condiciones de Tomapedidos', 
        'id_user'=>$c->id_user_client
      );

      AlpClientesHistory::create($data_history);

      return 'true';



      }else{
        return 'false';
      }

   
      
    }



public function marketingcliente()
    {


      $iduser=\Session::get('iduser');

      if ($iduser) {
         $id_cliente=$iduser;


      $data = array('tomapedidos_marketing' => 1);

      $c=AlpClientes::where('id_user_client', $id_cliente)->first();

      $c->update($data);


      $data_history = array(
        'id_cliente' => $c->id_user_client, 
        'estatus_cliente' => 'activado', 
        'notas' => 'El Cliente ha aceptado terminos y condiciones de Tomapedidos', 
        'id_user'=>$c->id_user_client
      );

      AlpClientesHistory::create($data_history);

      return 'true';
      }else{

        return 'false';
      }

     
      
    }




    private function reservarOrden($id_orden)
    {

    $configuracion=AlpConfiguracion::first();
      
    $orden=AlpOrdenes::where('id', $id_orden)->first();

    activity()->withProperties($orden)->log('compramas orden ');
        
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
                'observacionDomicilio' =>$orden->notas, 
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
                'observaciones' => $orden->notas,
                'paradas' => $dir, 
                'products' => $productos, 
              );


              $dataraw=json_encode($o);

              $urls=$configuracion->compramas_url.'/registerOrderReserved/'.$configuracion->compramas_hash;

             activity()
          ->withProperties($o)->log('compramas datos ');


      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, $configuracion->compramas_url.'/registerOrderReserved/'.$configuracion->compramas_hash);
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

   //    Log::info('compramas res '.json_encode($res));
       
   //    Log::info('compramas result '.$result);


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


       $notas=$notas.'Codigo: CC.';


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
                        } catch (Exception $e) {

                          activity()->withProperties(1)
                        ->log('error envio de correo pedidos l4888');
                          
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
            } catch (Exception $e) {

              activity()->withProperties(1)
                        ->log('error envio de correo pedidos l4925');
              
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

          $texto='No hubo respuesta compramas CC';

          Mail::to($configuracion->correo_sac)->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

           Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

                     

      }

      
    }


    public function generarPedido($estatus_orden, $estatus_pago, $json_pago, $tipo){

    $id_pago='0';

    //dd($tipo);

    if(isset($json_pago['response']['id'])){

      $id_pago=$json_pago['response']['id'];

    }else{

       if (\Session::has('pse')) {

              $id_pago=\Session::get('pse');

          }

    }

        $cart= \Session::get('cart');

        $carrito= \Session::get('cr');

        $configuracion = AlpConfiguracion::where('id','1')->first();

        $id_orden= \Session::get('orden');

        $orden=AlpOrdenes::where('id', $id_orden)->first();

        $total=$this->totalcart($orden);

        


      if (Sentinel::check()) {

        $user_id = Sentinel::getUser()->id;

       


      }else{

        $user_id= \Session::get('iduser');

       

      }




        $direccion=AlpDirecciones::where('id', $orden->id_address)->first();

        $date = Carbon::now();

        $dias=1;
        

        $fecha_entrega=$date->addDays($dias)->format('d-m-Y');

        $date = Carbon::now();

        $fecha_envio=$date->addDays($dias)->format('Y-m-d');


        $role=RoleUser::select('role_id')->where('user_id', $user_id)->first();

        $cliente=AlpClientes::where('id_user_client', $user_id)->first();

      

         $envio=$this->enviopago($orden);


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
         // 'fecha_envio' => $date->addDays($ciudad_forma->dias)->format('Y-m-d'),
          'fecha_envio' => $fecha_envio,
          'costo' => $envio, 
          'costo_base' => $envio_base, 
          'costo_impuesto' => $envio_impuesto, 
          'estatus' => 1, 
          'id_user' =>$user_id                   

        );

        $envio=AlpEnvios::create($data_envio);

        $data_envio_history = array(
          'id_envio' => $envio->id, 
          'estatus_envio' => 1, 
          'nota' => 'Envio Generar Pedido', 
          'id_user' =>$user_id                   

        );

        AlpEnviosHistory::create($data_envio_history);

        $comision_mp=$configuracion->comision_mp/100;
        $retencion_fuente_mp=$configuracion->retencion_fuente_mp/100;
        $retencion_iva_mp=$configuracion->retencion_iva_mp/100;
        $retencion_ica_mp=$configuracion->retencion_ica_mp/100;

       // dd($orden);

        if ($tipo=='credit_card') {

         
          $data_update = array(
          'estatus' =>$estatus_orden, 
          'estatus_pago' =>$estatus_pago,
          'comision_mp' =>(($orden->monto_total*$comision_mp)+($orden->monto_total*$comision_mp*0.19)),
          'retencion_fuente_mp' =>($orden->monto_total-$orden->monto_impuesto)*$retencion_fuente_mp,
          'retencion_iva_mp' =>$orden->monto_impuesto*$retencion_iva_mp,
          'retencion_ica_mp' =>($orden->monto_total-$orden->monto_impuesto)*$retencion_ica_mp
           );

        }elseif($tipo=='pse'){

          $comision_mp=$configuracion->comision_mp_pse/100;

          $data_update = array(
          'estatus' =>$estatus_orden, 
          'estatus_pago' =>$estatus_pago,
          'comision_mp' =>(($orden->monto_total*$comision_mp)+($orden->monto_total*$comision_mp*0.19)),
          'retencion_fuente_mp' =>0,
          'retencion_iva_mp' =>0,
          'retencion_ica_mp' =>0
           );

        }elseif($tipo=='baloto'){


          $comision_mp=$configuracion->comision_mp_baloto/100;

          $data_update = array(
          'estatus' =>$estatus_orden, 
          'estatus_pago' =>$estatus_pago,
          'comision_mp' =>(($orden->monto_total*$comision_mp)+($orden->monto_total*$comision_mp*0.19)),
          'retencion_fuente_mp' =>0,
          'retencion_iva_mp' =>0,
          'retencion_ica_mp' =>0
           );

        }else{

          $data_update = array(
          'estatus' =>$estatus_orden, 
          'estatus_pago' =>$estatus_pago,
          'comision_mp' =>(($orden->monto_total*$comision_mp)+($orden->monto_total*$comision_mp*0.19)),
          'retencion_fuente_mp' =>0,
          'retencion_iva_mp' =>0,
          'retencion_ica_mp' =>0
           );

        }

        //dd($data_update);


        $cupones=AlpOrdenesDescuento::where('id_orden', $carrito)->get();

        foreach ($cupones as $cupon) {
          
          $c=AlpOrdenesDescuento::where('id', $cupon->id)->first();

          $data_cupon = array('id_orden' => $orden->id, 'aplicado'=>'1' );

          $c->update($data_cupon);

        }


         $orden->update($data_update);

         $data_pago = array(
          'id_orden' => $orden->id, 
          'id_forma_pago' => $orden->id_forma_pago, 
          'id_estatus_pago' => $estatus_pago, 
          'monto_pago' => $total, 
          'json' => json_encode($json_pago), 
          'id_user' => $user_id, 
        );

         AlpPagos::create($data_pago);


         $data_history = array(
              'id_orden' => $orden->id, 
             'id_status' => $estatus_orden, 
              'notas' => 'Orden procesada', 
             'id_user' => 1
          );

        $history=AlpOrdenesHistory::create($data_history);


         //se limpian las sessiones

         \Session::forget('cart');
         \Session::forget('orden');
         \Session::forget('cr');
         \Session::forget('direccion');
         \Session::forget('envio');

         return array('id_orden' => $orden->id, 'fecha_entrega' => $fecha_entrega   );

  
    }








    private function total()
    {
       $cart= \Session::get('cart');

      // dd($cart);

        $carrito= \Session::get('cr');

      $total=0;


      $total_descuentos=0;

      foreach($cart as $row) {

        if (isset($row->id)) {

           $total=$total+($row->cantidad*$row->precio_oferta);

        }

      }

       return $total-$total_descuentos;
      
    }




     public function notascompra(Request $request)
    {

      if (!\Session::has('cart')) {
        \Session::put('cart', array());


      }


      $input=$request->all();


      $input['notas_orden']=strip_tags($input['notas_orden']);



      $id_almacen=1;

      $cart=\Session::get('cart');


      $cart['notas_orden']=$input['notas_orden'];



      \Session::put('cart', $cart);

      return 1;
      
    }





 private function combos()
    {

      $c=AlpProductos::where('tipo_producto', '2')->get();
      
      $inventario=$this->inventario();

      $combos = array();

      foreach ($c as $co) {

        $ban=0;
        
        $lista=AlpCombosProductos::select('alp_combos_productos.*', 'alp_productos.slug as slug', 'alp_productos.nombre_producto as nombre_producto', 'alp_productos.imagen_producto as imagen_producto')
        ->join('alp_productos', 'alp_combos_productos.id_producto', '=', 'alp_productos.id')
        ->whereNull('alp_productos.deleted_at')
        ->where('id_combo', $co->id)
        ->get();

        foreach ($lista as $l) {

            if (isset($inventario[$l->id_producto])) {
                
                if($inventario[$l->id_producto]>$l->cantidad){

                }else{

                $ban=1;

                }

            }else{

                $ban=1;
            }
            
        }


        if ($ban==0) {

            $combos[$co->id]=$lista;
        }

      }

      return $combos;
    }






    private function getAlmacen(){

      $tipo=0;

      $id_almacen=null;

      $cart=\Session::get('cart');
  
          if (isset($cart['id_cliente'])) {

         #   dd($cart['id_cliente']);
  
              $user_id = $cart['id_cliente'];
              
              $usuario=User::where('id', $user_id)->first();
  
              $user_cliente=User::where('id', $user_id)->first();
              
              $role=RoleUser::select('role_id')->where('user_id', $user_id)->first();
              
               $d = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
                ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
                ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
                ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
                ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
                ->where('alp_direcciones.id_client', $user_id)
                ->where('alp_direcciones.id', '=', $cart['id_direccion'])
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
                
                $tipo=0;
                
              if ($role->role_id=='14') {
  
                $tipo=1;
  
              }
  
  
                  if ($d->id_barrio==0) {

                       $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')
                      ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')
                      ->where('alp_almacenes.tipo_almacen', '=', $tipo)
                      ->where('alp_almacen_despacho.id_city', $d->city_id)
                      ->where('alp_almacenes.estado_registro', '=', '1')
                      ->first();
  
                  }else{
                    
                       $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')
                      ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')
                      ->where('alp_almacenes.tipo_almacen', '=', $tipo)
                      ->where('alp_almacen_despacho.id_barrio', $d->id_barrio)
                      ->where('alp_almacenes.estado_registro', '=', '1')
                      ->first();
  
                      if (isset($ad->id)) {
  
                          # code...
  
                      }else{
  
                          $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')
                          ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')
                          ->where('alp_almacenes.tipo_almacen', '=', $tipo)
                          ->where('alp_almacen_despacho.id_city', $d->city_id)
                          ->where('alp_almacenes.estado_registro', '=', '1')
                          ->first();
  
                      }
  
                  }
  
                  
                  if (isset($ad->id)) {
  
                  # code...
  
                  }else{
  
                    
                    $c=City::where('id', $d->city_id)->first();
                    
                    $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')
                  ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')
                  ->where('alp_almacenes.tipo_almacen', '=', $tipo)
                  ->where('alp_almacen_despacho.id_city', '0')
                  ->where('alp_almacen_despacho.id_state', $c->state_id)
                  ->where('alp_almacenes.estado_registro', '=', '1')
                  ->first();
                  
                    if (isset($ad->id)) {
  
                    }else{
                      
                      $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')
                    ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')
                    ->where('alp_almacenes.tipo_almacen', '=', $tipo)
                    ->where('alp_almacen_despacho.id_city', '0')
                    ->where('alp_almacen_despacho.id_state', '0')->where('alp_almacenes.estado_registro', '=', '1')->first();
                    
                    }
  
                    
                  }
  
                  
                  if (isset($ad->id)) {
  
                    
                    $almacen=AlpAlmacenes::where('id', $ad->id_almacen)->first();
  
                    
                    $id_almacen=$almacen->id;
  
                    # code...
  
                  }else{
  
                    
                     /*$almacen=AlpAlmacenes::where('defecto', '1')->first();
  
                     
                      if (isset($almacen->id)) {
  
                        $id_almacen=$almacen->id;
  
                      }else{
  
                        $id_almacen='1';
  
                      }*/
  
                      
                  }
  
                  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
              }else{
  
                
                $almacen=AlpAlmacenes::where('defecto', '1')->first();
  
                
                  if (isset($almacen->id)) {
  
                    $id_almacen=$almacen->id;
  
                  }else{
  
                    $id_almacen='1';
  
                  }
  
                     
  
              }
  
              
          }
  
          
         // dd($id_almacen);
         # echo  json_encode('id_almacen : '.$id_almacen);
          
        return $id_almacen;
  
        
      }













}