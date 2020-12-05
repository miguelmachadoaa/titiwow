<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Http\Requests\OrdenesRequest;
use App\Models\AlpOrdenes;
use App\Models\AlpOrdenesDescuento;
use App\Models\AlpEstatusOrdenes;
use App\Models\AlpOrdenesHistory;
use App\Models\AlpDetalles;
use App\Models\AlpAlmacenes;
use App\Models\AlpAlmacenProducto;
use App\Models\AlpPagos;
use App\Models\AlpProductos;
use App\Models\AlpPuntos;
use App\Models\AlpConfiguracion;
use App\Models\AlpEnvios;
use App\Models\AlpEnviosHistory;
use App\Models\AlpDirecciones;
use App\Models\AlpFeriados;
use App\Models\AlpFormaCiudad;
use App\Models\AlpFormaspago;
use App\Models\AlpFormasenvio;
use App\Models\AlpEstatusPagos;
use App\Models\AlpInventario;
use App\Models\AlpSaldo;
use App\Models\AlpTemp;
use App\Models\AlpAnchetaMensaje;
use Illuminate\Support\Facades\Log;

use App\User;
use App\City;
use App\RoleUser;
use App\Http\Requests;
use Illuminate\Http\Request;
use Mail;
use Redirect;
use Response;
use Sentinel;
use View;
use DB;
use MP;
use Intervention\Image\Facades\Image;
use DOMDocument;
use App\Custom\fpdf\fpdf;
use Carbon\Carbon;



