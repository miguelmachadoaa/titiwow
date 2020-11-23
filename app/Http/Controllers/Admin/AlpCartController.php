<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Models\AlpProductos;
use App\Models\AlpDirecciones;
use App\Models\AlpCategorias;
use App\Models\AlpCategoriasProductos;
use App\Models\AlpInventario;
use App\Models\AlpTDocumento;
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
use App\Models\AlpOrdenesHistory;
use App\Models\AlpEstructuraAddress;
use App\Models\AlpFeriados;
use App\Models\AlpImpuestos;

use App\Models\AlpAlmacenes;
use App\Models\AlpAlmacenDespacho;
use App\Models\AlpAlmacenFormaPago;
use App\Models\AlpAlmacenFormaEnvio;
use App\Models\AlpAlmacenProducto;
use App\Models\AlpAnchetaMensaje;

use App\Models\AlpSaldo;
use App\Models\AlpRolenvio;

use App\Models\AlpPromociones;
use App\Models\AlpPromocionesRegalo;
use App\Models\AlpPromocionesCategorias;

use App\Models\AlpOrdenesDescuentoIcg;
use App\Models\AlpConsultaIcg;

use App\Http\Requests\AddressRequest;

use App\Country;
use App\Barrio;
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

use App\Models\AlpCuponesCategorias;
use App\Models\AlpCuponesEmpresa;
use App\Models\AlpCuponesProducto;
use App\Models\AlpCuponesRol;
use App\Models\AlpCuponesMarcas;
use App\Models\AlpCuponesUser;
use App\Models\AlpOrdenesDescuento;
use App\Models\AlpCombosProductos;
use App\Models\AlpCuponesAlmacen;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;

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


        if (!\Session::has('envio')) {
          \Session::put('envio', '1');
        }


        if (!\Session::has('direccion')) {
          \Session::put('direccion', '0');
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

      //$fecha=$this->getFechaEntrega('2', '62');

      $states=State::where('config_states.country_id', '47')->get();

      $mensaje_promocion=$this->addPromocion();

      $cart=$this->reloadCart();

      //dd($cart);

      $combos=$this->combos();

      $configuracion=AlpConfiguracion::where('id', '1')->first();

      $total=$this->total();

      $inv=$this->inventario();

      $id_almacen=$this->getAlmacenCart();

     // echo $id_almacen;

      $descuento='1'; 

      $precio = array();


         if (\Session::has('cr')) {
          
          $carrito= \Session::get('cr');

          $cupones=AlpOrdenesDescuento::where('id_orden', $carrito)->get();

            foreach ($cupones as $cupon) {
              
              $c=AlpOrdenesDescuento::where('id', $cupon->id)->first();

              if (isset($c->id)) {

              $c->delete();
                # code...
              }

            }

        }

        $descuento=1;


      $productos = DB::table('alp_productos')->select('alp_productos.*')
        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
        ->where('alp_almacenes.id', '=', $id_almacen)
        ->whereNull('alp_almacen_producto.deleted_at')
        ->whereNull('alp_productos.deleted_at')
        ->where('alp_productos.sugerencia','=', 1)
        ->where('alp_productos.estado_registro','=',1)
        ->groupBy('alp_productos.id')
        ->orderBy('order', 'asc')
        ->inRandomOrder()
        ->orderBy('updated_at', 'desc')
        ->limit(6)->get();


        $prods=$this->addOferta($productos);

        $inventario=$this->inventario();

        $url=secure_url('cart/show');

        $almacen=AlpAlmacenes::where('id', $id_almacen)->first();



      return view('frontend.cart', compact('cart', 'total', 'configuracion', 'states', 'inv','productos', 'prods', 'descuento', 'combos', 'inventario','url', 'almacen', 'mensaje_promocion'));
    }

    public function detalle()
    {

      $cart= \Session::get('cart');

      $view= View::make('frontend.order.detalle', compact('cart'));

      $data=$view->render();

      return $data;

    }


     public function gracias($id)
    {

      $id=$id/1024;



      $compra =  DB::table('alp_ordenes')->select('alp_ordenes.*','users.first_name as first_name','users.last_name as last_name' ,'users.email as email','alp_formas_envios.nombre_forma_envios as nombre_forma_envios','alp_formas_envios.descripcion_forma_envios as descripcion_forma_envios','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago','alp_formas_pagos.descripcion_forma_pago as descripcion_forma_pago','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.doc_cliente as doc_cliente')
              ->join('users','alp_ordenes.id_cliente' , '=', 'users.id')
              ->join('alp_clientes','alp_ordenes.id_cliente' , '=', 'alp_clientes.id_user_client')
              ->join('alp_formas_envios','alp_ordenes.id_forma_envio' , '=', 'alp_formas_envios.id')
              ->join('alp_formas_pagos','alp_ordenes.id_forma_pago' , '=', 'alp_formas_pagos.id')
              ->where('alp_ordenes.id', $id)->first();


          $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug','alp_productos.presentacion_producto as presentacion_producto')
            ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
            ->where('alp_ordenes_detalle.id_orden', $id)
            ->whereNull('alp_ordenes_detalle.deleted_at')
            ->get();

            $pago=AlpPagos::where('id_orden', $id)->first();

          // dd($pago);

          if ($compra->id_forma_pago=='3' || $compra->id_forma_pago=='1') {

            $payment=null;
            # code...
          }else{

            $payment=json_decode($pago->json);

          }



          $envio=AlpEnvios::where('id_orden', $id)->first();

          $valor_impuesto=AlpImpuestos::where('id', '1')->first();

          if ($envio->costo>0) {
           
             $envio_base=$envio->costo/(1+$valor_impuesto->valor_impuesto);

          $envio_impuesto=$envio_base*$valor_impuesto->valor_impuesto;


          }else{

            $envio_base=0;

            $envio_impuesto=0;

          }
      

          $fecha_entrega=$envio->fecha_envio;

          $states=State::where('config_states.country_id', '47')->get();

          $configuracion = AlpConfiguracion::where('id','1')->first();

          $user_cliente=User::where('id', $compra->id_cliente)->first();

          $aviso_pago='';

          $metodo='';


 
          $estatus_aviso='success';

          if ($compra->id_forma_pago=='3') {

            $estatus_aviso='pending';

            $aviso_pago="Hemos recibido su orden y estaremos revisando su pago, en cuanto sea aprobado Le llegará un email con la descripción de su pago. ¡Muchas gracias por su Compra!";

            $metodo='Descuento Nomina';
            
          }elseif($compra->id_forma_pago=='1'){

            $estatus_aviso='pending';

            $aviso_pago="Hemos recibido su orden  en cuanto sea aprobado Le llegará un email con la descripción de su pedido. ¡Muchas gracias por su Compra!";

            $metodo='Contraentrega';

          }else{



          if (isset($payment->response->payment_method_id)) {


            if ($payment->response->payment_method_id=='pse') {


               $estatus_aviso='warning';

              $aviso_pago="Estamos verificando su pago, una vez sea confirmado, Le llegará un email con la descripción de su pedido. En caso de existir algún error en el pago le invitamos a Mis Compras desde su perfil para intentar pagar nuevamente";

              $metodo=$payment->response->payment_method_id;
              
            }


            if ($payment->response->payment_type_id=='ticket'  ) {

              $metodo=$payment->response->payment_method_id;


               $estatus_aviso='warning';

                
              $aviso_pago="Hemos procesado su orden satisfactoriamente, Su id para realizar el deposito en efectivo es <h4> ".$payment->response->id." </h4>. Las indicaciones para finalizar su pago puede seguirlas en este enlace <a target='_blank' href='".$payment->response->transaction_details->external_resource_url."' >Ticket</a>. Tiene 72 Horas para realizar el pago, o su orden sera cancelada. ¡Muchas gracias por su Compra!";
              
            }


            if ($payment->response->payment_type_id=='credit_card' ) {


               $estatus_aviso='warnsing';
                
              $aviso_pago="Hemos recibido su pago satisfactoriamente, una vez sea confirmado, Le llegará un email con la descripción de su pago. ¡Muchas gracias por su Compra!";

              $metodo=$payment->response->payment_type_id;
              
            }

            //$metodo=$payment->response->payment_method_id;

          }

        }

          
        return view('frontend.order.gracias', compact('compra', 'detalles', 'fecha_entrega', 'states', 'aviso_pago', 'payment', 'estatus_aviso', 'metodo', 'envio', 'envio_base', 'envio_impuesto'));

    }




    public function orderRapipago(){

      $path='uploads/productos/';

      $dir = opendir($path);

      $files = array();

      while ($current = readdir($dir)){

          if( $current != "." && $current != "..") {

              if(is_dir($path.$current)) {

                  //showFiles($path.$current.'/');

              }

              else {

                  echo $current.'<br>';


                  $imageSizeArray = getimagesize($path.$current);
                $imageTypeArray = $imageSizeArray[2];

                if (in_array($imageTypeArray , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP))) {


                   $image = \Image::make(file_get_contents(public_path().'/'.$path.$current));
                   
                   $image->resize(200,200);
                   // Guardar
                   $image->save($path.'150/'.$current);

                  
                }


              }

          }

      }

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

    $compra =  DB::table('alp_ordenes')->select('alp_ordenes.*','users.first_name as first_name','users.last_name as last_name' ,'users.email as email','alp_formas_envios.nombre_forma_envios as nombre_forma_envios','alp_formas_envios.descripcion_forma_envios as descripcion_forma_envios','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago','alp_formas_pagos.descripcion_forma_pago as descripcion_forma_pago','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.doc_cliente as doc_cliente')
            ->join('users','alp_ordenes.id_cliente' , '=', 'users.id')
            ->join('alp_clientes','alp_ordenes.id_cliente' , '=', 'alp_clientes.id_user_client')
            ->join('alp_formas_envios','alp_ordenes.id_forma_envio' , '=', 'alp_formas_envios.id')
            ->join('alp_formas_pagos','alp_ordenes.id_forma_pago' , '=', 'alp_formas_pagos.id')
            ->where('alp_ordenes.id', 85)->first();
     $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.referencia_producto as referencia_producto','alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto' ,'alp_productos.slug as slug','alp_productos.presentacion_producto as presentacion_producto')
              ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
              ->where('alp_ordenes_detalle.id_orden', 85)->get();

     $states=State::where('config_states.country_id', '47')->get();

     $aviso_pago='esto es una prueba para el diseño de procesar';

    return view('frontend.order.procesar', compact('compra', 'detalles', 'fecha_entrega', 'states', 'aviso_pago'));


    }


    public function getPse(Request $request)
    {

      $carrito= \Session::get('cr');

      $id_orden= \Session::get('orden');

      $orden=AlpOrdenes::where('id', $id_orden)->first();


      $envio=$this->envio();


      $valor_impuesto=AlpImpuestos::where('id', '1')->first();

      if ($envio>0) {
       
         $envio_base=$envio/(1+$valor_impuesto->valor_impuesto);

      $envio_impuesto=$envio_base*$valor_impuesto->valor_impuesto;


      }else{

        $envio_base=0;

        $envio_impuesto=0;

      }


      if (Sentinel::check()) {

        

       $user = Sentinel::getUser();

       activity($user->full_name)
                    ->performedOn($user)
                    ->causedBy($user)
                    ->withProperties($request->all())->log('Get Pse');

        $user_id = Sentinel::getUser()->id;
      }else{

        $user_id= \Session::get('iduser');
      }


      

      
      
      


      $total=$orden->monto_total+$envio;

      $impuesto=$orden->monto_impuesto+$envio_impuesto;
      
      $configuracion = AlpConfiguracion::where('id', '1')->first();

      $comision_mp=$configuracion->comision_mp_pse/100;

      $data_update = array(
        
          'comision_mp' =>(($orden->monto_total*$comision_mp)+($orden->monto_total*$comision_mp*0.19)),
         
           );

      $orden->update($data_update);


        $mp = new MP();

        if ($configuracion->mercadopago_sand=='1') {
          
          $mp::sandbox_mode(TRUE);

        }

        if ($configuracion->mercadopago_sand=='2') {
          
          $mp::sandbox_mode(FALSE);

        }

        MP::setCredenciales($configuracion->id_mercadopago, $configuracion->key_mercadopago);

        $net_amount=$total-$impuesto;


                       $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.descripcion_corta as descripcion_corta','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug','alp_productos.presentacion_producto as presentacion_producto')
          ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
          ->where('alp_ordenes_detalle.id_orden', $orden->id)->get();

      $det_array = array();

      $total_descuentos=0;



      $descuentos=AlpOrdenesDescuento::where('id_orden', $carrito)->get();

            foreach ($descuentos as $pago) {

              $total_descuentos=$total_descuentos+$pago->monto_descuento;

            }



      foreach ($detalles as $d ) {


        $det_array[]= array(

                "id"          => $d->id_producto,
                "title"       => $d->nombre_producto,
                "description" => $d->descripcion_corta,
                "quantity"    => (int)number_format($d->cantidad, 0, '.', ''),
                "unit_price"  => intval($d->precio_unitario),
                );

       
      }

        $preference_data = '{
           "payer": {
               "email": "'.$request->email.'",
               "entity_type": "individual",
               "identification": {
                   "type": "'.$request->id_type_doc.'",
                   "number": "'.$request->doc_cliente.'"
               }
           },
           "description": "Pago de orden Nro. '.$orden->id.'",
           "external_reference": "'.$orden->referencia.'",
           "callback_url": "'.secure_url('/order/pse').'",
           "additional_info": {
               "ip_address": "'.request()->ip().'",
               "items":'.json_encode($det_array).'
           },
           "payment_method_id": "pse",
           "transaction_amount": '.(float)number_format($total, 2, '.', '').',
           "transaction_details": {
               "financial_institution": '.$request->id_fi.'
           },
           "net_amount": '.(float)number_format($net_amount, 2, '.', '').',
           "taxes":[{
                               "value": '.(float)number_format($impuesto, 2, '.', '').',
                               "type": "IVA"
                       }]
       }' ;

       $pse=[];

       
        try {

          $pse = MP::post("/v1/payments",$preference_data);
          
        } catch (Exception $e) {
          
        }

          

          //$user_id = Sentinel::getUser()->id;
         

          if (isset($pse['response']['id'])) {

             $data_pago = array(
              'id_orden' => $orden->id, 
              'id_forma_pago' => $orden->id_forma_pago, 
              'id_estatus_pago' => '4', 
              'monto_pago' => '0', 
              'json' => json_encode($pse), 
              'id_user' => $user_id
            );


            AlpPagos::create($data_pago);


            \Session::put('pse', $pse['response']['id']);

            return $pse['response']['transaction_details']['external_resource_url'];

          }else{

            return 'false';

          }

    }



    public function orderPse(Request $request)
    {

      $input=$request->all();


      $user = Sentinel::getUser();

      if (isset($user->id)) { activity($user->full_name)
                    ->performedOn($user)
                    ->causedBy($user)
                    ->withProperties($input)->log('Order Pse, captura de pago con pse');
        # code...
      }else{

         activity()->withProperties($input)->log('Order Pse, captura de pago con pse');
      }

           


       if (\Session::has('pse')) {

        $id_pago=\Session::get('pse');

        $configuracion = AlpConfiguracion::where('id', '1')->first();

            $mp = new MP();

            if ($configuracion->mercadopago_sand=='1') {
          
              $mp::sandbox_mode(TRUE);

            }

            if ($configuracion->mercadopago_sand=='2') {
              
              $mp::sandbox_mode(FALSE);

            }

            MP::setCredenciales($configuracion->id_mercadopago, $configuracion->key_mercadopago);

            $input = MP::get("/v1/payments/".$id_pago);

       }



      if (Sentinel::check()) {

        $user_id = Sentinel::getUser()->id;

      }else{

        $user_id= \Session::get('iduser');

      }


      if ($user_id) {

        // 1.- eststus orden, 2.- estatus pago, 3 json pedido 

        $data=$this->generarPedido('8', '4', $input, 'pse');

        
        if (isset($data['id_orden'])) {
          # code...
        }else{

            if ($data==0) {
          
              return redirect('order/detail')->withInput()->with('error', trans('Error al procesar su orden, por favor intente nuevamente.'));

            }

        }
       

       // $data=$this->generarPedido('8', '4', $input, 'credit_card');

        $id_orden=$data['id_orden'];

        $fecha_entrega=$data['fecha_entrega'];
       
        $compra =  DB::table('alp_ordenes')->select('alp_ordenes.*','users.first_name as first_name','users.last_name as last_name' ,'users.email as email','alp_formas_envios.nombre_forma_envios as nombre_forma_envios','alp_formas_envios.descripcion_forma_envios as descripcion_forma_envios','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago','alp_formas_pagos.descripcion_forma_pago as descripcion_forma_pago','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.doc_cliente as doc_cliente')
            ->join('users','alp_ordenes.id_cliente' , '=', 'users.id')
            ->join('alp_clientes','alp_ordenes.id_cliente' , '=', 'alp_clientes.id_user_client')
            ->join('alp_formas_envios','alp_ordenes.id_forma_envio' , '=', 'alp_formas_envios.id')
            ->join('alp_formas_pagos','alp_ordenes.id_forma_pago' , '=', 'alp_formas_pagos.id')
            ->where('alp_ordenes.id', $id_orden)->first();

        $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug','alp_productos.presentacion_producto as presentacion_producto')
          ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
          ->where('alp_ordenes_detalle.id_orden', $id_orden)->get();

         $states=State::where('config_states.country_id', '47')->get();

         $configuracion = AlpConfiguracion::where('id','1')->first();

          $user_cliente=User::where('id', $user_id)->first();

          $texto='Se ha creado la siguiente orden '.$compra->id.' y esta a espera de aprobacion  ';


      //  Mail::to($user_cliente->email)->send(new \App\Mail\CompraRealizada($compra, $detalles, $fecha_entrega));

      //  Mail::to($configuracion->correo_sac)->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega));

           if ($compra->id_forma_envio!=1) {

              $formaenvio=AlpFormasenvio::where('id', $compra->id_forma_envio)->first();

              Mail::to($formaenvio->email)->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega, 1));

              Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega, 1));
                
            }
          
          $estatus_aviso='warning';

          $aviso_pago="Estamos verificando su pago, una vez sea confirmado, Le llegará un email con la descripción de su pedido. En caso de existir algún error en el pago le invitamos a Mis Compras desde su perfil para intentar pagar nuevamente";


          $aviso_pago = array(
            'tipo' => 'yellow', 
            'medio' => 'PSE', 
            'mensaje' => 'Estamos verificando su pago, una vez sea confirmado, Le llegará un email con la descripción de su pedido. En caso de existir algún error en el pago le invitamos a Mis Compras desde su perfil para intentar pagar nuevamente', 
          );

          $idc=$id_orden*1024;


           





          return redirect('cart/'.$idc.'/gracias?pago=pendiente');
          
        #  return view('frontend.order.procesar_completo', compact('compra', 'detalles', 'fecha_entrega', 'states', 'aviso_pago', 'estatus_aviso'));
        

      }else{

          return redirect('login');
      }

}


    public function notificacion(Request $request)
    {
      
      $input=$request->all();


            activity()->withProperties($input)->log('Notificacion mercadopago');

      $configuracion = AlpConfiguracion::where('id', '1')->first();

        $mp = new MP();
        if ($configuracion->mercadopago_sand=='1') {
          
          $mp::sandbox_mode(TRUE);

        }

        if ($configuracion->mercadopago_sand=='2') {
          
          $mp::sandbox_mode(FALSE);

        }

        MP::setCredenciales($configuracion->id_mercadopago, $configuracion->key_mercadopago);

        $pago=AlpPagos::where('json', 'like', '%'.$input['data_id'].'%')->first();

        //dd($pago);

        if (isset($pago->id)) {


          $orden=AlpOrdenes::where('id', $pago->id_orden)->first();

        

          try {

            $pse = MP::get("/v1/payments/".$input['data_id']);
            
          } catch (MercadoPagoException $e) {

            $pse = $input;
            
          }

          if (  isset($pse['response']['status'])) {

              if ( $pse['response']['status']=='rejected' or $pse['response']['status']=='cancelled' ) 
              {
                    $data_update = array(
                      'estatus' =>4, 
                      'estatus_pago' =>3,
                       );

                     $orden->update($data_update);

                      $data_history = array(
                          'id_orden' => $orden->id, 
                         'id_status' => '4', 
                          'notas' => 'Notificacion Mercadopago', 
                         'id_user' => 1
                      );

                        $history=AlpOrdenesHistory::create($data_history);
               
              }

              if ( $pse['response']['status']=='approved' ) 
              {

                    $envio=AlpEnvios::where('id_orden', $pago->id_orden)->first();

                      $data_update = array(
                        'estatus' =>1, 
                        'estatus_pago' =>2,
                         );

                       $orden->update($data_update);

                       $data_history = array(
                            'id_orden' => $orden->id, 
                           'id_status' => '1', 
                            'notas' => 'Notificacion Mercadopago', 
                           'id_user' => 1
                        );


                         $data_pago = array(
                          'id_orden' => $orden->id, 
                          'id_forma_pago' => $orden->id_forma_pago, 
                          'id_estatus_pago' => '2', 
                          'monto_pago' => $orden->monto_total, 
                          'json' => json_encode($pse), 
                          'id_user' => '1'
                        );

                       AlpPagos::create($data_pago);

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

              if ($compra->id_forma_envio!=1) {

                  $formaenvio=AlpFormasenvio::where('id', $compra->id_forma_envio)->first();

                  Mail::to($formaenvio->email)->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega,1));

                  Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega,1));


                }

                Mail::to($compra->email)->send(new \App\Mail\CompraRealizada($compra, $detalles, $envio->fecha_envio));

                Mail::to($configuracion->correo_sac)->send(new \App\Mail\CompraSac($compra, $detalles, $envio->fecha_envio));


             Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\CompraRealizada($compra, $detalles, $envio->fecha_envio));

                Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\CompraSac($compra, $detalles, $envio->fecha_envio));
          }

              if ( $pse['response']['status']=='in_process' || $pse['response']['status']=='pending' ) 
              {
                    $data_update = array(
                      'estatus' =>8, 
                      'estatus_pago' =>4,
                       );

                     $orden->update($data_update);

                      $data_history = array(
                          'id_orden' => $orden->id, 
                         'id_status' => '8', 
                          'notas' => 'Notificacion Mercadopago', 
                         'id_user' => 1
                      );

                        $history=AlpOrdenesHistory::create($data_history);
               
              }

          }

         // $pse = MP::get("/v1/payments/".$input['data_id']);

          $data_pago = array(
            'id_orden' => $pago->id_orden, 
            'id_forma_pago' => $pago->id_forma_pago, 
            'id_estatus_pago' => 4, 
            'monto_pago' => $pago->monto_pago, 
            'json' => json_encode($pse), 
            'id_user' => '0' 
          );

         AlpPagos::create($data_pago);

         return response('true', 200);

          
        }else{

         return response('true', 201);

        }

    }


  public function orderCreditcard(Request $request)
    {


      $user = Sentinel::getUser();

      if (isset($user->id)) { 
        activity($user->full_name)
                    ->performedOn($user)
                    ->causedBy($user)
                    ->withProperties($request->all())
                    ->log('orderCreditcard captura de pago con creditcard');
        # code...
      }else{

       activity()->withProperties($request->all())->log('orderCreditcard captura de pago con creditcard');
      }

      


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


        $user_id = Sentinel::getUser()->id;

        $user_cliente=User::where('id', $user_id)->first();

        $datos_cliente=AlpClientes::where('id_user_client', $user_id)->first();

        $configuracion = AlpConfiguracion::where('id', '1')->first();

        MP::setCredenciales($configuracion->id_mercadopago, $configuracion->key_mercadopago);


        $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.descripcion_corta as descripcion_corta','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug','alp_productos.presentacion_producto as presentacion_producto')
          ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
          ->where('alp_ordenes_detalle.id_orden', $orden->id)->get();

          $envio=$this->envio();


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
          //"binary_mode" => true,
          "description" => 'Pago de orden: '.$orden->id,
          "installments" => intval($request->installments),
          "external_reference"=> "".$orden->referencia."",
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

            return redirect('order/detail')->with('aviso', $aviso);

          }

           /// $data=$this->generarPedido('1', '2', $preference, 'credit_card');
           $data=$this->generarPedido('8', '4', $preference, 'credit_card');


           if (isset($data['id_orden'])) {
          # code...
          }else{

              if ($data==0) {
            
                return redirect('order/detail')->withInput()->with('error', trans('Error al procesar su orden, por favor intente nuevamente.'));

              }

          }




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


    public function orderDetail()
    {

      $configuracion=AlpConfiguracion::where('id', '1')->first();
      
      $carrito= \Session::get('cr');

      $cart=$this->addPromocion();

      $cart=$this->reloadCart();

      $total=$this->total();

      $total_base=$this->precio_base();

      $impuesto=$this->impuesto();

      $id_almacen=$this->getAlmacenCart();

      $almacen=AlpAlmacenes::where('id', $id_almacen)->first();

      //dd($total);

      if ($total<0 ){

        return redirect('cart/show');

      }


      if (!isset($almacen->id) ){

        //dd($almacen);

        return redirect('cart/show');

      }



      foreach ($cart as $vcart) {

        if (isset($vcart->id)) {
          if ($vcart->disponible==0) {

          if (isset($vcart->promocion)) {
            # code...
          }else{

              return redirect('cart/show');
          }
          
        }
        }

        
        # code...
      }


      if (Sentinel::check()) {

        $user = Sentinel::getUser();

        activity($user->full_name)
                    ->performedOn($user)
                    ->causedBy($user)
                    ->withProperties($cart)
                    ->log('Orden Detail');


        $cupo_icg=$this->consultaIcg();
       // 
      // $cupo_icg=0;

        $user_id = Sentinel::getUser()->id;

        $usuario=User::where('id', $user_id)->first();

        $user_cliente=User::where('id', $user_id)->first();

        $role=RoleUser::select('role_id')->where('user_id', $user_id)->first();


        $r=Roles::where('id', $role->role_id)->first();

       // dd($r);


          if ($total<$almacen->minimo_compra ){

            $aviso='El monto mínimo de compra es de $'.number_format($almacen->minimo_compra ,0,",",".");

            $cart=$this->reloadCart();

            $configuracion=AlpConfiguracion::where('id', '1')->first();

            $total=$this->total();

            $inv=$this->inventario();

            return view('frontend.cart', compact('cart', 'total', 'configuracion', 'inv', 'aviso'));

            return redirect('cart/show');

          }



        $direcciones = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
          ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
          ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
          ->where('alp_direcciones.id_client', $user_id)->get();


          $d = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
          ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
          ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
          ->where('alp_direcciones.id_client', $user_id)
          ->where('alp_direcciones.default_address', '=', '1')
          ->first();

          if (isset($d->id)) {
            
              \Session::put('direccion', $d->id);

          }else{

              $d = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
            ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
            ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
            ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
            ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
            ->where('alp_direcciones.id_client', $user_id)
            ->first();

              if (isset($d->id)) {
            
                  \Session::put('direccion', $d->id);

              }else{

                return redirect('misdirecciones')->withInput()->with('error', trans('Debes crear una dirección para continuar con el proceso.'));


              }

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
            ->where('alp_rol_envio.id_rol', $role->role_id)->get();

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
              ->where('alp_rol_pago.id_rol', $role->role_id)->get();

          }
         

          $countries = Country::all();

          $inv = $this->inventario();


          $pagos=AlpPagos::where('id_orden', $carrito)->get();

          $total_pagos=0;

          foreach ($pagos as $pago) {

            $total_pagos=$total_pagos+$pago->monto_pago;

          }

          $total_descuentos=0;

         // dd($carrito);

            $descuentos=AlpOrdenesDescuento::where('id_orden','=', $carrito)->get();

            foreach ($descuentos as $pago) {

              $total_pagos=$total_pagos+$pago->monto_descuento;

              $total_descuentos=$total_descuentos+$pago->monto_descuento;

            }

            $descuentosIcg=AlpOrdenesDescuentoIcg::where('id_orden','=', $carrito)->get();

            $total_descuentos_icg=0;

            foreach ($descuentosIcg as $pagoi) {

              $total_pagos=$total_pagos+$pagoi->monto_descuento;

              $total_descuentos_icg=$total_descuentos_icg+$pagoi->monto_descuento;

            }




          //dd($descuentos);

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

            //echo json_encode($cart);

           $mp = new MP();

           if ($configuracion->mercadopago_sand=='1') {
          
          $mp::sandbox_mode(TRUE);

        }

        if ($configuracion->mercadopago_sand=='2') {
          
          $mp::sandbox_mode(FALSE);

        }



          MP::setCredenciales($configuracion->id_mercadopago, $configuracion->key_mercadopago);

          $preference = MP::post("/checkout/preferences",$preference_data);

         // dd($preference);

          $this->saveOrden($preference);

          $net_amount=$total-$impuesto;

         $pse = array();

          $payment_methods = MP::get("/v1/payment_methods");

          //dd($payment_methods);

         // $payment_methods = array('response'=>array());


          $carro=AlpCarrito::where('id', $carrito)->first();

          $data_carrito = array(
            'id_user' => $user_id );

          if (isset($carro['id'])) {
             $data_carrito = array(
              'id_user' => $user_id );

            $carro->update($data_carrito);
          }

          /*actualizamos la data del carrito */

          $states=State::where('config_states.country_id', '47')->get();

          $tdocumento=AlpTDocumento::get();

          $estructura = AlpEstructuraAddress::where('estado_registro','=',1)->get();

          $labelpagos = array(
            'pse' => 'Tarjeta débito', 
            'visa' => 'Tarjeta crédito', 
            'efecty' => 'Pago en efectivo a través de Efecty', 
            'efecty' => 'Pago en efectivo a través de Efecty', 
            'davivienda' => 'Pago en efectivo a través de Davivienda', 
            'baloto' => 'Pago en efectivo a través de Baloto'
          );

        $costo_envio=$this->envio();

        $id_forma_envio= \Session::get('envio');

        $fev = AlpFormasenvio::select('alp_formas_envios.*')
          ->join('alp_almacen_formas_envio', 'alp_formas_envios.id', '=', 'alp_almacen_formas_envio.id_forma_envio')
          //->where('alp_rol_envio.id_rol', $role->role_id)
          ->where('alp_almacen_formas_envio.id_forma_envio', $id_forma_envio)
          ->where('alp_almacen_formas_envio.id_almacen', $id_almacen)
          ->whereNull('alp_almacen_formas_envio.deleted_at')
          ->first();

       // dd($fev);


          if (isset($fev->id)) {
            # code...
          }else{



              $fev = AlpFormasenvio::select('alp_formas_envios.*')
                ->join('alp_almacen_formas_envio', 'alp_formas_envios.id', '=', 'alp_almacen_formas_envio.id_forma_envio')
                //->where('alp_rol_envio.id_rol', $role->role_id)
               ->where('alp_almacen_formas_envio.id_almacen', $id_almacen)
                ->whereNull('alp_almacen_formas_envio.deleted_at')
                ->first();


                //dd($fev);

                  if (isset($fev->id)) {

                    \Session::put('envio', $fev->id);

                    $id_forma_envio=$fev->id;
                    # code...
                  }else{

                    $id_forma_envio=0;
                  }


          }

    //   echo $id_forma_envio;


        $valor_impuesto=AlpImpuestos::where('id', '1')->first();

        if ($costo_envio>0) {
         
           $envio_base=$costo_envio/(1+$valor_impuesto->valor_impuesto);

          $envio_impuesto=$envio_base*$valor_impuesto->valor_impuesto;

        }else{

         $envio_base=0;

          $envio_impuesto=0;

        }


        /*limitar forma de envio segun la hora o dias feriados */

        $express=0;


        $ciudad_forma=AlpFormaCiudad::where('id_rol', $role->role_id)->where('id_forma', '2')->where('id_ciudad', '62')->first();

        $date = Carbon::now();

        if (isset($ciudad_forma->id)) {

            $date = Carbon::now();

            $hora=$date->format('Hi');

            $hora_base=str_replace(':', '', $ciudad_forma->hora);

            if (intval($hora)>intval($hora_base)) {

              $express=1;

            }

          }else{


          }

        $feriados=AlpFeriados::feriados();


         if ($date->isSunday()) {

             $express=1;
          
          }else{

            if (isset($feriados[$date->format('Y-m-d')])) {

                $express=1;
             
            }

          }

          $saldo=$this->getSaldo();

          if (isset($saldo[$user->id])) {
            # code...
          }else{
            $saldo[$user->id]=0;
          }

          $url=secure_url('order/detail');

          //dd($url);

          return view('frontend.order.detail', compact('cart', 'total', 'direcciones', 'formasenvio', 'formaspago', 'countries', 'configuracion', 'states', 'preference', 'inv', 'pagos', 'total_pagos', 'impuesto', 'payment_methods', 'pse', 'tdocumento', 'estructura', 'labelpagos', 'total_base', 'descuentos', 'total_descuentos', 'costo_envio', 'id_forma_envio', 'envio_base', 'envio_impuesto', 'express', 'saldo', 'user','role', 'url', 'cupo_icg', 'total_descuentos_icg', 'descuentosIcg'));

         }


      }else{

        $url='order.detail';

          //return redirect('login');
        return view('frontend.order.login', compact('url'));

      }

    }


  public function orderProcesarTicket(Request $request)
    {

     
      $input=$request->all();

      if (Sentinel::check()) {

         $user = Sentinel::getUser();

       activity($user->full_name)
                    ->performedOn($user)
                    ->causedBy($user)
                    ->withProperties($input)
                    ->log('orderProcesarTicket pago con ticket');

      }

        

      $cart= \Session::get('cart');

      $carrito= \Session::get('cr');

      $id_orden= \Session::get('orden');


      $envio=$this->envio();

      $valor_impuesto=AlpImpuestos::where('id', '1')->first();

      if ($envio>0) {
       
         $envio_base=$envio/(1+$valor_impuesto->valor_impuesto);

        $envio_impuesto=$envio_base*$valor_impuesto->valor_impuesto;


      }else{

        $envio_base=0;

        $envio_impuesto=0;

      }

      $orden=AlpOrdenes::where('id', $id_orden)->first();

      $total=$orden->monto_total+$envio;

      $impuesto=$orden->monto_impuesto+$envio_impuesto;

      $net_amount=$total-$impuesto;


      if (Sentinel::check()) {

        $user_id = Sentinel::getUser()->id;

        $user_cliente=User::where('id', $user_id)->first();


      }else{

        $user_id= \Session::get('iduser');

         $user_cliente=User::where('id', $user_id)->first();

      }

        

      if ($user_id) {
      
         $configuracion = AlpConfiguracion::where('id', '1')->first();

          MP::setCredenciales($configuracion->id_mercadopago, $configuracion->key_mercadopago);

     
           $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.descripcion_corta as descripcion_corta','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug','alp_productos.presentacion_producto as presentacion_producto')
          ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
          ->where('alp_ordenes_detalle.id_orden', $orden->id)->get();


          $total_descuentos=0;


            $descuentos=AlpOrdenesDescuento::where('id_orden', $carrito)->get();

            foreach ($descuentos as $pago) {

              //$total_pagos=$total_pagos+$pago->monto_descuento;
              $total_descuentos=$total_descuentos+$pago->monto_descuento;

            }

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

               $preference_data = [
                "transaction_amount" => doubleval($orden->monto_total+$envio),
                "external_reference" =>"".$orden->referencia."",
                "description" => 'Pago de orden: '.$orden->id,
                "payment_method_id" => $request->idpago,
                  "additional_info"=> [
                    "items" => $det_array ],
                "payer" => [
                  "email"=>$user_cliente->email],
                  "additional_info"=> [
                    "items" => $det_array ],
                "net_amount"=>(float)number_format($net_amount, 2, '.', ''),
                "taxes"=>[[
                  "value"=>(float)number_format($impuesto, 2, '.', ''),
                  "type"=>"IVA"]]
              ];


              //dd($preference_data);

          
            //Log::info($preference_data);
             activity()->withProperties($preference_data)
                        ->log('intento de pago pse ');
            
            if (($orden->monto_total+$envio)>0) {
              
               $payment = MP::post("/v1/payments",$preference_data);

            }else{

              $payment = array();

            }

           




            //Log::info($payment);


            if (isset($payment['response']['id'])) {
           
          
              // 1.- eststus orden, 2.- estatus pago, 3 json pedido 
              $data=$this->generarPedido('8', '4', $payment, 'baloto');

              if (isset($data['id_orden'])) {
          # code...
                }else{

                    if ($data==0) {
                  
                      return redirect('order/detail')->withInput()->with('error', trans('Error al procesar su orden, por favor intente nuevamente.'));

                    }

                }
        

              $id_orden=$data['id_orden'];

              $fecha_entrega=$data['fecha_entrega'];


              //  $datalles=AlpDetalles::where('id_orden', $orden->id)->get();

              $compra =  DB::table('alp_ordenes')->select('alp_ordenes.*','users.first_name as first_name','users.last_name as last_name' ,'users.email as email','alp_formas_envios.nombre_forma_envios as nombre_forma_envios','alp_formas_envios.descripcion_forma_envios as descripcion_forma_envios','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago','alp_formas_pagos.descripcion_forma_pago as descripcion_forma_pago','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.doc_cliente as doc_cliente')
                  ->join('users','alp_ordenes.id_cliente' , '=', 'users.id')
                  ->join('alp_clientes','alp_ordenes.id_cliente' , '=', 'alp_clientes.id_user_client')
                  ->join('alp_formas_envios','alp_ordenes.id_forma_envio' , '=', 'alp_formas_envios.id')
                  ->join('alp_formas_pagos','alp_ordenes.id_forma_pago' , '=', 'alp_formas_pagos.id')
                  ->where('alp_ordenes.id', $id_orden)->first();


              $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug','alp_productos.presentacion_producto as presentacion_producto')
                ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
                ->where('alp_ordenes_detalle.id_orden', $id_orden)->get();

               $states=State::where('config_states.country_id', '47')->get();

               $configuracion = AlpConfiguracion::where('id','1')->first();

                $user_cliente=User::where('id', $user_id)->first();

                $texto='Se ha creado la siguiente orden '.$compra->id.' y esta a espera de aprobacion  ';


                if ($compra->id_forma_envio!=1) {

                  $formaenvio=AlpFormasenvio::where('id', $compra->id_forma_envio)->first();

                  Mail::to($formaenvio->email)->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega, 1));

                     Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega, 1));
                }

           

                $estatus_aviso='success';

                $aviso_pago="Hemos procesado su orden satisfactoriamente, Su id para realizar el deposito en efectivo es <h4>".$payment['response']['id']."</h4>. Las indicaciones para finalizar su pago puede seguirlas en este enlace <a target='_blank' href='".$payment['response']['transaction_details']['external_resource_url']."' >Ticket</a>. Tiene 72 Horas para realizar el pago, o su orden sera cancelada. ¡Muchas gracias por su Compra!";

                $metodo=$payment['response']['payment_method_id'];

                $idc=$compra->id*1024;


                return secure_url('cart/'.$idc.'/gracias?pago=pendiente');


                \Session::forget('pagando');

        }else{

          \Session::forget('pagando');

          return secure_url('order/detail');

        }
        

      }else{

          return redirect('login');
      }


}


  public function getFechaEntrega($id_forma_envio, $id_city){


      $ciudad_forma=AlpFormaCiudad::where('id_forma', $id_forma_envio)->where('id_ciudad', $id_city)->first();

      $forma_envio=AlpFormasenvio::where('id', $id_forma_envio)->first();

      $feriados=AlpFeriados::feriados();

      $dias=0;

      if ($forma_envio->tipo=='0') {

        $date = Carbon::now();

        $dia=$date->format('d');

        //dd($dia);

        $ciudades_formas=AlpFormaCiudad::where('id_forma', $id_forma_envio)->where('id_ciudad', $id_city)->get();

        //dd($ciudades_formas);
          
          foreach ($ciudades_formas as $cf){
            
            if ($cf->desde<= $dia && $cf->hasta>=$dia) {
              
              for ($i=0; $i < 31; $i++) { 

                $ds = Carbon::now();

                $ds->addDays($i);

                $d=$ds->format('d');

                //dd(($cf->dias));
                //dd(($d));

                if ($d==$cf->dias) {
                  
                  //dd($i);

                  $ds = Carbon::now();

                  $ds->addDays($i);

                  $d=$ds->format('Y-m-d');

                  $dias=$i;

                }

                

              }

            }


          }


      }else{

        if (isset($ciudad_forma->hora)) {
          # code...


        $date = Carbon::now();

       $hora=$date->format('Hi');

        $hora_base=str_replace(':', '', $ciudad_forma->hora);

        if (intval($hora)>intval($hora_base)) {

          $ciudad_forma->dias=$ciudad_forma->dias+1;

        }

        for ($i=0; $i <=$ciudad_forma->dias ; $i++) { 

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


          $dias=$ciudad_forma->dias;

        }else{

          $dias=1;
        }

      }


        //$fecha_entrega=$date->addDays($dias)->format('d-m-Y');

        //dd($fecha_entrega);

        return $dias;

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

        $total=$this->total();


        if (isset($orden->id)) {
          # code...
        }else{

          return 0;
        }

        


      if (Sentinel::check()) {

        $user_id = Sentinel::getUser()->id;

      }else{

        $user_id= \Session::get('iduser');

      }


        $direccion=AlpDirecciones::where('id', $orden->id_address)->first();

        $date = Carbon::now();

        $dias=$this->getFechaEntrega($orden->id_forma_envio, $direccion->city_id );
        

        $fecha_entrega=$date->addDays($dias)->format('d-m-Y');

        $date = Carbon::now();

        $fecha_envio=$date->addDays($dias)->format('Y-m-d');


        $role=RoleUser::select('role_id')->where('user_id', $user_id)->first();

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


         $envio=$this->envio();


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



 public function saveOrden($preference){


      $cr= \Session::get('cr');

      //$cart=$this->reloadCart();

      $cart= \Session::get('cart');

     //dd($cart)


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

            //dd($data_detalle);

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

      $id_orden= \Session::get('orden');

      $orden=AlpOrdenes::where('id', $id_orden)->first();


      $aviso_pago='0';

      $configuracion = AlpConfiguracion::where('id','1')->first();


      if (Sentinel::check()) {

        $user_id = Sentinel::getUser()->id;

        $direccion=AlpDirecciones::where('id', $orden->id_address)->first();

       // dd($direccion);

        $ciudad_forma=AlpFormaCiudad::where('id_forma', $orden->id_forma_envio)->where('id_ciudad', $direccion->city_id)->first();

        if (isset($ciudad_forma->dias)) {
          $diasd=$ciudad_forma->dias;
        }else{

          $diasd=5;

        }



        $date = Carbon::now();
        


      $dias=$this->getFechaEntrega($orden->id_forma_envio, $direccion->city_id );


       $fecha_entrega=$date->addDays($dias)->format('d-m-Y');

       $date = Carbon::now();

       $fecha_envio=$date->addDays($diasd)->format('Y-m-d');

        $role=RoleUser::select('role_id')->where('user_id', $user_id)->first();

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
          'fecha_envio' => $date->addDays($diasd)->format('Y-m-d'),
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

        

         if ($orden->id_forma_pago=='1') {

            $data_update = array(
              'estatus' =>'5', 
              'estatus_pago' =>'1', 
            );

             $orden->update($data_update);

              $data_history = array(
                'id_orden' => $orden->id, 
               'id_status' => '5', 
                'notas' => 'Orden Aprobada por ser Contraentrega.', 
               'id_user' => 1
              );

            $history=AlpOrdenesHistory::create($data_history);





         }else{

             $data_update = array(
                'estatus' =>'1', 
                'estatus_pago' =>'1', 
              );

               $orden->update($data_update);


         }



         /*eliminamos el carrito*/

         $carro=AlpCarrito::where('id', $carrito)->first();

         if ( isset($carro->id)) {
           
            $carro->delete();

           $detalles_carrito=AlpCarritoDetalle::where('id_carrito', $carrito)->get();

           $ids = array();

           foreach ($detalles_carrito as $dc) {
             
            $ids[]=$dc->id;

           }

           AlpCarritoDetalle::destroy($ids);


         }

         if ($orden->id_forma_pago=='3') {

          $saldo_c=AlpSaldo::where('id_cliente', $user_id)->where('estado_registro', '1')->first();

          if (isset($saldo_c->id)) {
            
             $data_saldo = array(
              'id_cliente' => $user_id, 
              'saldo' => $orden->monto_total, 
              'operacion' => '2', 
              'nota' => 'Compra id '.$orden->id.'', 
              'fecha_vencimiento' => $saldo_c->fecha_vencimiento, 
              'id_user' => $user_id
            );

             AlpSaldo::create($data_saldo);


          }

         }


        $carrito= \Session::forget('cr');

        \Session::forget('orden');

        $compra =  DB::table('alp_ordenes')->select('alp_ordenes.*','users.first_name as first_name','users.last_name as last_name' ,'users.email as email','alp_formas_envios.nombre_forma_envios as nombre_forma_envios','alp_formas_envios.descripcion_forma_envios as descripcion_forma_envios','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago','alp_formas_pagos.descripcion_forma_pago as descripcion_forma_pago','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.doc_cliente as doc_cliente')
            ->join('users','alp_ordenes.id_cliente' , '=', 'users.id')
            ->join('alp_clientes','alp_ordenes.id_cliente' , '=', 'alp_clientes.id_user_client')
            ->join('alp_formas_envios','alp_ordenes.id_forma_envio' , '=', 'alp_formas_envios.id')
            ->join('alp_formas_pagos','alp_ordenes.id_forma_pago' , '=', 'alp_formas_pagos.id')
            ->where('alp_ordenes.id', $orden->id)->first();

        $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.referencia_producto as referencia_producto','alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto' ,'alp_productos.slug as slug','alp_productos.presentacion_producto as presentacion_producto')
          ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
          ->where('alp_ordenes_detalle.id_orden', $orden->id)->get();

         $cart= \Session::forget('cart');

         $states=State::where('config_states.country_id', '47')->get();

          $configuracion = AlpConfiguracion::where('id','1')->first();

          $user_cliente=User::where('id', $user_id)->first();

          $texto='Se ha creado la siguiente orden '.$compra->id.' y esta a espera de aprobacion  ';

          Mail::to($user_cliente->email)->send(new \App\Mail\CompraRealizada($compra, $detalles, $fecha_entrega));

          Mail::to($configuracion->correo_sac)->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega));


          Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\CompraRealizada($compra, $detalles, $fecha_entrega));

          Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega));

          //Mail::to($configuracion->correo_cedi)->send(new \App\Mail\NotificacionOrden($compra->id, $texto));
          //
          

           $idc=$orden->id*1024;

          return secure_url('cart/'.$idc.'/gracias?pago=pendiente');

          #return view('frontend.order.procesar', compact('compra', 'detalles', 'fecha_entrega', 'states', 'aviso_pago'));

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


     public function addlink( $id_producto, $b=NULL, $c=NULL, $d=NULL, $e=NULL, $f=NULL, $g=NULL, $h=NULL, $i=NULL)
    {

      $ps = array(
        'a' => $id_producto, 
        'b' => $b, 
        'c' => $c,
        'd' => $d, 
        'e' => $e, 
        'f' => $f, 
        'g' => $g, 
        'h' => $h, 
        'i' => $i
      );




        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id_producto'=>$id_producto])
                        ->log('Agregar producto desde link ');

        }else{

          activity()->withProperties(['id_producto'=>$id_producto])
                        ->log('Agregar producto desde link ');

        }

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

            $carrito= \Session::get('cr');

           $descuento='1'; 

           $error='0'; 

           $precio = array();

           $inv=$this->inventario();


            $cart= \Session::get('cart');

        foreach ($ps as $key => $value) {

          if ($value!=null) {
            
              $producto=AlpProductos::select('alp_productos.*', 'alp_impuestos.valor_impuesto as valor_impuesto')
              ->join('alp_impuestos', 'alp_productos.id_impuesto', '=', 'alp_impuestos.id')
              ->where('alp_productos.id', $value)
              ->first();

             if ( isset($producto->id)) {
              
                $producto->precio_oferta=$producto->precio_base;

                $producto->cantidad=1;

                $producto->impuesto=$producto->precio_oferta*$producto->valor_impuesto;


            if (isset($inv[$producto->id])) {

               if($inv[$producto->id]>=$producto->cantidad){

                $cart[$producto->slug]=$producto;

              }else{

                $error="No hay existencia suficiente de este producto";

              }

              # code...
            }else{

              $error="No hay existencia suficiente de este producto";

            }


             


             $data_detalle = array(
              'id_carrito' => $carrito, 
              'id_producto' => $producto->id, 
              'cantidad' => $producto->cantidad
            );

             AlpCarritoDetalle::create($data_detalle);

             if (isset($request->datasingle)) {

              $datasingle=$request->datasingle;
             
              $view= View::make('frontend.order.botones', compact('producto', 'cart', 'datasingle'));
              
             }else{

              $view= View::make('frontend.order.botones', compact('producto', 'cart'));


             }

           }


         }
     }


     \Session::put('cart', $cart);

       $this->reloadCart();


      return redirect('cart/show');
      
    }

    public function addtocart( Request $request)
    {

          $producto=AlpProductos::select('alp_productos.*', 'alp_impuestos.valor_impuesto as valor_impuesto')
          ->join('alp_impuestos', 'alp_productos.id_impuesto', '=', 'alp_impuestos.id')
          ->where('alp_productos.slug', $request->slug)
          ->first();

          //dd($producto);

        if (!\Session::has('cr')) {

          \Session::put('cr', '0');

          $ciudad= \Session::get('ciudad');

          $crrid=time();

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

       $almacen=$this->getAlmacen();

       //dd($almacen);



       if (isset($producto->id)) {

         $producto->precio_oferta=$request->price;

        $producto->cantidad=1;

        $producto->impuesto=$producto->precio_oferta*$producto->valor_impuesto;



        if (isset($inv[$producto->id])) {

          if($inv[$producto->id]>=$producto->cantidad){

          $cart[$producto->slug]=$producto;

           $data_detalle = array(
              'id_carrito' => $carrito, 
              'id_producto' => $producto->id, 
              'cantidad' => $producto->cantidad
            );

             AlpCarritoDetalle::create($data_detalle);

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


       if (isset($request->datasingle)) {

          $datasingle=$request->datasingle;
       
          $view= View::make('frontend.order.botones', compact('producto', 'cart', 'datasingle', 'error'));
        
      }else{

        $view= View::make('frontend.order.botones', compact('producto', 'cart', 'error'));


       }

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($cart)
                        ->log('addtocart ');

        }else{

          activity()->withProperties($cart)
                        ->log('addtocart');


        }


          $data=$view->render();

          $res = array('data' => $data);

          return $data;
      
      
    }


    public function addtocartdetail( Request $request)
    {

        $cart= \Session::get('cart');

        $combos=$this->combos();

        $configuracion=AlpConfiguracion::first();




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

       if (isset($producto->id)) {

          $producto->precio_oferta=$request->price;
          $producto->cantidad=1;
          $producto->disponible=1;
          $producto->impuesto=$producto->precio_oferta*$producto->valor_impuesto;


          if (isset($inv[$producto->id])) {

             if($inv[$producto->id]>=$producto->cantidad){

                $cart[$producto->slug]=$producto;

                 \Session::put('cart', $cart);

                 $data_detalle = array(
                  'id_carrito' => $carrito, 
                  'id_producto' => $producto->id, 
                  'cantidad' => $producto->cantidad
                );

                 AlpCarritoDetalle::create($data_detalle);




              }else{

                $error="No hay existencia suficiente de este producto";

              }


            # code...
          }else{

                $error="No hay existencia suficiente de este producto";

              }


          $impuesto=$this->impuesto();

           $total=$this->total();


            if (Sentinel::check()) {

              $user = Sentinel::getUser();

               activity($user->full_name)
                            ->performedOn($user)
                            ->causedBy($user)
                            ->withProperties($cart)
                            ->log('addtocartdetail ');

            }else{

              activity()->withProperties($cart)
                            ->log('addtocartdetail');


            }



       }

       $total=$this->total();


       $almacen_id=$this->getAlmacen();


      $almacen=AlpAlmacenes::where('id', $almacen_id)->first();


       $mensaje_promocion= $this->addPromocion();

        $cart= \Session::get('cart');




       $view= View::make('frontend.listcart', compact('combos', 'cart','configuracion', 'total', 'almacen', 'mensaje_promocion'));

              $data=$view->render();

              $res = array('data' => $data);

              return $data;


      
       // return json_encode($cart);
      
    }

    public function addtocartsingle( Request $request)
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


       if (isset($producto->id)) {
         

         $producto->precio_oferta=$request->price;

        $producto->cantidad=1;
        $producto->impuesto=$producto->precio_oferta*$producto->valor_impuesto;

        if (isset($inv[$producto->id])) {

          if($inv[$producto->id]>=$producto->cantidad){

          $cart[$producto->slug]=$producto;

          \Session::put('cart', $cart);

       $data_detalle = array(
        'id_carrito' => $carrito, 
        'id_producto' => $producto->id, 
        'cantidad' => $producto->cantidad
      );


      

      AlpCarritoDetalle::create($data_detalle);

      

        }else{

          $error="No hay existencia suficiente de este producto";

        }


          # code...
        }else{

          $error="No hay existencia suficiente de este producto";

        }

        

       




       }



        if (Sentinel::check()) {

        $user = Sentinel::getUser();

         activity($user->full_name)
                      ->performedOn($user)
                      ->causedBy($user)
                      ->withProperties($cart)
                      ->log('addtocartsingle ');

      }else{

        activity()->withProperties($cart)
                      ->log('addtocartsingle');


      }

       
     

     $single=1;

     $view= View::make('frontend.order.botones', compact('producto', 'cart', 'single'));

      $data=$view->render();

      $res = array('data' => $data);

      return $data;
      
    }

    public function delete( AlpProductos $producto)
    {
       $cart= \Session::get('cart');

       $carrito= \Session::get('cr');

      unset( $cart[$producto->slug]);

      $detalle=AlpCarritoDetalle::where('id_carrito', $carrito)->where('id_producto', $producto->id)->first();

      if (isset($detalle->id)) { 

        $detalle->delete();
        # code...
      }

     


       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($cart)
                        ->log('delete ');

        }else{

          activity()->withProperties($cart)
                        ->log('delete');


        }
       

       \Session::put('cart', $cart);

       return redirect('cart/show');

      
    }

    public function update( AlpProductos $producto, $cantidad)
    {
       $cart= \Session::get('cart');

       $carrito= \Session::get('cr');


       $cart[$producto->slug]->cantidad=$cantidad;




       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($cart)
                        ->log('cartcontroller/update ');

        }else{

          activity()->withProperties($cart)
                        ->log('cartcontroller/update');


        }

       
      // return $cart;

       \Session::put('cart', $cart);

       return redirect('cart/show');

      
    }



    public function updatecantidad(Request $request)
    {
      
      $cart= \Session::get('cart');

      $inv=$this->inventario();


       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cartcontroller/updatecantidad ');

        }else{

          activity()->withProperties($request->all())
                        ->log('cartcontroller/updatecantidad');


        }


        if (isset($inv[$request->id])) {
          

           if($inv[$request->id]>=$request->cantidad){

        $cart[$request->slug]->cantidad=$request->cantidad;

        \Session::put('cart', $cart);

        return 'true';

      }else{

        return 'false';

        
      }

      
        }else{

        return 'false';

        
      }

      

      
    }

    public function delproducto( Request $request)
    {
       $cart= \Session::get('cart');

       $producto=AlpProductos::where('slug', $request->slug)->first();


       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cartcontroller/delproducto ');

        }else{

          activity()->withProperties($request->all())
                        ->log('cartcontroller/delproducto');


        }


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


       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cartcontroller/updatecart ');

        }else{

          activity()->withProperties($request->all())
                        ->log('cartcontroller/updatecart');


        }

       $carrito= \Session::get('cr');

       $inv=$this->inventario();

       $error='0';

       if (isset($inv[$request->id])) {

         if($inv[$request->id]>=$request->cantidad){

        $cart[$request->slug]->cantidad=$request->cantidad;

      }else{

        $error="No hay existencia suficiente de este producto";
      }


         # code...
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

       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('cartcontroller/vaciar ');

        }else{

          activity()->log('cartcontroller/vaciar');


        }

       $carrito= \Session::get('cr');

       if (Sentinel::check()) {

         $user_id = Sentinel::getUser()->id;

          $usados_orden=AlpOrdenesDescuento::where('id_orden', $carrito)->where('id_user', $user_id)->get();


          foreach ($usados_orden as $uo) {
            
            $o=AlpOrdenesDescuento::where('id', $uo->id)->first();

            if (isset($o->id)) {
            $o->delete();
              # code...
            }


          }


       }

       $cart= \Session::forget('cart');

       $carrito= \Session::forget('cr');
      
       return redirect('cart/show');
      
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



  




    private function precio_base()
    {
       $cart= \Session::get('cart');

      $total=0;

      foreach($cart as $row) {

        if (isset($row->id)) {
            $total=$total+($row->cantidad*$row->precio_base);
        }

      

      }

       return $total;
      
    }

    private function impuesto()
    {
       $cart= \Session::get('cart');

      $impuesto=0;

      $valor_impuesto=0;

      $carrito= \Session::get('cr');


      $base=0;

      $total=$this->total();

      $total_descuentos=0;


        $descuentos=AlpOrdenesDescuento::where('id_orden', $carrito)->get();

        foreach ($descuentos as $pago) {

          $total_descuentos=$total_descuentos+$pago->monto_descuento;

        }

          foreach($cart as $row) {

            if (isset($row->id)) {
              if($row->valor_impuesto>0){

                $valor_impuesto=$row->valor_impuesto;

              }

              $impuesto=$impuesto+($row->impuesto*$row->cantidad);

              $base=$base+($row->precio_oferta*$row->cantidad);
              }

            

          }


      $resto=$total-$total_descuentos;

       if ($resto<$base) {

        $impuesto=($resto/(1+$valor_impuesto))*$valor_impuesto;

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
       
       $id_almacen=$this->getAlmacen();

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

    private function combos()
    {

      $c=AlpProductos::where('tipo_producto', '2')->get();

      $combos = array();

      foreach ($c as $co) {
        
        $lista=AlpCombosProductos::select('alp_combos_productos.*', 'alp_productos.slug as slug', 'alp_productos.nombre_producto as nombre_producto', 'alp_productos.imagen_producto as imagen_producto','alp_productos.presentacion_producto as presentacion_producto')
        ->join('alp_productos', 'alp_combos_productos.id_producto', '=', 'alp_productos.id')
        ->where('id_combo', $co->id)
        ->get();

        $combos[$co->id]=$lista;

      }

      return $combos;
    }


    private function reloadCart()
    {
       
      $cart= \Session::get('cart');

      $s_user= \Session::get('user');

     $id_almacen=$this->getAlmacenCart();

      $total=0;

      $cambio=1;

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

                  if (isset($producto->id)) {

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

                    if (isset($producto->id)) {
                      
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

      if (isset($producto->promocion)) {
        # code...
      }else{

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


            if (isset($producto->ancheta)) {

              $total=0;
              $total_oferta=0;

                foreach ($producto->ancheta as $c) {
          
                  $total=$total+($c->precio_base);
                  $total_oferta=$total_oferta+($c->precio_oferta);
                  
                }

                $producto->precio_oferta=$total_oferta;
                $producto->precio_base=$total;

               
             }






            $producto->impuesto=$producto->precio_oferta*$producto->valor_impuesto;

            $almp=AlpAlmacenProducto::where('id_almacen', $id_almacen)->where('id_producto', $producto->id)->first();

            //dd($almp);

            if (isset($almp->id)) {

              $producto->disponible=1;

            }else{

              if (isset($producto->promocion)) {
                $producto->disponible=1;
              }else{

                $producto->disponible=0;

              }

            }


           $cart[$producto->slug]=$producto;
           
          }
      }
      }

     // dd($cart);

       return $cart;

      }else{

        return $cart;

      }
      
    }

    public function botones(Request $request)
    {

        $input = $request->all();

        $producto=AlpProductos::where('id', $request->id)->first();

           if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cartcontroller/botones ');

        }else{

          activity()->withProperties($request->all())
                        ->log('cartcontroller/botones');


        }

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


    public function updatecartdetalle(Request $request)
    {


       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cartcontroller/updatecartdetalle ');

        }else{

          activity()->withProperties($request->all())
                        ->log('cartcontroller/updatecartdetalle');


        }


       $cart= \Session::get('cart');

       $configuracion=AlpConfiguracion::where('id', '1')->first();


       $carrito= \Session::get('cr');

       $inv=$this->inventario();

       $producto=AlpProductos::where('id', $request->id)->first();


       $error='0';

       if ( isset($producto->id)) {

        $producto->precio_oferta=$request->price;

        $producto->cantidad=1;

        $producto->impuesto=$producto->precio_oferta*$producto->valor_impuesto;

           if (isset($inv[$request->id])) {

             if ($request->cantidad>0) {
               
                 if($inv[$request->id]>=$request->cantidad){

                      if ($configuracion->maximo_productos<$request->cantidad) {

                      $error="No puede añadir más de ".$configuracion->maximo_productos." Unidades al carrito";
                      
                    }else{

                      if (isset($cart[$request->slug])) {

                        $cart[$request->slug]->cantidad=$request->cantidad;

                      }else{

                        $cart[$request->slug]=$producto;


                      }

                      

                    }
                  

                }else{

                  $error="No hay existencia suficiente de este producto";
                }

             }else{

              unset( $cart[$producto->slug]);


             }//aqui termina 

         }else{

          unset( $cart[$producto->slug]);

         }
       }


       //dd($producto->slug.' - '.$producto->id.' - '.$request->id);

       $detalle=AlpCarritoDetalle::where('id_carrito', $carrito)->where('id_producto', $producto->id)->first();

       if (isset($detalle->id)) {

        $data = array(
        'cantidad' => $request->cantidad, 
        );

        $detalle->update($data);
         
       }


      \Session::put('cart', $cart);

       $mensaje_promocion=$this->addPromocion();

       $cart= \Session::get('cart');


      $impuesto=$this->impuesto();

      $total=$this->total();

      $configuracion=AlpConfiguracion::where('id', '1')->first();

      $almacen_id=$this->getAlmacen();


      $almacen=AlpAlmacenes::where('id', $almacen_id)->first();


        $view= View::make('frontend.listcart', compact('producto', 'cart', 'total', 'impuesto', 'configuracion', 'error', 'almacen', 'mensaje_promocion'));

        $data=$view->render();

        return $data;
      
    }

    public function updatecartbotones(Request $request)
    {

       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cartcontroller/updatecartbotones ');

        }else{

          activity()->withProperties($request->all())
                        ->log('cartcontroller/updatecartbotones');


        }



       $cart= \Session::get('cart');

        $configuracion=AlpConfiguracion::where('id', '1')->first();

       $carrito= \Session::get('cr');

       $inv=$this->inventario();

       $producto=AlpProductos::where('id', $request->id)->first();

       //dd(print_r($producto));

       $error='0';

       if ($request->cantidad>0) {

        if (isset($inv[$request->id])) {

          if($inv[$request->id]>=$request->cantidad){

              if ($configuracion->maximo_productos<$request->cantidad) {

                $error="No puede añadir más de ".$configuracion->maximo_productos." Unidades al carrito";
                
              }else{

                if (isset($cart[$request->slug]->cantidad)) {

                  $cart[$request->slug]->cantidad=$request->cantidad;

                }

                

              }


          }else{

            $error="No hay existencia suficiente de este producto";
          }


         
        }


           

       }else{

        unset( $cart[$request->slug]);


       }

       $detalle=AlpCarritoDetalle::where('id_carrito', $carrito)->where('id_producto', $producto->id)->first();

       if (isset($detalle->id)) {

        $data = array(
        'cantidad' => $request->cantidad, 
        );

        $detalle->update($data);
         
       }

       \Session::put('cart', $cart);

      if (isset($request->datasingle)) {

        $datasingle=$request->datasingle;
        
        
         $view= View::make('frontend.order.botones', compact('producto', 'cart', 'datasingle'));
           

      }else{

         $view= View::make('frontend.order.botones', compact('producto', 'cart'));

      }

        $data=$view->render();

        return $data;

      
    }

    public function updatecartbotonessingle(Request $request)
    {

      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cartcontroller/updatecartbotonessingle ');

        }else{

          activity()->withProperties($request->all())
                        ->log('cartcontroller/updatecartbotonessingle');


        }



       $cart= \Session::get('cart');

       $carrito= \Session::get('cr');

       $inv=$this->inventario();

       $producto=AlpProductos::where('id', $request->id)->first();

       //dd(print_r($producto));

       $error='0';

       if ($request->cantidad>0) {
         

           if($inv[$request->id]>=$request->cantidad){

            if ($configuracion->maximo_productos<$request->cantidad) {

                $error="No puede añadir más de ".$configuracion->maximo_productos." Unidades al carrito";

                
              }else{

                $cart[$request->slug]->cantidad=$request->cantidad;

              }

          }else{

            $error="No hay existencia suficiente de este producto";
          }

       }else{

        unset( $cart[$producto->slug]);


       }

       $detalle=AlpCarritoDetalle::where('id_carrito', $carrito)->where('id_producto', $producto->id)->first();

       if (isset($detalle->id)) {

        $data = array(
        'cantidad' => $request->cantidad, 
        );

        $detalle->update($data);
         
       }

       \Session::put('cart', $cart);

       $single=1;


        $view= View::make('frontend.order.botones', compact('producto', 'cart', 'single'));

        $data=$view->render();

        return $data;

      
    }


      public function getcartbotones(Request $request)
    {


      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cartcontroller/getcartbotones ');

        }else{

          activity()->withProperties($request->all())
                        ->log('cartcontroller/getcartbotones');


        }




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

        $user_id = Sentinel::getUser()->id;

        $input = $request->all();

       // dd($input);


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


        if ($direccion->id) {

          

          return redirect('order/detail')->withInput()->with('success', trans('Se ha creado la direccion satisfactoriamente '));

        } else {

           return redirect('order/detail')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
        }       

    }

    public function setdir( $id)
    {

      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])
                        ->log('cartcontroller/setdir ');

        }else{

          activity()->withProperties(['id'=>$id])
                        ->log('cartcontroller/setdir');


        }

        \Session::put('direccion', $id);

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

          \Session::put('direccion', $direccion->id);

          return redirect('order/detail');
            

        } else {

            return Redirect::route('order/detail')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
        }       

    }

    public function deldir( $id)
    {

      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])
                        ->log('cartcontroller/deldir ');

        }else{

          activity()->withProperties(['id'=>$id])
                        ->log('cartcontroller/deldir');


        }



      $user_id = Sentinel::getUser()->id;

      $direccion= AlpDirecciones::find($id);

      if (isset($direccion->id)) {



      $direccion->delete();
        # code...
      }

     
      return redirect('order/detail');


    }

