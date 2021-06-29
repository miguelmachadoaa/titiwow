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

use App\Models\AlpOrdenesDescuentoIcg;
use App\Models\AlpConsultaIcg;

use App\Models\AlpSaldo;
use App\Models\AlpRolenvio;

use App\Models\AlpPromociones;
use App\Models\AlpPromocionesRegalo;
use App\Models\AlpPromocionesCategorias;

use App\Models\AlpAbonosDisponible;


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
     * Funcion Show
     * Descripción: Muestra el ddetalle de la compra en el carrito 
     * 
     * Variables:
     * $cart = contenido del carrito 
     *
     * $mensaje_promocion=Verifica si exite promociones activas y muestra el mensaje 
     *
     * reloadCart, recarga el carrito para verificar el cambio de precio por rol.
     *
     * $combos=contiene un array de combos disponibles para el momento, este verifica la exitencia de cada producto dentro del combo, si esta en la lista es visible 
     *
     * $total=contiene el monto total de la compra.
     *
     * $inv Contiene el inventario disponible de los productos de la tienda 
     *
     * $id_almacen contiene el almacen de la compra.
     *
     * $productos = Contiene los productos disponibles para la seccion de relacionados
     * 
     * @return View
     */

    public function show()

    {


      \Session::forget('aviso');

      $cart= \Session::get('cart');

      if (isset($cart['id_forma_pago']) || isset($cart['id_forma_envio']) || isset($cart['id_cliente']) || isset($cart['id_almacen']) || isset($cart['id_direccion']) || isset($cart['inventario']) ) {

        $cart= \Session::forget('cart');

        \Session::put('cart', array());
      
      }

                 
      //$fecha=$this->getFechaEntrega('2', '62');
                 
      $states=State::where('config_states.country_id', '47')->get();

      $mensaje_promocion=$this->addPromocion();

      $cart=$this->reloadCart();

      $combos=$this->combos();
      
      $configuracion=AlpConfiguracion::where('id', '1')->first();

      $total=$this->total();
      
      $inv=$this->inventario();

      $id_almacen=$this->getAlmacenCart();

      $descuento='1'; 
      
      $precio = array();

         if (\Session::has('cr')) {

          $carrito= \Session::get('cr');

          $cupones=AlpOrdenesDescuento::where('id_orden', $carrito)->get();

            foreach ($cupones as $cupon) {

              $c=AlpOrdenesDescuento::where('id', $cupon->id)->first();
              
              if (isset($c->id)) {

                $c->delete();

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



     /**
    * Funcion Gracias 
    * Descrioción: Pagina de resumen de compra, muestra el detalle de la compra con forma de pago, envio y fecha posible de entrega, se usa para enlazar con google analitycs 
    * 
    * 
    * Variables:
    *
     * $id = Id de la orden 
     * 
    
    *   $compra =  Contendido de la orden.
    *
    *   $detalles = Detalle de la compra, articulos precios y cantidad 
    *
    * $envio = Contiene la informacion del envio 
    *  $valor_impuesto =  cpontiene el monto de impuesto de la orden 
    *  $fecha_entrega =  fecha de emtrega de la porden 
    *  $user_cliente =  contiene la informacion del cliente de la compra 
    *  $aviso_pago =  Contiene mensaje personalizado de el pago de la orden 
    *  $metodo =  contiene la fomra de pago de la orden 
    * $estatus_aviso =  contiene mensaje personalizado de el estatus en general de la orden 
     * 
     * @return View
     */

    

     public function gracias($id)
    {


       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($id)
                        ->log('cart/gracias ');

        }else{

          activity()
          ->withProperties($id)
          ->log('cart/gracias ');


        }

        if (isset($user->id)) {
          
          $role=RoleUser::select('role_id')->where('user_id', $user->id)->first();

        }


       

      
      $id=$id/1024;

      $compra =  DB::table('alp_ordenes')->select('alp_ordenes.*','users.first_name as first_name','users.last_name as last_name' ,'users.email as email','alp_formas_envios.nombre_forma_envios as nombre_forma_envios','alp_formas_envios.descripcion_forma_envios as descripcion_forma_envios','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago','alp_formas_pagos.descripcion_forma_pago as descripcion_forma_pago','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.doc_cliente as doc_cliente')
      ->join('users','alp_ordenes.id_cliente' , '=', 'users.id')
      ->join('alp_clientes','alp_ordenes.id_cliente' , '=', 'alp_clientes.id_user_client')
      ->join('alp_formas_envios','alp_ordenes.id_forma_envio' , '=', 'alp_formas_envios.id')
      ->join('alp_formas_pagos','alp_ordenes.id_forma_pago' , '=', 'alp_formas_pagos.id')
      ->where('alp_ordenes.id', $id)->first();

      if (isset($compra->id)) {


        $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug','alp_productos.presentacion_producto as presentacion_producto')
            ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
            ->where('alp_ordenes_detalle.id_orden', $id)
            ->whereNull('alp_ordenes_detalle.deleted_at')
            ->get();

            $pago=AlpPagos::where('id_orden', $id)->first();

            if (isset($pago->id)) {
              
              if ($compra->id_forma_pago=='3' || $compra->id_forma_pago=='1') {
                $payment=null;

              }else{
                $payment=json_decode($pago->json);
              }


            }else{
              $payment=null;
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

              $aviso_pago="Hemos procesado su orden satisfactoriamente, Su id para realizar el deposito en efectivo es <h4> ".$payment->response->id." </h4>. Las indicaciones para finalizar su pago puede seguirlas en este enlace <a target='_blank' href='".$payment->response->transaction_details->external_resource_url."' >Ticket</a>. Tiene 48 Horas para realizar el pago, o su orden sera cancelada. ¡Muchas gracias por su Compra!";

            }

            if ($payment->response->payment_type_id=='credit_card' ) {

               $estatus_aviso='warnsing';

              $aviso_pago="Hemos recibido su pago satisfactoriamente, una vez sea confirmado, Le llegará un email con la descripción de su pago. ¡Muchas gracias por su Compra!";

              $metodo=$payment->response->payment_type_id;

            }

          }

          
        }

        $descuentos=AlpOrdenesDescuento::where('id_orden', $id)->get();


         $cupo_icg=null;

         $cupo_icg_total=null;

         $descuentosIcg=null;

         $porcentaje_icg=0;

         if (isset($role->role_id)) {

          if ($role->role_id=='16') {

          $descuentosIcg=AlpOrdenesDescuentoIcg::where('id_orden','=', $id)->get();

          $di=0;

          foreach ($descuentosIcg as $dd) {
            
            $di=$di+$dd->monto_descuento;

          }

       // dd($di);

          if ($di>0) {

            $cupo_icg=$this->consultaIcg($id);

            $cupo_icg_total=$this->consultaIcgTotal($id);

            $porcentaje_icg=($cupo_icg/$cupo_icg_total)*100;

          }

        }

        }


       



          

        return view('frontend.order.gracias', compact('compra', 'detalles', 'fecha_entrega', 'states', 'aviso_pago', 'payment', 'estatus_aviso', 'metodo', 'envio', 'envio_base', 'envio_impuesto', 'descuentos', 'descuentosIcg', 'cupo_icg', 'cupo_icg_total', 'porcentaje_icg'));


        }else{
        
          abort('404');

        }

        
    }

    



    public function orderRapipago(){
      
      $path='uploads/productos/';

      $dir = opendir($path);
      
      $files = array();
      
      while ($current = readdir($dir)){

          if( $current != "." && $current != "..") {

              if(is_dir($path.$current)) {
                
              } else {
                
                  echo $current.'<br>';

                  $imageSizeArray = getimagesize($path.$current);

                $imageTypeArray = $imageSizeArray[2];

                if (in_array($imageTypeArray , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP))) {

                   $image = \Image::make(file_get_contents(public_path().'/'.$path.$current));

                   $image->resize(200,200);

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

      $almacen=AlpAlmacenes::where('id', $orden->id_almacen)->first();

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
      
      $comision_mp=$almacen->comision_mp_pse/100;
      
      $data_update = array(

          'comision_mp' =>(($orden->monto_total*$comision_mp)+($orden->monto_total*$comision_mp*0.19)),

           );

      $orden->update($data_update);

          $mp = new MP();

        
        if ($almacen->mercadopago_sand=='1') {

          

          $mp::sandbox_mode(TRUE);

          
        }

        
        if ($almacen->mercadopago_sand=='2') {

          

          $mp::sandbox_mode(FALSE);

          
        }

        
        MP::setCredenciales($almacen->id_mercadopago, $almacen->key_mercadopago);

        
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

           "external_reference": "'.$orden->referencia_mp.'",

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

          

        } catch (\Exception $e) {

            activity()->withProperties(1)
                                    ->log('error envio de correo l1228');

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

      /**
     * Funcion orderPse
     * Descripción: Captura la respuesta de pse y procesa la orden y redifge a gracias 
     * 
     * Variables:
     *
     *
     * id_pago= Id pago que llega desde pse, con este dato se consulta la orden
    *  
    *  fecha_entrega = Fecha de entrega posible del pedido calculada en la funcion generarPedido
    *  
    *  compra =  Detalle de la compra
    *  
    *  detalles = Productos de la compra
      *  
     *
     * funcions
     *
     * generarPedido = Procesa y actualiza la orden, vacia el carrito de compras
     * 
     * @return View
     */

    


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

          $id_orden= \Session::get('orden');
      
        $orden=AlpOrdenes::where('id', $id_orden)->first();

        $almacen=AlpAlmacenes::where('id', $orden->id_almacen)->first();

        
          $configuracion = AlpConfiguracion::where('id', '1')->first();

            $mp = new MP();

            if ($almacen->mercadopago_sand=='1') {

              $mp::sandbox_mode(TRUE);

            }

            
            if ($almacen->mercadopago_sand=='2') {

              $mp::sandbox_mode(FALSE);

            }

            MP::setCredenciales($almacen->id_mercadopago, $almacen->key_mercadopago);

            
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
                    ->whereNull('alp_ordenes_detalle.deleted_at')
                    ->where('alp_ordenes_detalle.id_orden', $id_orden)->get();

                    
         $states=State::where('config_states.country_id', '47')->get();

         
         $configuracion = AlpConfiguracion::where('id','1')->first();

         
          $user_cliente=User::where('id', $user_id)->first();

                    if (isset($compra->id)) {
                      $texto='Se ha creado la siguiente orden '.$compra->id.' y esta a espera de aprobacion  ';
                      # code...
                      }else{

            $texto='Se ha creado la  orden, y esta a espera de aprobacion  ';
                      }

                        try {

                           Mail::to($user_cliente->email)->send(new \App\Mail\CompraRealizada($compra, $detalles, $fecha_entrega));

                          Mail::to($configuracion->correo_sac)->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega));

                        } catch (\Exception $e) {

                            activity()->withProperties(1)
                                    ->log('error envio de correo l1507');

                      }

                           

     
           if (isset($compra->id_forma_envio)) {

            if ($compra->id_forma_envio!=1) {

              try {

                $formaenvio=AlpFormasenvio::where('id', $compra->id_forma_envio)->first();

                Mail::to($formaenvio->email)->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega, 1));

                Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega, 1));

              } catch (\Exception $e) {

                    activity()->withProperties(1)
                                            ->log('error envio de correo');

                      }

                    }

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