class AlpOrdenesController extends JoshController
{
    /**
     * Show a list of all recibthe groups.
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
                        ->log('AlpOrdenesController/index ');

        }else{

          activity()
          ->log('AlpOrdenesController/index');

        }

         if (!Sentinel::getUser()->hasAnyAccess(['ordenes.index'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }





        $estatus_ordenes = AlpEstatusOrdenes::all();

          $ordenes = AlpOrdenes::where('id', '1')->get();

        // Show the page
        return view('admin.ordenes.index', compact('ordenes', 'estatus_ordenes'));

    }




       public function sendmail($id)
    {

      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('AlpOrdenesController/sendmail ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpOrdenesController/sendmail');

        }

         if (!Sentinel::getUser()->hasAnyAccess(['ordenes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

         $configuracion = AlpConfiguracion::where('id', '1')->first();

        $orden = AlpOrdenes::find($id);

        $cliente =  User::select('users.*','roles.name as name_role','alp_clientes.estado_masterfile as estado_masterfile','alp_clientes.estado_registro as estado_registro','alp_clientes.telefono_cliente as telefono_cliente','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.cod_alpinista as cod_alpinista','alp_clientes.doc_cliente as doc_cliente')
        ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
        ->join('role_users', 'users.id', '=', 'role_users.user_id')
        ->join('roles', 'role_users.role_id', '=', 'roles.id')
        ->where('users.id', '=', $orden->id_user)->first();

        $data = [
            'cliente' => $cliente,
            'orden' => $orden
        ];


        // Send Email to admin
        Mail::to($cliente->email)
            ->send( new \App\Mail\NovedadMail($data));

        // Send Email to user
        Mail::to($configuracion->correo_admin)
            ->send( new \App\Mail\NovedadMail($data));

        //Redirect to contact page
        return redirect('admin/ordenes/'.$orden->id.'/detalle/')->with('success', trans('Su correo ha sido enviado'));
    }






public function compramasupdate()
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpOrdenesController/compramas ');

        }else{

          activity()
          ->log('AlpOrdenesController/compramas');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['ordenes.compramas'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        $estatus_ordenes = AlpEstatusOrdenes::all();

        $ordenes=AlpOrdenes::limit(1)->get();

       // dd($ordenes);

          $almacen=AlpAlmacenes::where('id', $user->almacen)->first();

        return view('admin.ordenes.compramas', compact('ordenes', 'estatus_ordenes', 'almacen'));

    }


    public function compramas()
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpOrdenesController/compramas ');

        }else{

          activity()
          ->log('AlpOrdenesController/compramas');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['ordenes.compramas'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        $estatus_ordenes = AlpEstatusOrdenes::all();

        $ordenes=AlpOrdenes::limit(1)->get();

       // dd($ordenes);

          $almacen=AlpAlmacenes::where('id', $user->almacen)->first();

        return view('admin.ordenes.compramas', compact('ordenes', 'estatus_ordenes', 'almacen'));

    }




      public function datacompramas()
    {

     // dd('i');

      $permiso_cancelar = array('1','2','3' );
       

       $id_rol=0;

        if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

            $role=RoleUser::where('user_id', $user_id)->first();

            $id_rol=$role->role_id;
        }
      

        $ordenes = AlpOrdenes::select(
          'alp_ordenes.id as id',
          'alp_ordenes.origen as origen', 
          'alp_ordenes.estado_compramas as estado_compramas',
          'alp_ordenes.estatus as estatus', 
          'alp_ordenes.estatus_pago as estatus_pago', 
          'alp_ordenes.ordencompra as ordencompra', 
          'alp_ordenes.monto_total as monto_total', 
          'alp_ordenes.factura as factura', 
          'alp_ordenes.referencia as referencia', 
          'alp_ordenes.tracking as tracking', 
          'alp_ordenes.id_forma_envio as id_forma_envio', 
          'alp_ordenes.id_forma_pago as id_forma_pago', 
          'alp_ordenes.id_almacen as id_almacen', 
          'alp_ordenes.id_address as id_address', 
          'alp_ordenes.envio_compramas as envio_compramas', 
          'alp_ordenes.created_at as created_at', 
          'alp_clientes.telefono_cliente as telefono_cliente',
          'users.first_name as first_name', 
          'users.last_name as last_name')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
          ->whereNotNull('alp_ordenes.estado_compramas')
          ->where('alp_ordenes.estado_compramas', '<>', '200')
          ->groupBy('alp_ordenes.id')
         ->limit('2000')
         ->orderBy('alp_ordenes.id', 'desc')
          ->get();
         

          $formaspago=AlpFormaspago::pluck('nombre_forma_pago', 'id');
          $formasenvio=AlpFormasenvio::pluck('nombre_forma_envios', 'id');
          $estatus_pago=AlpEstatusPagos::pluck('estatus_pago_nombre', 'id');
          $estatus_ordenes=AlpEstatusOrdenes::pluck('estatus_nombre', 'id');
          $almacenes=AlpAlmacenes::pluck('nombre_almacen', 'id');
          $direcciones=AlpDirecciones::pluck('city_id', 'id');

         // dd($ordenes);

          //dd($formasenvio);

            $data = array();

          foreach($ordenes as $row){

            $envio=AlpEnvios::where('id_orden', $row->id)->first();

            $pago="<div style='display: inline-block;' class='pago_".$row->id."'>  
            <button data-id='".$row->id."' class='btn btn-xs btn-success pago' > ".$estatus_pago[$row->estatus_pago]." </button></div>";

             $estatus="<span class='badge badge-default' >".$estatus_ordenes[$row->estatus]."</span>";


                 $actions = " 
                  <a class='btn btn-primary btn-xs' href='".route('admin.ordenes.detalle', $row->id)."'>
                      ver detalles
                  </a>

                   <div style='display: inline-block;' class='estatus_".$row->id."'>";

                if (in_array($id_rol, $permiso_cancelar)) {
                  
                  if ($row->estatus!=4) { 
                    
                    $cancelado = " <button data-id='".$row->id."'  data-codigo='".$row->ordencompra."'  data-estatus='".$estatus_ordenes[$row->estatus]."' class='btn btn-xs btn-danger confirmar' > Cancelar </button></div>";

                  }else{

                    $cancelado = " ";
                    
                  }

              }else{

                  $cancelado = " ";

              }

              if (isset($envio->id)) {

                $row->monto_total=$row->monto_total+$envio->costo;

              }

              $descuento=AlpOrdenesDescuento::where('id_orden', $row->id)->first();

              if (isset($descuento->id)) {

                $cupon=$descuento->codigo_cupon;
                # code...
              }else{

                $cupon='N/A';

              }

              if (isset($formasenvio[$row->id_forma_envio])) {
               $fe=$formasenvio[$row->id_forma_envio];
              }else{

                $fe='No se reconoce';
              }

              if (isset($formaspago[$row->id_forma_pago])) {
                $fp=$formaspago[$row->id_forma_pago];
              }else{
                $fp='No se reconoce';
              }

              $nombre_almacen='';

              $nombre_ciudad='';

             // dd($cities); 

              if (isset($almacenes[$row->id_almacen])) {
                
                $nombre_almacen=$almacenes[$row->id_almacen];
              }

              if (isset($direcciones[$row->id_address])) {

                if (isset($ciudades[$direcciones[$row->id_address]])) {

                  //dd($ciudades[$direcciones[$row->id_address]]);
                  
                  $nombre_ciudad=$ciudades[$direcciones[$row->id_address]];

                }
               
              }


               if ($row->estado_compramas=='200') {
                    
                    $compramas = " <button  class='btn btn-xs btn-primary ' > Recibido </button></div>";


            $reenviar="<div style='display: inline-block;' class='compramas_".$row->id."'>  
            <a href='".secure_url('admin/ordenes/'.$row->id.'/reenviarcompramas')."' data-id='".$row->id."' class='btn btn-xs btn-success compramas' >Aprobar Orden Compramas </a></div>";



                  }else{

                    

                     $reenviar="<div style='display: inline-block;' class='compramas_".$row->id."'>  
            <a href='".secure_url('admin/ordenes/'.$row->id.'/reenviarcompramas')."' data-id='".$row->id."' class='btn btn-xs btn-success compramas' >Aprobar Orden Compramas</a></div>";


                    $compramas = " <button  class='btn btn-xs btn-danger ' > Error compramas </button></div>";
                    
                  }



                  if ($row->envio_compramas=='1') {
                    
                    $ec='Orden Recibida';

                  }elseif($row->envio_compramas=='2'){

                     $ec='Orden Aprobada';

                  }elseif($row->envio_compramas=='3'){

                     $ec='Orden Cancelada';

                  }else{

                      $ec='Orden Sin Estatus';
                  }


                   if ($row->origen=='1') {

                $origen='Tomapedidos';
                # code...
              }else{

                $origen='Web';

              }

              

              //dd($nombre_almacen);

               $data[]= array(
                 $row->id, 
                 $row->referencia, 
                 $row->first_name.' '.$row->last_name, 
                 $row->telefono_cliente, 
                 $fe, 
                 $fp,
                 number_format($row->monto_total,2), 
                 $row->ordencompra, 
                 $cupon, 
                 $row->factura, 
                 $row->tracking, 
                 $nombre_almacen, 
                 $nombre_ciudad, 
                 $origen, 
                 date("d/m/Y H:i:s", strtotime($row->created_at)),
                 $compramas, 
                 $pago, 
                 $estatus, 
                 $ec, 
                 $reenviar, 
                 $actions.$cancelado
              );

          }


          return json_encode( array('data' => $data ));

    }















   

      public function data2(Request $request)
    {

  
    $permiso_cancelar = array('1','2','3' );
       

       $id_rol=0;

        if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

            $role=RoleUser::where('user_id', $user_id)->first();

            $id_rol=$role->role_id;
        }

    $input=$request->all();

   // dd($input['order'][0]['column']);
   
   //$temp=AlpTemp::orderBy('id', 'desc')->first();

   //dd($temp->id);

    $c_orden = AlpOrdenes::select(
          'alp_ordenes.id as id',
          'alp_ordenes.estatus as estatus', 
          'alp_ordenes.estatus_pago as estatus_pago', 
          'alp_ordenes.ordencompra as ordencompra', 
          'alp_ordenes.monto_total as monto_total', 
          'alp_ordenes.factura as factura', 
          'alp_ordenes.referencia as referencia', 
          'alp_ordenes.tracking as tracking', 
          'alp_ordenes.id_forma_envio as id_forma_envio', 
          'alp_ordenes.id_forma_pago as id_forma_pago', 
          'alp_ordenes.id_almacen as id_almacen', 
          'alp_ordenes.id_address as id_address', 
          'alp_ordenes.created_at as created_at', 
          'alp_clientes.telefono_cliente as telefono_cliente',
          'users.first_name as first_name', 
          'users.last_name as last_name')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
          ->groupBy('alp_ordenes.id')->limit(2000);


         $ordenes=$c_orden
         //->skip($input['start'])
         //->take($input['length'])
         ->where('alp_ordenes.id', '>', $temp->id)
          ->get();

          //dd($ordenes);
          $formaspago=AlpFormaspago::pluck('nombre_forma_pago', 'id');
          $formasenvio=AlpFormasenvio::pluck('nombre_forma_envios', 'id');
          $estatus_pago=AlpEstatusPagos::pluck('estatus_pago_nombre', 'id');
          $estatus_ordenes=AlpEstatusOrdenes::pluck('estatus_nombre', 'id');
          $almacenes=AlpAlmacenes::pluck('nombre_almacen', 'id');
          $direcciones=AlpDirecciones::pluck('city_id', 'id');

           $data = array();

          foreach($ordenes as $row){

            $envio=AlpEnvios::where('id_orden', $row->id)->first();

            $pago="<div style='display: inline-block;' class='pago_".$row->id."'>  
            <button data-id='".$row->id."' class='btn btn-xs btn-success pago' > ".$estatus_pago[$row->estatus_pago]." </button></div>";

             $estatus="<span class='badge badge-default' >".$estatus_ordenes[$row->estatus]."</span>";


                 $actions = " 
                  <a class='btn btn-primary btn-xs' href='".route('admin.ordenes.detalle', $row->id)."'>
                      ver detalles
                  </a>

                   <div style='display: inline-block;' class='estatus_".$row->id."'>";

                if (in_array($id_rol, $permiso_cancelar)) {
                  
                  if ($row->estatus!=4) {
                    
                    $cancelado = " <button data-id='".$row->id."'  data-codigo='".$row->ordencompra."'  data-estatus='".$estatus_ordenes[$row->estatus]."' class='btn btn-xs btn-danger confirmar' > Cancelar </button></div>";

                  }else{

                    $cancelado = " ";
                    
                  }

              }else{

                  $cancelado = " ";

              }

              if (isset($envio->id)) {

                $row->monto_total=$row->monto_total+$envio->costo;

              }

              $descuento=AlpOrdenesDescuento::where('id_orden', $row->id)->first();

              if (isset($descuento->id)) {

                $cupon=$descuento->codigo_cupon;
                # code...
              }else{

                $cupon='N/A';

              }

              if (isset($formasenvio[$row->id_forma_envio])) {
               $fe=$formasenvio[$row->id_forma_envio];
              }else{

                $fe='No se reconoce';
              }

              if (isset($formaspago[$row->id_forma_pago])) {
                $fp=$formaspago[$row->id_forma_pago];
              }else{
                $fp='No se reconoce';
              }

              $nombre_almacen='';

              $nombre_ciudad='';

             // dd($cities); 

              if (isset($almacenes[$row->id_almacen])) {
                
                $nombre_almacen=$almacenes[$row->id_almacen];
              }

              if (isset($direcciones[$row->id_address])) {

                if (isset($ciudades[$direcciones[$row->id_address]])) {

                  //dd($ciudades[$direcciones[$row->id_address]]);
                  
                  $nombre_ciudad=$ciudades[$direcciones[$row->id_address]];

                }
               
              }

              //dd($nombre_almacen);

               $data[]= array(
                 $row->id, 
                 $row->referencia, 
                 $row->first_name.' '.$row->last_name, 
                 $row->telefono_cliente, 
                 $fe, 
                 $fp,
                 number_format($row->monto_total,2), 
                 $row->ordencompra, 
                 $cupon, 
                 $row->factura, 
                 $row->tracking, 
                 $nombre_almacen, 
                 $nombre_ciudad, 
                 $origen,
                 date("d/m/Y H:i:s", strtotime($row->created_at)),
                 $pago, 
                 $estatus
                 //$actions.$cancelado
              );


                $data_n= array(
                 'id'=>$row->id, 
                 'referencia'=>$row->referencia, 
                 'cliente'=>$row->first_name.' '.$row->last_name, 
                 'telefono'=>$row->telefono_cliente, 
                 'forma_envio'=>$fe, 
                 'forma_pago'=>$fp,
                 'total'=>number_format($row->monto_total,2), 
                 'codigo_oracle'=>$row->ordencompra, 
                 'cupon'=>$cupon, 
                 'factura'=>$row->factura, 
                 'tracking'=>$row->tracking, 
                 'almacen'=>$nombre_almacen, 
                 'ciudad'=>$nombre_ciudad, 
                 'creado'=>date("d/m/Y H:i:s", strtotime($row->created_at)),
                 'estado_pago'=>$row->estatus_pago, 
                 'estado'=>$row->estatus
                 //$actions.$cancelado
              );


                AlpTemp::create($data_n);

          }



          return json_encode( array('data' => $data ));



    }



      public function data()
    {

     // dd('i');

      $permiso_cancelar = array('1','2','3' );
       

       $id_rol=0;

        if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

            $role=RoleUser::where('user_id', $user_id)->first();

            $id_rol=$role->role_id;
        }
      

        $ordenes = AlpOrdenes::select(
          'alp_ordenes.id as id',
          'alp_ordenes.origen as origen', 
          'alp_ordenes.estatus as estatus', 
          'alp_ordenes.estatus_pago as estatus_pago', 
          'alp_ordenes.ordencompra as ordencompra', 
          'alp_ordenes.monto_total as monto_total', 
          'alp_ordenes.factura as factura', 
          'alp_ordenes.referencia as referencia', 
          'alp_ordenes.tracking as tracking', 
          'alp_ordenes.id_forma_envio as id_forma_envio', 
          'alp_ordenes.id_forma_pago as id_forma_pago', 
          'alp_ordenes.id_almacen as id_almacen', 
          'alp_ordenes.id_address as id_address', 
          'alp_ordenes.created_at as created_at', 
          'alp_clientes.telefono_cliente as telefono_cliente',
          'users.first_name as first_name', 
          'users.last_name as last_name')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
          ->groupBy('alp_ordenes.id')
         ->limit(2000)
         ->orderBy('alp_ordenes.id', 'desc')
          ->get();
         

          $formaspago=AlpFormaspago::pluck('nombre_forma_pago', 'id');
          $formasenvio=AlpFormasenvio::pluck('nombre_forma_envios', 'id');
          $estatus_pago=AlpEstatusPagos::pluck('estatus_pago_nombre', 'id');
          $estatus_ordenes=AlpEstatusOrdenes::pluck('estatus_nombre', 'id');
          $almacenes=AlpAlmacenes::pluck('nombre_almacen', 'id');
          $direcciones=AlpDirecciones::pluck('city_id', 'id');

         // dd($ordenes);

          //dd($formasenvio);

            $data = array();

          foreach($ordenes as $row){

            $envio=AlpEnvios::where('id_orden', $row->id)->first();

            $pago="<div style='display: inline-block;' class='pago_".$row->id."'>  
            <button data-id='".$row->id."' class='btn btn-xs btn-success pago' > ".$estatus_pago[$row->estatus_pago]." </button></div>";

             $estatus="<span class='badge badge-default' >".$estatus_ordenes[$row->estatus]."</span>";


                 $actions = " 
                  <a class='btn btn-primary btn-xs' href='".route('admin.ordenes.detalle', $row->id)."'>
                      ver detalles
                  </a>

                   <div style='display: inline-block;' class='estatus_".$row->id."'>";

                if (in_array($id_rol, $permiso_cancelar)) {
                  
                  if ($row->estatus!=4) {
                    
                    $cancelado = " <button data-id='".$row->id."'  data-codigo='".$row->ordencompra."'  data-estatus='".$estatus_ordenes[$row->estatus]."' class='btn btn-xs btn-danger confirmar' > Cancelar </button></div>";

                  }else{

                    $cancelado = " ";
                    
                  }

              }else{

                  $cancelado = " ";

              }

              if (isset($envio->id)) {

                $row->monto_total=$row->monto_total+$envio->costo;

              }

              $descuento=AlpOrdenesDescuento::where('id_orden', $row->id)->first();

              if (isset($descuento->id)) {

                $cupon=$descuento->codigo_cupon;
                # code...
              }else{

                $cupon='N/A';

              }


              if ($row->origen=='1') {

                $origen='Tomapedidos';
                # code...
              }else{

                $origen='Web';

              }

              if (isset($formasenvio[$row->id_forma_envio])) {
               $fe=$formasenvio[$row->id_forma_envio];
              }else{

                $fe='No se reconoce';
              }

              if (isset($formaspago[$row->id_forma_pago])) {
                $fp=$formaspago[$row->id_forma_pago];
              }else{
                $fp='No se reconoce';
              }

              $nombre_almacen='';

              $nombre_ciudad='';

             // dd($cities); 

              if (isset($almacenes[$row->id_almacen])) {
                
                $nombre_almacen=$almacenes[$row->id_almacen];
              }

              if (isset($direcciones[$row->id_address])) {

                if (isset($ciudades[$direcciones[$row->id_address]])) {

                  //dd($ciudades[$direcciones[$row->id_address]]);
                  
                  $nombre_ciudad=$ciudades[$direcciones[$row->id_address]];

                }
               
              }


              $mensaje=AlpAnchetaMensaje::where('id_orden', $row->id)->first();

              if (isset($mensaje->id)) {

                $actions = $actions." 
                  <a target='_blank' class='btn btn-info  btn-xs' href='".secure_url('admin/ordenes/'.$row->id.'/pdf'). "'>
                      Ver Pdf
                  </a>";
                
              }





              //dd($nombre_almacen);

               $data[]= array(
                 $row->id, 
                 $row->referencia, 
                 $row->first_name.' '.$row->last_name, 
                 $row->telefono_cliente, 
                 $fe, 
                 $fp,
                 number_format($row->monto_total,2), 
                 $row->ordencompra, 
                 $cupon, 
                 $row->factura, 
                 $row->tracking, 
                 $nombre_almacen, 
                 $nombre_ciudad, 
                 $origen, 
                 date("d/m/Y H:i:s", strtotime($row->created_at)),
                 $pago, 
                 $estatus, 
                 $actions.$cancelado
              );

          }


          return json_encode( array('data' => $data ));

    }

     public function descuento()
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpOrdenesController/descuento ');

        }else{

          activity()
          ->log('AlpOrdenesController/descuento');

        }

         if (!Sentinel::getUser()->hasAnyAccess(['ordenes.descuento'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        // Grab all the groups
      
       

        $estatus_ordenes = AlpEstatusOrdenes::all();

         $ordenes = AlpOrdenes::where('id', '1')->get();

        //  dd($ordenes);
       
        // Show the page
        return view('admin.ordenes.descuento', compact('ordenes', 'estatus_ordenes'));

    }




     public function datadescuento()
    {
    
          $ordenes = AlpOrdenes::select('alp_ordenes.*', 'alp_clientes.telefono_cliente as telefono_cliente','users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre', 'alp_ordenes_pagos.json as json')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
           ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->leftJoin('alp_ordenes_pagos', 'alp_ordenes.id', '=', 'alp_ordenes_pagos.id_orden')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
           ->groupBy('alp_ordenes.id')
           ->limit(2000)
          ->get();

          $almacenes=AlpAlmacenes::pluck('nombre_almacen', 'id');
          $direcciones=AlpDirecciones::pluck('city_id', 'id');
          $ciudades=City::pluck('city_name', 'id');

          

            $data = array();

          foreach($ordenes as $row){

            $pago="<div style='display: inline-block;' class='pago_".$row->id."'>  

                                            <button data-id='".$row->id."' class='btn btn-xs btn-success pago' > ".$row->estatus_pago_nombre." </button></div>";

             $estatus="<span class='badge badge-default' >".$row->estatus_nombre."</span>";



                 $actions = " 
                  <a class='btn btn-primary btn-xs' href='".route('admin.ordenes.detalle', $row->id)."'>
                                                ver detalles
                                            </a>  <div style='display: inline-block;' class='estatus_".$row->id."'>
                                           ";


              $envio=AlpEnvios::where('id_orden', $row->id)->first();

              if (isset($envio->id)) {
                # code...

                $row->monto_total=$row->monto_total+$envio->costo;
              }    

              $descuento=AlpOrdenesDescuento::where('id_orden', $row->id)->first();

              if (isset($descuento->id)) {

                $cupon=$descuento->codigo_cupon;
                # code...
              }else{

                $cupon='N/A';

              }


              $nombre_almacen='';

              $nombre_ciudad='';

             // dd($cities); 

              if (isset($almacenes[$row->id_almacen])) {
                
                $nombre_almacen=$almacenes[$row->id_almacen];
              }

              if (isset($direcciones[$row->id_address])) {

                if (isset($ciudades[$direcciones[$row->id_address]])) {

                  //dd($ciudades[$direcciones[$row->id_address]]);
                  
                  $nombre_ciudad=$ciudades[$direcciones[$row->id_address]];

                }
               
              }


               if ($row->origen=='1') {

                $origen='Tomapedidos';
                # code...
              }else{

                $origen='Web';

              }


              $mensaje=AlpAnchetaMensaje::where('id_orden', $row->id)->first();

              if (isset($mensaje->id)) {

                $actions = $actions." 
                  <a target='_blank' class='btn btn-info  btn-xs' href='".secure_url('admin/ordenes/'.$row->id.'/pdf'). "'>
                      Ver Pdf
                  </a>";
                
              }







               $data[]= array(
                 $row->id, 
                 $row->referencia, 
                 $row->first_name.' '.$row->last_name, 
                 $row->telefono_cliente, 
                 $row->nombre_forma_envios, 
                 $row->nombre_forma_pago, 
                 $nombre_almacen, 
                 $nombre_ciudad, 
                 $origen,
                 number_format($row->monto_total,2), 
                 number_format($row->monto_total_base,2), 
                 $cupon,
                 $actions
              );

          }

          return json_encode( array('data' => $data ));

    }

    public function consolidado()
    {
        // Grab all the groups

       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpOrdenesController/consolidado ');

        }else{

          activity()
          ->log('AlpOrdenesController/consolidado');

        }

         if (!Sentinel::getUser()->hasAnyAccess(['ordenes.consolidado'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        $dt = Carbon::now();

        $date_inicio = Carbon::create($dt->year, $dt->month, $dt->day, 5, 59, 0); 
        
        $date_inicio->subDay();

        $date_fin = Carbon::create($dt->year, $dt->month, $dt->day, 6, 0, 0); 
              
       

        $estatus_ordenes = AlpEstatusOrdenes::all();

          $ordenes = AlpOrdenes::where('id', '1')->get();
       
        // Show the page
        return view('admin.ordenes.consolidado', compact('ordenes', 'estatus_ordenes'));

    }

    public function espera()
    {
        // Grab all the groups

       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpOrdenesController/espera ');

        }else{

          activity()
          ->log('AlpOrdenesController/espera');

        }

         if (!Sentinel::getUser()->hasAnyAccess(['ordenes.espera'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

    
        $estatus_ordenes = AlpEstatusOrdenes::all();

         $ordenes = AlpOrdenes::where('id', '1')->get();
        // Show the page
        return view('admin.ordenes.espera', compact('ordenes', 'estatus_ordenes'));

    }



      public function dataespera()
    {

       $ordenes = AlpOrdenes::select('alp_ordenes.*', 'alp_clientes.telefono_cliente as telefono_cliente','users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre', 'alp_ordenes_pagos.json as json')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
           ->leftJoin('alp_ordenes_pagos', 'alp_ordenes.id', '=', 'alp_ordenes_pagos.id_orden')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
          ->where('alp_ordenes.estatus', '8')
          ->groupBy('alp_ordenes.id')
          ->limit(2000)
          ->get();

          $almacenes=AlpAlmacenes::pluck('nombre_almacen', 'id');
          $direcciones=AlpDirecciones::pluck('city_id', 'id');
          $ciudades=City::pluck('city_name', 'id');



          //dd($ordenes);

            $data = array();

          foreach($ordenes as $row){

            $pago="<div style='display: inline-block;' class='pago_".$row->id."'>  

              <button data-id='".$row->id."' class='btn btn-xs btn-success pago' > ".$row->estatus_pago_nombre." </button></div>";

             $estatus="<span class='badge badge-default' >".$row->estatus_nombre."</span>";


                 $actions = " 
                  <a class='btn btn-primary btn-xs' href='".route('admin.ordenes.detalle', $row->id)."'>
                                                ver detalles
                                            </a>";

                 $envio=AlpEnvios::where('id_orden', $row->id)->first();
              if (isset($envio->id)) {
                # code...

                $row->monto_total=$row->monto_total+$envio->costo;
              } 

              $descuento=AlpOrdenesDescuento::where('id_orden', $row->id)->first();

              if (isset($descuento->id)) {

                $cupon=$descuento->codigo_cupon;
                # code...
              }else{

                $cupon='N/A';

              }

              $nombre_almacen='';

              $nombre_ciudad='';

             // dd($cities); 

              if (isset($almacenes[$row->id_almacen])) {
                
                $nombre_almacen=$almacenes[$row->id_almacen];
              }

              if (isset($direcciones[$row->id_address])) {

                if (isset($ciudades[$direcciones[$row->id_address]])) {

                  //dd($ciudades[$direcciones[$row->id_address]]);
                  
                  $nombre_ciudad=$ciudades[$direcciones[$row->id_address]];

                }
               
              }

               if ($row->origen=='1') {

                $origen='Tomapedidos';
                # code...
              }else{

                $origen='Web';

              }


              $mensaje=AlpAnchetaMensaje::where('id_orden', $row->id)->first();

              if (isset($mensaje->id)) {

                $actions = $actions." 

                <a class='btn btn-primary btn-xs' href='".route('admin.ordenes.detalle', $row->id)."'>
                      ver detalles
                  </a>

                  
                  <a target='_blank' class='btn btn-info  btn-xs' href='".secure_url('admin/ordenes/'.$row->id.'/pdf'). "'>
                      Ver Pdf
                  </a>";
                
              }







               $data[]= array(
                 $row->id, 
                 $row->referencia, 
                 $row->first_name.' '.$row->last_name, 
                 $row->telefono_cliente, 
                 $row->nombre_forma_envios, 
                 $row->nombre_forma_pago, 
                 $nombre_almacen, 
                 $nombre_ciudad, 
                 $origen,
                 number_format($row->monto_total,2), 
                 $row->ordencompra, 
                 $cupon, 
                 $row->factura, 
                 $row->tracking, 
                 
                 date("d/m/Y H:i:s", strtotime($row->created_at)), 
                 $pago, 
                 $estatus, 
                 $actions
              );



          }


          return json_encode( array('data' => $data ));

    }


    public function aprobados()
    {
        // Grab all the groups

      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpOrdenesController/aprobados ');

        }else{

          activity()
          ->log('AlpOrdenesController/aprobados');

        }

         if (!Sentinel::getUser()->hasAnyAccess(['ordenes.aprobados'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }




       

        $estatus_ordenes = AlpEstatusOrdenes::all();

         $ordenes = AlpOrdenes::where('id', '1')->get();
        // Show the page
        return view('admin.ordenes.aprobados', compact('ordenes', 'estatus_ordenes'));

    }






 public function dataaprobados()
    {
       

        

    
         $ordenes = AlpOrdenes::select('alp_ordenes.*', 'alp_clientes.telefono_cliente as telefono_cliente', 'users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre', 'alp_ordenes_pagos.json as json')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
           ->leftJoin('alp_ordenes_pagos', 'alp_ordenes.id', '=', 'alp_ordenes_pagos.id_orden')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
          ->where('alp_ordenes.estatus', '5')
          ->groupBy('alp_ordenes.id')
          ->limit(2000)
          ->get();

            $data = array();

            $almacenes=AlpAlmacenes::pluck('nombre_almacen', 'id');
          $direcciones=AlpDirecciones::pluck('city_id', 'id');
          $ciudades=City::pluck('city_name', 'id');




          foreach($ordenes as $row){


            $pago="<div style='display: inline-block;' class='pago_".$row->id."'>  

                                            <button data-id='".$row->id."' class='btn btn-xs btn-success pago' > ".$row->estatus_pago_nombre." </button></div>";

             $estatus="<span class='badge badge-default' >".$row->estatus_nombre."</span>";

                 $actions = " 
                  <a class='btn btn-primary btn-xs' href='".route('admin.ordenes.detalle', $row->id)."'>
                                                ver detalles
                                            </a> ";

                  if ($row->factura=='') {

                    $actions=$actions."<div style='display: inline-block;' class='facturar_".$row->id."'>
                  <button data-id='".$row->id."'  data-codigo='".$row->factura."'  data-estatus='".$row->estatus."' class='btn btn-xs btn-info facturar' > Facturar </button></div>";

                    

                  }else{
                    $actions=$actions."<div style='display: inline-block;' class='facturar_".$row->id."'>
                  <button data-id='".$row->id."'  data-codigo='".$row->ordencompra."'  data-estatus='".$row->estatus."' class='btn btn-xs btn-success facturar' > Facturado </button></div>";
                  }


                   $envio=AlpEnvios::where('id_orden', $row->id)->first();
              if (isset($envio->id)) {
                # code...

                $row->monto_total=$row->monto_total+$envio->costo;
              } 

              $descuento=AlpOrdenesDescuento::where('id_orden', $row->id)->first();

              if (isset($descuento->id)) {

                $cupon=$descuento->codigo_cupon;
                # code...
              }else{

                $cupon='N/A';

              }

             $nombre_almacen='';

              $nombre_ciudad='';

             // dd($cities); 

              if (isset($almacenes[$row->id_almacen])) {
                
                $nombre_almacen=$almacenes[$row->id_almacen];
              }

              if (isset($direcciones[$row->id_address])) {

                if (isset($ciudades[$direcciones[$row->id_address]])) {

                  //dd($ciudades[$direcciones[$row->id_address]]);
                  
                  $nombre_ciudad=$ciudades[$direcciones[$row->id_address]];

                }
               
              }

               if ($row->origen=='1') {

                $origen='Tomapedidos';
                # code...
              }else{

                $origen='Web';

              }


              $mensaje=AlpAnchetaMensaje::where('id_orden', $row->id)->first();

              if (isset($mensaje->id)) {

                $actions = $actions." 
                  <a target='_blank' class='btn btn-info  btn-xs' href='".secure_url('admin/ordenes/'.$row->id.'/pdf'). "'>
                      Ver Pdf
                  </a>";
                
              }






               $data[]= array(
                 $row->id, 
                 $row->referencia, 
                 $row->first_name.' '.$row->last_name, 
                 $row->telefono_cliente, 
                 $row->nombre_forma_envios, 
                 $row->nombre_forma_pago, 
                 number_format($row->monto_total,2), 
                 $cupon, 
                 $row->factura, 
                 $nombre_almacen, 
                 $nombre_ciudad, 
                 $origen,
                 date("d/m/Y H:i:s", strtotime($row->created_at)), 
                 $actions
              );



          }


          return json_encode( array('data' => $data ));

    }
















    public function recibidos()
    {
        // Grab all the groups


      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpOrdenesController/recibidos ');

        }else{

          activity()
          ->log('AlpOrdenesController/recibidos');

        }

         if (!Sentinel::getUser()->hasAnyAccess(['ordenes.recibidos'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


      
       

        $estatus_ordenes = AlpEstatusOrdenes::all();

          $ordenes = AlpOrdenes::where('id', '1')->get();
       
        // Show the page
        return view('admin.ordenes.recibidos', compact('ordenes', 'estatus_ordenes'));

    }



 public function datarecibidos()
    {
       

        

      $ordenes = AlpOrdenes::select('alp_ordenes.*', 'alp_clientes.telefono_cliente as telefono_cliente','users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre', 'alp_ordenes_pagos.json as json')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->leftJoin('alp_ordenes_pagos', 'alp_ordenes.id', '=', 'alp_ordenes_pagos.id_orden')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
          ->where('alp_ordenes.estatus', '1')
          ->where('alp_ordenes.id_forma_pago', '<>', '3')
          ->groupBy('alp_ordenes.id')
          ->limit(2000)
          ->get();

          $almacenes=AlpAlmacenes::pluck('nombre_almacen', 'id');
          $direcciones=AlpDirecciones::pluck('city_id', 'id');
          $ciudades=City::pluck('city_name', 'id');



            $data = array();


          foreach($ordenes as $row){


            $pago="<div style='display: inline-block;' class='pago_".$row->id."'>  

                                            <button data-id='".$row->id."' class='btn btn-xs btn-success pago' > ".$row->estatus_pago_nombre." </button></div>";

             $estatus="<span class='badge badge-default' >".$row->estatus_nombre."</span>";



                 $actions = " 
                  <a class='btn btn-primary btn-xs' href='".route('admin.ordenes.detalle', $row->id)."'>
                                                ver detalles
                                            </a> ";


                                            if ($row->ordencompra=='') {

                                              $actions=$actions."<div style='display: inline-block;' class='aprobar_".$row->id."'>
                                            <button data-id='".$row->id."'  data-codigo='".$row->ordencompra."'  data-estatus='".$row->estatus."' class='btn btn-xs btn-info aprobar' > Aprobar </button></div>";
 
                                              

                                            }else{
                                              $actions=$actions."<div style='display: inline-block;' class='aprobar_".$row->id."'>
                                            <button data-id='".$row->id."'  data-codigo='".$row->ordencompra."'  data-estatus='".$row->estatus."' class='btn btn-xs btn-success aprobar' > Aprobado </button></div>";
                                            }


                                             $envio=AlpEnvios::where('id_orden', $row->id)->first();
              if (isset($envio->id)) {
                # code...

                $row->monto_total=$row->monto_total+$envio->costo;
              } 


               $descuento=AlpOrdenesDescuento::where('id_orden', $row->id)->first();

              if (isset($descuento->id)) {

                $cupon=$descuento->codigo_cupon;
                # code...
              }else{

                $cupon='N/A';

              }



                $nombre_almacen='';

              $nombre_ciudad='';

             // dd($cities); 

              if (isset($almacenes[$row->id_almacen])) {
                
                $nombre_almacen=$almacenes[$row->id_almacen];
              }

              if (isset($direcciones[$row->id_address])) {

                if (isset($ciudades[$direcciones[$row->id_address]])) {

                  //dd($ciudades[$direcciones[$row->id_address]]);
                  
                  $nombre_ciudad=$ciudades[$direcciones[$row->id_address]];

                }
               
              }

               if ($row->origen=='1') {

                $origen='Tomapedidos';
                # code...
              }else{

                $origen='Web';

              }


              $mensaje=AlpAnchetaMensaje::where('id_orden', $row->id)->first();

              if (isset($mensaje->id)) {

                $actions = $actions." 
                  <a target='_blank'  class='btn btn-info btn-xs' href='".secure_url('admin/ordenes/'.$row->id.'/pdf'). "'>
                      Ver Pdf
                  </a>";
                
              }




               $data[]= array(
                 $row->id, 
                 $row->referencia, 
                 $row->first_name.' '.$row->last_name, 
                 $row->telefono_cliente, 
                 $row->nombre_forma_envios, 
                 $row->nombre_forma_pago, 
                 $nombre_almacen, 
                 $nombre_ciudad, 
                 $origen,
                 number_format($row->monto_total,2), 
                 //$row->ordencompra, 
                 $cupon, 
                 $row->factura, 
                 
                 //$row->tracking, 
                 date("d/m/Y H:i:s", strtotime($row->created_at)), 
                 $actions
              );



          }


          return json_encode( array('data' => $data ));

    }











    public function facturados()
    {
        // Grab all the groups

      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpOrdenesController/facturados ');

        }else{

          activity()
          ->log('AlpOrdenesController/facturados');

        }

         if (!Sentinel::getUser()->hasAnyAccess(['ordenes.facturados'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


      
        

        $estatus_ordenes = AlpEstatusOrdenes::all();

         $ordenes = AlpOrdenes::where('id', '1')->get();
       
        // Show the page
        return view('admin.ordenes.facturados', compact('ordenes', 'estatus_ordenes'));

    }




 public function datafacturados()
    {
       
    
         $ordenes = AlpOrdenes::select('alp_ordenes.*', 'alp_clientes.telefono_cliente as telefono_cliente','users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre', 'alp_ordenes_pagos.json as json')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->leftJoin('alp_ordenes_pagos', 'alp_ordenes.id', '=', 'alp_ordenes_pagos.id_orden')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
          ->where('alp_ordenes.estatus', '6')
          ->groupBy('alp_ordenes.id')
          ->limit(2000)
          ->get();

          $almacenes=AlpAlmacenes::pluck('nombre_almacen', 'id');
          $direcciones=AlpDirecciones::pluck('city_id', 'id');
          $ciudades=City::pluck('city_name', 'id');



            $data = array();


          foreach($ordenes as $row){


            $pago="<div style='display: inline-block;' class='pago_".$row->id."'>  

                                            <button data-id='".$row->id."' class='btn btn-xs btn-success pago' > ".$row->estatus_pago_nombre." </button></div>";

             $estatus="<span class='badge badge-default' >".$row->estatus_nombre."</span>";



                 $actions = " 
                  <a class='btn btn-primary btn-xs' href='".route('admin.ordenes.detalle', $row->id)."'>
                                                ver detalles
                                            </a> ";


                                            if ($row->tracking=='') {

                                              $actions=$actions."<div style='display: inline-block;' class='tracking_".$row->id."'>
                                            <button data-id='".$row->id."'  data-codigo='".$row->tracking."'  data-estatus='".$row->estatus."' class='btn btn-xs btn-info tracking' > Enviar </button></div>";
 
                                              

                                            }else{
                                              $actions=$actions."<div style='display: inline-block;' class='tracking_".$row->id."'>
                                            <button data-id='".$row->id."'  data-codigo='".$row->tracking."'  data-estatus='".$row->estatus."' class='btn btn-xs btn-success tracking' > Enviado </button></div>";
                                            }

                                             $envio=AlpEnvios::where('id_orden', $row->id)->first();
              if (isset($envio->id)) {
                # code...

                $row->monto_total=$row->monto_total+$envio->costo;
              } 




               $descuento=AlpOrdenesDescuento::where('id_orden', $row->id)->first();

              if (isset($descuento->id)) {

                $cupon=$descuento->codigo_cupon;
                # code...
              }else{

                $cupon='N/A';

              }




              $nombre_almacen='';

              $nombre_ciudad='';

             // dd($cities); 

              if (isset($almacenes[$row->id_almacen])) {
                
                $nombre_almacen=$almacenes[$row->id_almacen];
              }

              if (isset($direcciones[$row->id_address])) {

                if (isset($ciudades[$direcciones[$row->id_address]])) {

                  //dd($ciudades[$direcciones[$row->id_address]]);
                  
                  $nombre_ciudad=$ciudades[$direcciones[$row->id_address]];

                }
               
              }

               if ($row->origen=='1') {

                $origen='Tomapedidos';
                # code...
              }else{

                $origen='Web';

              }


              $mensaje=AlpAnchetaMensaje::where('id_orden', $row->id)->first();

              if (isset($mensaje->id)) {

                $actions = $actions." 
                  <a target='_blank' class='btn btn-info  btn-xs' href='".secure_url('admin/ordenes/'.$row->id.'/pdf'). "'>
                      Ver Pdf
                  </a>";
                
              }





               $data[]= array(
                 $row->id, 
                 $row->referencia, 
                 $row->first_name.' '.$row->last_name, 
                 $row->telefono_cliente, 
                 $row->nombre_forma_envios, 
                 $row->nombre_forma_pago, 
                 $nombre_almacen, 
                 $nombre_ciudad, 
                 $origen,
                 number_format($row->monto_total,2), 
                 $row->ordencompra, 
                 $cupon, 
                 $row->factura, 
                 $row->tracking, 
                 
                 date("d/m/Y H:i:s", strtotime($row->created_at)), 
                 $actions
              );



          }


          return json_encode( array('data' => $data ));

    }








    public function enviados()
    {
        // Grab all the groups

       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpOrdenesController/enviados ');

        }else{

          activity()
          ->log('AlpOrdenesController/enviados');

        } 

        if (!Sentinel::getUser()->hasAnyAccess(['ordenes.enviados'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }



      
        

        $estatus_ordenes = AlpEstatusOrdenes::all();

        $ordenes = AlpOrdenes::where('id', '1')->get();
       
        // Show the page
        return view('admin.ordenes.enviados', compact('ordenes', 'estatus_ordenes'));

    }


     public function dataenviados()
    {
       
    
           $ordenes = AlpOrdenes::select('alp_ordenes.*', 'alp_clientes.telefono_cliente as telefono_cliente','users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre', 'alp_ordenes_pagos.json as json')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
           ->leftJoin('alp_ordenes_pagos', 'alp_ordenes.id', '=', 'alp_ordenes_pagos.id_orden')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
          ->where('alp_ordenes.estatus', '7')
          ->groupBy('alp_ordenes.id')
          ->limit(2000)
          ->get();

          $almacenes=AlpAlmacenes::pluck('nombre_almacen', 'id');
          $direcciones=AlpDirecciones::pluck('city_id', 'id');
          $ciudades=City::pluck('city_name', 'id');


       

            $data = array();


          foreach($ordenes as $row){


            $pago="<div style='display: inline-block;' class='pago_".$row->id."'>  

                                            <button data-id='".$row->id."' class='btn btn-xs btn-success pago' > ".$row->estatus_pago_nombre." </button></div>";

             $estatus="<span class='badge badge-default' >".$row->estatus_nombre."</span>";



                 $actions = " 
                  <a class='btn btn-primary btn-xs' href='".route('admin.ordenes.detalle', $row->id)."'>
                                                ver detalles
                                            </a> ";


                                             $envio=AlpEnvios::where('id_orden', $row->id)->first();
              if (isset($envio->id)) {
                # code...

                $row->monto_total=$row->monto_total+$envio->costo;
              } 

              
               $descuento=AlpOrdenesDescuento::where('id_orden', $row->id)->first();

              if (isset($descuento->id)) {

                $cupon=$descuento->codigo_cupon;
                # code...
              }else{

                $cupon='N/A';

              }

                                          
              $nombre_almacen='';

              $nombre_ciudad='';

             // dd($cities); 

              if (isset($almacenes[$row->id_almacen])) {
                
                $nombre_almacen=$almacenes[$row->id_almacen];
              }

              if (isset($direcciones[$row->id_address])) {

                if (isset($ciudades[$direcciones[$row->id_address]])) {

                  //dd($ciudades[$direcciones[$row->id_address]]);
                  
                  $nombre_ciudad=$ciudades[$direcciones[$row->id_address]];

                }
               
              }


               if ($row->origen=='1') {

                $origen='Tomapedidos';
                # code...
              }else{

                $origen='Web';

              }


              $mensaje=AlpAnchetaMensaje::where('id_orden', $row->id)->first();

              if (isset($mensaje->id)) {

                $actions = $actions." 
                  <a target='_blank' class='btn btn-info  btn-xs' href='".secure_url('admin/ordenes/'.$row->id.'/pdf'). "'>
                      Ver Pdf
                  </a>";
                
              }




               $data[]= array(
                 $row->id, 
                 $row->referencia, 
                 $row->first_name.' '.$row->last_name, 
                 $row->telefono_cliente, 
                 $row->nombre_forma_envios, 
                 $row->nombre_forma_pago, 
                 $nombre_almacen, 
                 $nombre_ciudad, 
                 $origen,
                 number_format($row->monto_total,2), 
                 $row->ordencompra, 
                 $cupon, 
                 $row->factura, 
                 $row->tracking, 
                 
                 date("d/m/Y H:i:s", strtotime($row->created_at)), 
                 $actions
              );



          }


          return json_encode( array('data' => $data ));

    }


    public function cancelados()
    {
        // Grab all the groups

      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpOrdenesController/cancelados ');

        }else{

          activity()
          ->log('AlpOrdenesController/cancelados');

        } 

        if (!Sentinel::getUser()->hasAnyAccess(['ordenes.cancelados'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


      
       

        $estatus_ordenes = AlpEstatusOrdenes::all();

       $ordenes = AlpOrdenes::where('id', '1')->get();
       
        // Show the page
        return view('admin.ordenes.cancelados', compact('ordenes', 'estatus_ordenes'));

    }



     public function empresas()
    {
        // Grab all the groups

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpOrdenesController/empresas ');

        }else{

          activity()
          ->log('AlpOrdenesController/empresas');

        } 

        if (!Sentinel::getUser()->hasAnyAccess(['ordenes.empresas'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


      
        $ordenes = AlpOrdenes::where('id', '1')->get();

        $estatus_ordenes = AlpEstatusOrdenes::all();

        
       
        // Show the page
        return view('admin.ordenes.empresas', compact('ordenes', 'estatus_ordenes'));

    }



     public function dataempresas()
    {
       
    
          $ordenes = AlpOrdenes::select('alp_ordenes.*', 'users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre', 'alp_empresas.nombre_empresa as nombre_empresa', 'alp_ordenes_pagos.json as json')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
           ->leftJoin('alp_ordenes_pagos', 'alp_ordenes.id', '=', 'alp_ordenes_pagos.id_orden')
          
          ->Join('alp_empresas', 'alp_clientes.id_empresa', '=', 'alp_empresas.id')
          ->groupBy('alp_ordenes.id')
          ->limit(2000)
          ->get();

          $almacenes=AlpAlmacenes::pluck('nombre_almacen', 'id');
          $direcciones=AlpDirecciones::pluck('city_id', 'id');
          $ciudades=City::pluck('city_name', 'id');


       

            $data = array();


          foreach($ordenes as $row){


            $pago="<div style='display: inline-block;' class='pago_".$row->id."'>  

                                            <button data-id='".$row->id."' class='btn btn-xs btn-success pago' > ".$row->estatus_pago_nombre." </button></div>";

             $estatus="<span class='badge badge-default' >".$row->estatus_nombre."</span>";



                 $actions = " 
                  <a class='btn btn-primary btn-xs' href='".route('admin.ordenes.detalle', $row->id)."'>
                                                ver detalles
                                            </a>  <div style='display: inline-block;' class='estatus_".$row->id."'>
                                            <button data-id='".$row->id."'  data-codigo='".$row->cod_oracle_pedido."'  data-estatus='".$row->estatus."' class='btn btn-xs btn-info confirmar' > ".$row->estatus_nombre." </button></div>";


                               $envio=AlpEnvios::where('id_orden', $row->id)->first();
              if (isset($envio->id)) {
                # code...

                $row->monto_total=$row->monto_total+$envio->costo;
              }          

              $descuento=AlpOrdenesDescuento::where('id_orden', $row->id)->first();

              if (isset($descuento->id)) {

                $cupon=$descuento->codigo_cupon;
                # code...
              }else{

                $cupon='N/A';

              }



   
              $nombre_almacen='';

              $nombre_ciudad='';

             // dd($cities); 

              if (isset($almacenes[$row->id_almacen])) {
                
                $nombre_almacen=$almacenes[$row->id_almacen];
              }

              if (isset($direcciones[$row->id_address])) {

                if (isset($ciudades[$direcciones[$row->id_address]])) {

                  //dd($ciudades[$direcciones[$row->id_address]]);
                  
                  $nombre_ciudad=$ciudades[$direcciones[$row->id_address]];

                }
               
              }

               $data[]= array(
                 $row->id, 
                 $row->referencia, 
                 $row->first_name.' '.$row->last_name, 
                 $row->nombre_forma_envios, 
                 $row->nombre_forma_pago, 
                 $nombre_almacen, 
                 $nombre_ciudad, 
                 $origen,
                 number_format($row->monto_total,2), 
                 $row->ordencompra, 
                 $cupon, 
                 $row->factura, 
                 $row->tracking, 
                 date("d/m/Y H:i:s", strtotime($row->created_at)), 
                 $actions
              );



          }


          return json_encode( array('data' => $data ));

    }



    


    /**
     * Group create.
     *
     * @return View
     */
    public function create()
    {

       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpOrdenesController/create ');

        }else{

          activity()
          ->log('AlpOrdenesController/create');

        } 

