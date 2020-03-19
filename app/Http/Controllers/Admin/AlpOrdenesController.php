<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Http\Requests\OrdenesRequest;
use App\Models\AlpOrdenes;
use App\Models\AlpOrdenesDescuento;
use App\Models\AlpEstatusOrdenes;
use App\Models\AlpOrdenesHistory;
use App\Models\AlpDetalles;
use App\Models\AlpPagos;
use App\Models\AlpPuntos;
use App\Models\AlpConfiguracion;
use App\Models\AlpEnvios;
use App\Models\AlpDirecciones;
use App\Models\AlpFeriados;
use App\Models\AlpFormaCiudad;
use App\Models\AlpFormasenvio;
use App\Models\AlpInventario;

use App\User;
use App\RoleUser;
use App\Http\Requests;
use Illuminate\Http\Request;
use Mail;
use Redirect;
use Response;
use Sentinel;
use View;
use DB;
use Intervention\Image\Facades\Image;
use DOMDocument;
use Carbon\Carbon;



class AlpOrdenesController extends JoshController
{
    /**
     * Show a list of all the groups.
     *
     * @return View
     */

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
          ->get();

        //  dd($ordenes);
       
        // Show the page
        return view('admin.ordenes.index', compact('ordenes', 'estatus_ordenes'));

    }


      public function data()
    {

      $permiso_cancelar = array('1','2','3' );
       

       $id_rol=0;

        if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

            $role=RoleUser::where('user_id', $user_id)->first();

            $id_rol=$role->role_id;
        }
      

       $ordenes = AlpOrdenes::select('alp_ordenes.*', 'alp_clientes.telefono_cliente as telefono_cliente','users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre', 'alp_ordenes_pagos.json as json')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->leftJoin('alp_ordenes_pagos', 'alp_ordenes.id', '=', 'alp_ordenes_pagos.id_orden')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
          ->groupBy('alp_ordenes.id')
          ->get();
        

            $data = array();

          foreach($ordenes as $row){


            $envio=AlpEnvios::where('id_orden', $row->id)->first();

            $pago="<div style='display: inline-block;' class='pago_".$row->id."'>  
            <button data-id='".$row->id."' class='btn btn-xs btn-success pago' > ".$row->estatus_pago_nombre." </button></div>";

             $estatus="<span class='badge badge-default' >".$row->estatus_nombre."</span>";



                 $actions = " 
                  <a class='btn btn-primary btn-xs' href='".route('admin.ordenes.detalle', $row->id)."'>
                      ver detalles
                  </a>

                   <div style='display: inline-block;' class='estatus_".$row->id."'>";

                if (in_array($id_rol, $permiso_cancelar)) {
                  
                  if ($row->estatus!=4) {
                  	
                  	$cancelado = " <button data-id='".$row->id."'  data-codigo='".$row->ordencompra."'  data-estatus='".$row->estatus."' class='btn btn-xs btn-danger confirmar' > Cancelar </button></div>";

                  }else{

                  	$cancelado = " ";
                  	
                  }

              }else{

                  $cancelado = " ";

              }

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

               $data[]= array(
                 $row->id, 
                 $row->referencia, 
                 $row->first_name.' '.$row->last_name, 
                 $row->telefono_cliente, 
                 $row->nombre_forma_envios, 
                 $row->nombre_forma_pago, 
                 number_format($row->monto_total,2), 
                 $row->ordencompra, 
                 $cupon, 
                 $row->factura, 
                 $row->tracking, 
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

        // Grab all the groups
      
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
          ->get();

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
          ->get();

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


               $data[]= array(
                 $row->id, 
                 $row->referencia, 
                 $row->first_name.' '.$row->last_name, 
                 $row->telefono_cliente, 
                 $row->nombre_forma_envios, 
                 $row->nombre_forma_pago, 
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

        $dt = Carbon::now();

        $date_inicio = Carbon::create($dt->year, $dt->month, $dt->day, 5, 59, 0); 
        
        $date_inicio->subDay();

        $date_fin = Carbon::create($dt->year, $dt->month, $dt->day, 6, 0, 0); 
              
        $ordenes = AlpOrdenes::all();

        $estatus_ordenes = AlpEstatusOrdenes::all();

         $ordenes = AlpOrdenes::select('alp_ordenes.*', 'users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre', 'alp_ordenes_pagos.json as json')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
           ->leftJoin('alp_ordenes_pagos', 'alp_ordenes.id', '=', 'alp_ordenes_pagos.id_orden')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
          ->where('alp_ordenes.created_at', '>=', $date_inicio)
          ->where('alp_ordenes.created_at', '<=', $date_fin)

          ->get();
       
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

        $ordenes = AlpOrdenes::all();

        $estatus_ordenes = AlpEstatusOrdenes::all();

         $ordenes = AlpOrdenes::select('alp_ordenes.*', 'users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre', 'alp_ordenes_pagos.json as json')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
           ->leftJoin('alp_ordenes_pagos', 'alp_ordenes.id', '=', 'alp_ordenes_pagos.id_orden')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
          ->where('alp_ordenes.estatus', '8')
          ->groupBy('alp_ordenes.id')
          ->get();
       
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
          ->get();

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


               $data[]= array(
                 $row->id, 
                 $row->referencia, 
                 $row->first_name.' '.$row->last_name, 
                 $row->telefono_cliente, 
                 $row->nombre_forma_envios, 
                 $row->nombre_forma_pago, 
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




        $ordenes = AlpOrdenes::all();

        $estatus_ordenes = AlpEstatusOrdenes::all();

         $ordenes = AlpOrdenes::select('alp_ordenes.*', 'users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre', 'alp_ordenes_pagos.json as json')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
           ->leftJoin('alp_ordenes_pagos', 'alp_ordenes.id', '=', 'alp_ordenes_pagos.id_orden')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
          ->where('alp_ordenes.estatus', '5')
          ->groupBy('alp_ordenes.id')
          ->get();
       
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
          ->get();

            $data = array();


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






               $data[]= array(
                 $row->id, 
                 $row->referencia, 
                 $row->first_name.' '.$row->last_name, 
                 $row->telefono_cliente, 
                 $row->nombre_forma_envios, 
                 $row->nombre_forma_pago, 
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
          ->get();
       
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
          ->get();

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







               $data[]= array(
                 $row->id, 
                 $row->referencia, 
                 $row->first_name.' '.$row->last_name, 
                 $row->telefono_cliente, 
                 $row->nombre_forma_envios, 
                 $row->nombre_forma_pago, 
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


      
        $ordenes = AlpOrdenes::all();

        $estatus_ordenes = AlpEstatusOrdenes::all();

         $ordenes = AlpOrdenes::select('alp_ordenes.*', 'users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre', 'alp_ordenes_pagos.json as json')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->leftJoin('alp_ordenes_pagos', 'alp_ordenes.id', '=', 'alp_ordenes_pagos.id_orden')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
          ->where('alp_ordenes.estatus', '6')
          ->groupBy('alp_ordenes.id')
          ->get();
       
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
          ->get();

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







               $data[]= array(
                 $row->id, 
                 $row->referencia, 
                 $row->first_name.' '.$row->last_name, 
                 $row->telefono_cliente, 
                 $row->nombre_forma_envios, 
                 $row->nombre_forma_pago, 
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



      
        $ordenes = AlpOrdenes::all();

        $estatus_ordenes = AlpEstatusOrdenes::all();

        $ordenes = AlpOrdenes::select('alp_ordenes.*', 'users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre', 'alp_ordenes_pagos.json as json')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
           ->leftJoin('alp_ordenes_pagos', 'alp_ordenes.id', '=', 'alp_ordenes_pagos.id_orden')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
          ->where('alp_ordenes.estatus', '7')
          ->groupBy('alp_ordenes.id')
          ->get();
       
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
          ->get();
       

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

                                          


               $data[]= array(
                 $row->id, 
                 $row->referencia, 
                 $row->first_name.' '.$row->last_name, 
                 $row->telefono_cliente, 
                 $row->nombre_forma_envios, 
                 $row->nombre_forma_pago, 
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


      
        $ordenes = AlpOrdenes::all();

        $estatus_ordenes = AlpEstatusOrdenes::all();

        $ordenes = AlpOrdenes::select('alp_ordenes.*', 'users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre', 'alp_ordenes_pagos.json as json')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
           ->leftJoin('alp_ordenes_pagos', 'alp_ordenes.id', '=', 'alp_ordenes_pagos.id_orden')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
          ->where('alp_ordenes.estatus', '4')
          ->groupBy('alp_ordenes.id')
          ->get();
       
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


      
        $ordenes = AlpOrdenes::all();

        $estatus_ordenes = AlpEstatusOrdenes::all();

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
          ->get();
       
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
          ->get();
       

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



   


               $data[]= array(
                 $row->id, 
                 $row->referencia, 
                 $row->first_name.' '.$row->last_name, 
                 $row->nombre_forma_envios, 
                 $row->nombre_forma_pago, 
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


        return view('admin.ordenes.detalle', compact('detalles', 'orden', 'history', 'pago', 'pagos', 'cliente', 'direccion', 'cupones', 'formaenvio', 'envio', 'pago_aprobado'));

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

        }//endif


         
        $history=AlpOrdenesHistory::create($data_history);

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

          ///dd($orden);

        /*$detalles =  DB::table('alp_ordenes_detalle')->select(
          'alp_ordenes_detalle.*',
          'alp_productos.referencia_producto as referencia_producto',
          'alp_productos.presentacion_producto as presentacion_producto',
          'alp_productos.nombre_producto as nombre_producto',
          'alp_productos.referencia_producto_sap as referencia_producto_sap' ,
          'alp_productos.imagen_producto as imagen_producto' ,
          'alp_productos.slug as slug'
        )
          ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
          ->where('alp_ordenes_detalle.id_orden', $orden->id)->get();

        $envio=AlpEnvios::where('id_orden', $orden->id)->first();*/



        if ($orden->id) {


          //$user_cliente=Users::where('id', $orden->id_cliente)->first();

          $texto="La orden ".$orden->id.", el pago ha sido notificado y espera por el proceso de aprobación!";

           //return $texto;

          Mail::to($configuracion->correo_cedi)->send(new \App\Mail\NotificacionOrden($orden->id, $texto));

          
          //Mail::to($orden->email)->send(new \App\Mail\CompraAprobada($orden, $detalles, $envio->fecha_envio));


          //Mail::to($configuracion->correo_cedi)->send(new \App\Mail\CompraSac($orden, $detalles, $envio->fecha_envio));


         // Mail::to($user_cliente->email)->send(new \App\Mail\NotificacionOrden($orden->id, $texto));

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

    //    dd($configuracion->correo_cedi);

        //var_dump($input);

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




}