public function verificarDireccion( Request $request)
    {

     if (Sentinel::check()) {

        $user = Sentinel::getUser();

         activity($user->full_name)
                      ->performedOn($user)
                      ->causedBy($user)
                      ->withProperties($request->all())
                      ->log('cartcontroller/verificarDireccion ');

      }else{

        activity()->withProperties($request->all())
                      ->log('cartcontroller/verificarDireccion');

      }


      if (Sentinel::check()) {

        $user_id = Sentinel::getUser()->id;

        }else{

          $direccion=AlpDirecciones::where('id', $request->id_direccion)->first();

          $user_id=$direccion->id_client;

        }




      \Session::put('direccion', $request->id_direccion);

      $direccion=AlpDirecciones::where('id', $request->id_direccion)->first();

      //dd($direccion->city_id);

      if ($direccion->id_barrio!=0) {

          $ciudad=AlpFormaCiudad::where('id_forma', $request->id_forma_envio)->where('id_barrio', $direccion->id_barrio)->first();

        
      }else{


         $ciudad=AlpFormaCiudad::where('id_forma', $request->id_forma_envio)->where('id_ciudad', $direccion->city_id)->first();


      }



      $validado=0;

      $role=RoleUser::select('role_id')->where('user_id', $user_id)->first();

      $re=AlpRolenvio::where('id_rol', $role->role_id)->get();

      $re_u=AlpRolenvio::where('id_rol', $role->role_id)->first();

      $id_almacen=$this->getAlmacen();

      //dd($re_u);

      if (count($re)==1) {
          
          if ($re_u->id_forma_envio=='4') {

              $validado='1';
              
          }

      }

     // dd($validado);


      $carrito= \Session::get('cr');

      $cart=$this->reloadCart();

      $total=$this->total();

    

      $impuesto=$this->impuesto();

      $envio=$this->envio();

      $id_almacen=$this->getAlmacen();

      $edata = array(
        'carrito' => $carrito,
        'cart' => $cart,
        'total' => $total,
        'impuesto' => $impuesto,
        'envio' => $envio

      );



     if (Sentinel::check()) {

          activity($user->full_name)
          ->performedOn($user)
          ->causedBy($user)
          ->withProperties($edata)
          ->log('cartcontroller/verificarDireccion Mostrar Datos ');

      }else{



      }




      $clientIP = \Request::getClientIp(true);

      if (isset($ciudad->id)  ||  $validado=='1'){

        if (!\Session::has('orden')){

        if ($total>0) {
            

         
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
              'ip' =>$clientIP,
              'id_almacen' =>$id_almacen,
              'id_user' =>$user_id
          );

           activity()->withProperties($data_orden)
                        ->log('intento de pago pse ');

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


              activity()->withProperties($data_orden)
                        ->log('detalle de orden  ');

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


              if ($detalle->tipo_producto=='3') {

                  if (isset($detalle->ancheta)) {

                      foreach ($detalle->ancheta as $l) {

                          $data_detalle_l = array(
                            'id_orden' => $orden->id, 
                            'id_producto' => $l->id, 
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
                            'id_producto' => $l->id, 
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
               }



          }//endfreach detalle

            $total_descuentos=0;

            $descuentos=AlpOrdenesDescuento::where('id_orden', $carrito)->get();

            foreach ($descuentos as $pago) {

              $total_descuentos=$total_descuentos+$pago->monto_descuento;

            }

           
            $descuentosIcg=AlpOrdenesDescuentoIcg::where('id_orden','=', $carrito)->get();

            $total_descuentos_icg=0;

            foreach ($descuentosIcg as $pagoi) {

              $total_descuentos=$total_descuentos+$pagoi->monto_descuento;

              $total_descuentos_icg=$total_descuentos_icg+$pagoi->monto_descuento;

            }



          //se calcula lo que queda luego del descuento

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

            \Session::put('orden', $orden->id);

            $cupones=AlpOrdenesDescuento::where('id_orden', $carrito)->get();

            foreach ($cupones as $cupon) {
              
              $c=AlpOrdenesDescuento::where('id', $cupon->id)->first();

              $data_cupon = array('id_orden' => $orden->id );

              $c->update($data_cupon);

            }


            $descuentosIcg=AlpOrdenesDescuentoIcg::where('id_orden','=', $carrito)->get();


            foreach ($descuentosIcg as $pagoi) {

              $dicg=AlpOrdenesDescuentoIcg::where('id', '=', $pagoi->id)->first();

               $data_dicg = array('id_orden' => $orden->id );

              $dicg->update($data_dicg);

            }



            if ($orden->id_almacen==1) {

              $compramas=$this->reservarOrden($orden->id);

            }

            if ($total_descuentos_icg>0) {

              $this->registroIcg($orden->id);

            }


            if (\Session::has('mensajeancheta')) {

              $mensaje=\Session::get('mensajeancheta');

                $data_ancheta_mensaje = array(
                  'id_orden' => $orden->id,
                  'id_ancheta' => $orden->id,
                  'mensaje_de' => $mensaje['ancheta_de'],
                  'mensaje_para' => $mensaje['ancheta_para'],
                  'mensaje_mensaje' => $mensaje['ancheta_mensaje'],
              );

              AlpAnchetaMensaje::create($data_ancheta_mensaje);

            }

            \Session::put('cr', $orden->id);

          }else{

            return 'false0';

          }


         }else{



















          $o=\Session::get('orden');

          $orden=AlpOrdenes::where('id', '=', $o)->first();





           $data_orden = array(
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
              'ip' =>$clientIP,
              'id_almacen' =>$id_almacen,
              'id_user' =>$user_id
          );

           activity()->withProperties($data_orden)
                        ->log('intento de pago pse ');

          $orden->update($data_orden);

          $detalles_orden=AlpDetalles::where('id_orden', $orden->id)->get();


           foreach ($detalles_orden as $do) {

                  $data_inventario = array(
                    'id_producto' => $do->id_producto, 
                    'cantidad' =>$do->cantidad, 
                    'operacion' =>'1', 
                    'id_user' =>'1'
                  );

                  AlpInventario::create($data_inventario);
                
            }

          $detalles_orden=AlpDetalles::where('id_orden', $orden->id)->delete();

          $monto_total_base=0;

          $base_impuesto=0;

          $monto_impuesto=0;

          $valor_impuesto=0;

          foreach ($cart as $detalle) {

            if (isset($detalle->id)) {
             

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


              activity()->withProperties($data_orden)
                        ->log('detalle de orden  ');

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


              if ($detalle->tipo_producto=='3') {

                  if (isset($detalle->ancheta)) {

                      foreach ($detalle->ancheta as $l) {

                          $data_detalle_l = array(
                            'id_orden' => $orden->id, 
                            'id_producto' => $l->id, 
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
                            'id_producto' => $l->id, 
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
               }
               }



          }//endfreach detalle

            $total_descuentos=0;

            $descuentos=AlpOrdenesDescuento::where('id_orden', $carrito)->get();

            foreach ($descuentos as $pago) {

              $total_descuentos=$total_descuentos+$pago->monto_descuento;

            }

             $descuentosIcg=AlpOrdenesDescuentoIcg::where('id_orden','=', $carrito)->get();

            $total_descuentos_icg=0;

            foreach ($descuentosIcg as $pagoi) {

              $total_descuentos=$total_descuentos+$pagoi->monto_descuento;

            }



          //se calcula lo que queda luego del descuento

            $resto=$total-$total_descuentos;

            if ($resto<$base_impuesto) {

              $base_impuesto=$resto;
              
            }

          $monto_impuesto=($base_impuesto/(1+$valor_impuesto))*$valor_impuesto;

          $base_imponible=($base_impuesto/(1+$valor_impuesto));

           $data_update = array(
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
                'notas' => 'Orden Recargada', 
               'id_user' => 1
              );

            $history=AlpOrdenesHistory::create($data_history);

            \Session::put('orden', $orden->id);

            $cupones=AlpOrdenesDescuento::where('id_orden', $carrito)->get();

            foreach ($cupones as $cupon) {
              
              $c=AlpOrdenesDescuento::where('id', $cupon->id)->first();

              $data_cupon = array('id_orden' => $orden->id );

              $c->update($data_cupon);

            }


             $descuentosIcg=AlpOrdenesDescuentoIcg::where('id_orden','=', $carrito)->get();


            foreach ($descuentosIcg as $pagoi) {

              $dicg=AlpOrdenesDescuentoIcg::where('id', '=', $pagoi->id)->first();

               $data_dicg = array('id_orden' => $orden->id );

              $dicg->update($data_dicg);

            }





































         }

        // dd($cart);

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


      $id_almacen=$this->getAlmacen();

     $configuracion=AlpConfiguracion::where('id', '1')->first();

      $carrito= \Session::get('cr');

     //echo($carrito);
      
      $cart=$this->reloadCart();

      $total=$this->total();

      $total_base=$this->precio_base();

      $impuesto=$this->impuesto();

        $date = Carbon::now();

        $hoy=$date->format('Y-m-d');

        $user_id = Sentinel::getUser()->id;

        $usuario=User::where('id', $user_id)->first();

        $user_cliente=User::where('id', $user_id)->first();

        $cliente=AlpClientes::where('id_user_client', $user_id)->first();

      $cupon=AlpCupones::where('codigo_cupon', $codigo)
          ->whereDate('fecha_inicio','<=',$hoy)
          ->whereDate('fecha_final','>=',$hoy)
          ->first();

      $usados=AlpOrdenesDescuento::where('codigo_cupon', $codigo)->where('aplicado', '1')->get();

      $usados_persona=AlpOrdenesDescuento::where('codigo_cupon', $codigo)->where('id_user', $user_id)->where('aplicado', '1')->get();

      $usados_orden=AlpOrdenesDescuento::where('id_orden', $carrito)->where('id_user', $user_id)->get();


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

                $cupones=AlpOrdenesDescuento::where('id_orden', $carrito)->get();

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


                 $carrito= \Session::get('cr');


                $data_pago = array(
                  'id_orden' => $carrito, 
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
      
      $carrito= \Session::get('cr');

      $cart=$this->reloadCart();

      $total=$this->total();

      $total_base=$this->precio_base();

      $impuesto=$this->impuesto();

      $aviso='';


      if ($total<$configuracion->minimo_compra) {

            $aviso='El monto mínimo de compra es de $'.number_format($configuracion->minimo_compra,0,",",".");

            $cart=$this->reloadCart();


          $configuracion=AlpConfiguracion::where('id', '1')->first();

          $total=$this->total();

          $inv=$this->inventario();

          return view('frontend.cart', compact('cart', 'total', 'configuracion', 'inv', 'aviso'));

            return redirect('cart/show');

      }

      if (Sentinel::check()) {

        $user_id = Sentinel::getUser()->id;

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


      }else{

        $url='order.detail';

          //return redirect('login');
        return view('frontend.order.login', compact('url'));

      }

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

      $configuracion=AlpConfiguracion::where('id', '1')->first();
      
      $carrito= \Session::get('cr');

      $cart=$this->reloadCart();

      $total=$this->total();

      $total_base=$this->precio_base();

      $impuesto=$this->impuesto();

      $aviso='';


      if ($total<$configuracion->minimo_compra) {

            $aviso='El monto mínimo de compra es de $'.number_format($configuracion->minimo_compra,0,",",".");


            $cart=$this->reloadCart();


          $configuracion=AlpConfiguracion::where('id', '1')->first();

          $total=$this->total();

          $inv=$this->inventario();

          return view('frontend.cart', compact('cart', 'total', 'configuracion', 'inv', 'aviso'));

            return redirect('cart/show');

      }

      if (Sentinel::check()) {

        $user_id = Sentinel::getUser()->id;

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

        return redirect('order/detail')->with('aviso', $aviso);

      }else{

        $url='order.detail';

          //return redirect('login');
        return view('frontend.order.login', compact('url'));

      }

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
      
      $carrito= \Session::get('cr');

      $cart=$this->reloadCart();

      $total=$this->total();

      $total_base=$this->precio_base();

      $impuesto=$this->impuesto();

      $texto="<div class='alert alert-danger'>El cupón ha sido eliminado</div>";

      $o=AlpOrdenesDescuento::where('id', $request->id)->first();

      if (isset($o->id)) {

      $o->delete();
        # code...
      }


      return $texto;

    }


    public function envio(){

      $formasenvio= \Session::get('envio');

      $direccion= \Session::get('direccion');

     //dd($formasenvio.' '.$direccion);
     //
        if (Sentinel::check()) {

          $user_id = Sentinel::getUser()->id;

        }else{

          $user_id= \Session::get('iduser');

        }
     



      
      
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


    public function setformaenvio(Request $request)
    {

      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cartcontroller/setformaenvio ');

        }else{

          activity()->withProperties($request->all())
                        ->log('cartcontroller/setformaenvio');

        }

        $user_id = Sentinel::getUser()->id;

        $input = $request->all();

         \Session::put('envio', $input['id_forma_envio']);

         $envio=$this->envio();

        return $envio;

    }


private function getAlmacen3(){


        if (isset(Sentinel::getUser()->id)) {


        if (isset(Sentinel::getUser()->id)) {

        }

            # code...
            $user_id = Sentinel::getUser()->id;

            $usuario=User::where('id', $user_id)->first();

            $user_cliente=User::where('id', $user_id)->first();

            $role=RoleUser::select('role_id')->where('user_id', $user_id)->first();

            //dd($role);


            $d = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
              ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
              ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
              ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
              ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
              ->where('alp_direcciones.id_client', $user_id)
              ->where('alp_direcciones.default_address', '=', '1')
              ->first();



            if (isset($d->id)) {
                  
                  $almacen=AlpAlmacenes::where('id_city', $d->city_id)->first();

                  if (isset($almacen->id)) {
                    
                    $id_almacen=$almacen->id;

                  }else{

                    $almacen=AlpAlmacenes::where('defecto', '1')->first();

                    $id_almacen=$almacen->id;

                  }

            }else{

                  $d = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
                ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
                ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
                ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
                ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
                ->where('alp_direcciones.id_client', $user_id)
                ->first();


                    if (isset($d->id)) {
                                
                        $almacen=AlpAlmacenes::where('id_city', $d->city_id)->first();

                        if (isset($almacen->id)) {
                            
                            $id_almacen=$almacen->id;

                        }else{

                            $almacen=AlpAlmacenes::where('defecto', '1')->first();

                            $id_almacen=$almacen->id;

                        }


                    }else{

                      $almacen=AlpAlmacenes::where('defecto', '1')->first();

                      $id_almacen=$almacen->id;
                    }
            }


        }else{

            $almacen=AlpAlmacenes::where('defecto', '1')->first();

            $id_almacen=$almacen->id;
        
        }


        if (isset(Sentinel::getUser()->id)) {

            $user_id = Sentinel::getUser()->id;

            $role=RoleUser::select('role_id')->where('user_id', $user_id)->first();

            $re=AlpRolenvio::where('id_rol', $role->role_id)->get();

            $re_u=AlpRolenvio::where('id_rol', $role->role_id)->first();

            //dd($re_u);

            if (count($re)==1) {
                
                if ($re_u->id_forma_envio=='4') {

                    $id_almacen='2';
                    
                }

            }

        }


      return $id_almacen;

    }




 private function getAlmacen4(){




        if (isset(Sentinel::getUser()->id)) {

            # code...
            $user_id = Sentinel::getUser()->id;

            $usuario=User::where('id', $user_id)->first();

            $user_cliente=User::where('id', $user_id)->first();

            $role=RoleUser::select('role_id')->where('user_id', $user_id)->first();

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

                $ad=AlpAlmacenDespacho::where('id_city', $d->city_id)->first();

                if (isset($ad->id)) {
                # code...
                }else{

                  $c=City::where('id', $d->city_id)->first();

                  if (  isset($c->id)) {

                      $ad=AlpAlmacenDespacho::where('id_city', '0')->where('id_state', $c->state_id)->first();

                      if (isset($ad->id)) {
                    
                      }else{

                        $ad=AlpAlmacenDespacho::where('id_city', '0')->where('id_state', '0')->first();

                      }

                  }
                

                  

                }

                if (isset($ad->id)) {

                  $almacen=AlpAlmacenes::where('id', $ad->id_almacen)->first();

                  $id_almacen=$almacen->id;
                  # code...
                }else{

                   $almacen=AlpAlmacenes::where('defecto', '1')->first();

                    if (isset($almacen->id)) {
                      $id_almacen=$almacen->id;
                    }else{
                      $id_almacen='1';
                    }

                }

            }else{

              $almacen=AlpAlmacenes::where('defecto', '1')->first();

              if (isset($almacen->id)) {
                $id_almacen=$almacen->id;
              }else{
                $id_almacen='1';
              }
                 
            }

        }else{

            $almacen=AlpAlmacenes::where('defecto', '1')->first();

            if (isset($almacen->id)) {
                $id_almacen=$almacen->id;
              }else{
                $id_almacen='1';
              }
        
        }

      return $id_almacen;

    }



 private function getAlmacen_5(){




        if (isset(Sentinel::getUser()->id)) {

            # code...
            $user_id = Sentinel::getUser()->id;

            $usuario=User::where('id', $user_id)->first();

            $user_cliente=User::where('id', $user_id)->first();

            $role=RoleUser::select('role_id')->where('user_id', $user_id)->first();

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


              $tipo=0;

              if ($role->role_id=='14') {
                
                $tipo=1;
              }






                $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')
                ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')
                ->where('alp_almacenes.tipo_almacen', '=', $tipo)
                ->where('alp_almacen_despacho.id_city', $d->city_id)
                ->first();

                if (isset($ad->id)) {
                # code...
                }else{

                  $c=City::where('id', $d->city_id)->first();

                  $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')
                ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')
                ->where('alp_almacenes.tipo_almacen', '=', $tipo)
                ->where('alp_almacen_despacho.id_city', '0')
                ->where('alp_almacen_despacho.id_state', $c->state_id)
                ->first();

                  if (isset($ad->id)) {
                    
                  }else{

                    $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')
                  ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')
                  ->where('alp_almacenes.tipo_almacen', '=', $tipo)
                  ->where('alp_almacen_despacho.id_city', '0')
                  ->where('alp_almacen_despacho.id_state', '0')->first();

                  }

                }

                if (isset($ad->id)) {

                  $almacen=AlpAlmacenes::where('id', $ad->id_almacen)->first();

                  $id_almacen=$almacen->id;
                  # code...
                }else{

                   $almacen=AlpAlmacenes::where('defecto', '1')->first();

                    if (isset($almacen->id)) {
                      $id_almacen=$almacen->id;
                    }else{
                      $id_almacen='1';
                    }

                    


                }


















            }else{

              $almacen=AlpAlmacenes::where('defecto', '1')->first();

                if (isset($almacen->id)) {
                  $id_almacen=$almacen->id;
                }else{
                  $id_almacen='1';
                }

                
                   
            }

        }else{

            $almacen=AlpAlmacenes::where('defecto', '1')->first();

            if (isset($almacen->id)) {
                $id_almacen=$almacen->id;
              }else{
                $id_almacen='1';
              }


        
        }

      return $id_almacen;

    }




 private function getAlmacenCart(){


  $tipo=0;


        if (isset(Sentinel::getUser()->id)) {

            # code...
            $user_id = Sentinel::getUser()->id;

            $usuario=User::where('id', $user_id)->first();

            $user_cliente=User::where('id', $user_id)->first();

            $role=RoleUser::select('role_id')->where('user_id', $user_id)->first();

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
                ->where('alp_almacenes.estado_registro', '=', '1')->first();

                  if (isset($ad->id)) {
                    
                  }else{

                    $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')
                  ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')
                  ->where('alp_almacenes.tipo_almacen', '=', $tipo)
                  ->where('alp_almacen_despacho.id_city', '0')
                  ->where('alp_almacenes.estado_registro', '=', '1')->where('alp_almacen_despacho.id_state', '0')->first();

                  }

                }

                if (isset($ad->id)) {

                  $almacen=AlpAlmacenes::where('id', $ad->id_almacen)->where('alp_almacenes.estado_registro', '=', '1')->first();

                  $id_almacen=$almacen->id;
                  # code...
                }else{

                   $almacen=AlpAlmacenes::where('defecto', '1')->where('alp_almacenes.estado_registro', '=', '1')->first();

                    if (isset($almacen->id)) {
                      $id_almacen=$almacen->id;
                    }else{
                      $id_almacen='0';
                    }

                    $id_almacen='0';


                }


            }else{

              $almacen=AlpAlmacenes::where('defecto', '1')->where('alp_almacenes.estado_registro', '=', '1')->first();

                if (isset($almacen->id)) {
                  $id_almacen=$almacen->id;
                }else{
                  $id_almacen='0';
                }

                $id_almacen='0';
                   
            }

        }else{


          $ciudad= \Session::get('ciudad');



            if (isset($ciudad)) {




              $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')
                ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')
                ->where('alp_almacenes.tipo_almacen', '=', $tipo)
                ->where('alp_almacen_despacho.id_city', $ciudad)
                ->where('alp_almacenes.estado_registro', '=', '1')->first();

                if (isset($ad->id)) {
                # code...
                }else{

                  $c=City::where('id', $ciudad)->first();

                  if (isset($c->id)) {

                      $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')
                ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')
                ->where('alp_almacenes.tipo_almacen', '=', $tipo)
                ->where('alp_almacen_despacho.id_city', '0')
                ->where('alp_almacen_despacho.id_state', $c->state_id)
                ->where('alp_almacenes.estado_registro', '=', '1')->first();


                    # code...
                  }

                
                  if (isset($ad->id)) {
                    
                  }else{

                    $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')
                  ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')
                  ->where('alp_almacenes.tipo_almacen', '=', $tipo)
                  ->where('alp_almacen_despacho.id_city', '0')
                  ->where('alp_almacenes.estado_registro', '=', '1')->where('alp_almacen_despacho.id_state', '0')->first();

                  }

                }

                if (isset($ad->id)) {

                  $almacen=AlpAlmacenes::where('id', $ad->id_almacen)->where('alp_almacenes.estado_registro', '=', '1')->first();

                  $id_almacen=$almacen->id;
                  # code...
                }else{

                   $almacen=AlpAlmacenes::where('defecto', '1')->where('alp_almacenes.estado_registro', '=', '1')->first();

                    if (isset($almacen->id)) {

                      $id_almacen=$almacen->id;

                    }else{

                      $id_almacen='1';

                    }

                }

              
            }else{


               $almacen=AlpAlmacenes::where('defecto', '1')->where('alp_almacenes.estado_registro', '=', '1')->first();

              if (isset($almacen->id)) {
                  $id_almacen=$almacen->id;
                }else{
                  $id_almacen='1';
                }

            }
        
        }

      return $id_almacen;

    }

 


 private function getAlmacen(){



    $tipo=0;


        if (isset(Sentinel::getUser()->id)) {

            # code...
            $user_id = Sentinel::getUser()->id;

            $usuario=User::where('id', $user_id)->first();

            $user_cliente=User::where('id', $user_id)->first();

            $role=RoleUser::select('role_id')->where('user_id', $user_id)->first();

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

                   $almacen=AlpAlmacenes::where('defecto', '1')->first();

                    if (isset($almacen->id)) {
                      $id_almacen=$almacen->id;
                    }else{
                      $id_almacen='1';
                    }

                }


















            }else{

              $almacen=AlpAlmacenes::where('defecto', '1')->first();

                if (isset($almacen->id)) {
                  $id_almacen=$almacen->id;
                }else{
                  $id_almacen='1';
                }
                   
            }

        }else{ //no esta logueado 


            $ciudad= \Session::get('ciudad');



            if (isset($ciudad)) {




              $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')
                ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')
                ->where('alp_almacenes.tipo_almacen', '=', $tipo)
                ->where('alp_almacen_despacho.id_city', $ciudad)
                ->where('alp_almacenes.estado_registro', '=', '1')
                ->first();

                if (isset($ad->id)) {
                # code...
                }else{

                  $c=City::where('id', $ciudad)->first();

                  if (isset($c->id)) {
                     $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')
                ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')
                ->where('alp_almacenes.tipo_almacen', '=', $tipo)
                ->where('alp_almacen_despacho.id_city', '0')
                ->where('alp_almacen_despacho.id_state', $c->state_id)
                ->where('alp_almacenes.estado_registro', '=', '1')
                ->first();
                  
                    # code...
                  }

                 

                  if (isset($ad->id)) {
                    
                  }else{

                    $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')
                  ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')
                  ->where('alp_almacenes.tipo_almacen', '=', $tipo)
                  ->where('alp_almacen_despacho.id_city', '0')
                  ->where('alp_almacenes.estado_registro', '=', '1')
                  ->where('alp_almacen_despacho.id_state', '0')->first();

                  }

                }

                if (isset($ad->id)) {

                  $almacen=AlpAlmacenes::where('id', $ad->id_almacen)->where('alp_almacenes.estado_registro', '=', '1')->first();

                  $id_almacen=$almacen->id;
                  # code...
                }else{

                   $almacen=AlpAlmacenes::where('defecto', '1')->where('alp_almacenes.estado_registro', '=', '1')->first();

                    if (isset($almacen->id)) {

                      $id_almacen=$almacen->id;

                    }else{

                      $id_almacen='1';

                    }

                }








              
            }else{


               $almacen=AlpAlmacenes::where('defecto', '1')->where('alp_almacenes.estado_registro', '=', '1')->first();

              if (isset($almacen->id)) {
                  $id_almacen=$almacen->id;
                }else{
                  $id_almacen='1';
                }



            }

        
        }

       // dd($id_almacen);

      return $id_almacen;

    }



    private function getSaldo()
    {
       
      $entradas = AlpSaldo::groupBy('id_cliente')
              ->select("alp_saldo.*", DB::raw(  "SUM(alp_saldo.saldo) as cantidad_total"))
              ->where('alp_saldo.operacion', '1')
              ->get();

              $inv = array();

              foreach ($entradas as $row) {
                
                $inv[$row->id_cliente]=$row->cantidad_total;

              }


            $salidas = AlpSaldo::groupBy('id_cliente')
              ->select("alp_saldo.*", DB::raw(  "SUM(alp_saldo.saldo) as cantidad_total"))
              ->where('alp_saldo.operacion', '2')
              ->get();

              foreach ($salidas as $row) {

                if (isset($inv[$row->id_cliente])) {
                  $inv[$row->id_cliente]= $inv[$row->id_cliente]-$row->cantidad_total;
                }else{
                  $inv[$row->id_cliente]= 0;
                }
                
                

            }

            return $inv;
      
    }



        private function addOferta($productos){


        $descuento='1'; 

        $precio = array();

        $ciudad= \Session::get('ciudad');

        if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

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

                    }else{


                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->first();

                      if (isset($pregiogrupo->id)) {
                         
                          $precio[$row->id]['precio']=$pregiogrupo->precio;
                          $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                          $precio[$row->id]['pum']=$pregiogrupo->pum;

                      }

                    

                    }

                }
                
            }

        }else{

          $role = array( );

            $r='9';

                foreach ($productos as  $row) {

                  //dd($row);
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $r)->where('city_id', $ciudad)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;

                    }else{

                      $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $r)->first();

                      if (isset($pregiogrupo->id)) {
                       
                          $precio[$row->id]['precio']=$pregiogrupo->precio;
                          $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                          $precio[$row->id]['pum']=$pregiogrupo->pum;

                      }

                      

                    }

                }
                
        }

        $prods = array( );

        foreach ($productos as $producto) {

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


           $prods[]=$producto;
           
          }

        return $prods;


    }



    private function reservarOrden($id_orden)
    {

      $configuracion=AlpConfiguracion::first();
      
       $orden=AlpOrdenes::where('id', $id_orden)->first();

        Log::useDailyFiles(storage_path().'/logs/compramas.log');
        
        Log::info('compramas orden '.json_encode($orden));

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

              $urls=$configuracion->compramas_url.'/registerOrderReserved/'.$configuracion->compramas_hash;

               Log::info('compramas urls '.$urls);

               Log::info($dataraw);

               activity()->withProperties($dataraw)->log('dataraw');


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

       Log::info('compramas res '.json_encode($res));
       
       Log::info('compramas result '.$result);


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


          Mail::to($configuracion->correo_sac)->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

           Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));
         
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

          Mail::to($configuracion->correo_sac)->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

           Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));


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