        if (!Sentinel::getUser()->hasAnyAccess(['ordenes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


        // Show the page
        return view ('admin.ordenes.create');
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(Request $request)
    {
        
       
    }


    /**
     * Group update.
     *
     * @param  int $id
     * @return View
     */
    public function edit($id)
    {
     
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(Request $request, $id)
    {
      

       
    }

    /**
     * Delete confirmation for the given group.
     *
     * @param  int $id
     * @return View
     */
    public function getModalDelete($id = null)
    {
        $model = 'ordenes';
        $confirm_route = $error = null;
        try {
            // Get group information
            
            $categoria = AlpOrdenes::find($id);

            $confirm_route = route('admin.ordenes.delete', ['id' => $categoria->id]);

            return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {
            $error = trans('Ha ocurrido un error al eliminar registro');
            return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }

    /**
     * Delete the given group.
     *
     * @param  int $id
     * @return Redirect
     */
    public function destroy($id)
    {

      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('AlpOrdenesController/destroy ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpOrdenesController/destroy');


        }


        try {
            // Get group information
           
            $categoria = AlpOrdenes::find($id);


            // Delete the group
            $categoria->delete();

            // Redirect to the group management page
            return Redirect::route('admin.ordenes.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.ordenes.index')->with('error', trans('Error al eliminar el registro'));
        }
    }

    /**
     * Group update.
     *
     * @param  int $id
     * @return View
     */
    public function detalle($id)
    {

      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('AlpOrdenesController/detalle ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpOrdenesController/detalle');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['ordenes.detalle'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        $user = Sentinel::getUser();


       
    $orden = AlpOrdenes::find($id);

      //dd($orden);

    $detalles = AlpDetalles::select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.imagen_producto as imagen_producto','alp_productos.referencia_producto as referencia_producto')
          ->join('alp_productos', 'alp_ordenes_detalle.id_producto', '=', 'alp_productos.id')
          ->where('alp_ordenes_detalle.id_orden', $id)
          ->get();

    $pago = AlpPagos::select('alp_ordenes_pagos.*','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago')
          ->join('alp_formas_pagos', 'alp_ordenes_pagos.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->where('alp_ordenes_pagos.id_orden', $id)
          ->first();


    $pago_aprobado = AlpPagos::select('alp_ordenes_pagos.*','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago')
          ->join('alp_formas_pagos', 'alp_ordenes_pagos.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->where('alp_ordenes_pagos.id_orden', $id)
          ->orderBy('alp_ordenes_pagos.id', 'desc')
          ->first();

        $pagos = AlpPagos::select('alp_ordenes_pagos.*','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago')
          ->join('alp_formas_pagos', 'alp_ordenes_pagos.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->where('alp_ordenes_pagos.id_orden', $id)
          ->get();

          //dd($pago);

    $history = AlpOrdenesHistory::select('alp_ordenes_history.*', 'users.first_name as first_name', 'users.last_name as last_name', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre' )
          ->join('alp_ordenes_estatus', 'alp_ordenes_history.id_status', '=', 'alp_ordenes_estatus.id')
          ->join('users', 'alp_ordenes_history.id_user', '=', 'users.id')
          ->where('alp_ordenes_history.id_orden', $id)
          ->get();


          $cliente =  User::select('users.*','roles.name as name_role','alp_clientes.estado_masterfile as estado_masterfile','alp_clientes.estado_registro as estado_registro','alp_clientes.telefono_cliente as telefono_cliente','alp_clientes.cod_oracle_cliente as cod_oracle_cliente',
            'alp_clientes.cod_alpinista as cod_alpinista',
            'alp_clientes.origen as origen',
            'alp_clientes.tomapedidos_termino as tomapedidos_termino',
            'alp_clientes.tomapedidos_marketing as tomapedidos_marketing',
            'alp_clientes.marketing_cliente as marketing_cliente',
            'alp_clientes.habeas_cliente as habeas_cliente',
            'alp_clientes.doc_cliente as doc_cliente')
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


          $cupones=AlpOrdenesDescuento::where('id_orden', $orden->id)->get();

          $formaenvio=AlpFormasenvio::where('id', $orden->id_forma_envio)->first();

          $envio=AlpEnvios::where('id_orden', $orden->id)->first();

          if (isset($envio->id)) {


            $history_envio = AlpEnviosHistory::select('alp_envios_history.*', 'alp_envios_status.estatus_envio_nombre as estatus_envio_nombre', 'users.first_name as first_name', 'users.last_name as last_name' )
          ->join('alp_envios_status', 'alp_envios_history.estatus_envio', '=', 'alp_envios_status.id')
          ->join('users', 'alp_envios_history.id_user', '=', 'users.id')
          ->where('alp_envios_history.id_envio', $envio->id)
          ->orderBy('alp_envios_history.id', 'desc')
          ->get();
            # code...
          }else{

            $history_envio = AlpEnviosHistory::select('alp_envios_history.*', 'alp_envios_status.estatus_envio_nombre as estatus_envio_nombre', 'users.first_name as first_name', 'users.last_name as last_name' )
          ->join('alp_envios_status', 'alp_envios_history.estatus_envio', '=', 'alp_envios_status.id')
          ->join('users', 'alp_envios_history.id_user', '=', 'users.id')
          ->where('alp_envios_history.id_envio', 99999)
          ->orderBy('alp_envios_history.id', 'desc')
          ->get();

          }

         // dd($history_envio);


        return view('admin.ordenes.detalle', compact('detalles', 'orden', 'history', 'pago', 'pagos', 'cliente', 'direccion', 'cupones', 'formaenvio', 'envio', 'pago_aprobado', 'history_envio', 'user'  ));

    }

    public function storeconfirm(Request $request)
    {


          if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpOrdenesController/storeconfirm ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpOrdenesController/storeconfirm');

        }

        $user_id = Sentinel::getUser()->id;

        $input = $request->all();

        $orden=AlpOrdenes::where('id', $input['confirm_id'])->first();


        //var_dump($input);

        $data_history = array(
            'id_orden' => $input['confirm_id'], 
            'id_status' => $input['id_status'], 
            'notas' => $input['notas'], 
            'id_user' => $user_id 
        );

        $data_update_orden = array(
            'estatus' =>$input['id_status']
        );

        if ($input['id_status']=='4') {

          $punto=AlpPuntos::where('id_orden',$input['confirm_id'])->first();

          if (isset($punto->id)) {

             $data_estatus_puntos = array('estado_registro' => $input['id_status'] );

            $punto->update($data_estatus_puntos);

            $punto->delete();
          
          }

        $detalles=AlpDetalles::where('id_orden', $input['confirm_id'])->get();

          foreach ($detalles as $detalle) {

              $data_inventario = array(
                'id_producto' => $detalle->id_producto, 
                'cantidad' =>$detalle->cantidad, 
                'operacion' =>'1', 
                'id_user' =>$user_id 
              );

              AlpInventario::create($data_inventario);
            
          }

          $descuentos=AlpOrdenesDescuento::where('id_orden',$input['confirm_id'])->get();

          foreach ($descuentos as $desc) {
            
            $d=AlpOrdenesDescuento::where('id', $desc->id)->first();

            $d->delete();

          }


          if ($orden->id_forma_pago=='3') {

              $ss=AlpSaldo::where('id_cliente', $orden->id_cliente)->first();
              
              $data_saldo = array(
                'id_cliente' => $orden->id_cliente, 
                'saldo' => $orden->monto_total, 
                'operacion' => '1', 
                'fecha_vencimiento' => $ss->fecha_vencimiento, 
                'id_user' => $orden->id_cliente
              );

              AlpSaldo::create($data_saldo);

          }


          $cmercadopago=$this->cancelarMercadopago($orden->id);

             if ($orden->id_almacen==1) {

                //$this->sendcompramas($orden->id, 'rejected');

                $result=$this->sendcompramascancelar($orden->id);




              }else{

                $result='';
              }//enif si es almacen 1


               $configuracion = AlpConfiguracion::where('id','1')->first();

           $data_history_json = array(
                'id_orden' => $input['confirm_id'], 
                'id_status' => $input['id_status'], 
                'notas' => $input['notas'], 
                'json' => $result, 
                'id_user' => $user_id 
            );

            $history=AlpOrdenesHistory::create($data_history_json);

             $orden=AlpOrdenes::find($input['confirm_id']);



        }else{

           $history=AlpOrdenesHistory::create($data_history);
        }//endif


         
       

        $orden=AlpOrdenes::find($input['confirm_id']);

        $orden->update($data_update_orden);

        $orden = AlpOrdenes::select('alp_ordenes.*', 'users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
          ->where('alp_ordenes.id', $input['confirm_id'])
          ->first();

        if ($orden->id) {



          $view= View::make('admin.ordenes.storeconfirm', compact('orden'));

          $data=$view->render();

          $res = array('data' => $data);

          return $data;

        } else {

            return 0;
        }     


    }


       public function recibir(Request $request)
    {

       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpOrdenesController/recibir ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpOrdenesController/recibir');


        }



        $user_id = Sentinel::getUser()->id;

        $input = $request->all();

        $configuracion = AlpConfiguracion::where('id','1')->first();

    //    dd($configuracion->correo_cedi);

        //var_dump($input);

        $data_history = array(
            'id_orden' => $input['id'], 
            'id_status' => '1', 
            'notas' => $input['notas'], 
            'id_user' => $user_id 
        );

        $data_update_orden = array(
            'estatus' =>'1'
        );

         
        $history=AlpOrdenesHistory::create($data_history);

        $orden=AlpOrdenes::find($input['id']);

        $orden->update($data_update_orden);

        $orden = AlpOrdenes::select(
          'alp_ordenes.*', 'users.first_name as first_name', 
          'users.last_name as last_name', 
          'users.email as email', 
          'alp_clientes.doc_cliente as doc_cliente', 
          'alp_clientes.cod_oracle_cliente as cod_oracle_cliente', 
          'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 
          'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 
          'alp_ordenes_estatus.estatus_nombre as estatus_nombre',
          'alp_envios.fecha_envio as fecha_envio',
          'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre'
        )
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_clientes', 'alp_ordenes.id_cliente', '=', 'alp_clientes.id_user_client')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_envios', 'alp_ordenes.id', '=', 'alp_envios.id_orden')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
          ->where('alp_ordenes.id', $input['id'])
          ->first();

        if ($orden->id) {

          $texto="La orden ".$orden->id.", el pago ha sido notificado y espera por el proceso de aprobación!";

          Mail::to($configuracion->correo_cedi)->send(new \App\Mail\NotificacionOrden($orden->id, $texto));

          $view= View::make('admin.ordenes.recibir', compact('orden'));

          $data=$view->render();

          $res = array('data' => $data);

          return $data;

        } else {

            return 'error orden';
        }       

    }


     public function aprobar(Request $request)
    {

          if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpOrdenesController/aprobar');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpOrdenesController/aprobar');


        }



        $user_id = Sentinel::getUser()->id;

        $input = $request->all();

        $configuracion = AlpConfiguracion::where('id','1')->first();


        $data_history = array(
            'id_orden' => $input['id'], 
            'id_status' => '5', 
            'notas' => $input['notas'], 
            'id_user' => $user_id 
        );

        $data_update_orden = array(
            'ordencompra' =>$input['codigo'], 
            'estatus' =>'5'
        );

         
        $history=AlpOrdenesHistory::create($data_history);

        $orden=AlpOrdenes::find($input['id']);

        $orden->update($data_update_orden);

        $orden = AlpOrdenes::select(
          'alp_ordenes.*', 'users.first_name as first_name', 
          'users.last_name as last_name', 
          'users.email as email', 
          'alp_clientes.doc_cliente as doc_cliente', 
          'alp_clientes.cod_oracle_cliente as cod_oracle_cliente', 
          'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 
          'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 
          'alp_ordenes_estatus.estatus_nombre as estatus_nombre',
          'alp_envios.fecha_envio as fecha_envio',
          'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre'
        )
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_clientes', 'alp_ordenes.id_cliente', '=', 'alp_clientes.id_user_client')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_envios', 'alp_ordenes.id', '=', 'alp_envios.id_orden')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
          ->where('alp_ordenes.id', $input['id'])
          ->first();

          ///dd($orden);

        $detalles =  DB::table('alp_ordenes_detalle')->select(
          'alp_ordenes_detalle.*',
          'alp_productos.presentacion_producto as presentacion_producto',
          'alp_productos.referencia_producto as referencia_producto',
          'alp_productos.nombre_producto as nombre_producto',
          'alp_productos.referencia_producto_sap as referencia_producto_sap' ,
          'alp_productos.imagen_producto as imagen_producto' ,
          'alp_productos.slug as slug'
        )
          ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
          ->where('alp_ordenes_detalle.id_orden', $input['id'])->get();

        $envio=AlpEnvios::where('id_orden', $input['id'])->first();

        if ($orden->id) {

          $direccion=AlpDirecciones::where('id', $orden->id_address)->first();

        $feriados=AlpFeriados::feriados();

        $ciudad_forma=AlpFormaCiudad::where('id_forma', $orden->id_forma_envio)->where('id_ciudad', $direccion->city_id)->first();


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

        $fecha_entrega=$date->addDays($ciudad_forma->dias)->format('d-m-Y');


        $data_envio = array('fecha_envio' =>  $fecha_entrega);

        $envio->update($data_envio);

          $texto="La orden ".$orden->id." Ha sido aprobada y espera para ser facturada!";
          
         // Mail::to($orden->email)->send(new \App\Mail\CompraAprobada($orden, $detalles, $fecha_entrega));


          if ($orden->id_forma_envio!=1) {

              $formaenvio=AlpFormasenvio::where('id', $compra->id_forma_envio)->first();

              Mail::to($formaenvio->email)->send(new \App\Mail\CompraSac($compra, $detalles, $fecha_entrega));
                
            }

          Mail::to($configuracion->correo_cedi)->send(new \App\Mail\CompraSac($orden, $detalles, $fecha_entrega));




         // Mail::to($user_cliente->email)->send(new \App\Mail\NotificacionOrden($orden->id, $texto));

          $view= View::make('admin.ordenes.aprobar', compact('orden'));

          $data=$view->render();

          $res = array('data' => $data);

          return $data;

        } else {

            return 'error orden';
        }       

    }

    public function facturar(Request $request)
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpOrdenesController/facturar');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpOrdenesController/facturar');


        }



        $user_id = Sentinel::getUser()->id;

        $input = $request->all();

         $configuracion = AlpConfiguracion::where('id','1')->first();

        //var_dump($input);

        $data_history = array(
            'id_orden' => $input['id'], 
            'id_status' => '6', 
            'notas' => $input['notas'], 
            'id_user' => $user_id 
        );

        $data_update_orden = array(
            'factura' =>$input['codigo'], 
            'estatus' =>'6'
        );

         
        $history=AlpOrdenesHistory::create($data_history);

        $orden=AlpOrdenes::find($input['id']);

        $orden->update($data_update_orden);

        $orden = AlpOrdenes::select('alp_ordenes.*', 'users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
          ->where('alp_ordenes.id', $input['id'])
          ->first();

        if ($orden->id) {

          $texto="La orden ".$orden->id." Ha sido Facturada  y espera para ser Enviada!";

         //  $user_cliente=Users::where('id', $orden->id_cliente)->first();
          
          //Mail::to($user_cliente->email)->send(new \App\Mail\NotificacionOrden($orden->id, $texto));

          Mail::to($configuracion->correo_logistica)->send(new \App\Mail\NotificacionOrden($orden->id, $texto));


          $view= View::make('admin.ordenes.facturar', compact('orden'));

          $data=$view->render();

          $res = array('data' => $data);

          return $data;

        } else {

            return 0;
        }       

    }


    public function enviar(Request $request)
    {

       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpOrdenesController/enviar');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpOrdenesController/enviar');


        }



        $user_id = Sentinel::getUser()->id;

         $configuracion = AlpConfiguracion::where('id','1')->first();

        $input = $request->all();

        //var_dump($input);

        $data_history = array(
            'id_orden' => $input['id'], 
            'id_status' => '7', 
            'notas' => $input['notas'], 
            'id_user' => $user_id 
        );

        $data_update_orden = array(
            'tracking' =>$input['codigo'], 
            'estatus' =>'7'
        );

         
        $history=AlpOrdenesHistory::create($data_history);

        $orden=AlpOrdenes::find($input['id']);

        $orden->update($data_update_orden);

        $orden = AlpOrdenes::select('alp_ordenes.*', 'users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
          ->where('alp_ordenes.id', $input['id'])
          ->first();

        if ($orden->id) {

          $texto="La orden ".$orden->id." Ha sido Enviada  !";

          $user_cliente=User::where('id', $orden->id_cliente)->first();
          
          //Mail::to($user_cliente->email)->send(new \App\Mail\NotificacionOrden($orden->id, $texto));

          Mail::to($configuracion->correo_shopmanager)->send(new \App\Mail\NotificacionOrden($orden->id, $texto));

          $view= View::make('admin.ordenes.enviar', compact('orden'));

          $data=$view->render();

          $res = array('data' => $data);

          return $data;

        } else {

            return 0;
        }       

    }













    public function nomina()
    {
        // Grab all the groups


      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpOrdenesController/nomina ');

        }else{

          activity()
          ->log('AlpOrdenesController/nomina');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['ordenes.nomina'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


      
        $ordenes = AlpOrdenes::all();

        $estatus_ordenes = AlpEstatusOrdenes::all();

         $ordenes = AlpOrdenes::select('alp_ordenes.*', 'users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre', 'alp_ordenes_pagos.json as json')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->leftJoin('alp_ordenes_pagos', 'alp_ordenes.id', '=', 'alp_ordenes_pagos.id_orden')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
          ->where('alp_ordenes.estatus', '1')
          ->groupBy('alp_ordenes.id')
          ->limit(2000)
          ->get();
       
        // Show the page
        return view('admin.ordenes.nomina', compact('ordenes', 'estatus_ordenes'));

    }



 public function datanomina()
    {
       

        

      $ordenes = AlpOrdenes::select('alp_ordenes.*', 'alp_clientes.telefono_cliente as telefono_cliente','users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre', 'alp_ordenes_pagos.json as json')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->leftJoin('alp_ordenes_pagos', 'alp_ordenes.id', '=', 'alp_ordenes_pagos.id_orden')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
          ->where('alp_ordenes.estatus', '1')
          ->where('alp_ordenes.id_forma_pago', '=', '3')
          ->groupBy('alp_ordenes.id')
          ->limit(2000)
          ->get();

          $almacenes=AlpAlmacenes::pluck('nombre_almacen', 'id');
          $direcciones=AlpDirecciones::pluck('city_id', 'id');
          $ciudades=City::pluck('city_name', 'id');



            $data = array();


          foreach($ordenes as $row){

            $pago="<div style='display: inline-block;' class='pago_".$row->id."'>  

                                            <button data-id='".$row->id."' class='btn btn-xs btn-success pago' > ".$row->estatus_pago_nombre." </button></div>";

             $estatus="<span class='badge badge-default' >".$row->estatus_nombre."</span>";

                 $actions = " 
                  <a class='btn btn-primary btn-xs' href='".route('admin.ordenes.detalle', $row->id)."'>
                                                ver detalles
                                            </a> ";


                                            if ($row->ordencompra=='') {

                                              $actions=$actions."<div style='display: inline-block;' class='aprobar_".$row->id."'>
                                            <button data-id='".$row->id."'  data-codigo='".$row->ordencompra."'  data-estatus='".$row->estatus."' class='btn btn-xs btn-info aprobar' > Aprobar </button></div>";
 
                                              

                                            }else{
                                              $actions=$actions."<div style='display: inline-block;' class='aprobar_".$row->id."'>
                                            <button data-id='".$row->id."'  data-codigo='".$row->ordencompra."'  data-estatus='".$row->estatus."' class='btn btn-xs btn-success aprobar' > Aprobado </button></div>";
                                            }


                                             $envio=AlpEnvios::where('id_orden', $row->id)->first();
              if (isset($envio->id)) {
                # code...

                $row->monto_total=$row->monto_total+$envio->costo;
              } 


               $descuento=AlpOrdenesDescuento::where('id_orden', $row->id)->first();

              if (isset($descuento->id)) {

                $cupon=$descuento->codigo_cupon;
                # code...
              }else{

                $cupon='N/A';

              }


              $nombre_almacen='';

              $nombre_ciudad='';


              if (isset($almacenes[$row->id_almacen])) {
                
                $nombre_almacen=$almacenes[$row->id_almacen];
              }

              if (isset($direcciones[$row->id_address])) {

                if (isset($ciudades[$direcciones[$row->id_address]])) {

                  $nombre_ciudad=$ciudades[$direcciones[$row->id_address]];

                }
               
              }


              $mensaje=AlpAnchetaMensaje::where('id_orden', $row->id)->first();

              if (isset($mensaje->id)) {

                $actions = $actions." 
                  <a target='_blank' class='btn btn-info  btn-xs' href='".secure_url('admin/ordenes/'.$row->id.'/pdf'). "'>
                      Ver Pdf
                  </a>";
                
              }





               $data[]= array(
                 $row->id, 
                 $row->referencia, 
                 $row->first_name.' '.$row->last_name, 
                 $row->telefono_cliente, 
                 $row->nombre_forma_envios, 
                 $row->nombre_forma_pago, 
                 $nombre_almacen, 
                 $nombre_ciudad, 
                 $origen,
                 number_format($row->monto_total,2), 
                 //$row->ordencompra, 
                 $cupon, 
                 $row->factura, 
                 //$row->tracking, 
                 date("d/m/Y H:i:s", strtotime($row->created_at)), 
                 $actions
              );



          }


          return json_encode( array('data' => $data ));

    }




  public function almacen()
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpOrdenesController/almacen ');

        }else{

          activity()
          ->log('AlpOrdenesController/almacen');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['ordenes.almacen'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        // Grab all the groups
        // 
        
      
        $ordenes = AlpOrdenes::all();

        $estatus_ordenes = AlpEstatusOrdenes::all();

         $ordenes = AlpOrdenes::select('alp_ordenes.*', 'users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre', 'alp_ordenes_pagos.json as json')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->leftJoin('alp_ordenes_pagos', 'alp_ordenes.id', '=', 'alp_ordenes_pagos.id_orden')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
           ->groupBy('alp_ordenes.id')
           ->limit(2000)
           ->where('alp_ordenes.id_almacen', $user->almacen)
          ->get();

          $almacen=AlpAlmacenes::where('id', $user->almacen)->first();

        return view('admin.ordenes.almacen', compact('ordenes', 'estatus_ordenes', 'almacen'));

    }




     public function dataalmacen()
    {

      $user = Sentinel::getUser();

      if (isset($user->id)) {
        # code...
    
          $ordenes = AlpOrdenes::select('alp_ordenes.*', 'alp_clientes.telefono_cliente as telefono_cliente','users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre', 'alp_ordenes_pagos.json as json')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
           ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->leftJoin('alp_ordenes_pagos', 'alp_ordenes.id', '=', 'alp_ordenes_pagos.id_orden')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
           ->groupBy('alp_ordenes.id')
           ->limit(2000)
           ->where('alp_ordenes.id_almacen', $user->almacen)
          ->get();

          $almacenes=AlpAlmacenes::pluck('nombre_almacen', 'id');
          $direcciones=AlpDirecciones::pluck('city_id', 'id');
          $ciudades=City::pluck('city_name', 'id');



            $data = array();

          foreach($ordenes as $row){

            $pago="<div style='display: inline-block;' class='pago_".$row->id."'>  

                                            <button data-id='".$row->id."' class='btn btn-xs btn-success pago' > ".$row->estatus_pago_nombre." </button></div>";

             $estatus="<span class='badge badge-default' >".$row->estatus_nombre."</span>";



                 $actions = " 
                  <a class='btn btn-primary btn-xs' href='".route('admin.ordenes.detallealmacen', $row->id)."'>
                                                ver detalles
                                            </a>  <div style='display: inline-block;' class='estatus_".$row->id."'>
                                           ";


              $envio=AlpEnvios::where('id_orden', $row->id)->first();
              if (isset($envio->id)) {
                # code...

                $row->monto_total=$row->monto_total+$envio->costo;
              }    

              $descuento=AlpOrdenesDescuento::where('id_orden', $row->id)->first();

              if (isset($descuento->id)) {

                $cupon=$descuento->codigo_cupon;
                # code...
              }else{

                $cupon='N/A';

              }

              $nombre_almacen='';

              $nombre_ciudad='';

             // dd($cities); 

              if (isset($almacenes[$row->id_almacen])) {
                
                $nombre_almacen=$almacenes[$row->id_almacen];
              }

              if (isset($direcciones[$row->id_address])) {

                if (isset($ciudades[$direcciones[$row->id_address]])) {

                  //dd($ciudades[$direcciones[$row->id_address]]);
                  
                  $nombre_ciudad=$ciudades[$direcciones[$row->id_address]];

                }
               
              }


               if ($row->origen=='1') {

                $origen='Tomapedidos';
                # code...
              }else{

                $origen='Web';

              }


              $mensaje=AlpAnchetaMensaje::where('id_orden', $row->id)->first();

              if (isset($mensaje->id)) {

                $actions = $actions." 
                  <a target='_blank' class='btn btn-info  btn-xs' href='".secure_url('admin/ordenes/'.$row->id.'/pdf'). "'>
                      Ver Pdf
                  </a>";
                
              }





               $data[]= array(
                 $row->id, 
                 $row->referencia, 
                 $row->first_name.' '.$row->last_name, 
                 $row->telefono_cliente, 
                 $row->nombre_forma_envios, 
                 $row->nombre_forma_pago, 
                 $nombre_almacen, 
                 $nombre_ciudad, 
                 $origen,
                 number_format($row->monto_total,2), 
                 number_format($row->monto_total_base,2), 
                 $cupon,
                 $actions
              );

          }

          return json_encode( array('data' => $data ));


      }else{

        return json_encode( array('data' => false ));
      }


    }


public function detallealmacen($id)
    {

      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('AlpOrdenesController/detalle ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpOrdenesController/detalle');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['ordenes.detallealmacen'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


       
    $orden = AlpOrdenes::find($id);

      //dd($orden);

    $detalles = AlpDetalles::select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.imagen_producto as imagen_producto','alp_productos.referencia_producto as referencia_producto')
          ->join('alp_productos', 'alp_ordenes_detalle.id_producto', '=', 'alp_productos.id')
          ->where('alp_ordenes_detalle.id_orden', $id)
          ->get();

    $pago = AlpPagos::select('alp_ordenes_pagos.*','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago')
          ->join('alp_formas_pagos', 'alp_ordenes_pagos.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->where('alp_ordenes_pagos.id_orden', $id)
          ->first();


    $pago_aprobado = AlpPagos::select('alp_ordenes_pagos.*','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago')
          ->join('alp_formas_pagos', 'alp_ordenes_pagos.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->where('alp_ordenes_pagos.id_orden', $id)
          ->orderBy('alp_ordenes_pagos.id', 'desc')
          ->first();

        $pagos = AlpPagos::select('alp_ordenes_pagos.*','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago')
          ->join('alp_formas_pagos', 'alp_ordenes_pagos.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->where('alp_ordenes_pagos.id_orden', $id)
          ->get();

          //dd($pago);

    $history = AlpOrdenesHistory::select('alp_ordenes_history.*', 'users.first_name as first_name', 'users.last_name as last_name', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre' )
          ->join('alp_ordenes_estatus', 'alp_ordenes_history.id_status', '=', 'alp_ordenes_estatus.id')
          ->join('users', 'alp_ordenes_history.id_user', '=', 'users.id')
          ->where('alp_ordenes_history.id_orden', $id)
          ->get();


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


          $cupones=AlpOrdenesDescuento::where('id_orden', $orden->id)->get();

          $formaenvio=AlpFormasenvio::where('id', $orden->id_forma_envio)->first();

          $envio=AlpEnvios::where('id_orden', $orden->id)->first();


          if (isset($envio->id)) {
            $history_envio = AlpEnviosHistory::select('alp_envios_history.*', 'alp_envios_status.estatus_envio_nombre as estatus_envio_nombre', 'users.first_name as first_name', 'users.last_name as last_name' )
            ->join('alp_envios_status', 'alp_envios_history.estatus_envio', '=', 'alp_envios_status.id')
            ->join('users', 'alp_envios_history.id_user', '=', 'users.id')
            ->where('alp_envios_history.id_envio', $envio->id)
            ->orderBy('alp_envios_history.id', 'desc')
            ->get();

          }else{


            $history_envio = AlpEnviosHistory::select('alp_envios_history.*', 'alp_envios_status.estatus_envio_nombre as estatus_envio_nombre', 'users.first_name as first_name', 'users.last_name as last_name' )
            ->join('alp_envios_status', 'alp_envios_history.estatus_envio', '=', 'alp_envios_status.id')
            ->join('users', 'alp_envios_history.id_user', '=', 'users.id')
            ->where('alp_envios_history.id_envio', -1)
            ->orderBy('alp_envios_history.id', 'desc')
            ->get();
          }


           

         // dd($history_envio);


        return view('admin.ordenes.detallealmacen', compact('detalles', 'orden', 'history', 'pago', 'pagos', 'cliente', 'direccion', 'cupones', 'formaenvio', 'envio', 'pago_aprobado', 'history_envio'));

    }



  private function inventarioAlmacen()
    {
       
       $id_almacen=1;

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
                     $inv[$row->id_producto]= $inv[$row->id_producto]-$row->cantidad_total;
                }else{
                     $inv[$row->id_producto]=0;
                }

                $inv[$row->id_producto]= $inv[$row->id_producto]-$row->cantidad_total;
                
            }

            return $inv;
      
    }



    private function sendcompramas($id_orden, $estatus){


      $orden=AlpOrdenes::where('id', $id_orden)->first();

       $dataupdate = array(
          'ordenId' => $orden->referencia, 
          'status' => $estatus, 
        );


       $dataraw=json_encode($dataupdate);

         Log::useDailyFiles(storage_path().'/logs/compramas.log');
        
        //Log::info('compramas dataraw '.json_encode($dataupdate));

         $configuracion = AlpConfiguracion::where('id','1')->first();

         $urls=$configuracion->compramas_url.'/updatedOrderReserved/'.$configuracion->compramas_hash;

             // activity()->withProperties($urls)->log('compramas urls ');

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


                   $notas=$notas.'Codigo: OC.';


               Log::info('compramas result '.$result);

               Log::info('compramas res '.json_encode($res));

                  if (isset($res->codigo)) {
                  
                  if ($res->codigo=='200') {



                      if ($estatus=='approved') {
                      

                       $dtt = array(
                        'json' => $result,
                        'estado_compramas' => $res->codigo,
                        'envio_compramas' => '2'
                      );


                    }


                    if ($estatus=='rejected') {
                      

                       $dtt = array(
                        'json' => $result,
                        'estado_compramas' => $res->codigo,
                        'envio_compramas' => '3'
                        
                      );

                       
                    }


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
                        'estado_compramas' => $res->codigo
                      );

                      $orden->update($dtt);


                         $data_history = array(
                          'id_orden' => $orden->id, 
                         'id_status' => '9', 
                          'notas' => $notas,
                          'json' => json_encode($result), 
                         'id_user' => 1
                      );

                        $history=AlpOrdenesHistory::create($data_history);


                    $texto=''.$res->mensaje.' Codigo Respuesta '.$res->codigo;

                    Mail::to($configuracion->correo_sac)->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

                     Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

                  }


                }else{

                    $data_history = array(
                        'id_orden' => $orden->id, 
                       'id_status' => '9', 
                        'notas' => $notas,
                        'json' => json_encode($result), 
                       'id_user' => 1
                    );

                    $history=AlpOrdenesHistory::create($data_history);


                      $texto=''.$res->mensaje.' Codigo Respuesta '.$res->codigo;

                    Mail::to($configuracion->correo_sac)->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

                     Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));


                }



    }

 public function reenviarcompramas($id_orden){


      $orden=AlpOrdenes::where('id', $id_orden)->first();

       $dataupdate = array(
          'ordenId' => $orden->referencia, 
          'status' => 'approved', 
        );


       $dataraw=json_encode($dataupdate);

         Log::useDailyFiles(storage_path().'/logs/compramas.log');
        
        //Log::info('compramas dataraw '.json_encode($dataupdate));

         $configuracion = AlpConfiguracion::where('id','1')->first();

         $urls=$configuracion->compramas_url.'/updatedOrderReserved/'.$configuracion->compramas_hash;

             // activity()->withProperties($urls)->log('compramas urls ');

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

                  $notas='Reenvio aprobacion de orden en compramas.';

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


                   $notas=$notas.'Codigo: OC.';


               Log::info('compramas result '.$result);

               Log::info('compramas res '.json_encode($res));

                  if (isset($res->codigo)) {
                  
                  if ($res->codigo=='200') {

                       $dtt = array(
                        'json' => $result,
                        'estado_compramas' => $res->codigo,
                        'envio_compramas' => '2'
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
                        'estado_compramas' => $res->codigo
                      );

                      $orden->update($dtt);


                         $data_history = array(
                          'id_orden' => $orden->id, 
                         'id_status' => '9', 
                          'notas' => $notas,
                          'json' => json_encode($result), 
                         'id_user' => 1
                      );

                        $history=AlpOrdenesHistory::create($data_history);


                    $texto=''.$res->mensaje.' Codigo Respuesta '.$res->codigo;

                    Mail::to($configuracion->correo_sac)->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

                     Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

                  }


                }else{

                  $notas='No hubo Respuesta de compramas';

                    $data_history = array(
                        'id_orden' => $orden->id, 
                       'id_status' => '9', 
                        'notas' => $notas,
                        'json' => json_encode($result), 
                       'id_user' => 1
                    );

                    $history=AlpOrdenesHistory::create($data_history);


                      $texto='No Hubo respuesta de compramas';

                    Mail::to($configuracion->correo_sac)->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

                     Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));


                }

                return redirect('admin/ordenes/compramas/list')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');


    }






      public function dataupdateordenes()
    {

      $ordenes=AlpOrdenes::whereNotNull('json')->get();

      foreach ($ordenes as $o) {

        $ord=AlpOrdenes::where('id', $o->id)->first();

        $json=json_decode($o->json);

        $dtt = array('estado_compramas' => $json->codigo);

        $ord->update($dtt);
        

      }

      

    }

















  public function reenviaryaprobarcompramas($id_orden)
    {

      $configuracion=AlpConfiguracion::first();

     // dd($configuracion);
      
       $orden=AlpOrdenes::where('id', $id_orden)->first();


       $canc=$this->sendcompramascancelar($id_orden);

       //dd($canc);



       $dataupdateorden = array('referencia' => $orden->referencia.'C');

       $orden->update($dataupdateorden);

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

             // dd($o);


      $dataraw=json_encode($o);

      $urls=$configuracion->compramas_url.'/registerOrderReserved/'.$configuracion->compramas_hash;

      

       Log::info('compramas urls '.$urls);


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

     //dd($result);

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

       if (isset($res->causa[0])) {
         $notas=$notas.$res->causa[0].' ';
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

              $this->reenviarcompramas($orden->id);


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



















 public function cancelarcompramas($id_orden){


      $orden=AlpOrdenes::where('id', $id_orden)->first();

       $dataupdate = array(
          'ordenId' => $orden->referencia, 
          'status' => 'rejected', 
        );


       $dataraw=json_encode($dataupdate);

         Log::useDailyFiles(storage_path().'/logs/compramas.log');
        
        //Log::info('compramas dataraw '.json_encode($dataupdate));

         $configuracion = AlpConfiguracion::where('id','1')->first();

         $urls=$configuracion->compramas_url.'/updatedOrderReserved/'.$configuracion->compramas_hash;

             // activity()->withProperties($urls)->log('compramas urls ');

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

                  $notas='Reenvio aprobacion de orden en compramas.';

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


                   $notas=$notas.'Codigo: OC.';


               Log::info('compramas result '.$result);

               Log::info('compramas res '.json_encode($res));

                  if (isset($res->codigo)) {
                  
                  if ($res->codigo=='200') {

                       $dtt = array(
                        'json' => $result,
                        'estado_compramas' => $res->codigo,
                        'envio_compramas' => '2'
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
                        'estado_compramas' => $res->codigo
                      );

                      $orden->update($dtt);


                         $data_history = array(
                          'id_orden' => $orden->id, 
                         'id_status' => '9', 
                          'notas' => $notas,
                          'json' => json_encode($result), 
                         'id_user' => 1
                      );

                        $history=AlpOrdenesHistory::create($data_history);


                    $texto=''.$res->mensaje.' Codigo Respuesta '.$res->codigo;

                    Mail::to($configuracion->correo_sac)->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

                     Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

                  }


                }else{

                  $notas='No hubo Respuesta de compramas';

                    $data_history = array(
                        'id_orden' => $orden->id, 
                       'id_status' => '9', 
                        'notas' => $notas,
                        'json' => json_encode($result), 
                       'id_user' => 1
                    );

                    $history=AlpOrdenesHistory::create($data_history);


                      $texto='No Hubo respuesta de compramas';

                    Mail::to($configuracion->correo_sac)->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

                     Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));


                }

                return redirect('admin/ordenes/compramas/list')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');


    }



public function sendcompramascancelar($id_orden){


      $orden=AlpOrdenes::where('id', $id_orden)->first();

       $dataupdate = array(
          'ordenId' => $orden->referencia, 
          'status' => 'rejected', 
        );


       $dataraw=json_encode($dataupdate);

         Log::useDailyFiles(storage_path().'/logs/compramas.log');
        
        //Log::info('compramas dataraw '.json_encode($dataupdate));

         $configuracion = AlpConfiguracion::where('id','1')->first();

         $urls=$configuracion->compramas_url.'/updatedOrderReserved/'.$configuracion->compramas_hash;

             // activity()->withProperties($urls)->log('compramas urls ');

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

                  $notas='Cancelacion de Orden en Compramas .';

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


                   $notas=$notas.'Codigo: OC.';


               Log::info('compramas result '.$result);

               Log::info('compramas res '.json_encode($res));

                  if (isset($res->codigo)) {
                  
                  if ($res->codigo=='200') {

                       $dtt = array(
                        'json' => $result,
                        'estado_compramas' => $res->codigo,
                        'envio_compramas' => '2'
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
                        'estado_compramas' => $res->codigo
                      );

                      $orden->update($dtt);


                         $data_history = array(
                          'id_orden' => $orden->id, 
                         'id_status' => '9', 
                          'notas' => $notas,
                          'json' => json_encode($result), 
                         'id_user' => 1
                      );

                        $history=AlpOrdenesHistory::create($data_history);


                    $texto=''.$res->mensaje.' Codigo Respuesta '.$res->codigo;

                    Mail::to($configuracion->correo_sac)->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

                     Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

                  }


                }else{

                  $notas='No hubo Respuesta de compramas';

                    $data_history = array(
                        'id_orden' => $orden->id, 
                       'id_status' => '9', 
                        'notas' => $notas,
                        'json' => json_encode($result), 
                       'id_user' => 1
                    );

                    $history=AlpOrdenesHistory::create($data_history);


                      $texto='No Hubo respuesta de compramas';

                    Mail::to($configuracion->correo_sac)->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));

                     Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\NotificacionOrdenEnvio($orden, $texto));


                }

                return json_encode($result);


    }




    public function cancelarMercadopago($id_orden){

      $user_id = Sentinel::getUser()->id;


      $orden=AlpOrdenes::where('id', $id_orden)->first();

     // dd($orden);

      $configuracion = AlpConfiguracion::where('id', '1')->first();

       if ($configuracion->mercadopago_sand=='1') {
          
          MP::sandbox_mode(TRUE);

        }

        if ($configuracion->mercadopago_sand=='2') {
          
          MP::sandbox_mode(FALSE);

        }

        MP::setCredenciales($configuracion->id_mercadopago, $configuracion->key_mercadopago);

         $preference = MP::get("/v1/payments/search?external_reference=".$orden->referencia);

        // dd($preference);

          foreach ($preference['response']['results'] as $r) {

              $idpago=$r['id'];

             
               $preference_data_cancelar = '{"status": "cancelled"}';

               //dd($preference_data_cancelar);

              $pre = MP::put("/v1/payments/".$idpago."", $preference_data_cancelar);

              //dd($pre);
              //
              
              $data_cancelar = array(
                'id_orden' => $orden->id, 
                'id_forma_pago' => $orden->id_forma_pago, 
                'id_estatus_pago' => 4, 
                'monto_pago' => $orden->monto_total, 
                'json' => json_encode($pre), 
                'id_user' => $user_id
              );

              AlpPagos::create($data_cancelar);


               $data_history_json = array(
                'id_orden' => $orden->id, 
                'id_status' =>'4', 
                'notas' => 'Cancelacion de pago en Mercadopago', 
                'json' => json_encode($pre), 
                'id_user' => $user_id 
            );

            $history=AlpOrdenesHistory::create($data_history_json);


                 

            }


    }



    public function pdf($id)
    {
        // Grab all the groups

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpOrdenesController/pdf ');

        }else{

          activity()
          ->log('AlpOrdenesController/pdf');

        }

         if (!Sentinel::getUser()->hasAnyAccess(['ordenes.index'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

         $mensaje=AlpAnchetaMensaje::where('id_orden', '=', $id)->first();


         if (isset($mensaje->id)) {
          



        $this->fpdf = new fpdf;
        $this->fpdf->AddPage();
        $this->fpdf->SetFont('Times');
        $this->fpdf->SetMargins(20, 25 , 0);
        $this->fpdf->cell(20,40,'', 0, 1);


       // dd($mensaje->mensaje_mensaje);

        $partes=explode(' ', utf8_decode($mensaje->mensaje_mensaje) );

        $i=0;

        $j=0;

        $cadena='';
              $this->fpdf->Cell(100, 8, '', 0,1);
              $this->fpdf->Cell(100, 8, '', 0,1);


        foreach ($partes as $key => $value) {

        //dd($value);
            
            if ($i<50) {

              $cadena=$cadena.$value.' ';

              $i=strlen($value)+1+$i;


              
            }else{

              //dd($i);

              $i=0;

              $j=$j+1;

              $this->fpdf->Cell(100, 8, $cadena, 0,1);

              $cadena=$value.' ';

              $i=strlen($value)+1+$i;

              
            }


        }



         $this->fpdf->Cell(100, 8,utf8_decode($cadena) , 0,1);

         $j=$j+1;

         while ($j <= 5) {
           $this->fpdf->Cell(100, 8, '', 0,1);
           $j=$j+1;
         }

         $this->fpdf->Cell(100, 3, '', 0,1);

         $this->fpdf->Cell(10, 11, '', 0,0);

         $this->fpdf->Cell(100, 11, utf8_decode($mensaje->mensaje_de), 0,1);

         $this->fpdf->Cell(20, 11, '', 0,0);

         $this->fpdf->Cell(100, 11, utf8_decode($mensaje->mensaje_para), 0,1);



       // $this->fpdf->MultiCell(90, 8, $mensaje->mensaje_mensaje, 1);     

        $this->fpdf->Cell(100, 8, '', 0,0);

        $ord=AlpOrdenes::where('id', $id)->first();

        $this->fpdf->Cell(80, 8, $ord->referencia, 0,1,'R');  
         
        $this->fpdf->Output('D', $ord->referencia.'.pdf');

       exit;


     }else{

       abort('404');

     }

      

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

                 // dd($detalles);

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

              dd($dataraw);

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


















}