/**
     * Funcion Notificacion
     * Descripción: Captura las notificaciones automaticas desde mercadopago
     * 
     * Variables:
     *
     * input = Datos que llegan desde mercadopago por metodo post 
     *
     * pago = Verificacion con los pagos regstrados en la plataforma 
     *
     * Orden = Orden relacionada a la notificacion de mercadopago
     *
     * pse = Respuesta de Mercadopago a la consulta del estado del pago 
     * 
     * envio = Consulta del envio de la orden 
     *
    *  compra =  Detalle de la compra
    *  
    *  detalles = Productos de la compra
    *
    *   formaenvio = Forma de envio relacionada a la compra
    *
    *   data_pago = Datos del pago recibido por mercadopafo 
      *  
     *
     * funcions
     *
     * MP = Instancia de api mercadopago
     * 
     * @return View
     */



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

        if (isset($pago->id)) {

          $orden=AlpOrdenes::where('id', $pago->id_orden)->first();
        
          try {

            $pse = MP::get("/v1/payments/".$input['data_id']);


          } catch (MercadoPagoException $e) {

            $pse = $input;

            activity()->withProperties(1)
                                    ->log('error mercadopago notificaion l1695');

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

                                      try {

                      Mail::to($formaenvio->email)->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega,1));

                      Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega,1));

                    } catch (\Exception $e) {
                                            activity()->withProperties(1)
                                              ->log('error envio de correo l1844');
                                            }


                  

                }

                
                try {

                   Mail::to($compra->email)->send(new \App\Mail\CompraRealizada($compra, $detalles, $envio->fecha_envio));

                  Mail::to($configuracion->correo_sac)->send(new \App\Mail\CompraSac($compra, $detalles, $envio->fecha_envio));

                  Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\CompraRealizada($compra, $detalles, $envio->fecha_envio));

                  Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\CompraSac($compra, $detalles, $envio->fecha_envio));

                } catch (\Exception $e) {
                                    activity()->withProperties(1)
                                          ->log('error envio de correol1868');
                                        }

               

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

       /**
     * Funcion orderCreditcard
     * 
     * Descripción: Captura de datos de notificacion de marcadopago con pagos realizados con tarjeta de Credito 
     * 
     * Variables:
     *
     * avisos= Mensajes preestablecidos de estados de compra en mercadopago 
     *
     * carrito = Varible de session que almacena el id de orden temporal 
     *
     * id_orden = Id de orden de compra
     *
     * orden = detalle de la orden de compra
     *
     * input = Datos que llegan desde mercadopago por metodo post 
     *
     * pago = Verificacion con los pagos regstrados en la plataforma 
     *
     * Orden = Orden relacionada a la notificacion de mercadopago
     *
     * pse = Respuesta de Mercadopago a la consulta del estado del pago 
     * 
     * envio = Consulta del envio de la orden 
     *
     * valor_impuesto = valor del impuesto de la compra, se usa para calculo del monto de impuesto 
     *
     * total =  monto total de la compra
     *
     * impuesto = Monto de impuesto de la comrpa
     *
     * net_amount = monto de compra antes de impuesto
     *
     * det_array = contiene informacion que se procesa en mercadopago 
     *
     * direccion = Consulta de la direccion de la orden 
     *
     * address = Datos de la direccion que se envian a mercadopago 
    *  
    *  compra =  Detalle de la compra
    *  
    *  detalles = Productos de la compra
    *
    *   formaenvio = Forma de envio relacionada a la compra
    *
    *   data_pago = Datos del pago recibido por mercadopafo 
    *
    * additional_info = Contiene los articulos de la compra e informacion del cliente que se envia a mercadopago 
    *
    * preference_data = Array de datos que contiene toda la informacion que se envia a mercadopago
    *
    *data_pago = datos que se guardan en la tabla pagos para la orden  
    *
    * 
    *
    * payer = Detalles del cliente que se envian a mercadopago 
      *  
     *
     * funcions
     *
     * MP = Instancia de api mercadopago
     *
     * generarPedido = Procesa y actualiza la orden, vacia el carrito de compras
     * 
     * @return View
     */

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

      $almacen=AlpAlmacenes::where('id', $orden->id_almacen)->first();

      
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

        
        MP::setCredenciales($almacen->id_mercadopago, $almacen->key_mercadopago);

        

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

          "binary_mode" => true,

          "description" => 'Pago de orden: '.$orden->id,

          "installments" => intval($request->installments),

          "external_reference"=> "".$orden->referencia_mp."",

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

    
     /**
     * Funcion orderDetail
     * 
     * Descripción: Informacion para construir la vista de checkout 
     * 
     * Variables:
     *
     * cart= carrito de la compra
     *
     * carrito = Varible de session que almacena el id de orden temporal 
     *
     * total = Total de la compra
     *
     * total_base = total de la compra antes de impuesto 
     *
     * impuesto = monto de impuesto de la compra 
     *
     * id_almacen = almacen de la compra 
     *
     * almacen = contenido del almacen 
     *
     * id_orden = Id de orden de compra
     *
     * inv = inventario disponible del almacen actual 
     *
     * direcciones = direcciones del cliente 
     * 
     * afe = Formas de Envio por Almacen 
     * 
     * formasenvio   = Formas de Envio que se envian a la vista 
     * 
     * formaspago = Formas de pago quue se envian a la vista 
     *
     * pagos  = Pagos cargados a la orden 
     *  
     * total_descuentos = total de cupones y descuentos de la orden 
     *  
     * descuentos = lista de cupones alpllicados 
     *  
     * descuentosIcg  = descuento de icg aplicado a la orden 
     * 
     * total_descuentos_icg  = monto de descuento icg aplicado a la orden 
     * 
     * preference_data = datos enviados a mercadopago para codigo de pago con creditcard
     * 
     * net_amount=monto neto de la orden 
     * 
     * payment_methods = Metodos de pago activos por mercadopago 
     *
     *tdocumento = Listado de tipos de documentos
     *
     *estructura = listado de tipos de estructiras para direccion 
     *
     *labelpagos = mensaje personalizado para cada tipo de pago 
     * 
     *costo_envio = Costo del envio a la direccion asiganda 
     *
     *ciudad_forma = listado de ciudades disponibles para envio 
     *
     *feriados = listado de dias feriados 
     *
     *saldo = Saldo disponible para clientes alpina 
 
      *  
     *
     * funcions
     *
     * MP = Instancia de api mercadopago
     *
     * addPromocion  = Agrega un mensaje promocional si aplica 
     *
     * reloadCart = Recarga el carrito para actulizacion de precios 
     *
     * total = Devuelve el total generarl del carrito 
     *
     * precio_base = Devuelve el precio base total del carrito 
     *
     * Impuerto = Devuelve el monto de impuesto del carrito 
     *
     * getAlmacenCart = Devuelre el almacen para que el aplica la compra 
     * 
     * @return View
     */
    

    public function orderDetail()
    {

      
      $configuracion=AlpConfiguracion::where('id', '1')->first();

      $carrito= \Session::get('cr');
      
      $cart=$this->addPromocion();

      $cart=$this->reloadCart();
      
      $total=$this->total();
      
      $total_base=$this->precio_base();

      $base_imponible=$this->base_imponible();

      $impuesto=$this->impuesto();
      
      $id_almacen=$this->getAlmacenCart();

      $almacen=AlpAlmacenes::where('id', $id_almacen)->first();

      if ($total<0 ){
        
        return redirect('cart/show');
        
      }


      if (!isset($almacen->id) ){

        return redirect('cart/show');
        
      }

      


      foreach ($cart as $vcart) {

          if (isset($vcart->id)) {

            if ($vcart->disponible==0) {
              
            if (isset($vcart->promocion)) {

            }else{
              
                return redirect('cart/show');

            }

          }

        }
      }

      

      if (Sentinel::check()) {

        
        $user = Sentinel::getUser();

        
        activity($user->full_name)

                    ->performedOn($user)

                    ->causedBy($user)

                    ->withProperties($cart)

                    ->log('Orden Detail');

                    

        $user_id = Sentinel::getUser()->id;
        
        $usuario=User::where('id', $user_id)->first();
        
        $user_cliente=User::where('id', $user_id)->first();

        $role=RoleUser::select('role_id')->where('user_id', $user_id)->first();
        
        $cupo_icg=0;

        $cupo_credito_icg=0;

        $descuento_compra_icg=0;

        if (isset($role->role_id)) {

          if ($role->role_id=='16') {
            
               $cupo_icg=$this->consultaIcg();


               $cupo_credito_icg=$this->consultaCreditoIcg();

              // dd($cupo_credito_icg);


               $descuento_compra_icg=($total-$impuesto)*($configuracion->porcentaje_icg/100);

              # dd($impuesto);

               if ($descuento_compra_icg>$cupo_icg) {
           
                  $descuento_compra_icg=$cupo_icg;
                  
              } 

          }
        }

      #  dd($descuento_compra_icg);

        $r=Roles::where('id', $role->role_id)->first();

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

          

            $descuentos=AlpOrdenesDescuento::where('id_orden','=', $carrito)->get();

            
            foreach ($descuentos as $pago) {

              
              $total_pagos=$total_pagos+$pago->monto_descuento;

              
              $total_descuentos=$total_descuentos+$pago->monto_descuento;

              
            }


             $total_descuentos_icg=0;

            $descuentosIcg= array();

            if (isset($role->role_id)) {

              if ($role->role_id=='16') {

                $descuentosIcg=AlpOrdenesDescuentoIcg::where('id_orden','=', $carrito)->get();

                  foreach ($descuentosIcg as $pagoi) {

                    $total_pagos=$total_pagos+$pagoi->monto_descuento;

                    $total_descuentos_icg=$total_descuentos_icg+$pagoi->monto_descuento;

                  }
               }
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


            $afe_mp=AlpAlmacenFormaPago::where('id_almacen', $id_almacen)->where('id_forma_pago', '2')->first();


          if (isset($afe_mp->id)) {

            if (!is_null($almacen->id_mercadopago) &&  !is_null($almacen->key_mercadopago)) {



              $mp = new MP();
           
           if ($almacen->mercadopago_sand=='1') {

              $mp::sandbox_mode(TRUE);
            
            }
        
            if ($almacen->mercadopago_sand=='2') {

              $mp::sandbox_mode(FALSE);
              
            }

            MP::setCredenciales($almacen->id_mercadopago, $almacen->key_mercadopago);

            try {

             // $preference = MP::post("/checkout/preferences",$preference_data);

              $preference = array();

              $payment_methods = MP::get("/v1/payment_methods");

                 $this->saveOrden($preference);
              
            } catch (MercadoPagoException $e) {

              $preference = array();

              $payment_methods = array();
              
            }



              
            }else{

               $preference = array();

              $payment_methods = array();


            }
            
        }else{

          $preference = array();
          $payment_methods = array();

        }

          
          $net_amount=$total-$impuesto;
          
         $pse = array();


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
          ->where('alp_almacen_formas_envio.id_forma_envio', $id_forma_envio)
          ->where('alp_almacen_formas_envio.id_almacen', $id_almacen)
          ->whereNull('alp_almacen_formas_envio.deleted_at')
          ->first();

          if (isset($fev->id)) {

          }else{

              $fev = AlpFormasenvio::select('alp_formas_envios.*')
                ->join('alp_almacen_formas_envio', 'alp_formas_envios.id', '=', 'alp_almacen_formas_envio.id_forma_envio')
               ->where('alp_almacen_formas_envio.id_almacen', $id_almacen)
                ->whereNull('alp_almacen_formas_envio.deleted_at')
                ->first();

                
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

          }else{

            $saldo[$user->id]=0;

          }

          $url=secure_url('order/detail');

          $bono_disponible = AlpAbonosDisponible::groupBy('alp_abono_disponible.id_cliente')
              ->select("alp_abono_disponible.*", DB::raw(  "SUM(alp_abono_disponible.valor_abono) as total"))
              ->where('alp_abono_disponible.id_cliente', $user->id)
              ->first();


          $pagos=AlpPagos::select('alp_ordenes_pagos.*', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago')->where('id_orden', $carrito)->join('alp_formas_pagos', 'alp_ordenes_pagos.id_forma_pago','=', 'alp_formas_pagos.id')->whereIn('id_estatus_pago', ['1', '2'])->get();

          $total_pagos=0;

          foreach ($pagos as $pago) {

            $total_pagos=$total_pagos+$pago->monto_pago;

          }

          //dd($cupo_credito_icg);
          
          return view('frontend.order.detail', compact('cart', 'total', 'direcciones', 'formasenvio', 'formaspago', 'countries', 'configuracion', 'states', 'preference', 'inv', 'pagos', 'total_pagos', 'impuesto', 'payment_methods', 'pse', 'tdocumento', 'estructura', 'labelpagos', 'total_base', 'descuentos', 'total_descuentos', 'costo_envio', 'id_forma_envio', 'envio_base', 'envio_impuesto', 'express', 'saldo', 'user','role', 'url', 'cupo_icg', 'total_descuentos_icg', 'descuentosIcg', 'descuento_compra_icg','bono_disponible', 'pagos', 'total_pagos', 'almacen', 'cupo_credito_icg', 'base_imponible'));

          
         }

         

      }else{

        
        $url='order.detail';

        
          //return redirect('login');

        return view('frontend.order.login', compact('url'));

        
      }

      
    }

      /**
     * Funcion orderProcesarTicket
     * Descripción: Captura la respuesta de baloto y efecty  y procesa la orden y redifge a gracias 
     * 
     * Variables:
     *
     *input = datos recibidos para procesar el pago 
     *
     *cart  = contenido del carrito de compra 
     *
     *carrito = id de orden tempora 
     *
     *id_orden = id de la compra 
     *
     *envio = costo del envio 
     *  
     *valor_impuesto = monto del envio 
     *
     *orden = Detalle de compra  
     *
     *total impuesto = Monto del impuesto 
     *
     * net_amount = monto antes de impuesto de la compra 
     * 
     *detalles = Detalle de los productos de la compra 
     *
     * total_descuentos = monto total de descuentos aplicados 
     * 
     * descuentos = cupones aplicados 
     * 
     * preference_data = datos que se envian a mercadopago 
     * 
     * payment = respuesta de mercadopago 
     * 
     * fecha_entrega = fecha posible de la entrega de la orden 
     * 
     * compra = detalle de compra 
     * 
     * user_cliente = datos del cliente de la compra 
     * 
     * aviso_pago = mensaje personalizado del pago 
     * 
     *metodo = Metodo de pago usado 
      *  
     *
     * funcions
     *
     * generarPedido = Procesa y actualiza la orden, vacia el carrito de compras
     * 
     * @return View
     */

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

      $almacen=AlpAlmacenes::where('id', $orden->id_almacen)->first();

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
         
          MP::setCredenciales($almacen->id_mercadopago, $almacen->key_mercadopago);

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

                "external_reference" =>"".$orden->referencia_mp."",

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

              

             // dd($preference_data);

              
          

            //Log::info($preference_data);

              
            //$payment = MP::post("/v1/payments",$preference_data);

              
            //activity()->withProperties($preference_data)->log('intento de pago pse ');

            

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
                  

              $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug',
                'alp_productos.presentacion_producto as presentacion_producto',
                'alp_productos.tipo_producto as tipo_producto'
              )

                ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')

                ->where('alp_ordenes_detalle.id_orden', $id_orden)->get();

                
               $states=State::where('config_states.country_id', '47')->get();

               
               $configuracion = AlpConfiguracion::where('id','1')->first();

               
                $user_cliente=User::where('id', $user_id)->first();

                
                $texto='Se ha creado la siguiente orden '.$compra->id.' y esta a espera de aprobacion  ';

                

                if ($compra->id_forma_envio!=1) {

                  
                  $formaenvio=AlpFormasenvio::where('id', $compra->id_forma_envio)->first();

                  try {

                     Mail::to($formaenvio->email)->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega, 1));

                    Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega, 1));

                                      } catch (\Exception $e) {

                    activity()->withProperties(1)
                                            ->log('error envio de correo l3970');

                                      }

                 

                }





                foreach ($detalles as $d ) {

                    if ($d->tipo_producto=='4') {

                      $prod=AlpProductos::Where('id', '=', $d->id_producto)->first();

                        Mail::to('miguelmachadoaa@gmail.com')->send(new \App\Mail\NotificacionDigital($prod));

                        activity()->withProperties($prod)
                                            ->log('Correo de digital ');
                      
                       # Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionDigital($prod));

                    }
                    # code...
                  }

           
                $estatus_aviso='success';

                
                $aviso_pago="Hemos procesado su orden satisfactoriamente, Su id para realizar el deposito en efectivo es <h4>".$payment['response']['id']."</h4>. Las indicaciones para finalizar su pago puede seguirlas en este enlace <a target='_blank' href='".$payment['response']['transaction_details']['external_resource_url']."' >Ticket</a>. Tiene 48 Horas para realizar el pago, o su orden sera cancelada. ¡Muchas gracias por su Compra!";

                
                $metodo=$payment['response']['payment_method_id'];

                
                $idc=$compra->id*1024;

                

                return secure_url('cart/'.$idc.'/gracias?pago=pendiente');

                

                \Session::forget('pagando');

                
        }else{

          
          \Session::forget('pagando');

          
          return redirect('order/detail');

          
        }

        

        
      }else{

        
          return redirect('login');

      }

      

}



