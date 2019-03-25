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


     /**Mostar mas vendidos en el carro*/

        $descuento='1'; 

        $precio = array();


     $productos = DB::table('alp_productos')->select('alp_productos.*')->where('destacado','=', 1)->where('alp_productos.estado_registro','=',1)->orderBy('order', 'asc')->limit(12)->get();


      if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

            $role=RoleUser::where('user_id', $user_id)->first();

            $cliente = AlpClientes::where('id_user_client', $user_id )->first();

            if (isset($cliente) ) {

                if ($cliente->id_empresa!=0) {
                    
                    /* $empresa=AlpEmpresas::find($cliente->id_empresa);

                    $cliente['nombre_empresa']=$empresa->nombre_empresa;

                    $descuento=(1-($empresa->descuento_empresa/100));*/

                    $role->role_id='E'.$role->role_id.'';
                }
               
            }

            if ($role->role_id) {

               
                foreach ($productos as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->first();

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


       // $producto->impuesto=$producto->precio_oferta*$producto->valor_impuesto;


      // $cart[$producto->slug]=$producto;

       $prods[]=$producto;
       
      }


      return view('frontend.cart', compact('cart', 'total', 'configuracion', 'states', 'inv','productos', 'prods', 'descuento'));
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




    $compra =  DB::table('alp_ordenes')->select('alp_ordenes.*','users.first_name as first_name','users.last_name as last_name' ,'users.email as email','alp_formas_envios.nombre_forma_envios as nombre_forma_envios','alp_formas_envios.descripcion_forma_envios as descripcion_forma_envios','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago','alp_formas_pagos.descripcion_forma_pago as descripcion_forma_pago','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.doc_cliente as doc_cliente')
            ->join('users','alp_ordenes.id_cliente' , '=', 'users.id')
            ->join('alp_clientes','alp_ordenes.id_cliente' , '=', 'alp_clientes.id_user_client')
            ->join('alp_formas_envios','alp_ordenes.id_forma_envio' , '=', 'alp_formas_envios.id')
            ->join('alp_formas_pagos','alp_ordenes.id_forma_pago' , '=', 'alp_formas_pagos.id')
            ->where('alp_ordenes.id', 85)->first();
 $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.referencia_producto as referencia_producto','alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto' ,'alp_productos.slug as slug')
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

      $total=$orden->monto_total;

      $impuesto=$orden->monto_impuesto;
      
      $configuracion = AlpConfiguracion::where('id', '1')->first();

        $mp = new MP();

        if ($configuracion->mercadopago_sand=='1') {
          
          $mp::sandbox_mode(TRUE);

        }

        if ($configuracion->mercadopago_sand=='2') {
          
          $mp::sandbox_mode(FALSE);

        }

        MP::setCredenciales($configuracion->id_mercadopago, $configuracion->key_mercadopago);

        $net_amount=$total-$impuesto;


                       $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.descripcion_corta as descripcion_corta','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug')
          ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
          ->where('alp_ordenes_detalle.id_orden', $orden->id)->get();

      $det_array = array();



      foreach ($detalles as $d ) {


        $det_array[]= array(

                "id"          => $d->id_producto,
                "title"       => $d->nombre_producto,
                "description" => $d->descripcion_corta,
                "quantity"    => (int)number_format($d->cantidad, 0, '.', ''),
                "unit_price"  => (float)number_format($d->precio_unitario, 2, '.', ''),
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
           "external_reference": "ALP'.$orden->id.'",
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

       


          $pse = MP::post("/v1/payments",$preference_data);

          $user_id = Sentinel::getUser()->id;
         

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

      //dd($input);


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

        // 1.- eststus orden, 2.- estatus pago, 3 json pedido 
        $data=$this->generarPedido('8', '4', $input, 'mercadopago');
       // $data=$this->generarPedido('8', '4', $input, 'credit_card');

        $id_orden=$data['id_orden'];

        $fecha_entrega=$data['fecha_entrega'];
       
         $aviso_pago="Estamos verificando su pago, una vez sea confirmado, Le llegará un email con la descripción de su pedido. En caso de existir algún error en el pago le invitamos a Mis Compras desde su perfil para intentar pagar nuevamente";

        $compra =  DB::table('alp_ordenes')->select('alp_ordenes.*','users.first_name as first_name','users.last_name as last_name' ,'users.email as email','alp_formas_envios.nombre_forma_envios as nombre_forma_envios','alp_formas_envios.descripcion_forma_envios as descripcion_forma_envios','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago','alp_formas_pagos.descripcion_forma_pago as descripcion_forma_pago','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.doc_cliente as doc_cliente')
            ->join('users','alp_ordenes.id_cliente' , '=', 'users.id')
            ->join('alp_clientes','alp_ordenes.id_cliente' , '=', 'alp_clientes.id_user_client')
            ->join('alp_formas_envios','alp_ordenes.id_forma_envio' , '=', 'alp_formas_envios.id')
            ->join('alp_formas_pagos','alp_ordenes.id_forma_pago' , '=', 'alp_formas_pagos.id')
            ->where('alp_ordenes.id', $id_orden)->first();

        $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug')
          ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
          ->where('alp_ordenes_detalle.id_orden', $id_orden)->get();

         $states=State::where('config_states.country_id', '47')->get();

         $configuracion = AlpConfiguracion::where('id','1')->first();

          $user_cliente=User::where('id', $user_id)->first();

          $texto='Se ha creado la siguiente orden '.$compra->id.' y esta a espera de aprobacion  ';


      //  Mail::to($user_cliente->email)->send(new \App\Mail\CompraRealizada($compra, $detalles, $fecha_entrega));

      //  Mail::to($configuracion->correo_sac)->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega));
          
          $estatus_aviso='warning';
          
          return view('frontend.order.procesar_completo', compact('compra', 'detalles', 'fecha_entrega', 'states', 'aviso_pago', 'estatus_aviso'));
        

      }else{

          return redirect('login');
      }

}


    public function notificacion(Request $request)
    {
      
      $input=$request->all();

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

                      $history=AlpOrdenesHistory::create($data_history);

            $compra =  DB::table('alp_ordenes')->select('alp_ordenes.*','users.first_name as first_name','users.last_name as last_name' ,'users.email as email','alp_formas_envios.nombre_forma_envios as nombre_forma_envios','alp_formas_envios.descripcion_forma_envios as descripcion_forma_envios','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago','alp_formas_pagos.descripcion_forma_pago as descripcion_forma_pago','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.doc_cliente as doc_cliente')
            ->join('users','alp_ordenes.id_cliente' , '=', 'users.id')
            ->join('alp_clientes','alp_ordenes.id_cliente' , '=', 'alp_clientes.id_user_client')
            ->join('alp_formas_envios','alp_ordenes.id_forma_envio' , '=', 'alp_formas_envios.id')
            ->join('alp_formas_pagos','alp_ordenes.id_forma_pago' , '=', 'alp_formas_pagos.id')
            ->where('alp_ordenes.id', $id_orden)->first();

        $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug')
          ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
          ->where('alp_ordenes_detalle.id_orden', $id_orden)->get();


              Mail::to($user_cliente->email)->send(new \App\Mail\CompraRealizada($compra, $detalles, $envio->fecha_envio));

              Mail::to($configuracion->correo_sac)->send(new \App\Mail\CompraSac($compra, $detalles, $envio->fecha_envio));
               
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

    public function orderRapipago(Request $request)
    {
    

      $configuracion = AlpConfiguracion::where('id', '1')->first();

      MP::setCredenciales($configuracion->id_mercadopago, $configuracion->key_mercadopago);

      MP::sandbox_mode(TRUE);



       $carrito= \Session::get('cr');

     // $id_orden= \Session::get('orden');
      $id_orden= "98";

      $orden=AlpOrdenes::where('id', $id_orden)->first();

      $detalles=AlpDetalles::where('id_orden', $id_orden)->get();


        $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.descripcion_corta as descripcion_corta','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug')
          ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
          ->where('alp_ordenes_detalle.id_orden', $id_orden)->get();

      $det_array = array();



      foreach ($detalles as $d ) {


        $det_array[]= array(

                "id"          => $d->id_producto,
                "title"       => $d->nombre_producto,
                "description" => $d->descripcion_corta,
                "quantity"    => (int)number_format($d->cantidad, 0, '.', ''),
                "unit_price"  => (float)number_format($d->precio_unitario, 2, '.', ''),
                );

       
      }

      $total=$orden->monto_total;

      $impuesto=$orden->monto_impuesto;
      
      $configuracion = AlpConfiguracion::where('id', '1')->first();

        $mp = new MP();

        if ($configuracion->mercadopago_sand=='1') {
          
          $mp::sandbox_mode(TRUE);

        }

        if ($configuracion->mercadopago_sand=='2') {
          
          $mp::sandbox_mode(FALSE);

        }

        MP::setCredenciales($configuracion->id_mercadopago, $configuracion->key_mercadopago);

        $net_amount=$total-$impuesto;

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
           "external_reference": "ALP'.$orden->id.'",
           "callback_url": "'.secure_url('/order/pse').'",
           "additional_info": {
               "ip_address": "'.request()->ip().'"
           },
           "payment_method_id": "pse",
           "transaction_amount": '.$total.',
           "transaction_details": {
               "financial_institution": "1001"
           },
           "net_amount": '.$net_amount.',
           "taxes":[{
                               "value": '.$impuesto.',
                               "type": "IVA"
                       }],
            "additional_info":{
            "items":'.json_encode($det_array).'}
       }' ;

       dd($preference_data);


          $pse = MP::post("/v1/payments",$preference_data);


      dd($pse);
  

    }


     public function orderCreditcard(Request $request)
    {


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

    $user_cliente=User::where('id', $user_id)->first();


     $configuracion = AlpConfiguracion::where('id', '1')->first();

     MP::setCredenciales($configuracion->id_mercadopago, $configuracion->key_mercadopago);


      $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.descripcion_corta as descripcion_corta','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug')
          ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
          ->where('alp_ordenes_detalle.id_orden', $orden->id)->get();

           $total=$orden->monto_total;

      $impuesto=$orden->monto_impuesto;
$net_amount=$total-$impuesto;

      $det_array = array();

      foreach ($detalles as $d ) {

        $det_array[]= array(

                "id"          => $d->id_producto,
                "title"       => $d->nombre_producto,
                "description" => $d->descripcion_corta,
                "quantity"    => (int)number_format($d->cantidad, 0, '.', ''),
                "unit_price"  => (float)number_format($d->precio_unitario, 2, '.', ''),
                );
      }

       $preference_data = [
        "transaction_amount" => doubleval($orden->monto_total),
        "net_amount"=>$net_amount,
            "taxes"=>[[
              "value"=>(float)number_format($impuesto, 2, '.', ''),
              "type"=>"IVA"]],
        "token" => $request->token,
        "description" => 'Pago de orden: '.$orden->id,
        "installments" => intval($request->installments),
        "external_reference"=> "ALP".$orden->id."",
        "payment_method_id" => $request->payment_method_id,
        
        "issuer_id" => $request->issuer_id,
        "payer" => [
          "email"=>$user_cliente->email]
      ];




      $preference = MP::post("/v1/payments",$preference_data);

     // dd($preference);

      if (isset($preference['response']['id'])) {


        if ($preference['response']['status']=='rejected' || $preference['response']['status']=='cancelled' || $preference['response']['status']=='cancelled/expired')  {

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

       
          $data=$this->generarPedido('1', '2', $preference, 'credit_card');

          $id_orden=$data['id_orden'];

          $fecha_entrega=$data['fecha_entrega'];

         $aviso_pago="Hemos recibido su pago satisfactoriamente, una vez sea confirmado, Le llegará un email con la descripción de su pago. ¡Muchas gracias por su Compra!";

       //  $datalles=AlpDetalles::where('id_orden', $orden->id)->get();

        $compra =  DB::table('alp_ordenes')->select('alp_ordenes.*','users.first_name as first_name','users.last_name as last_name' ,'users.email as email','alp_formas_envios.nombre_forma_envios as nombre_forma_envios','alp_formas_envios.descripcion_forma_envios as descripcion_forma_envios','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago','alp_formas_pagos.descripcion_forma_pago as descripcion_forma_pago','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.doc_cliente as doc_cliente')
            ->join('users','alp_ordenes.id_cliente' , '=', 'users.id')
            ->join('alp_clientes','alp_ordenes.id_cliente' , '=', 'alp_clientes.id_user_client')
            ->join('alp_formas_envios','alp_ordenes.id_forma_envio' , '=', 'alp_formas_envios.id')
            ->join('alp_formas_pagos','alp_ordenes.id_forma_pago' , '=', 'alp_formas_pagos.id')
            ->where('alp_ordenes.id', $id_orden)->first();


        $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug')
          ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
          ->where('alp_ordenes_detalle.id_orden', $id_orden)->get();


         $cart= \Session::forget('cart');

         $states=State::where('config_states.country_id', '47')->get();

         $configuracion = AlpConfiguracion::where('id','1')->first();


          $texto='Se ha creado la siguiente orden '.$compra->id.' y esta a espera de aprobacion  ';


        Mail::to($user_cliente->email)->send(new \App\Mail\CompraRealizada($compra, $detalles, $fecha_entrega));

        Mail::to($configuracion->correo_sac)->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega));
          

          return view('frontend.order.procesar_completo', compact('compra', 'detalles', 'fecha_entrega', 'states', 'aviso_pago'));
        

     

        
        }else{

          return redirect('order/detail');

        }


  }else{

          return redirect('login');
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

           if ($configuracion->mercadopago_sand=='1') {
          
          $mp::sandbox_mode(TRUE);

        }

        if ($configuracion->mercadopago_sand=='2') {
          
          $mp::sandbox_mode(FALSE);

        }

            MP::setCredenciales($configuracion->id_mercadopago, $configuracion->key_mercadopago);

          $preference = MP::post("/checkout/preferences",$preference_data);

          $this->saveOrden($preference);

          $net_amount=$total-$impuesto;


         $pse = array();

          $payment_methods = MP::get("/v1/payment_methods");


          $carro=AlpCarrito::where('id', $carrito)->first();

          $data_carrito = array(
            'id_user' => $user_id );

          $carro->update($data_carrito);

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

          return view('frontend.order.detail', compact('cart', 'total', 'direcciones', 'formasenvio', 'formaspago', 'countries', 'configuracion', 'states', 'preference', 'inv', 'pagos', 'total_pagos', 'impuesto', 'payment_methods', 'pse', 'tdocumento', 'estructura', 'labelpagos'));

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

           if ($configuracion->mercadopago_sand=='1') {
          
          $mp::sandbox_mode(TRUE);

        }

        if ($configuracion->mercadopago_sand=='2') {
          
          $mp::sandbox_mode(FALSE);

        }

            MP::setCredenciales($configuracion->id_mercadopago, $configuracion->key_mercadopago);

          $preference = MP::post("/checkout/preferences",$preference_data);

          $this->saveOrden($preference);



        /*  $pse_data = '{
         "payer": {
             "email": "'.$usuario->email.'",
             "entity_type": "individual",
             "identification": {
                 "type": "CC",
                 "number": "123456"
             }
         },
         "description": "'.'Pago de orden Nro.'.$carrito.'",
         "callback_url": "https://alpinago.com/public/orden/pse",
         "additional_info": {
             "ip_address": "172.17.0.1"
         },
         "payment_method_id": "pse",
         "transaction_amount": '.$total.',
         "transaction_details": {
             "financial_institution": 1007
         },
         "net_amount": '.$net_amount.',
         "taxes":[{
                             "value": '.$impuesto.',
                             "type": "IVA"
                     }]
     }';


          $pse = MP::post("/v1/payments",$pse_data);*/

           $pse = array();

          $payment_methods = MP::get("/v1/payment_methods");
            //$preference=null;

            ///print_r($preference);

            $states=State::where('config_states.country_id', '47')->get();

            $tdocumento=AlpTDocumento::get();

            return view('frontend.order.failure', compact('cart', 'total', 'impuesto', 'direcciones', 'formasenvio', 'formaspago', 'countries','preference', 'states', 'configuracion', 'inv', 'pagos', 'total_pagos', 'payment_methods', 'pse', 'tdocumento'));


         }

      }

    }

  }


  public function orderProcesarTicket(Request $request)
    {

     
      $input=$request->all();

      $cart= \Session::get('cart');

      $carrito= \Session::get('cr');

      $id_orden= \Session::get('orden');



      $orden=AlpOrdenes::where('id', $id_orden)->first();

      $total=$orden->monto_total;

      $impuesto=$orden->monto_impuesto;

        $net_amount=$total-$impuesto;


      if (Sentinel::check()) {

        $user_id = Sentinel::getUser()->id;

        $user_cliente=User::where('id', $user_id)->first();


         $configuracion = AlpConfiguracion::where('id', '1')->first();

          MP::setCredenciales($configuracion->id_mercadopago, $configuracion->key_mercadopago);

     
           $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.descripcion_corta as descripcion_corta','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug')
          ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
          ->where('alp_ordenes_detalle.id_orden', $orden->id)->get();

      $det_array = array();



      foreach ($detalles as $d ) {


        $det_array[]= array(

                "id"          => $d->id_producto,
                "title"       => $d->nombre_producto,
                "description" => $d->descripcion_corta,
                "quantity"    => (int)number_format($d->cantidad, 0, '.', ''),
                "unit_price"  => (float)number_format($d->precio_unitario, 2, '.', ''),
                );

       
      }

           $preference_data = [
            "transaction_amount" => doubleval($orden->monto_total),
            "external_reference" =>"ALP".$orden->id."",
            "description" => 'Pago de orden: '.$orden->id,
            "payment_method_id" => $request->idpago,
            "payer" => [
              "email"=>$user_cliente->email],
              "additional_info"=> [
                "items" => $det_array ],
            "net_amount"=>(float)number_format($net_amount, 2, '.', ''),
            "taxes"=>[[
              "value"=>(float)number_format($impuesto, 2, '.', ''),
              "type"=>"IVA"]]
          ];




          $payment = MP::post("/v1/payments",$preference_data);


          if (isset($payment['response']['id'])) {
           
          
          // 1.- eststus orden, 2.- estatus pago, 3 json pedido 
          $data=$this->generarPedido('8', '4', $payment, 'mercadopago');

          $id_orden=$data['id_orden'];

          $fecha_entrega=$data['fecha_entrega'];



           $aviso_pago="Hemos procesado su orden satisfactoriamente, Su id para realizar el deposito en efectivo es <h4>".$payment['response']['id']."</h4>. Las indicaciones para finalizar su pago puede seguirlas en este enlace <a target='_blank' href='".$payment['response']['transaction_details']['external_resource_url']."' >Ticket</a>. Tiene 72 Horas para realizar el pago, o su orden sera cancelada. ¡Muchas gracias por su Compra!";

         //  $datalles=AlpDetalles::where('id_orden', $orden->id)->get();

          $compra =  DB::table('alp_ordenes')->select('alp_ordenes.*','users.first_name as first_name','users.last_name as last_name' ,'users.email as email','alp_formas_envios.nombre_forma_envios as nombre_forma_envios','alp_formas_envios.descripcion_forma_envios as descripcion_forma_envios','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago','alp_formas_pagos.descripcion_forma_pago as descripcion_forma_pago','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.doc_cliente as doc_cliente')
              ->join('users','alp_ordenes.id_cliente' , '=', 'users.id')
              ->join('alp_clientes','alp_ordenes.id_cliente' , '=', 'alp_clientes.id_user_client')
              ->join('alp_formas_envios','alp_ordenes.id_forma_envio' , '=', 'alp_formas_envios.id')
              ->join('alp_formas_pagos','alp_ordenes.id_forma_pago' , '=', 'alp_formas_pagos.id')
              ->where('alp_ordenes.id', $id_orden)->first();


          $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug')
            ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
            ->where('alp_ordenes_detalle.id_orden', $id_orden)->get();

           $states=State::where('config_states.country_id', '47')->get();

           $configuracion = AlpConfiguracion::where('id','1')->first();

            $user_cliente=User::where('id', $user_id)->first();

            $texto='Se ha creado la siguiente orden '.$compra->id.' y esta a espera de aprobacion  ';

            //Mail::to($user_cliente->email)->send(new \App\Mail\NotificacionOrden($compra->id, $texto));

           // Mail::to($configuracion->correo_cedi)->send(new \App\Mail\NotificacionOrden($compra->id, $texto));


        //  Mail::to($user_cliente->email)->send(new \App\Mail\CompraRealizada($compra, $detalles, $fecha_entrega));

        //  Mail::to($configuracion->correo_sac)->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega));

            $estatus_aviso='success';
            

            return view('frontend.order.procesarticket', compact('compra', 'detalles', 'fecha_entrega', 'states', 'aviso_pago', 'payment', 'estatus_aviso'));

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

        $configuracion = AlpConfiguracion::where('id','1')->first();



        $id_orden= \Session::get('orden');

        $orden=AlpOrdenes::where('id', $id_orden)->first();

        $total=$this->total();

        $user_id = Sentinel::getUser()->id;

        $direccion=AlpDirecciones::where('id', $orden->id_address)->first();

        $feriados=AlpFeriados::feriados();

        $ciudad_forma=AlpFormaCiudad::where('id_forma', $orden->id_forma_envio)->where('id_ciudad', $direccion->city_id)->first();


        $date = Carbon::now();

        $hora=$date->format('hi');

        $hora_base=str_replace(':', '', $ciudad_forma->hora);

        if (intval($hora)>intval($hora_base)) {

          $ciudad_forma->dias=$ciudad_forma->dias+1;

        }

        for ($i=1; $i <=$ciudad_forma->dias ; $i++) { 

          $date2 = Carbon::now();

          $date2->addDays($i);

          if ($date2->isWeekend()) {

            $ciudad_forma->dias=$ciudad_forma->dias+1;
          
          }else{

            if (isset($feriados[$date2->format('Y-m-d')])) {

                $ciudad_forma->dias=$ciudad_forma->dias+1;
             
            }

          }

          
        }

        $fecha_entrega=$date->addDays($ciudad_forma->dias)->format('d-m-Y');

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
          'retencion_fuente_mp' =>$orden->base_impuesto*$retencion_fuente_mp,
          'retencion_iva_mp' =>$orden->monto_impuesto*$retencion_iva_mp,
          'retencion_ica_mp' =>$orden->base_impuesto*$retencion_ica_mp
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

         return array('id_orden' => $orden->id, 'fecha_entrega' => $fecha_entrega   );

  
    }






      public function success(Request $request)
    {


   /* if ($request->collection_status=='approved') {*/
     
      $input=$request->all();

      $cart= \Session::get('cart');

      $id_orden= \Session::get('orden');

      $total=$this->total();

      if (Sentinel::check()) {

        $user_id = Sentinel::getUser()->id;

        $data=$this->generarPedido('1', '2', $input, 'mercadopago');

        $id_orden=$data['id_orden'];
        
        $fecha_entrega=$data['fecha_entrega'];

         $aviso_pago="Hemos recibido su pago satisfactoriamente, una vez sea confirmado, Le llegará un email con la descripción de su pago. ¡Muchas gracias por su Compra!";

       //  $datalles=AlpDetalles::where('id_orden', $orden->id)->get();

        $compra =  DB::table('alp_ordenes')->select('alp_ordenes.*','users.first_name as first_name','users.last_name as last_name' ,'users.email as email','alp_formas_envios.nombre_forma_envios as nombre_forma_envios','alp_formas_envios.descripcion_forma_envios as descripcion_forma_envios','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago','alp_formas_pagos.descripcion_forma_pago as descripcion_forma_pago','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.doc_cliente as doc_cliente')
            ->join('users','alp_ordenes.id_cliente' , '=', 'users.id')
            ->join('alp_clientes','alp_ordenes.id_cliente' , '=', 'alp_clientes.id_user_client')
            ->join('alp_formas_envios','alp_ordenes.id_forma_envio' , '=', 'alp_formas_envios.id')
            ->join('alp_formas_pagos','alp_ordenes.id_forma_pago' , '=', 'alp_formas_pagos.id')
            ->where('alp_ordenes.id', $id_orden)->first();


        $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug')
          ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
          ->where('alp_ordenes_detalle.id_orden', $id_orden)->get();


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

         $data=$this->generarPedido('8', '4', $input, 'mercadopago');

        $id_orden=$data['id_orden'];
        
        $fecha_entrega=$data['fecha_entrega'];

         $aviso_pago="Su pago está siendo procesado , deberá finalizar el proceso en 24 horas o su pedido será cancelado.!";

       //  $datalles=AlpDetalles::where('id_orden', $orden->id)->get();

        $compra =  DB::table('alp_ordenes')->select('alp_ordenes.*','users.first_name as first_name','users.last_name as last_name' ,'users.email as email','alp_formas_envios.nombre_forma_envios as nombre_forma_envios','alp_formas_envios.descripcion_forma_envios as descripcion_forma_envios','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago','alp_formas_pagos.descripcion_forma_pago as descripcion_forma_pago','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.doc_cliente as doc_cliente')
            ->join('users','alp_ordenes.id_cliente' , '=', 'users.id')
            ->join('alp_clientes','alp_ordenes.id_cliente' , '=', 'alp_clientes.id_user_client')
            ->join('alp_formas_envios','alp_ordenes.id_forma_envio' , '=', 'alp_formas_envios.id')
            ->join('alp_formas_pagos','alp_ordenes.id_forma_pago' , '=', 'alp_formas_pagos.id')
            ->where('alp_ordenes.id', $id_orden)->first();


        $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.referencia_producto as referencia_producto','alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug')
          ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
          ->where('alp_ordenes_detalle.id_orden', $id_orden)->get();

         $cart= \Session::forget('cart');

         $states=State::where('config_states.country_id', '47')->get();

         $configuracion = AlpConfiguracion::where('id','1')->first();

          $user_cliente=User::where('id', $user_id)->first();

          $texto='Se ha creado la siguiente orden '.$compra->id.' y está en proceso de espera para aprobación de pago, deberá completar el pago en 24 horas o la orden será cancelada';


        Mail::to($user_cliente->email)->send(new \App\Mail\CompraRealizada($compra, $detalles, $fecha_entrega));

        Mail::to($configuracion->correo_sac)->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega));
          

          return view('frontend.order.procesar', compact('compra', 'detalles', 'fecha_entrega', 'states', 'aviso_pago'));

      }else{

          return redirect('login');
      }


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

      $id_orden= \Session::get('orden');

      $orden=AlpOrdenes::where('id', $id_orden)->first();



      $aviso_pago='0';

      $configuracion = AlpConfiguracion::where('id','1')->first();


      if (Sentinel::check()) {

        $user_id = Sentinel::getUser()->id;

        $direccion=AlpDirecciones::where('id', $orden->id_address)->first();

       // dd($direccion);

        $ciudad_forma=AlpFormaCiudad::where('id_forma', $orden->id_forma_envio)->where('id_ciudad', $direccion->city_id)->first();

        $feriados=AlpFeriados::feriados();



        $date = Carbon::now();

        $hora=$date->format('hi');

        $hora_base=str_replace(':', '', $ciudad_forma->hora);

        if (intval($hora)>intval($hora_base)) {
          $ciudad_forma->dias=$ciudad_forma->dias+1;
        }

        for ($i=1; $i <=$ciudad_forma->dias ; $i++) { 

          $date2 = Carbon::now();

          $date2->addDays($i);

          if ($date2->isWeekend()) {

            $ciudad_forma->dias=$ciudad_forma->dias+1;
          
          }else{

            if (isset($feriados[$date2->format('Y-m-d')])) {

                $ciudad_forma->dias=$ciudad_forma->dias+1;
             
            }

          }

          
        }


       $fecha_entrega=$date->addDays($ciudad_forma->dias)->format('d-m-Y');





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
          'estatus' =>'1', 
          'estatus_pago' =>'1', 
        );

         $orden->update($data_update);


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

        

        $carrito= \Session::forget('cr');

        \Session::forget('orden');


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

       if (isset($request->datasingle)) {

        $datasingle=$request->datasingle;
       
        $view= View::make('frontend.order.botones', compact('producto', 'cart', 'datasingle'));
        
       }else{

       $view= View::make('frontend.order.botones', compact('producto', 'cart'));


       }


          $data=$view->render();

          $res = array('data' => $data);

          return $data;
       // return json_encode($cart);
      
    }


        public function addtocartdetail( Request $request)
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


      $impuesto=$this->impuesto();

       $total=$this->total();

     $configuracion=AlpConfiguracion::where('id', '1')->first();
       

        $view= View::make('frontend.listcart', compact('producto', 'cart', 'total', 'impuesto', 'configuracion'));
        

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

       $single=1;

       $view= View::make('frontend.order.botones', compact('producto', 'cart', 'single'));

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


    public function updatecartdetalle(Request $request)
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

      $impuesto=$this->impuesto();


       $total=$this->total();

     $configuracion=AlpConfiguracion::where('id', '1')->first();


        $view= View::make('frontend.listcart', compact('producto', 'cart', 'total', 'impuesto', 'configuracion'));

        $data=$view->render();

        return $data;
      
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

          $view= View::make('frontend.order.direcciones', compact('direcciones'));

          $data=$view->render();

          $res = array('data' => $data);
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


       $carrito= \Session::get('cr');

      $cart=$this->reloadCart();

      $total=$this->total();

      $impuesto=$this->impuesto();

      if (isset($ciudad->id)) {

        if (!\Session::has('orden')) {
         
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

            $base_impuesto=$base_impuesto+$total_detalle;

            $valor_impuesto=$detalle->valor_impuesto;
            
          }else{

            $base_imponible_detalle=0;

            $base_impuesto=$base_impuesto+$total_detalle;

            //$valor_impuesto=0;
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
            'cantidad' =>$detalle->cantidad, 
            'operacion' =>'2', 
            'id_user' =>$user_id 
          );

          AlpDetalles::create($data_detalle);

          AlpInventario::create($data_inventario);

        }//endfreach

         $data_update = array(
            'referencia' => 'ALP'.$orden->id,
            'monto_total_base' => $monto_total_base,
            'base_impuesto' => $base_impuesto,
            'monto_impuesto' => ($base_impuesto/(1+$valor_impuesto))*$valor_impuesto,
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

       }

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

           if ($configuracion->mercadopago_sand=='1') {
          
          $mp::sandbox_mode(TRUE);

        }

        if ($configuracion->mercadopago_sand=='2') {
          
          $mp::sandbox_mode(FALSE);

        }

            MP::setCredenciales($configuracion->id_mercadopago, $configuracion->key_mercadopago);

          $preference = MP::post("/checkout/preferences",$preference_data);

          $this->saveOrden($preference);

          $pse = array();

          $payment_methods = MP::get("/v1/payment_methods");
         
          $carro=AlpCarrito::where('id', $carrito)->first();


          $data_carrito = array(
            'id_user' => $user_id );

          $carro->update($data_carrito);

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