private function addpromocion(){
       
      $cart= \Session::get('cart');

      $inventario=$this->inventario();

       $id_almacen=$this->getAlmacenCart();

      //dd($inventario);

      $date = Carbon::now();

        $hoy=$date->format('Y-m-d');


      foreach ($cart as $c) {

        if (isset($c->promocion)) {
          unset($cart[$c->slug]);
        }
        # code...
      }

      $mensaje='';


      $promociones=AlpPromociones::whereDate('fecha_inicio','<=',$hoy)
          ->whereDate('fecha_final','>=',$hoy)
          ->get();

        //dd($promociones);

          foreach ($promociones as $promo) {

              $disponible=1;

              $iprs=AlpPromocionesRegalo::where('id_promocion', $promo->id)->get();

              foreach ($iprs as $ipr) {

                $apa=AlpAlmacenProducto::where('id_almacen', '=', $id_almacen)->where('id_producto', '=', $ipr->id_producto)->first();

                //dd($apa);

                  if (isset($apa->id)) {
                    
                      if (isset($inventario[$ipr->id_producto])) {

                        if ($inventario[$ipr->id_producto]>=$ipr->cantidad) {

                            $disponible=0;

                        }else{

                            $disponible=1;
                        }
                        
                      }else{

                        $disponible=1;

                      }

                  }else{

                    $disponible=1;

                  }


               }


            if ($disponible==0) {

                if ($promo->tipo==1) {

                  $pcs=AlpPromocionesCategorias::where('id_promocion', $promo->id)->get();

                  $categorias = array();

                  $i=0;

                  $des_categoria='';

                    foreach ($pcs as $pc) {

                      $cc=AlpCategorias::where('id', $pc->id_categoria)->first();

                       if ($i==0) {

                        $des_categoria=$cc->nombre_categoria;

                        $i=1;
                        # code...
                      }else{

                         $des_categoria=$des_categoria.', '.$cc->nombre_categoria;
                      }

                      $categorias[]=$pc->id_categoria;

                    }

                    $cc=AlpCategorias::where('id', $pc->id_categoria)->first();

                      if ($i==0) {

                        $des_categoria=$cc->nombre_categoria;

                        $i=1;
                        # code...
                      }else{

                         $des_categoria=$des_categoria.', '.$cc->nombre_categoria;
                      }




                    $categorias[]=$promo->referencia;

                   // dd($categorias);

                  $monto=0;
                  
                  foreach ($cart as $c) {

                    if (isset($c->id)) {

                       if (in_array($c->id_categoria_default, $categorias)) {

                     #echo($c->id.'-1<br>');
                      
                      $monto=$monto+($c->precio_oferta*$c->cantidad);

                    }else{

                      $cps=AlpCategoriasProductos::where('id_producto', $c->id)->get();

                      foreach ($cps as $cp) {
                        #echo($cp->id_producto.'-3<br>');
                        
                        if (in_array($cp->id_categoria, $categorias)) {

                         # echo($cp->id_producto.'-4<br>');
                        
                            $monto=$monto+($c->precio_oferta*$c->cantidad);
                        }

                      }

                    }

                    
                      # code...
                    }

                   # echo($c->id.'-1<br>');

                   

                    
                  }

                  if ($monto>$promo->monto_minimo) {

                    $prs=AlpPromocionesRegalo::where('id_promocion', $promo->id)->get();

                    foreach ($prs as $pr) {

                      $p=AlpProductos::where('id', $pr->id_producto)->first();

                      if (isset($p->id)) {

                          $p->promocion='1';
                          $p->cantidad=$pr->cantidad;
                          $p->precio_oferta=$pr->precio;
                          $p->nombre_producto=$p->nombre_producto.' x '.$pr->cantidad;

                          $cart[$p->slug]=$p;
                          # code...
                      }
                        # code...
                    }


                  }else{

                    $categoria=AlpCategorias::where('id', $promo->referencia)->first();

                    $dif=$promo->monto_minimo-$monto;

                    $enlace='<a href="'.secure_url('categoria/'.$categoria->slug).'" class="btn btn-link ">'.$categoria->nombre_categoria.'</a>';

                    $mensaje='Si agregas '.number_format($dif,0,",",".").' COP más en compras de productos de las categorias: '.$categoria->nombre_categoria.',  puedes obtener un regalo.'; 


                  }


              }


              if ($promo->tipo==2) {


                 $pcs=AlpPromocionesCategorias::where('id_promocion', $promo->id)->get();

                 $des_marca='';

                  $marcas = array();

                 $i=0;

                    foreach ($pcs as $pc) {

                      $mc=AlpMarcas::where('id', $pc->id_categoria)->first();

                      if ($i==0) {

                        $des_marca=$mc->nombre_marca;

                        $i=1;
                        # code...
                      }else{

                         $des_marca=$des_marca.', '.$mc->nombre_marca;
                      }

                      $marcas[]=$pc->id_categoria;

                    }

                    $marcas[]=$promo->referencia;



                $monto=0;
                
                foreach ($cart as $c) {

                    if (in_array($c->id_marca, $marcas)) {

                      $monto=$monto+($c->precio_oferta*$c->cantidad);

                    }
                  
                }

                if ($monto>$promo->monto_minimo) {

                  $prs=AlpPromocionesRegalo::where('id_promocion', $promo->id)->get();

                  foreach ($prs as $pr) {

                    $p=AlpProductos::where('id', $pr->id_producto)->first();

                    if (isset($p->id)) {

                        $p->promocion='1';
                        $p->cantidad=$pr->cantidad;
                        $p->precio_oferta=$pr->precio;

                        $cart[$p->slug]=$p;
                        # code...
                    }
                      # code...
                  }


                }else{

                    $marca=AlpMarcas::where('id', $promo->referencia)->first();

                    $dif=$promo->monto_minimo-$monto;

                    $mensaje='Si agregas '.number_format($dif,0,",",".").' COP más en compras de productos de las marcas: '.$des_marca.',  puedes obtener un regalo.';


                  }


              }


            }else{

              $mensaje_promocion='';
            }



            # code...
          }//endforeach



      \Session::put('cart', $cart);

      return $mensaje;

     
      
}















  public function addtocartancheta( Request $request)
    {

          $p=AlpProductos::select('alp_productos.*', 'alp_impuestos.valor_impuesto as valor_impuesto')
          ->join('alp_impuestos', 'alp_productos.id_impuesto', '=', 'alp_impuestos.id')
          ->where('alp_productos.slug', $request->slug)
          ->first();

          //dd($p);

        if (!\Session::has('cartancheta')) {

          \Session::put('cartancheta',   array());

        }

       $cartancheta= \Session::get('cartancheta');

       $descuento='1'; 

       $error=''; 

       $precio = array();

       $inv=$this->inventario();

       $almacen=$this->getAlmacen();


       if (isset($p->id)) {

          $p->precio_oferta=$request->price;

          $p->cantidad=1;

          $p->impuesto=$p->precio_oferta*$p->valor_impuesto;


        if (isset($inv[$p->id])) {

          if($inv[$p->id]>=$p->cantidad){

          $cartancheta[$p->slug]=$p;


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

          
       \Session::put('cartancheta', $cartancheta);
       
        $view= View::make('frontend.pancheta', compact('p',  'cartancheta', 'error'));

        $data=$view->render();

        $res = array('data' => $data);

        return $data;
      
      
    }







public function deltocartancheta( Request $request)
    {

          $p=AlpProductos::select('alp_productos.*', 'alp_impuestos.valor_impuesto as valor_impuesto')
          ->join('alp_impuestos', 'alp_productos.id_impuesto', '=', 'alp_impuestos.id')
          ->where('alp_productos.slug', $request->slug)
          ->first();

          $p=$this->addOfertaUn($p);

          //dd($producto);

        if (!\Session::has('cartancheta')) {

          \Session::put('cartancheta',   array());

        }

       $cartancheta= \Session::get('cartancheta');

       $descuento='1'; 

       $error=''; 

       $precio = array();

       if (isset($p->id)) {

          if (isset($cartancheta[$p->slug])) {

            unset($cartancheta[$p->slug]);

         }
         
       }

       \Session::put('cartancheta', $cartancheta);
       
        $view= View::make('frontend.pancheta', compact('p',  'cartancheta', 'error'));

        $data=$view->render();

        $res = array('data' => $data);

        return $data;
      
      
    }




public function totalancheta()
    {

          

      if (!\Session::has('cartancheta')) {

        \Session::put('cartancheta',  array());

      }



      $cartancheta= \Session::get('cartancheta');

      $producto= \Session::get('producto_ancheta');

     // dd($producto);

      $total=0;

      foreach ($cartancheta as $c) {
        
        $total=$total+($c->precio_oferta);
        
      }


          $view= View::make('frontend.listaancheta', compact('cartancheta', 'total', 'producto'));
          
          $data=$view->render();

          return $data;
      
    }



public function verificarancheta(Request $request)
    {

          

      if (!\Session::has('cartancheta')) {

        \Session::put('cartancheta',  array());

      }


      if (!\Session::has('mensajeancheta')) {

        \Session::put('mensajeancheta',  array());

      }

      $request->ancheta_de=strip_tags($request->ancheta_de);
      $request->ancheta_para=strip_tags($request->ancheta_para);
      $request->ancheta_mensaje=strip_tags($request->ancheta_mensaje);


      $mensaje = array(
        'ancheta_de' => $request->ancheta_de, 
        'ancheta_para' => $request->ancheta_para, 
        'ancheta_mensaje' => $request->ancheta_mensaje, 
      );

      \Session::put('mensajeancheta',  $mensaje);


      $cartancheta= \Session::get('cartancheta');

      $producto= \Session::get('producto_ancheta');

      $inv=$this->inventario();

      //dd($cartancheta);

      $total=0;

      $respuesta=0;

      foreach ($cartancheta as $c) {
        
        if (isset($inv[$c->id])) {
          
          if ($inv[$c->id]<$c->cantidad) {
            
            $respuesta=1;

          }

        }else{
          $respuesta=1;
        }
        
      }


        return $respuesta;
      
    }



public function reiniciarancheta()
    {

          

        \Session::forget('cartancheta');


      return 'true';
     
      
    }


    public function addtocartunaancheta( Request $request)
    {

          $producto=AlpProductos::select('alp_productos.*', 'alp_impuestos.valor_impuesto as valor_impuesto')
          ->join('alp_impuestos', 'alp_productos.id_impuesto', '=', 'alp_impuestos.id')
          ->where('alp_productos.slug', $request->slug)
          ->first();


           if (!\Session::has('mensajeancheta')) {

        \Session::put('mensajeancheta',  array());

      }

      $request->ancheta_de=strip_tags($request->ancheta_de);
      $request->ancheta_para=strip_tags($request->ancheta_para);
      $request->ancheta_mensaje=strip_tags($request->ancheta_mensaje);


      $mensaje = array(
        'ancheta_de' => $request->ancheta_de, 
        'ancheta_para' => $request->ancheta_para, 
        'ancheta_mensaje' => $request->ancheta_mensaje, 
      );

      \Session::put('mensajeancheta',  $mensaje);


          //dd($producto);

        if (!\Session::has('cr')) {

          \Session::put('cr', '0');

          $ciudad= \Session::get('ciudad');

          $crrid=time();

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

       $almacen=$this->getAlmacen();

       //dd($almacen);



       if (isset($producto->id)) {


        $cartancheta= \Session::get('cartancheta');

         $producto->precio_oferta=$request->price;

         $producto->ancheta=$cartancheta;

        $producto->cantidad=1;

        $producto->impuesto=$producto->precio_oferta*$producto->valor_impuesto;


        if (isset($inv[$producto->id])) {

          if($inv[$producto->id]>=$producto->cantidad){

          $cart[$producto->slug]=$producto;

           $data_detalle = array(
              'id_carrito' => $carrito, 
              'id_producto' => $producto->id, 
              'cantidad' => $producto->cantidad
            );

             AlpCarritoDetalle::create($data_detalle);

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

       \Session::forget('cartancheta');


       \Session::put('cart', $cart);


       if (isset($request->datasingle)) {

          $datasingle=$request->datasingle;
       
          $view= View::make('frontend.order.botones', compact('producto', 'cart', 'datasingle', 'error'));
        
      }else{

        $view= View::make('frontend.order.botones', compact('producto', 'cart', 'error'));


       }

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($cart)
                        ->log('addtocart ');

        }else{

          activity()->withProperties($cart)
                        ->log('addtocart');


        }


          $data=$view->render();

          $res = array('data' => $data);

          return $data;
      
      
    }

















    private function addOfertaUn($producto)
    {
       

      $s_user= \Session::get('user');

     $id_almacen=$this->getAlmacenCart();

      $total=0;

      $cambio=1;

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
                    
                    

                  if (isset($producto->id)) {

                      $pregiogrupo=AlpPrecioGrupo::where('id_producto', $producto->id)->where('id_role', $role->role_id)->first();

                      if (isset($pregiogrupo->id)) {
                         
                          $precio[$producto->id]['precio']=$pregiogrupo->precio;

                          $precio[$producto->id]['operacion']=$pregiogrupo->operacion;
                          $precio[$producto->id]['pum']=$pregiogrupo->pum;

                      }
                    
                    
                  }

                  

                
            }

          }

        }else{

          $role = array( );

            $r='9';

                    if (isset($producto->id)) {
                      
                       $pregiogrupo=AlpPrecioGrupo::where('id_producto', $producto->id)->where('id_role', $r)->first();

                      if (isset($pregiogrupo->id)) {
                         
                          $precio[$producto->id]['precio']=$pregiogrupo->precio;
                          $precio[$producto->id]['operacion']=$pregiogrupo->operacion;
                          $precio[$producto->id]['pum']=$pregiogrupo->pum;

                      }

                    }
                    
                   

                
        } //end sentinel check



    if ($cambio==1) {


      if (isset($producto->promocion)) {
        # code...
      }else{

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


            if (isset($producto->ancheta)) {

              $total=0;
              $total_oferta=0;

                foreach ($producto->ancheta as $c) {
          
                  $total=$total+($c->precio_base);
                  $total_oferta=$total_oferta+($c->precio_oferta);
                  
                }

                $producto->precio_oferta=$total_oferta;
                $producto->precio_base=$total;

               
             }






            $producto->impuesto=$producto->precio_oferta*$producto->valor_impuesto;

            $almp=AlpAlmacenProducto::where('id_almacen', $id_almacen)->where('id_producto', $producto->id)->first();

            //dd($almp);

            if (isset($almp->id)) {

              $producto->disponible=1;

            }else{

              if (isset($producto->promocion)) {
                $producto->disponible=1;
              }else{

                $producto->disponible=0;

              }

            }

          }
      }

     // dd($cart);

       return $producto;

      }else{

        return $producto;

      }
      
    }




























    private function consultaIcg()
    {

     //dd($id_orden);
      $configuracion=AlpConfiguracion::where('id', '=', 1)->first();

      $s_user= \Session::get('user');

      $carrito= \Session::get('cr');

     // dd($s_user);

      $c=AlpClientes::where('id_user_client', $s_user)->first();

      $data = array('DocumentoEmpleado' =>$c->doc_cliente);

      $dataraw=json_encode($data);

      $urls=$configuracion->endpoint_icg;

       Log::info('api icg urls '.$urls);

       Log::info($dataraw);

       activity()->withProperties($dataraw)->log('dataraw');


      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, $configuracion->endpoint_icg);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $dataraw); 
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

      $headers = array();
      $headers[] = 'Content-Type: application/json';
    //  $headers[] = 'Woobsing-Token: '.$configuracion->api icg_token;
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

      try {

        $result = curl_exec($ch);
        
      } catch (Exception $e) {
        
      }

      
      if (curl_errno($ch)) {
          //echo 'Error:' . curl_error($ch);

           Log::info('Error:' . curl_error($ch));
      }
      curl_close($ch);

      $res=json_decode($result);

       Log::info('api icg res '.json_encode($res));
       
       Log::info('api icg result '.$result);

     //  dd($result);


       $notas='Registro de orden en api icg res.';


      if (isset($res->CodigoRta)) {
        
        if ($res->CodigoRta=='OK') {

            $dataicg = array(
            'id_orden' => $carrito, 
            'doc_cliente' => $c->doc_cliente, 
            'monto_descuento' => 0, 
            'json' => json_encode($res), 
            'id_user' => $s_user, 
          );

          AlpConsultasIcg::create($dataicg);



          return $res->CupoCredito;

           
        }else{

          $dataicg = array(
          'id_orden' => $carrito, 
          'doc_cliente' => $c->doc_cliente, 
          'monto_descuento' => 0, 
          'json' => json_encode($res), 
          'id_user' => $s_user, 
        );

        AlpConsultasIcg::create($dataicg);



          return 1;

        }


      }else{

        $dataicg = array(
          'id_orden' => $carrito, 
          'doc_cliente' => $c->doc_cliente, 
          'monto_descuento' => 0, 
          'json' => json_encode($res), 
          'id_user' => $s_user, 
        );

        AlpConsultaIcg::create($dataicg);

        return 30;

                       

      }

      
    }







    public function addDescuentoIcg(Request $request)
    {


       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cartcontroller/addDescuentoIcg ');

        }else{

          activity()->withProperties($request->all())
                        ->log('cartcontroller/addDescuentoIcg');


        }

      $user = Sentinel::getUser();

      $configuracion=AlpConfiguracion::where('id', '1')->first();
      
      $carrito= \Session::get('cr');

      $cart=$this->reloadCart();

      $total=$this->total();

      $total_base=$this->precio_base();

      $impuesto=$this->impuesto();

      $aviso='';


      $cupo_icg=$this->consultaIcg();

      $descuento=$total*(($cupo_icg/100));

      $datadescuento = array(
        'id_orden' => $carrito,
        'codigo_orden' => $carrito,
        'monto_descuento' => $descuento,
        'json' => 0,
        'aplicado' => 0,
        'id_user' => $user->id
      );


      AlpOrdenesDescuentoIcg::create($datadescuento);


       return redirect('order/detail');

    }







    private function registroIcg($ordenId)
    {

     //dd($id_orden);
      $configuracion=AlpConfiguracion::where('id', '=', 1)->first();

      $s_user= \Session::get('user');

      $carrito= \Session::get('cr');

     
     $orden=AlpOrdenes::where('id', $ordenId)->first();

     $descuentosIcg=AlpOrdenesDescuentoIcg::where('id_orden', '=', $orden->id)->first();

     $monto_descuentoicg=0;

     if (isset($descuentosIcg->id)) {
       $monto_descuentoicg=$descuentoIcg->monto_descuento;
     }

      $c=AlpClientes::where('id_user_client', $s_user)->first();

      $data = array(
        'NumeroPedido' =>$orden->referencia,
        'Fecha'=>$orden->create_at,
        'DocumentoEmpleado'=>$orden->doc_cliente,
        'FormaPago'=>'Mercadopago',
        'ValorTransaccion'=>$orden->total,
        'ValorDescuento'=>$monto_descuentoicg
      );

      $dataraw=json_encode($data);

      $urls=$configuracion->endpoint_icg;

       Log::info('api icg urls '.$urls);

       Log::info($dataraw);

       activity()->withProperties($dataraw)->log('dataraw');


      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, $configuracion->endpoint_icg);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $dataraw); 
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

      $headers = array();
      $headers[] = 'Content-Type: application/json';
    //  $headers[] = 'Woobsing-Token: '.$configuracion->api icg_token;
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

      try {

        $result = curl_exec($ch);
        
      } catch (Exception $e) {
        
      }

      
      if (curl_errno($ch)) {
          //echo 'Error:' . curl_error($ch);

           Log::info('Error:' . curl_error($ch));
      }
      curl_close($ch);

      $res=json_decode($result);

       Log::info('api icg res '.json_encode($res));
       
       Log::info('api icg result '.$result);

     //  dd($result);


       $notas='Registro de orden en api icg res.';


      if (isset($res->CodigoRta)) {
        
        if ($res->CodigoRta=='OK') {

            $dataicg = array(
            'id_orden' => $carrito, 
            'doc_cliente' => $c->doc_cliente, 
            'monto_descuento' => 0, 
            'json' => json_encode($res), 
            'id_user' => $s_user, 
          );

          AlpConsultasIcg::create($dataicg);



          return $res->CupoCredito;

           
        }else{

          $dataicg = array(
          'id_orden' => $carrito, 
          'doc_cliente' => $c->doc_cliente, 
          'monto_descuento' => 0, 
          'json' => json_encode($res), 
          'id_user' => $s_user, 
        );

        AlpConsultasIcg::create($dataicg);



          return 1;

        }


      }else{

        $dataicg = array(
          'id_orden' => $carrito, 
          'doc_cliente' => $c->doc_cliente, 
          'monto_descuento' => 0, 
          'json' => json_encode($res), 
          'id_user' => $s_user, 
        );

        AlpConsultaIcg::create($dataicg);

        return 30;

                       

      }

      
    }









}