/**
     * Funcion orderProcesarTicket
     * Descripción: Captura la respuesta de baloto y efecty  y procesa la orden y redifge a gracias 
     * 
     * Variables:
     *
     *input = datos recibidos para procesar el pago 
     *
     *cart  = contenido del carrito de compra 
     *
     *carrito = id de orden tempora 
     *
     *id_orden = id de la compra 
     *
     *envio = costo del envio 
     *  
     *valor_impuesto = monto del envio 
     *
     *orden = Detalle de compra  
     *
     *total impuesto = Monto del impuesto 
     *
     * net_amount = monto antes de impuesto de la compra 
     * 
     *detalles = Detalle de los productos de la compra 
     *
     * total_descuentos = monto total de descuentos aplicados 
     * 
     * descuentos = cupones aplicados 
     * 
     * preference_data = datos que se envian a mercadopago 
     * 
     * payment = respuesta de mercadopago 
     * 
     * fecha_entrega = fecha posible de la entrega de la orden 
     * 
     * compra = detalle de compra 
     * 
     * user_cliente = datos del cliente de la compra 
     * 
     * aviso_pago = mensaje personalizado del pago 
     * 
     *metodo = Metodo de pago usado 
      *  
     *
     * funcions
     *
     * generarPedido = Procesa y actualiza la orden, vacia el carrito de compras
     * 
     * @return View
     */

  public function orderProcesarBono(Request $request)
    {


      $input=$request->all();

      
      if (Sentinel::check()) {

        
         $user = Sentinel::getUser();

         
       activity($user->full_name)

                    ->performedOn($user)

                    ->causedBy($user)

                    ->withProperties($input)

                    ->log('orderProcesarBono pago con bono');

                    
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

      $almacen=AlpAlmacenes::where('id', $orden->id_almacen)->first();

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
         
          MP::setCredenciales($almacen->id_mercadopago, $almacen->key_mercadopago);

           $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.descripcion_corta as descripcion_corta','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug','alp_productos.presentacion_producto as presentacion_producto')
          ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
          ->where('alp_ordenes_detalle.id_orden', $orden->id)->get();
          

          $total_descuentos=0;

            $descuentos=AlpOrdenesDescuento::where('id_orden', $carrito)->get();

            foreach ($descuentos as $pago) {

              $total_descuentos=$total_descuentos+$pago->monto_descuento;
              
            }


            $disponible = AlpAbonosDisponible::groupBy('alp_abono_disponible.id_cliente')
              ->select("alp_abono_disponible.*", DB::raw(  "SUM(alp_abono_disponible.valor_abono) as total"))
              ->where('alp_abono_disponible.id_cliente', $user_id)
              ->first();
            
              $det_array = array();

              \Session::put('aviso_bono', '');


            if (($orden->monto_total+$envio)>0) {

              if ($disponible->total>=$request->bono_use) {

                if ($request->bono_use<=($orden->monto_total+$envio)) {
                  
                   $rr=($orden->monto_total+$envio)-$request->bono_use;

                activity($user->full_name) ->performedOn($user) ->causedBy($user) ->withProperties($rr)  ->log('diferencia al procesar bono ');

                  if ($almacen->minimo_compra<=$rr || $rr==0) {


                    $ppa=AlpAbonosDisponible::where('id_cliente', $user_id)->first();

                
                  $data_abono = array(
                    'id_abono'=>$ppa->id_abono,
                    'id_cliente'=>$user_id,
                    'operacion'=>1,
                    'codigo_abono'=>'',
                    'valor_abono'=>-$request->bono_use,
                    'fecha_final'=>now(),
                    'origen'=>'Compra',
                    'token'=>md5(time()),
                    'json'=>json_encode($orden),
                    'id_user'=>$user_id
                  );

                 $pa=AlpAbonosDisponible::create($data_abono);


                 if ($total-$request->bono_use>0) {
                  

                    $data_pago = array(
                    'id_orden' => $orden->id, 
                    'id_forma_pago' => '4', 
                    'id_estatus_pago' => '1', 
                    'monto_pago' => $request->bono_use, 
                    'json' => json_encode($pa), 
                    'id_user' => $user_id, 
                  );

                   
                   AlpPagos::create($data_pago);

                   \Session::put('aviso_bono', 'Pago aplicado satisfactoriamente, puede asignar un nuevo pago para completar la compra');


                   return secure_url('order/detail');
                  
                }

                }else{

                \Session::put('aviso_bono', 'El monto que intenta aplicar es mayor al monto de la compra por favor verifique e intente nuevamente.');

                return secure_url('order/detail');

               }

               

               }else{

                \Session::put('aviso_bono', 'No se puede aplicar el pago, debido a que el restante de la compra seria menor a '.number_format($almacen->minimo_compra,0,',','.').' y no podria ser procesado correctamento');

                return secure_url('order/detail');

               }


              }else{

                \Session::put('aviso_bono', 'No posee el saldo suficiente para asignar este pago.');

                return secure_url('order/detail');

              }

              
            }else{


              $pa = array();
              
            }



            if (isset($pa->id)) {


              $data=$this->generarPedido('8', '4', $pa, 'bono');

              
              if (isset($data['id_orden'])) {

              }else{

                  if ($data==0) {

                    return redirect('order/detail')->withInput()->with('error', trans('Error al procesar su orden, por favor intente nuevamente.'));

                  }

                  
              }



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
                

                if ($compra->id_forma_envio!=1) {
                  
                    $formaenvio=AlpFormasenvio::where('id', $compra->id_forma_envio)->first();

                    try {

                       Mail::to($formaenvio->email)->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega, 1));

                      Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega, 1));

                    } catch (\Exception $e) {

                      activity()->withProperties(1)->log('error envio de correo l3970');

                    }

                }

           
                $estatus_aviso='success';

                $aviso_pago="Hemos procesado su orden satisfactoriamente, Ha cancelado su compra con Bono disponible en su cuenta. ¡Muchas gracias por su Compra!";
                
                $metodo='Bono';

                $idc=$compra->id*1024;

                return secure_url('cart/'.$idc.'/gracias?pago=pendiente');

                \Session::forget('pagando');

                
        }else{

          \Session::forget('pagando');
          
          return redirect('order/detail');
          
        }

        
      }else{

          return redirect('login');

      }

      

}




public function orderProcesarIcg(Request $request)
    {


      $input=$request->all();

      
      if (Sentinel::check()) {

        
         $user = Sentinel::getUser();

         
       activity($user->full_name)

                    ->performedOn($user)

                    ->causedBy($user)

                    ->withProperties($input)

                    ->log('orderProcesarIcg pago con Nomina');
                    
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

      $almacen=AlpAlmacenes::where('id', $orden->id_almacen)->first();

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
         
          MP::setCredenciales($almacen->id_mercadopago, $almacen->key_mercadopago);

           $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.descripcion_corta as descripcion_corta','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.referencia_producto_sap as referencia_producto_sap' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug','alp_productos.presentacion_producto as presentacion_producto')
          ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
          ->where('alp_ordenes_detalle.id_orden', $orden->id)->get();
          

          $total_descuentos=0;

            $descuentos=AlpOrdenesDescuento::where('id_orden', $carrito)->get();

            foreach ($descuentos as $pago) {

              $total_descuentos=$total_descuentos+$pago->monto_descuento;
              
            }


            $disponible = AlpAbonosDisponible::groupBy('alp_abono_disponible.id_cliente')
              ->select("alp_abono_disponible.*", DB::raw(  "SUM(alp_abono_disponible.valor_abono) as total"))
              ->where('alp_abono_disponible.id_cliente', $user_id)
              ->first();
            
              $det_array = array();

              \Session::put('aviso_bono', '');


            if (($orden->monto_total+$envio)>0) {

              if ($disponible->total>=$request->bono_use) {

                if ($request->bono_use<=($orden->monto_total+$envio)) {
                  
                   $rr=($orden->monto_total+$envio)-$request->bono_use;

                activity($user->full_name) ->performedOn($user) ->causedBy($user) ->withProperties($rr)  ->log('diferencia al procesar bono ');

                  if ($almacen->minimo_compra<=$rr || $rr==0) {


                    $ppa=AlpAbonosDisponible::where('id_cliente', $user_id)->first();

                
                  $data_abono = array(
                    'id_abono'=>$ppa->id_abono,
                    'id_cliente'=>$user_id,
                    'operacion'=>1,
                    'codigo_abono'=>'',
                    'valor_abono'=>-$request->bono_use,
                    'fecha_final'=>now(),
                    'origen'=>'Compra',
                    'token'=>md5(time()),
                    'json'=>json_encode($orden),
                    'id_user'=>$user_id
                  );

                 $pa=AlpAbonosDisponible::create($data_abono);


                 if ($total-$request->bono_use>0) {
                  

                    $data_pago = array(
                    'id_orden' => $orden->id, 
                    'id_forma_pago' => '4', 
                    'id_estatus_pago' => '1', 
                    'monto_pago' => $request->bono_use, 
                    'json' => json_encode($pa), 
                    'id_user' => $user_id, 
                  );

                   
                   AlpPagos::create($data_pago);

                   \Session::put('aviso_bono', 'Pago aplicado satisfactoriamente, puede asignar un nuevo pago para completar la compra');


                   return secure_url('order/detail');
                  
                }

                }else{

                \Session::put('aviso_bono', 'El monto que intenta aplicar es mayor al monto de la compra por favor verifique e intente nuevamente.');

                return secure_url('order/detail');

               }

               

               }else{

                \Session::put('aviso_bono', 'No se puede aplicar el pago, debido a que el restante de la compra seria menor a '.number_format($almacen->minimo_compra,0,',','.').' y no podria ser procesado correctamento');

                return secure_url('order/detail');

               }


              }else{

                \Session::put('aviso_bono', 'No posee el saldo suficiente para asignar este pago.');

                return secure_url('order/detail');

              }

              
            }else{


              $pa = array();
              
            }



            if (isset($pa->id)) {


              $data=$this->generarPedido('8', '4', $pa, 'bono');

              
              if (isset($data['id_orden'])) {

              }else{

                  if ($data==0) {

                    return redirect('order/detail')->withInput()->with('error', trans('Error al procesar su orden, por favor intente nuevamente.'));

                  }

                  
              }



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
                

                if ($compra->id_forma_envio!=1) {
                  
                    $formaenvio=AlpFormasenvio::where('id', $compra->id_forma_envio)->first();

                    try {

                       Mail::to($formaenvio->email)->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega, 1));

                      Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega, 1));

                    } catch (\Exception $e) {

                      activity()->withProperties(1)->log('error envio de correo l3970');

                    }

                }

           
                $estatus_aviso='success';

                $aviso_pago="Hemos procesado su orden satisfactoriamente, Ha cancelado su compra con Bono disponible en su cuenta. ¡Muchas gracias por su Compra!";
                
                $metodo='Bono';

                $idc=$compra->id*1024;

                return secure_url('cart/'.$idc.'/gracias?pago=pendiente');

                \Session::forget('pagando');

                
        }else{

          \Session::forget('pagando');
          
          return redirect('order/detail');
          
        }

        
      }else{

          return redirect('login');

      }

      

}





     /**
     * Funcion getFechaEntrega
     * Descripción: calculo de fecha de entrega tomando en cuanta forma de envio, ciudad y almacen.
     * Variables:
     *
    *  ciudad_forma = listado de formas de envio por ciudad 
    *  
    *  forma_envio = detalle de la forma de envio  
    *  
    *  feriados =  listado de dias festivos 
    *  dias = dias para la entrega 
    *  
    *  date = dia de hoy 
    *  
     *
     * funcions
     *
     * generarPedido = Procesa y actualiza la orden, vacia el carrito de compras
     * 
     * @return View
     */
    



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

  
/**
     * Funcion generarPedido
     * Descripción: procesa las ordenes segun su metodo de pago, calcula la fechha de envio, registra en la tabla ordenes, detalles y pago las actualizaciones y vacia las variables de carrito 
     *
     * 
     * Variables:
     *
     *
     * id_pago = id de pago recibido por mercadopago 
     * 
     * cart = carrito de compra 
     * 
     * carrito = id temporal de la orden 
     * 
     * id_orden = id de la orden procesada 
     * 
     * orden = informacion de la orden 
     * 
     * total = monto total de la orden 
     * 
     * direccion = direccion de la orden 
     * 
     * dias = dias para la entrega
     * 
     * fecha_entrega = fecha posible de entrega de la orden 
     * 
     * cliente = informacion del cliente de la orden 
     * 
     * envio = data enviada a la tabla envios 
     * 
     * valor_impuesto = monto del impuesto 
     * 
     * data_envio = data registrada en la tabla envios 
     * 
     * envio = calculo del costo del envio 
     * 
     * data_envio_history = registro en el history del envio 
     * 
     * comision_mp = calculo de retenciones mercadopago
     * 
     * retencion_fuente_mp = calculo de retenciones mercadopago
     * 
     * retencion_iva_mp = calculo de retenciones mercadopago
     * 
     * retencion_ica_mp = calculo de retenciones mercadopago
     * 
     * cupones = listado de cupones aplicados a la orden 
     * 
     * data_pago = datos registrados en la tabla pagos 
     * 
     * data_history = registro en history de la orden 
    *  
     *
     * funcions
     *
     * generarPedido = Procesa y actualiza la orden, vacia el carrito de compras
     * 
     * @return View
     */

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

        $almacen=AlpAlmacenes::where('id', $orden->id_almacen)->first();

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

        
        $comision_mp=$almacen->comision_mp/100;

        $retencion_fuente_mp=$almacen->retencion_fuente_mp/100;

        $retencion_iva_mp=$almacen->retencion_iva_mp/100;

        $retencion_ica_mp=$almacen->retencion_ica_mp/100;

        
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

          
          $comision_mp=$almacen->comision_mp_pse/100;

          
          $data_update = array(

          'estatus' =>$estatus_orden, 

          'estatus_pago' =>$estatus_pago,

          'comision_mp' =>(($orden->monto_total*$comision_mp)+($orden->monto_total*$comision_mp*0.19)),

          'retencion_fuente_mp' =>0,

          'retencion_iva_mp' =>0,

          'retencion_ica_mp' =>0

           );

          
        }elseif($tipo=='baloto'){

          

          $comision_mp=$almacen->comision_mp_baloto/100;

          
          $data_update = array(

          'estatus' =>$estatus_orden, 

          'estatus_pago' =>$estatus_pago,

          'comision_mp' =>(($orden->monto_total*$comision_mp)+($orden->monto_total*$comision_mp*0.19)),

          'retencion_fuente_mp' =>0,

          'retencion_iva_mp' =>0,

          'retencion_ica_mp' =>0

           );

          
        }elseif($tipo=='bono'){

          

          $comision_mp=$almacen->comision_mp_baloto/100;

          
          $data_update = array(

          'estatus' =>$estatus_orden, 

          'estatus_pago' =>$estatus_pago,

          'comision_mp' =>0,

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

          'monto_pago' => $orden->monto_total, 

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

    
       /**
     * Funcion saveOrden
     * Descripción: registro de pre orden , es un registro de los datos de la orden en una tabla temporal por seguridad 
     *
     * 
     **cr = id de orden temporal 
      *cart = contenido del carrito de compra 
      *total = total del carrito 
      *data_orden = datos registrados en la table preordenes 
      *orden = detalles de la orden 
      *monto_total_base = monto total de la orden 
      *data_update = datos registrado en la tabla preordenes 
      *orden = datos de la orden 
     * 
     * Variables:
     *
    *  
     *
     * funcions
     *
     * generarPedido = Procesa y actualiza la orden, vacia el carrito de compras
     * 
     * @return View
     */


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

            #'preferencia_id' => $preference['response']['id'],
            'preferencia_id' => '0',


            'json' => json_encode($preference),

            'id_user' =>$user_id

          );

      
        $orden=AlpPreOrdenes::create($data_orden);

        
        $monto_total_base=0;

        
        foreach ($cart as $detalle) {

          
          if (isset($detalle->id)) {

            
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

          
          //dd($detalle);

          
           

           
         }

         
         $data_update = array(

          'referencia' => 'PRE'.$orden->id,

          'monto_total_base' => $monto_total_base,

        );

         
         $orden->update($data_update);

         
    }



      /**
     * Funcion orderProcesar
     * Descripción: procesa las ordenes que son por medios de pagos diferentes  a mercadopago.
     * 
     * Variables:
     *
     * cart = carrito de compra 
     * 
     * carrito = id temporal de la orden 
     * 
     * id_orden = id de la orden procesada 
     * 
     * orden = informacion de la orden 
     * 
     * total = monto total de la orden 
      *
      * aviso_pago = mensaje personalizado del pago 
      *
      * direccion = direccion de la orden 
      *
      *ciudad_forma= ciudades disponibles por forma de envio 
      *
      *
      *date= fecha de hoy 
      *
      *dias= dias para la entrega 
      *
      *fecha_entrega= fecha posible de entrega 
      *
      *cliente= datos del cliente de la compra 
      *
      *data_envio= data registrada en la tabla envios 
      *
      *envio= costo del envio 
      *
      *data_envio_history= data registrada en el historico de la orden 
      *
      *carro= registro en la tabla carrito 
      *
      *detalles_carrito=  registros en la tabla detalles de carrito 
      *
      *saldo_c=  registro en la tabla saldo cliente 
      *
      *carrito= id de carrrito 
      * 
      *compra= informacion de la compra 
      *
      *user_cliente = detalles del clientes de la compra 
     *
     * funcions
     *
     * generarPedido = Procesa y actualiza la orden, vacia el carrito de compras
     * 
     * @return true
     */


    
    public function orderProcesar(Request $request)

    {

       $cart= \Session::get('cart');

       
       if (count($cart)>0) {

        
      $carrito= \Session::get('cr');

      
      $total=$this->total();

      
      $id_orden= \Session::get('orden');

      
      $orden=AlpOrdenes::where('id', $id_orden)->first();

      $almacen=AlpAlmacenes::where('id', $orden->id)->first();

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

          try {
                         Mail::to($user_cliente->email)->send(new \App\Mail\CompraRealizada($compra, $detalles, $fecha_entrega));

            Mail::to($configuracion->correo_sac)->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega));

            Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\CompraRealizada($compra, $detalles, $fecha_entrega));

            Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega));

            Mail::to($configuracion->correo_cedi)->send(new \App\Mail\NotificacionOrden($compra->id, $texto));
                      } catch (\Exception $e) {
                        activity()->withProperties(1)
                                    ->log('error envio de correo l5398');

                      }

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


       /**
     * Funcion add
     * Descripción: funcion para agregar productos al carrito por enlace 
     * 
     * 
     * Variables:
     * *descuento = condicion para calculo de descuento de los productos
     * 
      *cliente = informacion del cliente de la compra 
      *
      *producto = productos a agregar en la compra 
      *
      *cart = infromacion del carrito 
      *
      * 

     * 
     * @return true
     */

    
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

       $ban_disponible=0;

      if (isset($producto->id)) {

        $producto->precio_oferta=$request->price;

        $producto->cantidad=1;

        $base_imponible_detalle=0;

        $base_impuesto=0;

        if ($producto->tipo_producto=='2') {

            $lista=AlpCombosProductos::select('alp_combos_productos.*', 'alp_productos.id_impuesto as id_impuesto')
                    ->join('alp_productos', 'alp_combos_productos.id_producto','=', 'alp_productos.id' )
                    ->where('id_combo', $producto->id)->get();



                    foreach ($lista as $l) {

                      if ($l->id_impuesto==1) {


                        $base_imponible_detalle=$l->precio*(1-$producto->valor_impuesto);

                        $base_impuesto=$base_impuesto+($l->precio*$l->cantidad);

                        $valor_impuesto=$producto->valor_impuesto;
                        
                      }
                        
                    }

              }else{

                $base_impuesto=$producto->precio_base*$producto->cantidad;

              }

            #$producto->impuesto=$base_impuesto-($base_impuesto/(1+$producto->valor_impuesto));

            $producto->impuesto=$base_impuesto*$producto->valor_impuesto;

            $producto->base_imponible=$base_impuesto;

        
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

                    $data_detalle = array(
                      'id_carrito' => $carrito, 
                      'id_producto' => $producto->id, 
                      'cantidad' => $producto->cantidad
                    );

                 AlpCarritoDetalle::create($data_detalle);
                 
              }


          }else{

            $error="No hay existencia suficiente de este producto";

          }

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

          activity($user->full_name)->performedOn($user) ->causedBy($user) ->withProperties($cart) ->log('addtocart ');

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

       $ban_disponible=0;

        $precio = array();

       $inv=$this->inventario();

       if (isset($producto->id)) {

          $producto->precio_oferta=$request->price;
                    $producto->cantidad=1;
                    $producto->disponible=1;
                    $producto->impuesto=$producto->precio_oferta*$producto->valor_impuesto;

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

              activity()->withProperties($cart)->log('addtocartdetail');

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

        $ban_disponible=0;
       
        $precio = array();

        $inv=$this->inventario();

        if (isset($producto->id)) {


        $producto->precio_oferta=$request->price;

        $producto->cantidad=1;
        
        $producto->impuesto=$producto->precio_oferta*$producto->valor_impuesto;

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

                                $data_detalle = array(
                                    'id_carrito' => $carrito, 
                                    'id_producto' => $producto->id, 
                                    'cantidad' => $producto->cantidad
                                  );

                AlpCarritoDetalle::create($data_detalle);

                
              }

          \Session::put('cart', $cart);

       

      

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

      }
     

       if (Sentinel::check()) {
        
          $user = Sentinel::getUser();

           activity($user->full_name)

            ->performedOn($user)

            ->causedBy($user)

            ->withProperties($cart)

            ->log('delete ');

                        
        }else{
          
          activity()->withProperties($cart)->log('delete');

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

          activity()->withProperties($cart)->log('cartcontroller/update');

        }

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

          activity()->withProperties($request->all())->log('cartcontroller/updatecantidad');

        }

        $ban_disponible=0;

        if (isset($inv[$request->id])) {

           if($inv[$request->id]>=$request->cantidad){

            if (isset($cart[$request->slug])) {

              if ($cart[$request->slug]->tipo_producto=='2') {

                $lista=AlpCombosProductos::where('id_combo', $cart[$request->slug]->id)->get();

                  foreach ($lista as $l) {

                    if (isset($inv[$l->id_producto])) {

                      if($inv[$l->id_producto]>=($l->cantidad*$request->cantidad)){

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

            if ($cart[$request->slug]->tipo_producto=='3') {

                if (isset($cart[$request->slug]->ancheta)) {

                      foreach ($cart[$request->slug]->ancheta as $l) {

                        if (isset($inv[$l->id])) {

                          if($inv[$l->id]>=($l->cantidad*$request->cantidad)){

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

              }

              if ($ban_disponible==0) {

                $cart[$request->slug]->cantidad=$request->cantidad;

              }

            \Session::put('cart', $cart);

            return 'true';

            }

            }else{

              return 'false';
              
            }

      
        }else{

          return 'false';

        }

      
      }

      public function delproducto( Request $request){

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

        unset($cart[$request->slug]);

        \Session::put('cart', $cart);

        $view= View::make('frontend.order.botones', compact('producto', 'cart'));

        $data=$view->render();
        
        return $data;

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

       $ban_disponible=0;

       if (isset($inv[$request->id])) {

         if($inv[$request->id]>=$request->cantidad){

          
            if (isset($cart[$request->slug])) {
            if ($cart[$request->slug]->tipo_producto=='2') {

                              $lista=AlpCombosProductos::where('id_combo', $cart[$request->slug]->id)->get();

                    foreach ($lista as $l) {

                         if (isset($inv[$l->id_producto])) {

                            if($inv[$l->id_producto]>=($l->cantidad*$request->cantidad)){

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

                        if ($cart[$request->slug]->tipo_producto=='3') {

                if (isset($cart[$request->slug]->ancheta)) {

                      foreach ($cart[$request->slug]->ancheta as $l) {

                                                if (isset($inv[$l->id])) {

                            if($inv[$l->id]>=($l->cantidad*$request->cantidad)){

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
                              }

                              
          if (isset($cart[$request->slug])) {

            if ($ban_disponible==0) {
                            $cart[$request->slug]->cantidad=$request->cantidad;
                          }

            

          }

          }

        

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

          if ($row->precio_base>0) {

            $total=$total+($row->cantidad*$row->precio_oferta);

          }

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


    private function base_imponible()
    {

       $cart= \Session::get('cart');
       
      $total=0;
      
      foreach($cart as $row) {
        
        if (isset($row->id)) {

          $total=$total+($row->base_imponible*$row->cantidad);

        }

      }
      
       return $total;

    }




    private function impuesto()
    {
      
     

      if (Sentinel::check()) {

        $user_id = Sentinel::getUser()->id;
        
        $role=RoleUser::select('role_id')->where('user_id', $user_id)->first();

      }

        $cart= \Session::get('cart');

      $impuesto=0;

      $valor_impuesto=0;

      $carrito= \Session::get('cr');

      $base=0;

      $total=$this->total();

      $total_descuentos=0;

      $total_descuentos_icg=0;

      $ban_icg=0;

      $porcentaje_descuento_icg=0;


        $descuentos=AlpOrdenesDescuento::where('id_orden', $carrito)->get();

        foreach ($descuentos as $pago) {

          $total_descuentos=$total_descuentos+$pago->monto_descuento;

        }

        if (isset($role->role_id)) {

          if ($role->role_id=='16') {


            $descuentosIcg=AlpOrdenesDescuentoIcg::where('id_orden','=', $carrito)->get();


              foreach ($descuentosIcg as $pagoi) {

                $ban_icg=1;

                $total_descuentos=$total_descuentos+$pagoi->monto_descuento;

                $total_descuentos_icg=$total_descuentos_icg+$pagoi->monto_descuento;


              }

              if ($ban_icg==1) {

                $configuracion=AlpConfiguracion::where('id', 1)->first();

                $porcentaje_descuento_icg=$configuracion->porcentaje_icg/100;

              }

           }

         }

          foreach($cart as $row) {

            if (isset($row->id)) {

              if($row->valor_impuesto>0){

                $valor_impuesto=$row->valor_impuesto;

                $base_impuesto=0;

                if ($row->tipo_producto=='2') {

                      $lista=AlpCombosProductos::select('alp_combos_productos.*', 'alp_productos.id_impuesto as id_impuesto')
                        ->join('alp_productos', 'alp_combos_productos.id_producto','=', 'alp_productos.id' )
                        ->where('id_combo', $row->id)->get();

                      foreach ($lista as $l) {

                        if ($l->id_impuesto==1) {

                          $base_impuesto=$base_impuesto+($l->precio*$l->cantidad);
                          
                        }
                          
                      }

                }else{

                  $base_impuesto=$row->base_imponible;

                }

                $base=$base+($row->precio_oferta*$row->cantidad);

               // $base=$base

                #$impuesto=$impuesto+(($base_impuesto)/(1+$valor_impuesto))*$valor_impuesto;

                #$impuesto=$impuesto+($base_impuesto*$valor_impuesto);

                $impuesto=$impuesto+($base_impuesto*$valor_impuesto);


                }

            }

          }


        $resto=$total-$total_descuentos;

        if ($resto<$base) {

          //$impuesto=($resto/(1+$valor_impuesto))*$valor_impuesto;

          $impuesto=$resto+($base_impuesto*$valor_impuesto);

        }


        if ($ban_icg==1) {

         $impuesto=$impuesto*(1-$porcentaje_descuento_icg);

        # dd($impuesto);

        }

       return $impuesto;
      
    }

          
    private function cantidad()
    {
      
      $cart= \Session::get('cart');
       
      $cantidad=0;
      
      foreach($cart as $row) {

        if (isset($row->id)) {

          $cantidad=$cantidad+($row->cantidad);

        }
        
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

      $ciudad= \Session::get('ciudad');

        if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

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

                      $pregiogrupo=AlpPrecioGrupo::where('id_producto', $producto->id)->where('id_role', $role->role_id)->where('city_id', $ciudad)->first();

                      if (isset($pregiogrupo->id)) {

                          $precio[$producto->id]['precio']=$pregiogrupo->precio;

                          $precio[$producto->id]['operacion']=$pregiogrupo->operacion;
                                                    $precio[$producto->id]['pum']=$pregiogrupo->pum;

                      }else{

                        $pregiogrupo=AlpPrecioGrupo::where('id_producto', $producto->id)->where('id_role', $role->role_id)->where('city_id', '=','62')->first();

                        if (isset($pregiogrupo->id)) {

                          $precio[$producto->id]['precio']=$pregiogrupo->precio;

                          $precio[$producto->id]['operacion']=$pregiogrupo->operacion;
                          
                          $precio[$producto->id]['pum']=$pregiogrupo->pum;

                        }

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

                        $pregiogrupo=AlpPrecioGrupo::where('id_producto', $producto->id)->where('id_role', $r)->where('city_id', '=','62')->first();

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

                  $producto->precio_oferta=($producto->precio_base+$producto->impuesto)*$descuento;

                  break;

                case 2:

                  $producto->precio_oferta=($producto->precio_base+$producto->impuesto)*(1-($precio[$producto->id]['precio']/100));

                  break;

                  case 3:

                  $producto->precio_oferta=$precio[$producto->id]['precio'];

                  break;

                  default:

                  $producto->precio_oferta=($producto->precio_base+$producto->impuesto)*$descuento;
                                   # code...
                  break;
              }

            }else{

              $producto->precio_oferta=($producto->precio_base+$producto->impuesto)*$descuento;

            }

          }else{

           $producto->precio_oferta=($producto->precio_base+$producto->impuesto)*$descuento;

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


            $base_imponible_detalle=0;

            $base_impuesto=0;

            if ($producto->tipo_producto=='2') {

              $lista=AlpCombosProductos::select('alp_combos_productos.*', 'alp_productos.id_impuesto as id_impuesto')
              ->join('alp_productos', 'alp_combos_productos.id_producto','=', 'alp_productos.id' )
              ->where('id_combo', $producto->id)->get();

                foreach ($lista as $l) {

                    if ($l->id_impuesto==1) {

                     # $base_imponible_detalle=$l->precio/(1+$producto->valor_impuesto);
                      
                      $base_imponible_detalle=$l->precio;

                      $base_impuesto=$base_impuesto+($l->precio*$l->cantidad);

                      $valor_impuesto=$producto->valor_impuesto;
                      
                    }
                      
                  }

            }else{

              $base_impuesto=$producto->precio_oferta+$producto->cantidad;

            }
                            

            #$producto->impuesto=$producto->precio_oferta*$producto->valor_impuesto;

          $almp=AlpAlmacenProducto::where('id_almacen', $id_almacen)->where('id_producto', $producto->id)->first();

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
          
          activity()->withProperties($request->all()) ->log('cartcontroller/botones');

        }
        
       $cart= \Session::get('cart');


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



        if (isset($producto->id)) {
          
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

      $ban_disponible=0;

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
                                            
                  if ($cart[$request->slug]->tipo_producto=='2') {

                  $lista=AlpCombosProductos::where('id_combo', $cart[$request->slug]->id)->get();

                    foreach ($lista as $l) {

                         if (isset($inv[$l->id_producto])) {

                            if($inv[$l->id_producto]>=($l->cantidad*$request->cantidad)){

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

                if ($cart[$request->slug]->tipo_producto=='3') {

                  if (isset($cart[$request->slug]->ancheta)) {

                    foreach ($cart[$request->slug]->ancheta as $l) {

                      if (isset($inv[$l->id])) {

                        if($inv[$l->id]>=($l->cantidad*$request->cantidad)){

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

                }

                              
                if ($ban_disponible==0) {

                  if (isset($cart[$request->slug])) {

                    $cart[$request->slug]->cantidad=$request->cantidad;

                  }else{

                    $cart[$request->slug]=$producto;

                  }

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

       $ban_disponible=0;

       if ($request->cantidad>0) {

        if (isset($inv[$request->id])) {

          if($inv[$request->id]>=$request->cantidad){

              if ($configuracion->maximo_productos<$request->cantidad) {

                $error="No puede añadir más de ".$configuracion->maximo_productos." Unidades al carrito";

                }else{

                  if (isset($cart[$request->slug])) {

                  if ($cart[$request->slug]->tipo_producto=='2') {

                    $lista=AlpCombosProductos::where('id_combo', $cart[$request->slug]->id)->get();

                    foreach ($lista as $l) {

                      if (isset($inv[$l->id_producto])) {

                          if($inv[$l->id_producto]>=($l->cantidad*$request->cantidad)){

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

                  if ($cart[$request->slug]->tipo_producto=='3') {

                    if (isset($cart[$request->slug]->ancheta)) {

                      foreach ($cart[$request->slug]->ancheta as $l) {

                        if (isset($inv[$l->id])) {

                            if($inv[$l->id]>=($l->cantidad*$request->cantidad)){

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
                      }

                              
                      if ($ban_disponible==0) {
                        
                        if (isset($cart[$request->slug]->cantidad)) {

                          $cart[$request->slug]->cantidad=$request->cantidad;

                        }
                      
                      }

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

       $ban_disponible=0;

       if ($request->cantidad>0) {

           if($inv[$request->id]>=$request->cantidad){

            if ($configuracion->maximo_productos<$request->cantidad) {

                $error="No puede añadir más de ".$configuracion->maximo_productos." Unidades al carrito";

                
            }else{

              if (isset($cart[$request->slug])) {

              if ($cart[$request->slug]->tipo_producto=='2') {

                  $lista=AlpCombosProductos::where('id_combo', $cart[$request->slug]->id)->get();

                    foreach ($lista as $l) {

                          if (isset($inv[$l->id_producto])) {

                              if($inv[$l->id_producto]>=($l->cantidad*$request->cantidad)){

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

              if ($cart[$request->slug]->tipo_producto=='3') {

                if (isset($cart[$request->slug]->ancheta)) {

                      foreach ($cart[$request->slug]->ancheta as $l) {

                          if (isset($inv[$l->id])) {

                              if($inv[$l->id]>=($l->cantidad*$request->cantidad)){

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
              }

              if ($ban_disponible==0) {

                $cart[$request->slug]->cantidad=$request->cantidad;
                                  # code...
              }

              }
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
          
          activity()->withProperties(['id'=>$id])->log('cartcontroller/setdir');

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
      
      if ($direccion->id_barrio!=0) {
        
          $ciudad=AlpFormaCiudad::where('id_forma', $request->id_forma_envio)->where('id_barrio', $direccion->id_barrio)->first();

          if (isset($ciudad->id)) {
            
          }else{

            $ciudad=AlpFormaCiudad::where('id_forma', $request->id_forma_envio)->where('id_ciudad', $direccion->city_id)->where('id_barrio', '=', '0')->first();

          }

      }else{
        

         $ciudad=AlpFormaCiudad::where('id_forma', $request->id_forma_envio)->where('id_ciudad', $direccion->city_id)->first();

      }

      $validado=0;

      $role=RoleUser::select('role_id')->where('user_id', $user_id)->first();
      
      $re=AlpRolenvio::where('id_rol', $role->role_id)->get();
      
      $re_u=AlpRolenvio::where('id_rol', $role->role_id)->first();
      
      $id_almacen=$this->getAlmacen();

      $almacen=AlpAlmacenes::where('id', $id_almacen)->first();

      $impuesto=$this->impuesto();

      $configuracion=AlpConfiguracion::where('id', '1')->first();

      if (count($re)==1) {

          if ($re_u->id_forma_envio=='4') {

              $validado='1';

          }
          
      }

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

      
      $r='true';

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
                        ->log('varificar pagos crear orden ');

          
          $orden=AlpOrdenes::create($data_orden);
          
          $monto_total_base=0;
          
          $base_impuesto=0;
          
          $monto_impuesto=0;
          
          $valor_impuesto=0;
          
          foreach ($cart as $detalle) {

            $base_imponible_detalle=0;
            
            $monto_total_base=$monto_total_base+($detalle->cantidad*$detalle->precio_base);
            
             $total_detalle=$detalle->precio_oferta*$detalle->cantidad;
             
              if ($detalle->valor_impuesto!=0) {

                if ($detalle->tipo_producto=='2') {

                      $lista=AlpCombosProductos::select('alp_combos_productos.*', 'alp_productos.id_impuesto as id_impuesto')
                      ->join('alp_productos', 'alp_combos_productos.id_producto','=', 'alp_productos.id' )
                      ->where('id_combo', $detalle->id)->get();

                     # dd(json_encode($lista));

                      foreach ($lista as $l) {

                        if ($l->id_impuesto==1) {

                          $base_imponible_detalle=$base_imponible_detalle+($l->precio*$l->cantidad)/(1+$detalle->valor_impuesto);

                          $base_impuesto=$base_impuesto+($l->precio*$l->cantidad);

                          $valor_impuesto=$detalle->valor_impuesto;
                          
                        }
                          
                      }

                }else{

                  $base_imponible_detalle=$total_detalle/(1+$detalle->valor_impuesto);

                  $base_impuesto=$base_impuesto+$total_detalle;

                  $valor_impuesto=$detalle->valor_impuesto;


                }
                
              
              }else{

                $base_imponible_detalle=0;

                #$base_impuesto=$base_impuesto+$total_detalle;

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
                #'monto_impuesto' =>$detalle->impuesto,
                'id_user' =>$user_id 

              );

              if(isset($detalle->monto_descuento)){
                $data_detalle['monto_descuento']=$detalle->monto_descuento;
              }

              
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

              $detalleguardado=AlpDetalles::create($data_detalle);
              
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

               


          }//endfreach cart impuesto

          
            $total_descuentos=0;
            
            $descuentos=AlpOrdenesDescuento::where('id_orden', $carrito)->get();

            foreach ($descuentos as $pago) {
              
              $total_descuentos=$total_descuentos+$pago->monto_descuento;
              
            }

            $total_descuentos_icg=0;

            $porcentaje_descuento_icg=0;

            $ban_icg=0;

          if (isset($role->role_id)) {

            if ($role->role_id=='16') {

              $descuentosIcg=AlpOrdenesDescuentoIcg::where('id_orden','=', $carrito)->get();

                foreach ($descuentosIcg as $pagoi) {

                  $ban_icg=1;

                  $total_descuentos=$total_descuentos+$pagoi->monto_descuento;

                  $total_descuentos_icg=$total_descuentos_icg+$pagoi->monto_descuento;

                  $data_update_icg = array('id_orden' => $orden->id);

                  $pagoi->update($data_update_icg);

                }

            }

          }

            $resto=$total-$total_descuentos;

            if ($resto<$base_impuesto) {

              $base_impuesto=$resto;

            }

            
          $monto_impuesto=($base_impuesto/(1+$valor_impuesto))*$valor_impuesto;

          $base_imponible=($base_impuesto/(1+$valor_impuesto));

          if ($ban_icg==1) {

            $porcentaje_descuento_icg=$configuracion->porcentaje_descuento_icg;

           $monto_impuesto=$monto_impuesto*(1-$porcentaje_descuento_icg);

           $base_imponible=$base_imponible*(1-$porcentaje_descuento_icg);

          }



           $data_update = array(

              'referencia' => 'ALP'.$orden->id,

              'referencia_mp' => 'ALP'.$orden->id.'_'.str_slug($almacen->nombre_almacen),

              'monto_total' =>$resto,

              'monto_descuento' =>$total_descuentos,

              'monto_total_base' => $monto_total_base,

              'base_impuesto' => $base_imponible,

              'monto_impuesto' => $monto_impuesto,

             # 'monto_impuesto' => $impuesto,

              'valor_impuesto' => $valor_impuesto,

              'token' =>substr(md5(time()), 0, 16)

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




            if ($total_descuentos_icg>0) {


              $ricg=$this->registroIcg($orden->id);

              if (isset($ricg->idRegistro)) {
                 
              }else{

                $r='falseicg';
              }

            }






          if ($orden->id_almacen==1) {

             try {
            # $compramas=$this->reservarOrden($orden->id);
              } catch (Exception $e) {

              }

          }

                        }else{ //if la orden es menor a 0

                          
            return 'false0';

            
          }

          


                  }else{

                    















            $o=\Session::get('orden');

          $orden=AlpOrdenes::where('id', '=', $o)->first();

          if (isset($orden->id)) {
            // code...
          }else{

            \Session::forget('orden');

            return 'falseCancelado';

          }

          if ($orden->estatus=='4') {

            \Session::forget('orden');
                        \Session::forget('cr');

                        return 'falseCancelado';

          }

          if ($orden->estatus!='8') {

            \Session::forget('orden');
                        \Session::forget('cr');
                        \Session::forget('cart');

                        return 'falseAprobado';

          }

          
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
                        ->log('verificar direccion orden l9837');

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

            $base_imponible_detalle=0;

            if (isset($detalle->id)){

            if ($detalle->precio_base>0) {

            $monto_total_base=$monto_total_base+($detalle->cantidad*$detalle->precio_base);
            
             $total_detalle=$detalle->precio_oferta*$detalle->cantidad;

              if ($detalle->valor_impuesto!=0) {

                if ($detalle->tipo_producto=='2') {

                      $lista=AlpCombosProductos::select('alp_combos_productos.*', 'alp_productos.id_impuesto as id_impuesto')
                      ->join('alp_productos', 'alp_combos_productos.id_producto','=', 'alp_productos.id' )
                      ->where('id_combo', $detalle->id)->get();

                     # dd(json_encode($lista));

                      foreach ($lista as $l) {

                        if ($l->id_impuesto==1) {

                          $base_imponible_detalle=$base_imponible_detalle+($l->precio*$l->cantidad)/(1+$detalle->valor_impuesto);

                          $base_impuesto=$base_impuesto+($l->precio*$l->cantidad);

                          $valor_impuesto=$detalle->valor_impuesto;
                          
                        }
                          
                      }

                }else{

                  $base_imponible_detalle=$total_detalle/(1+$detalle->valor_impuesto);

                  $base_impuesto=$base_impuesto+$total_detalle;

                  $valor_impuesto=$detalle->valor_impuesto;


                }
              
              }else{

                $base_imponible_detalle=0;

                #$base_impuesto=$base_impuesto+$total_detalle;

              }

             ## dd($base_imponible_detalle);

              $imp=$detalle->valor_impuesto+1;

              
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

                #'monto_impuesto' =>$detalle->impuesto,

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

                        ->log('detalle de orden  10003');

                        
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
                              }

                              


          }//endfreach detalle

          
            $total_descuentos=0;

            
            $descuentos=AlpOrdenesDescuento::where('id_orden', $orden->id)->get();

            
            foreach ($descuentos as $pago) {

              
              $total_descuentos=$total_descuentos+$pago->monto_descuento;

              
            }

            
              $total_descuentos_icg=0;

              $ban_icg=0;


          if (isset($role->role_id)) {

            if ($role->role_id=='16') {


              $descuentosIcg=AlpOrdenesDescuentoIcg::where('id_orden','=', $orden->id)->get();

            $total_descuentos_icg=0;

            foreach ($descuentosIcg as $pagoi) {

              $ban_icg=1;

              $total_descuentos=$total_descuentos+$pagoi->monto_descuento;

               $total_descuentos_icg=$total_descuentos_icg+$pagoi->monto_descuento;

            }

            activity()->withProperties($total_descuentos_icg)
                        ->log('total descuento icg  ');

                        

            }

          }


             


          //se calcula lo que queda luego del descuento

            
            $resto=$total-$total_descuentos;

            
            if ($resto<$base_impuesto) {

              
              $base_impuesto=$resto;

              

            }

            
          $monto_impuesto=($base_impuesto/(1+$valor_impuesto))*$valor_impuesto;

          $base_imponible=($base_impuesto/(1+$valor_impuesto));


          if ($ban_icg==1) {

            $porcentaje_descuento_icg=$configuracion->porcentaje_descuento_icg;

           $monto_impuesto=$monto_impuesto*(1-$porcentaje_descuento_icg);

           $base_imponible=$base_imponible*(1-$porcentaje_descuento_icg);

          }




           $data_update = array(

              'monto_total' =>$resto,

              'monto_descuento' =>$total_descuentos,

              'monto_total_base' => $monto_total_base,

              'base_impuesto' => $base_imponible,

              'monto_impuesto' => $monto_impuesto,

              #'monto_impuesto' => $impuesto,

              'valor_impuesto' => $valor_impuesto,

             # 'token' =>substr(md5(time()), 0, 16)

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

            

            
            if (isset($role->role_id)) {

            if ($role->role_id=='16') {


              
             $descuentosIcg=AlpOrdenesDescuentoIcg::where('id_orden','=', $carrito)->get();


            foreach ($descuentosIcg as $pagoi) {

              $dicg=AlpOrdenesDescuentoIcg::where('id', '=', $pagoi->id)->first();

               $data_dicg = array('id_orden' => $orden->id );

              $dicg->update($data_dicg);

            }


             if ($total_descuentos_icg>0) {


              
                
              $ricgc=$this->registroIcgCancelar($orden->id);

              $ricg=$this->registroIcg($orden->id);

              //dd($ricg->idRegistro);

              if (isset($ricg->idRegistro)) {
                 
              }else{

                $r='falseicg';
              }

            }

            }

          }

            




































         }

         
          return 'true';

          
      }else{

        
        return 'false1';

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

      }


      return $texto;

      
    }


    public function delbono(Request $request)
    {

       if (Sentinel::check()) {
        
          $user = Sentinel::getUser();
          
           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cartcontroller/delbono ');
                        
        }else{
          
          activity()->withProperties($request->all())
                        ->log('cartcontroller/delbono');

        }

      $configuracion=AlpConfiguracion::where('id', '1')->first();

      $carrito= \Session::get('cr');
      
      $cart=$this->reloadCart();
      
      $total=$this->total();
      
      $total_base=$this->precio_base();
      
      $impuesto=$this->impuesto();
      
      $texto="<div class='alert alert-danger'>El bono  ha sido eliminado</div>";
      
      $pagobono=AlpPagos::where('id', $request->id)->first();

      if (isset($pagobono->id)) {

        $pbe=AlpAbonosDisponible::where('id_cliente', $user->id)->where('valor_abono', '=', -$pagobono->monto_pago)->first();

        if (isset($pbe->id)) {


          $texto="<div class='alert alert-danger'>El bono  ha sido eliminado</div>";
          

                $data_abono = array(
                    'id_abono'=>$pbe->id_abono,
                    'id_cliente'=>$user->id,
                    'operacion'=>1,
                    'codigo_abono'=>'',
                    'valor_abono'=>$pagobono->monto_pago,
                    'fecha_final'=>now(),
                    'origen'=>'Eliminar bono asignado  en compra',
                    'token'=>md5(time()),
                    'json'=>json_encode($carrito),
                    'id_user'=>$user->id
                  );

                 $pa=AlpAbonosDisponible::create($data_abono);

                 $pagobono->delete();

        }else{

          $texto="<div class='alert alert-danger'>Error al eliminar el pago </div>";
        }


        # code...
      }

      return $texto;
      
    }




    public function delcuponicg(Request $request)
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

      $texto="<div class='alert alert-danger'>El descuento de icg ha sido eliminado</div>";

      $o=AlpOrdenesDescuentoIcg::where('id', $request->id)->first();

      if (isset($o->id)) {

      $o->delete();
        # code...
      }

      return $texto;

    }




    

    public function envio(){
      
      $formasenvio= \Session::get('envio');
      
      $direccion= \Session::get('direccion');


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

    


 private function getAlmacenCart(){

  $tipo=0;

        if (isset(Sentinel::getUser()->id)) {

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

                }else{

                  $c=City::where('id', $ciudad)->first();
                  
                  if (isset($c->id)) {
                    
                      $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')
                ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')
                ->where('alp_almacenes.tipo_almacen', '=', $tipo)
                ->where('alp_almacen_despacho.id_city', '0')
                ->where('alp_almacen_despacho.id_state', $c->state_id)
                ->where('alp_almacenes.estado_registro', '=', '1')->first();

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

                      

                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->where('city_id', '=', '62')->first();

                    
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

                      
                      $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $r)->where('city_id', '=', '62')->first();

                      
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

                                            $pc=AlpProductos::where('id', $d->id_combo)->first();

                      if (isset($pc->id)) {

                          if ($pc->tipo_producto=='3') {

                                                           $dt = array(
                                                              'sku' => $d->referencia_producto, 
                                                            'name' => $d->nombre_producto, 
                                                            'url_img' => $d->imagen_producto, 
                                                            'value' => $d->precio_unitario, 
                                                            'value_prom' => $d->precio_unitario, 
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

                                                try {

                          Mail::to($configuracion->correo_sac)->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

                          Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

                                                  } catch (\Exception $e) {
                                                    activity()->withProperties(1)
                                                  ->log('error envio de correo l15075');

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
                                      ->log('error envio de correo l15135');

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

          try {

            Mail::to($configuracion->correo_sac)->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

            Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

                     } catch (\Exception $e) {
                        activity()->withProperties(1)
                                    ->log('error envio de correo l15190');

                      }

       

       
                     

                     
      }

      
      

    }

    


private function addpromocion(){

       

      $cart= \Session::get('cart');

      
      $inventario=$this->inventario();

      
       $id_almacen=$this->getAlmacenCart();

       
      //dd($inventario);

       
      $date = Carbon::now();

      
        $hoy=$date->format('Y-m-d');

        
      if (!is_null($cart)) {

          foreach ($cart as $c) {

            if (isset($c->promocion)) {

              unset($cart[$c->slug]);

            }

          }

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

                  
                    if (isset($c->id)) {

                      if (in_array($c->id_marca, $marcas)) {

                        
                        $monto=$monto+($c->precio_oferta*$c->cantidad);

                        
                      }

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

      
      $total=0;

      
      foreach ($cartancheta as $c) {

        

        $total=$total+($c->precio_oferta);

        

      }

      

      //$total=$total+$producto->precio_base;

      

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

    

    private function consultaCreditoIcg()
    {

      $configuracion=AlpConfiguracion::where('id', '=', 1)->first();

      $s_user= \Session::get('user');

      $carrito= \Session::get('cr');

      $c=AlpClientes::where('id_user_client','=', '6512')->first();

      $data = array('DocumentoEmpleado' =>$c->doc_cliente);

      $configuracion=AlpConfiguracion::where('id', '=', '1')->first();
        
       $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://qacupo.alpina.com:8099//api/cupo/ValidarCuposGo?DocumentoEmpleado='.$c->doc_cliente);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'apikeyalp2go: 1';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        //dd($result);
        if (curl_errno($ch)) {
            echo 'Error curl:' . curl_error($ch);
        }
        curl_close($ch);

        $res=json_decode($result);

        activity()->withProperties($res)->log('respuesta credito icg res');

      if (isset($res->codigoRta)) {
        
        if ($res->codigoRta=='OK') {

          if (is_null($carrito)) {
            $carrito=time();
          }

            $dataicg = array(
            'id_orden' => $carrito, 
            'doc_cliente' => $c->doc_cliente, 
            'monto_descuento' => 0, 
            'json' => json_encode($res), 
            'id_user' => $s_user, 
          );

          AlpConsultaIcg::create($dataicg);

          return $res->cupoCredito-$res->acumuladoCredito;

           
        }else{

          if (is_null($carrito)) {
            $carrito=time();
          }

          $dataicg = array(
          'id_orden' => $carrito, 
          'doc_cliente' => $c->doc_cliente, 
          'monto_descuento' => 0, 
          'json' => json_encode($res), 
          'id_user' => $s_user, 
        );

        AlpConsultaIcg::create($dataicg);

          return 0;

        }


      }else{

        if (is_null($carrito)) {
            $carrito=time();
          }

        $dataicg = array(
          'id_orden' => $carrito, 
          'doc_cliente' => $c->doc_cliente, 
          'monto_descuento' => 0, 
          'json' => json_encode($res), 
          'id_user' => $s_user, 
        );

        AlpConsultaIcg::create($dataicg);

        return 0;

      }

      return 0;
      
    }







    private function consultaIcg()
    {

      $configuracion=AlpConfiguracion::where('id', '=', 1)->first();

      $s_user= \Session::get('user');

      $carrito= \Session::get('cr');

      $c=AlpClientes::where('id_user_client','=', '6512')->first();

      $data = array('DocumentoEmpleado' =>$c->doc_cliente);

      $configuracion=AlpConfiguracion::where('id', '=', '1')->first();
        
       $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://qacupo.alpina.com:8099//api/cupo/ValidarCuposGo?DocumentoEmpleado='.$c->doc_cliente);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'apikeyalp2go: 1';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        //dd($result);
        if (curl_errno($ch)) {
            echo 'Error curl:' . curl_error($ch);
        }
        curl_close($ch);

        $res=json_decode($result);

        activity()->withProperties($res)->log('respuesta consulta icg res');

      if (isset($res->codigoRta)) {
        
        if ($res->codigoRta=='OK') {

          if (is_null($carrito)) {
            $carrito=time();
          }

            $dataicg = array(
            'id_orden' => $carrito, 
            'doc_cliente' => $c->doc_cliente, 
            'monto_descuento' => 0, 
            'json' => json_encode($res), 
            'id_user' => $s_user, 
          );

          AlpConsultaIcg::create($dataicg);

          #return $res->cupoDescuento-$res->acumuladoDescuentos;

          #return 1000000;

           
        }else{

          if (is_null($carrito)) {
            $carrito=time();
          }

          $dataicg = array(
          'id_orden' => $carrito, 
          'doc_cliente' => $c->doc_cliente, 
          'monto_descuento' => 0, 
          'json' => json_encode($res), 
          'id_user' => $s_user, 
        );

        AlpConsultaIcg::create($dataicg);

          #return 0;

        }


      }else{

        if (is_null($carrito)) {
            $carrito=time();
          }

        $dataicg = array(
          'id_orden' => $carrito, 
          'doc_cliente' => $c->doc_cliente, 
          'monto_descuento' => 0, 
          'json' => json_encode($res), 
          'id_user' => $s_user, 
        );

        AlpConsultaIcg::create($dataicg);

        #return 0;

      }

      #return 0;
      return 1000000;
      
    }





    private function consultaIcgTotal()
    {

     //dd($id_orden);
      $configuracion=AlpConfiguracion::where('id', '=', 1)->first();

      $s_user= \Session::get('user');

      $carrito= \Session::get('cr');

     // dd($s_user);

      $c=AlpClientes::where('id_user_client', $s_user)->first();

      $data = array('DocumentoEmpleado' =>$c->doc_cliente);


      $configuracion=AlpConfiguracion::where('id', '=', '1')->first();
        
       $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://qacupo.alpina.com:8099//api/cupo/ValidarCuposGo?DocumentoEmpleado='.$c->doc_cliente);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'apikeyalp2go: 1';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);


        //dd($result);
        if (curl_errno($ch)) {
            echo 'Error curl:' . curl_error($ch);
        }
        curl_close($ch);

        $res=json_decode($result);

        activity()->withProperties($res)->log('respuesta icg res');


      #if (isset($res->codigoRta)) {
      if (1) {

        
        #if ($res->codigoRta=='OK') {
          if (1) {

          if (is_null($carrito)) {
            $carrito=time();
          }

            $dataicg = array(
            'id_orden' => $carrito, 
            'doc_cliente' => $c->doc_cliente, 
            'monto_descuento' => 0, 
            'json' => json_encode($res), 
            'id_user' => $s_user, 
          );

          AlpConsultaIcg::create($dataicg);



          return $res->cupoDescuento;

          return 1000000;

           
        }else{

          if (is_null($carrito)) {
            $carrito=time();
          }

          $dataicg = array(
          'id_orden' => $carrito, 
          'doc_cliente' => $c->doc_cliente, 
          'monto_descuento' => 0, 
          'json' => json_encode($res), 
          'id_user' => $s_user, 
        );

        AlpConsultaIcg::create($dataicg);



          return 0;

        }


      }else{

        if (is_null($carrito)) {
            $carrito=time();
          }

        $dataicg = array(
          'id_orden' => $carrito, 
          'doc_cliente' => $c->doc_cliente, 
          'monto_descuento' => 0, 
          'json' => json_encode($res), 
          'id_user' => $s_user, 
        );

        AlpConsultaIcg::create($dataicg);

        return 0;

                       

      }

      return 0;

      
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

      $descuento=($total-$impuesto)*($configuracion->porcentaje_icg/100);

      if ($descuento>$cupo_icg) {
        
        $descuento=$cupo_icg;
      }
         // dd($descuento);

      if ($cupo_icg>=$descuento) {

          $datadescuento = array(
          'id_orden' => $carrito,
          'codigo_orden' => $carrito,
          'monto_descuento' => $descuento,
          'json' => 0,
          'aplicado' => 0,
          'id_user' => $user->id
        );

        AlpOrdenesDescuentoIcg::create($datadescuento);



        foreach($cart as $c){

          $precio_base=0;

          if ($c->id_impuesto==1) {
            
            $precio_base=$c->precio_oferta*(1-$c->valor_impuesto);

            $precio_base=$precio_base*(1-($configuracion->porcentaje_icg/100));

            $c->impuesto=$precio_base*$c->valor_impuesto;

            $c->monto_descuento=$precio_base*(1-($configuracion->porcentaje_icg/100));

          }

        }

      }

      


       return redirect('order/detail');

    }







    private function registroIcg($ordenId)
    {

      activity()->withProperties(1)
                        ->log('registro icg  ');

     //dd($id_orden);
      $configuracion=AlpConfiguracion::where('id', '=', 1)->first();

      $s_user= \Session::get('user');

      $carrito= \Session::get('cr');

     
     $orden=AlpOrdenes::where('id', $ordenId)->first();

     $descuentosIcg=AlpOrdenesDescuentoIcg::where('id_orden', '=', $orden->id)->first();

     $monto_descuentoicg=0;

     if (isset($descuentosIcg->id)) {

       $monto_descuentoicg=$descuentosIcg->monto_descuento;

     }

      $c=AlpClientes::where('id_user_client', $orden->id_cliente)->first();



      
      $urls=$configuracion->endpoint_icg;

       Log::info('api icg urls '.$urls);


       $date = Carbon::now();

        $hoy=$date->format('YmdH:m:s');

        $fechad=$date->format('Ymd');
        $fechadt=$date->format('Y-m-d');
        $fechah=$date->format('H:m:s');
        $fecha=$fechad.' '.$fechah;
        $fecha_cont=$fechadt.'T'.$fechah;


         $data_consumo = array(
        'NumeroPedido' => $orden->referencia.'-'.time().'1', 
        'Fecha' => $fecha_cont, 
        'DocumentoEmpleado' => $c->doc_cliente, 
        'FormaPago' => 'CONTADO', 
        'ValorTransaccion' => $orden->monto_total, 
        'ValorDescuento' => $monto_descuentoicg
      );

         $dataraw=json_encode($data_consumo);

          activity()->withProperties($dataraw)->log('respuesta icg dataraw');

         $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, 'https://qacupo.alpina.com:8099//api/cupo/cupoAplicar');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
      curl_setopt($ch, CURLOPT_POSTFIELDS, $dataraw); 
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

      $headers = array();
      $headers[] = 'Content-Type: application/json';
       $headers[] = 'apikeyalp2go: 1';
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

      $result = curl_exec($ch);
      if (curl_errno($ch)) {
          echo 'Error:' . curl_error($ch);
      }
      curl_close($ch);

      $res=json_decode($result);

      //dd($res);


      activity()->withProperties($res)->log('registro consumo  icg res');

       

       $notas='Registro de orden en api icg res.';


      #if (isset($res->codigoRta)) {
        if (1) {
        
       # if ($res->codigoRta=='OK') {
        if (1) {


            $dataicg = array(
            'id_orden' => $carrito, 
            'doc_cliente' => $c->doc_cliente, 
            'monto_descuento' => 0, 
            'json' => json_encode($result), 
            'aplicado' => json_encode($result), 
            'id_user' => 1, 
          );

          AlpConsultaIcg::create($dataicg);

          $orden->update(['json_icg'=>json_encode($result)]);

          $data_descuento_update = array(
            'json' => json_encode($result), 
            'aplicado' => $res->idRegistro, 
          );

         //dd($data_descuento_update);

          $descuentosIcg=AlpOrdenesDescuentoIcg::where('id_orden', '=', $orden->id)->first();

          $descuentosIcg->update($data_descuento_update);



          return $res;

           
        }else{

          $dataicg = array(
          'id_orden' => $carrito, 
          'doc_cliente' => $c->doc_cliente, 
          'monto_descuento' => 0, 
          'json' => json_encode($result), 
          'id_user' => 1, 
        );

        AlpConsultaIcg::create($dataicg);


        $data_descuento_update = array(
            'json' => json_encode($result), 
            'aplicado' => $res->idRegistro, 
          );


        $orden->update(['json_icg'=>json_encode($result)]);

         $descuentosIcg->update($data_descuento_update);

          return $res;

        }


      }else{

        $dataicg = array(
          'id_orden' => $carrito, 
          'doc_cliente' => $c->doc_cliente, 
          'monto_descuento' => 0, 
          'json' => json_encode($result), 
          'id_user' => 1
        );

        AlpConsultaIcg::create($dataicg);

        $orden->update(['json_icg'=>json_encode($result)]);

        if (isset($descuentosIcg->id)) {

          $descuentosIcg->update(['json'=>json_encode($result)]);

        }

        return $res;

                       

      }

      
    }


    private function registroIcgCancelar($ordenId)
    {

     //dd($id_orden);
      $configuracion=AlpConfiguracion::where('id', '=', 1)->first();

     
     $orden=AlpOrdenes::where('id', $ordenId)->first();

     $descuentosIcg=AlpOrdenesDescuentoIcg::where('id_orden', '=', $orden->id)->first();

     $monto_descuentoicg=0;

     if (isset($descuentosIcg->id)) {

       $monto_descuentoicg=$descuentosIcg->monto_descuento;



       $ReferenciaRegistroAnulado=$descuentosIcg->aplicado;

     }

      $c=AlpClientes::where('id_user_client', $orden->id_cliente)->first();

      $urls=$configuracion->endpoint_icg;

       Log::info('api icg urls '.$urls);

       $date = Carbon::now();

        $hoy=$date->format('YmdH:m:s');

        $fechad=$date->format('Ymd');
        $fechadt=$date->format('Y-m-d');
        $fechah=$date->format('H:m:s');
        $fecha=$fechad.' '.$fechah;
        $fecha_cont=$fechadt.'T'.$fechah;


         $data_consumo = array(
        'NumeroPedido' => $orden->referencia.'-'.time(), 
        'Fecha' => $fecha_cont, 
        'DocumentoEmpleado' => $c->doc_cliente, 
        'FormaPago' => 'CONTADO', 
        'ValorTransaccion' => '-'.$orden->monto_total, 
        'ValorDescuento' => '-'.$monto_descuentoicg,
        'ReferenciaRegistroAnulado' => $ReferenciaRegistroAnulado
      );



         $dataraw=json_encode($data_consumo);

activity()->withProperties($dataraw)->log(' cancelar icg dataraw');

         $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, 'https://qacupo.alpina.com:8099/api/cupo/cupoAplicar');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
      curl_setopt($ch, CURLOPT_POSTFIELDS, $dataraw); 
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

      $headers = array();
      $headers[] = 'Content-Type: application/json';
       $headers[] = 'apikeyalp2go: 1';
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

      $result = curl_exec($ch);
      if (curl_errno($ch)) {
          echo 'Error:' . curl_error($ch);
             if ($res->codigoRta=='OK') {

            $dataicg = array(
            'id_orden' => $orden->id, 
            'doc_cliente' => $c->doc_cliente, 
            'monto_descuento' => 0, 
            'json' => json_encode($res), 
            'id_user' => 1, 
          );

          //  dd($dataicg)

          AlpConsultaIcg::create($dataicg);

          $orden->update(['json_icg'=>json_encode($result)]);

        // $descuentosIcg->update(['json'=>json_encode($result)]);



         // $descuentosIcg->delete();

          return $res;
           
        }else{

          $dataicg = array(
          'id_orden' => $orden->id, 
          'doc_cliente' => $c->doc_cliente, 
          'monto_descuento' => 0, 
          'json' => json_encode($res), 
          'id_user' => 1, 
        );

        AlpConsultaIcg::create($dataicg);


        $orden->update(['json_icg'=>json_encode($result)]);

         // $descuentosIcg->update(['json'=>json_encode($result)]);



          return $res;

        }


      }else{

        $dataicg = array(
          'id_orden' => $orden->id, 
          'doc_cliente' => $c->doc_cliente, 
          'monto_descuento' => 0, 
          'json' => json_encode($res), 
          'id_user' => 1, 
        );

        AlpConsultaIcg::create($dataicg);

        $orden->update(['json_icg'=>json_encode($result)]);

        //  $descuentosIcg->update(['json'=>json_encode($result)]);



        return $res;

                       

      }

      
    }










}