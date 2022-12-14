<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use Intervention\Image\Facades\Image;
use DOMDocument;
use App\Http\Requests\ClientesRequest;
use App\Http\Requests\DireccionRequest;
use App\Mail\Register;
use App\Mail\Restore;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use App\Models\AlpClientes;
use App\Models\AlpClientesHistory;
use App\Models\AlpClientesEmbajador;
use App\Models\AlpDirecciones;
use App\Models\AlpTDocumento;
use App\Models\AlpEmpresas;
use App\Models\AlpConfiguracion;
use App\Models\AlpSaldo;
use App\Models\AlpAlmacenes;
use App\Models\AlpAbonosDisponible;
use App\Models\AlpAbonos;
use App\Models\AlpAbonosUser;
use App\User;
use App\Roles;
use App\RoleUser;


use App\Models\AlpEstructuraAddress;

use App\State;
use App\Country;

use App\City;



use App\Http\Requests;
use Illuminate\Http\Request;
use Redirect;
use Response;
use Sentinel;
use View;
use DB;
use File;
use Hash;
use Mail;
use URL;
use Validator;
use Yajra\DataTables\DataTables;

use App\Imports\BucaramangaImport;
use App\Imports\SaldoImport;
use Maatwebsite\Excel\Facades\Excel;



class AlpClientesController extends JoshController
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
                        
                        ->log('clientes/index ');

        }else{

          activity()->log('clientes/index');


        }

         if (!Sentinel::getUser()->hasAnyAccess(['clientes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }



      
        $clientes =  User::select('users.*','roles.name as name_role','alp_clientes.estado_masterfile as estado_masterfile','alp_clientes.estado_registro as estado_registro','alp_clientes.telefono_cliente as telefono_cliente','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.cod_alpinista as cod_alpinista','alp_clientes.doc_cliente as doc_cliente','alp_clientes.genero_cliente as genero_cliente')
        ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
        ->join('role_users', 'users.id', '=', 'role_users.user_id')
        ->join('roles', 'role_users.role_id', '=', 'roles.id')
        ->where('role_users.role_id', '<>', 1)
        ->get();



        // Show the page
        return view('admin.clientes.index', compact('clientes'));
    }    



     public function data()
    {
        //$users = User::get(['id', 'first_name', 'last_name', 'email','created_at']);

         if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

            $role=RoleUser::where('user_id', $user_id)->first();

            $id_rol=$role->role_id;
        }else{

          $id_rol=9;
        }
      
        

        $clientes =  User::select('users.*','roles.name as name_role','alp_clientes.estado_masterfile as estado_masterfile','alp_clientes.estado_registro as estado_registro','alp_clientes.telefono_cliente as telefono_cliente','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.cod_alpinista as cod_alpinista'
          ,'alp_clientes.codigo_cliente as codigo_cliente'
          ,'alp_clientes.marketing_sms as marketing_sms'
          ,'alp_clientes.marketing_email as marketing_email'
          ,'alp_clientes.deleted_at as cliente_deleted_at'
        )
        ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
        ->join('role_users', 'users.id', '=', 'role_users.user_id')
        ->join('roles', 'role_users.role_id', '=', 'roles.id')
        ->where('alp_clientes.origen', '=', '0')
        ->where('role_users.role_id', '<>', 1)->get();

            $data = array();


          foreach($clientes as $cliente){

             if($cliente->estado_registro == 1){

                $estado= "<span class='label label-sm label-success'>Activo</span>";

              }else{

                $estado= "<span class='label label-sm label-warning'>Inactivo</span>";

              }


              if($cliente->estado_masterfile == 1){

                $masterfile= "<span class='label label-sm label-success'>Activo</span>";

              }else{

                $masterfile= "<span class='label label-sm label-warning'>Inactivo</span>";

              }

              if($cliente->marketing_email == 1){

                $marketing_email= "<span class='label label-sm label-success'>Activo</span>";

              }else{

                $marketing_email= "<span class='label label-sm label-warning'>Inactivo</span>";

              }


              if($cliente->marketing_sms == 1){

                $marketing_sms= "<span class='label label-sm label-success'>Activo</span>";

              }else{

                $marketing_sms= "<span class='label label-sm label-warning'>Inactivo</span>";

              }


              if(is_null($cliente->cliente_deleted_at) ){

                $cliente_deleted_at= "<span class='label label-sm label-success'>Activo</span>";

              }else{

                $cliente_deleted_at= "<span class='label label-sm label-warning'>Inactivo</span>";

              }



              if ($id_rol=='13') {

                 $actions = " 

                 <a href='".secure_url("admin/clientes/".$cliente->id."/detalle" )."'>
                    <i class='fa fa-eye' title='Detalles ' alt='Detalles' ></i>

                 </a>

                 <a href='".secure_url("admin/clientes/".$cliente->id."/direcciones" )."'>

                     <i class='livicon' data-name='location' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='view alpProductos'></i>
                 </a>


                 <a href='".secure_url("admin/clientes/".$cliente->id."/abono" )." ' class='btn btn-info btn-xs'>

                     Aplicar Abono
                 </a>


                  ";


                # code...
              }else{


                 $actions = " 

                 <a href='".secure_url("admin/clientes/".$cliente->id."/detalle" )."'>
                    <i class='fa fa-eye' title='Detalles ' alt='Detalles' ></i>

                 </a>

                 <a href='".secure_url("admin/clientes/".$cliente->id."/direcciones" )."'>

                     <i class='livicon' data-name='location' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='view alpProductos'></i>
                 </a>


                  <a href='".secure_url("admin/clientes/".$cliente->id."/edit")."'>

                     <i class='livicon' data-name='edit' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='edit alpProductos'></i>
                 </a>


                 <button class='btn btn-link deleteCliente' data-id='".$cliente->id."' data-url='".secure_url("admin/clientes/".$cliente->id."/delete")."'> <i class='livicon' data-name='remove-alt' data-size='18' data-loop='true' data-c='#f56954' data-hc='#f56954'  title='Eliminar'></i> </button>


                 <div id='botones_".$cliente->id."'>

                  <button type='button' data-id='".$cliente->id."' class='btn btn-xs btn-primary activarUsuario' >Activar</button>

                  <button type='button' data-id='".$cliente->id."' class='btn btn-xs btn-danger rechazarUsuario' >Desactivar</button>

                </div>


                <a href='".secure_url("admin/clientes/".$cliente->id."/abono" )." ' class='btn btn-info btn-xs'>

                     Aplicar Abono
                 </a>





                 ";

              }








               


               $data[]= array(
                 $cliente->id, 
                 $cliente->cod_oracle_cliente, 
                 $cliente->first_name.' '.$cliente->last_name, 
                 $cliente->email, 
                 $cliente->telefono_cliente, 
                 $cliente->codigo_cliente, 
                 $cliente->name_role, 
                 $masterfile, 
                 $estado, 
                 $marketing_sms, 
                 $marketing_email, 
                 $cliente_deleted_at, 
                 date("d/m/Y H:i:s", strtotime($cliente->created_at)),
                 $actions
              );



          }


          return json_encode( array('data' => $data ));

    }


    public function inactivos()
    {
        // Grab all the groups

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        
                        ->log('clientes/inactivos ');

        }else{

          activity()->log('clientes/inactivos');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['clientes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }




      
        $clientes =  User::select('users.*','roles.name as name_role','alp_clientes.estado_masterfile as estado_masterfile','alp_clientes.estado_registro as estado_registro','alp_clientes.telefono_cliente as telefono_cliente')
        ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
        ->join('role_users', 'users.id', '=', 'role_users.user_id')
        ->join('roles', 'role_users.role_id', '=', 'roles.id')
        ->where('role_users.role_id', '<>', 1)
        ->where('alp_clientes.estado_registro', '=', 0)
        ->whereNull('alp_clientes.deleted_at')
        ->get();


        // Show the page
        return view('admin.clientes.inactivos', compact('clientes'));
    }


      public function datainactivos()
    {
        //$users = User::get(['id', 'first_name', 'last_name', 'email','created_at']);

        

         $clientes =  User::select('users.*','roles.name as name_role','alp_clientes.estado_masterfile as estado_masterfile','alp_clientes.estado_registro as estado_registro','alp_clientes.telefono_cliente as telefono_cliente'
        ,'alp_clientes.marketing_sms as marketing_sms'
          ,'alp_clientes.marketing_email as marketing_email'
          ,'alp_clientes.deleted_at as cliente_deleted_at')
        ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
        ->join('role_users', 'users.id', '=', 'role_users.user_id')
        ->join('roles', 'role_users.role_id', '=', 'roles.id')
        ->where('role_users.role_id', '<>', 1)
        ->where('alp_clientes.estado_registro', '=', 0)
        ->where('alp_clientes.origen', '=', '0')
        ->whereNull('alp_clientes.deleted_at')
        ->get();


            $data = array();


          foreach($clientes as $cliente){

             if($cliente->estado_registro == 1){

                $estado= "<span class='label label-sm label-success'>Activo</span>";

              }else{

                $estado= "<span class='label label-sm label-warning'>Inactivo</span>";

              }


              if($cliente->estado_masterfile == 1){

                $masterfile= "<span class='label label-sm label-success'>Activo</span>";

              }else{

                $masterfile= "<span class='label label-sm label-warning'>Inactivo</span>";

              }

              if($cliente->marketing_email == 1){

                $marketing_email= "<span class='label label-sm label-success'>Activo</span>";

              }else{

                $marketing_email= "<span class='label label-sm label-warning'>Inactivo</span>";

              }


              if($cliente->marketing_sms == 1){

                $marketing_sms= "<span class='label label-sm label-success'>Activo</span>";

              }else{

                $marketing_sms= "<span class='label label-sm label-warning'>Inactivo</span>";

              }


              if(is_null($cliente->cliente_deleted_at) ){

                $cliente_deleted_at= "<span class='label label-sm label-success'>Activo</span>";

              }else{

                $cliente_deleted_at= "<span class='label label-sm label-warning'>Inactivo</span>";

              }


                 $actions = " 

                 <a href='".secure_url("admin/clientes/".$cliente->id."/detalle" )."'>
                    <i class='fa fa-eye' title='Detalles ' alt='Detalles' ></i>

                 </a>

                 <a href='".secure_url("admin/clientes/".$cliente->id."/direcciones" )."'>

                     <i class='livicon' data-name='eye' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='view alpProductos'></i>
                 </a>


                  <a href='".secure_url("admin/clientes/".$cliente->id."/edit")."'>

                     <i class='livicon' data-name='edit' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='edit alpProductos'></i>
                 </a>


                 <div id='botones_".$cliente->id."'>

                  <button type='button' data-id='".$cliente->id."' class='btn btn-xs btn-primary activarUsuario' >Activar</button>

                  <button type='button' data-id='".$cliente->id."' class='btn btn-xs btn-danger rechazarUsuario' >Desactivar</button>

                </div>";


               $data[]= array(
                 $cliente->id, 
                 $cliente->first_name.' '.$cliente->last_name, 
                 $cliente->email, 
                 $cliente->telefono_cliente, 
                 $cliente->name_role, 
                 $masterfile, 
                 $estado, 
                 $marketing_sms, 
                 $marketing_email, 
                 $cliente_deleted_at, 
                  date("d/m/Y H:i:s", strtotime($cliente->created_at)),
                 $actions
              );



          }


          return json_encode( array('data' => $data ));

    }




    public function rechazados()
    {
        // Grab all the groups

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        
                        ->log('clientes/rechazados ');

        }else{

          activity()->log('clientes/rechazados');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['clientes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }




      
        $clientes =  User::select('users.*','roles.name as name_role','alp_clientes.estado_masterfile as estado_masterfile','alp_clientes.estado_registro as estado_registro','alp_clientes.telefono_cliente as telefono_cliente')
        ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
        ->join('role_users', 'users.id', '=', 'role_users.user_id')
        ->join('roles', 'role_users.role_id', '=', 'roles.id')
        ->where('role_users.role_id', '<>', 1)
        ->where('alp_clientes.deleted_at', '<>', NULL)
        ->get();


        // Show the page
        return view('admin.clientes.rechazados', compact('clientes'));
    }


      public function datarechazados()
    {
        //$users = User::get(['id', 'first_name', 'last_name', 'email','created_at']);

        $clientes =  User::select('users.*','roles.name as name_role','alp_clientes.estado_masterfile as estado_masterfile','alp_clientes.estado_registro as estado_registro','alp_clientes.telefono_cliente as telefono_cliente'
       ,'alp_clientes.marketing_sms as marketing_sms'
          ,'alp_clientes.marketing_email as marketing_email'
          ,'alp_clientes.deleted_at as cliente_deleted_at')
        ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
        ->join('role_users', 'users.id', '=', 'role_users.user_id')
        ->join('roles', 'role_users.role_id', '=', 'roles.id')
        ->where('role_users.role_id', '<>', 1)
        ->where('alp_clientes.origen', '=', '0')
        ->where('alp_clientes.deleted_at', '<>', NULL)
        ->get();


            $data = array();


          foreach($clientes as $cliente){

             if($cliente->estado_registro == 1){

                $estado= "<span class='label label-sm label-success'>Activo</span>";

              }else{

                $estado= "<span class='label label-sm label-warning'>Inactivo</span>";

              }


              if($cliente->estado_masterfile == 1){

                $masterfile= "<span class='label label-sm label-success'>Activo</span>";

              }else{

                $masterfile= "<span class='label label-sm label-warning'>Inactivo</span>";

              }

              if($cliente->marketing_email == 1){

                $marketing_email= "<span class='label label-sm label-success'>Activo</span>";

              }else{

                $marketing_email= "<span class='label label-sm label-warning'>Inactivo</span>";

              }


              if($cliente->marketing_sms == 1){

                $marketing_sms= "<span class='label label-sm label-success'>Activo</span>";

              }else{

                $marketing_sms= "<span class='label label-sm label-warning'>Inactivo</span>";

              }


              if(is_null($cliente->cliente_deleted_at) ){

                $cliente_deleted_at= "<span class='label label-sm label-success'>Activo</span>";

              }else{

                $cliente_deleted_at= "<span class='label label-sm label-warning'>Inactivo</span>";

              }


                 $actions = " 

                 <a href='".secure_url("admin/clientes/".$cliente->id."/detalle" )."'>
                    <i class='fa fa-eye' title='Detalles ' alt='Detalles' ></i>

                 </a>

                 <a href='".secure_url("admin/clientes/".$cliente->id."/direcciones" )."'>

                     <i class='livicon' data-name='eye' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='view alpProductos'></i>
                 </a>


                  <a href='".secure_url("admin/clientes/".$cliente->id."/edit")."'>

                     <i class='livicon' data-name='edit' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='edit alpProductos'></i>
                 </a>


                 <div id='botones_".$cliente->id."'>

                                       

                                        <button type='button' data-id='".$cliente->id."' class='btn btn-xs btn-primary activarUsuario' >Activar</button>

                                        

                                     

                                        </div>";


               $data[]= array(
                 $cliente->id, 
                 $cliente->first_name.' '.$cliente->last_name, 
                 $cliente->email, 
                 $cliente->telefono_cliente, 
                 $cliente->name_role, 
                 $masterfile, 
                 $estado, 
                 $marketing_sms, 
                 $marketing_email, 
                 $cliente_deleted_at, 
                  $cliente->nota,
                 $actions
              );



          }


          return json_encode( array('data' => $data ));

    }

    public function empresas()
    {
        // Grab all the groups

          if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        
                        ->log('clientes/empresas ');

        }else{

          activity()->log('clientes/empresas');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['clientes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


      
        $clientes =  User::select('users.*','roles.name as name_role','alp_clientes.estado_masterfile as estado_masterfile','alp_clientes.estado_registro as estado_registro','alp_clientes.telefono_cliente as telefono_cliente','alp_empresas.nombre_empresa as nombre_empresa')
        ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
        ->join('alp_empresas', 'alp_clientes.id_empresa', '=', 'alp_empresas.id')
        ->join('role_users', 'users.id', '=', 'role_users.user_id')
        ->join('roles', 'role_users.role_id', '=', 'roles.id')
        ->where('alp_clientes.origen', '=', 0)
        ->where('role_users.role_id', '<>', 1)->get();


        // Show the page
        return view('admin.clientes.empresas', compact('clientes'));
    }

     public function dataempresas()
    {
        //$users = User::get(['id', 'first_name', 'last_name', 'email','created_at']);

        

         $clientes =  User::select('users.*','roles.name as name_role','alp_clientes.estado_masterfile as estado_masterfile','alp_clientes.estado_registro as estado_registro','alp_clientes.telefono_cliente as telefono_cliente','alp_empresas.nombre_empresa as nombre_empresa'
        ,'alp_clientes.marketing_sms as marketing_sms'
          ,'alp_clientes.marketing_email as marketing_email'
          ,'alp_clientes.deleted_at as cliente_deleted_at')
        ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
        ->join('alp_empresas', 'alp_clientes.id_empresa', '=', 'alp_empresas.id')
        ->join('role_users', 'users.id', '=', 'role_users.user_id')
        ->join('roles', 'role_users.role_id', '=', 'roles.id')
        ->where('alp_clientes.origen', '=', '0')
        ->where('role_users.role_id', '<>', 1)
        ->get();

            $data = array();


          foreach($clientes as $cliente){

             if($cliente->estado_registro == 1){

                $estado= "<span class='label label-sm label-success'>Activo</span>";

              }else{

                $estado= "<span class='label label-sm label-warning'>Inactivo</span>";

              }


              if($cliente->estado_masterfile == 1){

                $masterfile= "<span class='label label-sm label-success'>Activo</span>";

              }else{

                $masterfile= "<span class='label label-sm label-warning'>Inactivo</span>";

              }

              if($cliente->marketing_email == 1){

                $marketing_email= "<span class='label label-sm label-success'>Activo</span>";

              }else{

                $marketing_email= "<span class='label label-sm label-warning'>Inactivo</span>";

              }


              if($cliente->marketing_sms == 1){

                $marketing_sms= "<span class='label label-sm label-success'>Activo</span>";

              }else{

                $marketing_sms= "<span class='label label-sm label-warning'>Inactivo</span>";

              }


              if(is_null($cliente->cliente_deleted_at) ){

                $cliente_deleted_at= "<span class='label label-sm label-success'>Activo</span>";

              }else{

                $cliente_deleted_at= "<span class='label label-sm label-warning'>Inactivo</span>";

              }

                 $actions = " 

                 <a href='".secure_url("admin/clientes/".$cliente->id."/detalle" )."'>
                    <i class='fa fa-eye' title='Detalles ' alt='Detalles' ></i>

                 </a>

                 <a href='".secure_url("admin/clientes/".$cliente->id."/direcciones" )."'>

                     <i class='livicon' data-name='eye' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='view alpProductos'></i>
                 </a>


                  <a href='".secure_url("admin/clientes/".$cliente->id."/edit")."'>

                     <i class='livicon' data-name='edit' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='edit alpProductos'></i>
                 </a>


                 <button class='btn btn-link deleteCliente' data-id='".$cliente->id."' data-url='".secure_url("admin/clientes/".$cliente->id."/delete")."'>
                                        <i class='livicon' data-name='remove-alt' data-size='18'
                                            data-loop='true' data-c='#f56954' data-hc='#f56954'
                                            title='Eliminar'></i>
                                        </button>";


               $data[]= array(
                 $cliente->id, 
                 $cliente->first_name.' '.$cliente->last_name, 
                 $cliente->email, 
                 $cliente->telefono_cliente, 
                 $cliente->name_role, 
                 $cliente->nombre_empresa, 
                 $masterfile, 
                 $estado, 
                 $marketing_sms, 
                 $marketing_email, 
                 $cliente_deleted_at, 
                  $cliente->created_at->diffForHumans(),
                 $actions
              );



          }


          return json_encode( array('data' => $data ));

    }

    public function detalle($id)
    {
        // Grab all the groups

           if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])
                        ->log('clientes/detalle ');

        }else{

          activity()->withProperties(['id'=>$id])
                        ->log('clientes/detalle');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['clientes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }



        $user_id = Sentinel::getUser()->id;

        $saldo=AlpSaldo::where('id_cliente', $id)->get();

        //dd($saldo);

        $un_saldo=AlpSaldo::where('id_cliente', $id)->orderBy('id', 'desc')->first();

        $disponible=$this->getSaldo();



        $cliente=AlpClientes::select('alp_clientes.*', 'alp_tipo_documento.nombre_tipo_documento as nombre_tipo_documento')
        ->join('alp_tipo_documento', 'alp_clientes.id_type_doc', '=', 'alp_tipo_documento.id')
        ->where('id_user_client', $id)->first();

        //dd($cliente);


        if ($cliente->id_embajador!=0) {
                
             $embajador=User::where('users.id', $cliente->id_embajador)->first();

             $cliente->embajador=$embajador;

        }

        $referidos =  DB::table('alp_clientes')->select('alp_clientes.*','users.first_name as first_name','users.last_name as last_name' ,'users.email as email', DB::raw("SUM(alp_ordenes.monto_total) as puntos"))
            ->join('users','alp_clientes.id_user_client' , '=', 'users.id')
            ->leftJoin('alp_ordenes','users.id' , '=', 'alp_ordenes.id_cliente')
            ->groupBy('alp_clientes.id')
            ->where('alp_clientes.id_embajador', $id)->get();

        $usuario=User::select('users.*','roles.name as name_rol', 'role_users.role_id as role_id' )
        ->join('role_users', 'users.id', '=', 'role_users.user_id')
        ->join('roles', 'role_users.role_id', '=', 'roles.id')
        ->where('users.id', $id)
        ->first();

        $history = AlpClientesHistory::select('alp_clientes_history.*', 'users.first_name as first_name', 'users.last_name as last_name' )
          ->join('users', 'alp_clientes_history.id_user', '=', 'users.id')
          ->where('alp_clientes_history.id_cliente', $id)
          ->get();

          $roles=Roles::all();

      
        // Show the page
        return view('admin.clientes.detalle', compact('history','saldo', 'cliente', 'usuario', 'roles', 'referidos', 'un_saldo', 'disponible'));
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
                        
                        ->log('clientes/create ');

        }else{

          activity()->log('clientes/create');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['clientes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }



        // Get all the available groups
        $groups = DB::table('roles')->whereIn('roles.id', [9, 10, 11])->get();

        $tdocumento = AlpTDocumento::all();

        // Show the page
        return view ('admin.clientes.create', compact('groups','tdocumento'));
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(ClientesRequest $request)
    {
        $user_id = Sentinel::getUser()->id;


         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('clientes/store ');

        }else{

          activity()->withProperties($request->all())->log('clientes/store');


        }

        
        $activate = $request->get('activate') ? true : false;

        try {
            // Register the user

            $cliente = Sentinel::register(
            ['email' => $request->email, 
            'first_name' => $request->first_name, 
            'last_name' =>$request->last_name, 
            'dob' =>$request->dob, 
            'password' =>$request->password], $activate)->id;

            $data = array(
                'id_user_client' => $cliente, 
                'id_type_doc' => $request->id_type_doc, 
                'doc_cliente' =>$request->doc_cliente, 
                'genero_cliente' =>$request->genero_cliente, 
                'telefono_cliente' =>$request->telefono_cliente, 
                'marketing_cliente' =>$request->marketing_cliente,
                'cod_alpinista'  =>$request->cod_alpinista,
                'codigo_cliente'  =>$request->codigo_cliente,
                'habeas_cliente' => 1,
                'estado_masterfile' =>0,
                'id_user' =>$user_id,               
            );

            AlpClientes::create($data);

            $user = Sentinel::findUserById($cliente);

            //add user to 'User' group
            $role = Sentinel::findRoleById($request->get('group'));
            if ($role) {
                $role->users()->attach($user);
            }
            //check for activation and send activation mail if not activated by default
            if (!$request->get('activate')) {
                // Data to be used on the email view
                $data =[
                    'user_name' => $user->first_name .' '. $user->last_name,
                    'activationUrl' => URL::route('activate', [$user->id, Activation::create($user)->code])
                ];
                // Send the activation code through email
                /*Mail::to($user->email)
                    ->send(new Register($data));*/
            }
            // Activity log for New user create
            activity($user->full_name)
                ->performedOn($user)
                ->causedBy($user)
                ->log('Nuevo usuario, creado por '.Sentinel::getUser()->full_name);
            // Redirect to the home page with success menu
            return Redirect::route('admin.clientes.index')->with('exito', trans('users/message.success.create'));

        } catch (LoginRequiredException $e) {
            $error = trans('admin/users/message.user_login_required');
        } catch (PasswordRequiredException $e) {
            $error = trans('admin/users/message.user_password_required');
        } catch (UserExistsException $e) {
            $error = trans('admin/users/message.user_exists');
        }

        // Redirect to the user creation page
        return Redirect::back()->withInput()->with('error', $error);
    }


    /**
     * Group update.
     *
     * @param  int $id
     * @return View
     */
    public function edit($id)
    {
       
            if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])
                        ->log('clientes/edit ');

        }else{

          activity()->withProperties(['id'=>$id])->log('clientes/edit');

        }

         if (!Sentinel::getUser()->hasAnyAccess(['clientes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }




        if (!Sentinel::getUser()->hasAnyAccess(['clientes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }



        $cliente = DB::table('alp_clientes')
        //->leftJoin('users', 'alp_clientes.id_embajador', '=', 'users.id')
        ->where('alp_clientes.id_user_client', '=', $id)->first();

        //dd($cliente);

        $user = User::findOrFail($id);
        
        // Get this user groups
        $userRoles = $user->getRoles()->pluck('name', 'id')->all();
        // Get a list of all the available groups
        $roles = Sentinel::getRoleRepository()->all();

        $status = Activation::completed($user);

        // Get all the available groups
        $groups = DB::table('roles')->where('roles.id', '<>', 1)->get();

        $tdocumento = AlpTDocumento::select(
            DB::raw("CONCAT(nombre_tipo_documento,' - ', abrev_tipo_documento) AS nombre_tipo_documento"),'id')
            ->pluck('nombre_tipo_documento', 'id');

        return view('admin.clientes.edit', compact('groups','tdocumento','user','cliente','userRoles','roles','status'));
    }




    public function update(User $user, ClientesRequest $request)
    {

        $user_id = Sentinel::getUser()->id;


          if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$user_id, 'request'=>$request->all()])
                        ->log('clientes/update ');

        }else{

          activity()->withProperties(['id'=>$user_id, 'request'=>$request->all()])->log('clientes/update');


        }

         $user_history = array(
                'id_cliente' => $request->id_cliente,
                'estatus_cliente' => "Edicion",
                'notas' => "Se ha editado el cliente ",
                'id_user'=>$user_id
                 );

            AlpClientesHistory::create($user_history);
            

        try {

            $user=User::where('id', $request->id_cliente)->first();

            $data_user = array(
                'first_name' => $request->first_name, 
                'last_name' => $request->last_name, 
                'dob' => $request->dob, 
            );
            
            $user->update($data_user);

            if ( !empty($request->password)) {

                $data_user = array(
                'password' => Hash::make($request->password)                 
                );
                
                $user->update($data_user);
            }

            // is new image uploaded?
            if ($file = $request->file('pic_file')) {
                $extension = $file->extension()?: 'png';
                $destinationPath = public_path() . '/uploads/perfiles/';
                $safeName = str_random(10) . '.' . $extension;
                $file->move($destinationPath, $safeName);
                //delete old pic if exists
                if (File::exists($destinationPath . $user->pic)) {
                    File::delete($destinationPath . $user->pic);
                }
                //save new file path into db
                $pic = $safeName;

                 $data_user = array(
                'pic' => $safeName                 
                );
                
                $user->update($data_user);
            }


            $cliente=AlpClientes::where('id_user_client', $user->id)->first();

            $data_cliente = array(
                'genero_cliente' => $request->genero_cliente, 
                'id_type_doc' => $request->id_type_doc, 
                'doc_cliente' => $request->doc_cliente, 
                'telefono_cliente' => $request->telefono_cliente,
                'marketing_cliente' =>$request->marketing_cliente,
                'codigo_cliente' =>$request->codigo_cliente,
                'estado_masterfile' =>$request->activate,
                'id_user' =>$user_id, 
            );

            $cliente->update($data_cliente);

            //save record
           
            // Get the current user groups
            $userRoles = $user->roles()->pluck('id')->all();




            $roleusuario=RoleUser::where('user_id', $request->id_cliente)->first();

          //  print_r($roleusuario);

           // print_r($request->groups);



            $role = Sentinel::findRoleById($roleusuario->role_id);
                $role->users()->detach($request->id_cliente);

            $role = Sentinel::findRoleById($request->groups);
                $role->users()->attach($request->id_cliente);

            // Activate / De-activate user

            $status = $activation = Activation::completed($user);

            if ($request->get('activate') != $status) {
                if ($request->get('activate')) {
                    $activation = Activation::exists($user);
                    if ($activation) {
                        Activation::complete($user, $activation->code);
                    }
                } else {
                    //remove existing activation record
                    Activation::remove($user);
                    //add new record
                    Activation::create($user);
                    //send activation mail
                    $data=[
                        'user_name' =>$user->first_name .' '. $user->last_name,
                    'activationUrl' => URL::route('activate', [$user->id, Activation::exists($user)->code])
                    ];
                    // Send the activation code through email
                    /*Mail::to($user->email)
                        ->send(new Restore($data));*/

                }
            }

            // Was the user updated?
            //if ($user->update()) {
                // Prepare the success message
                $success = trans('users/message.success.update');
               //Activity log for user update
                activity($user->full_name)
                    ->performedOn($user)
                    ->causedBy($user)
                    ->log('User Updated by '.Sentinel::getUser()->full_name);
                // Redirect to the user page
                return Redirect::route('admin.clientes.index')->with('success', $success);
            //}

            // Prepare the error message
            $error = trans('users/message.error.update');

        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = trans('users/message.user_not_found', compact('id'));

            // Redirect to the user management page
            return Redirect::route('admin.clientes.index')->with('error', $error);
        }

        // Redirect to the user page
        return Redirect::route('admin.clientes.edit', $request->id_cliente)->withInput()->with('error', $error);
    }

    /**
     * Display specified user profile.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {


         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])
                        ->log('clientes/show ');

        }else{

          activity()->withProperties(['id'=>$id])->log('clientes/show');


        }

         if (!Sentinel::getUser()->hasAnyAccess(['clientes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }




        $saldo=AlpSaldo::where('id_cliente', $id)->get();

        try {
            // Get the user information
            $user = Sentinel::findUserById($id);
            //get country name
            if ($user->country) {
                $user->country = $this->countries[$user->country];
            }
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = trans('users/message.user_not_found', compact('id'));
            // Redirect to the user management page
            return Redirect::route('admin.clientes.index')->with('error', $error);
        }
        // Show the page
        return view('admin.clientes.show', compact('user', 'saldo'));

    }

    /**
     * Delete confirmation for the given group.
     *
     * @param  int $id
     * @return View
     */
    public function getModalDelete($id = null)
    {
        $model = 'categorias';
        $confirm_route = $error = null;
        try {
            // Get group information
            
            $cliente = AlpClientes::find($id);

            $confirm_route = route('admin/clientes/'.$cliente->id.'/delete');

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
                        ->withProperties(['id'=>$id])
                        ->log('clientes/destroy ');

        }else{

          activity()->withProperties(['id'=>$id])->log('clientes/destroy');


        }


        try {
            // Get group information
           
            $cliente = AlpClientes::where('id_user_client', $id)->first();

            //dd($cliente);


            $user = User::find($id);




            // Delete the group
            $cliente->delete();

            $user->delete();

            // Redirect to the group management page
            return Redirect::route('admin.clientes.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.clientes.index')->with('error', trans('Error al eliminar el registro'));
        }
    }


    public function direcciones($id)
    {


          if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])
                        ->log('clientes/direcciones ');

        }else{

          activity()->withProperties(['id'=>$id])->log('clientes/direcciones');


        }


         if (!Sentinel::getUser()->hasAnyAccess(['clientes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }



        $user = User::findOrFail($id);

        $cliente = DB::table('alp_clientes')->where('alp_clientes.id_user_client', '=', $id)->get();




        $direcciones = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
          ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
          ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
          ->where('alp_direcciones.id_client', $id)->get();


        return view('admin.clientes.direcciones', compact('user','cliente','direcciones'));
    }


    public function rechazar(Request $request)
    {


        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('clientes/rechazar ');

        }else{

          activity()->withProperties($request->all())->log('clientes/rechazar');


        }

        $user_id = Sentinel::getUser()->id;

           $configuracion = AlpConfiguracion::where('id','1')->first();

       try {


            $user=User::where('id', $request->cliente_id)->first();
            
            Activation::remove($user);
                        //add new record
            Activation::create($user);

            $data_history = array(
                'id_cliente' => $request->cliente_id, 
                'estatus_cliente' => 'rechazado',
                'notas' => $request->notas,
                'id_user' => $user_id
            );


            AlpClientesHistory::create($data_history);

            $data = array(
                'nota' => $request->notas
                 );

            $cliente=AlpClientes::where('id_user_client', $request->cliente_id)->first();

            $cliente->update($data);

            $cliente->delete();

            Mail::to($configuracion->correo_admin)->send(new \App\Mail\UserRechazado($user->first_name, $user->last_name));

            Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\UserRechazado($user->first_name, $user->last_name));
            //$user->delete();

            return 'true';
            
        } catch (Exception $e) {

            return 'false';
            
        }

        

        
          
    }


    public function updaterol(Request $request)
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('clientes/updaterol ');

        }else{

          activity()->withProperties($request->all())->log('clientes/updaterol');


        }

         if (!Sentinel::getUser()->hasAnyAccess(['clientes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }




        $user_id = Sentinel::getUser()->id;

           $configuracion = AlpConfiguracion::where('id','1')->first();

       try {


            $user=User::where('id', $request->cliente)->first();

            $roleusuario=RoleUser::where('user_id', $request->cliente)->first();

            //Elimanr el rol del cliente


            $role = Sentinel::findRoleById($roleusuario->role_id);

            $role->users()->detach($request->cliente);

            //Asignar al rol amigo 

            $role = Sentinel::findRoleById(11);
            
            $role->users()->attach($request->cliente);

            //HIstorico de cambios de cliente 

             $data_history = array(
                'id_cliente' => $request->cliente, 
                'estatus_cliente' => 'Cambio de Rol',
                'notas' => 'Este usuario ha dejado de ser Embajador y ahora es amigo alpina del Embajador invitadosalpina@yopmail.com',
                'id_user' => $user_id
            );

             //Registro de historico de embajador 


            AlpClientesHistory::create($data_history);

            $data_embajador = array(
                'id_cliente' => $request->cliente, 
                'id_embajador' => '632', 
                'notas'=>'Este usuario ha dejado de ser Embajador y ahora es amigo alpina del Embajador invitadosalpina@yopmail.com',
                'id_user' => $user_id
            );

            AlpClientesEmbajador::create($data_embajador);




            $data = array('id_embajador' => '632');


            $cliente=AlpClientes::where('id_user_client', $request->cliente)->first();

            $cliente->update($data);

            $amigos=AlpClientes::where('id_embajador', $request->cliente )->get();

            foreach ($amigos as $amigo) {

                 $data = array('id_embajador' => '632');

                $c=AlpClientes::where('id_user_client', $amigo->id_user_client)->first();

                $c->update($data);



             $data_history = array(
                'id_cliente' => $amigo->id_user_client, 
                'estatus_cliente' => 'Cambio de Rol',
                'notas' => 'Ha sido asignado como  amigo alpina del Embajador invitadosalpina@yopmail.com ',
                'id_user' => $user_id
            );




            AlpClientesHistory::create($data_history);


             $data_embajador = array(
                'id_cliente' => $amigo->id_user_client, 
                'id_embajador' => '632', 
                'notas'=>'Este usuario ha dejado de ser Embajador y ahora es amigo alpina del Embajador invitadosalpina@yopmail.com',
                'id_user' => $user_id
            );

            AlpClientesEmbajador::create($data_embajador);




            }

            return 'true';
            
        } catch (Exception $e) {

            return 'false';
            
        }

    }

    public function eliminar(Request $request)
    {
          if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('clientes/eliminar ');

        }else{

          activity()->withProperties($request->all())->log('clientes/eliminar');


        }

          $user = Sentinel::getUser();

          if(isset($user->id)){

            $u=User::where('id', $user->id)->delete();

            $data_history = array(
              'id_cliente' => $user->id, 
              'estatus_cliente' => 'Eliminado',
              'notas' => 'Cliente eliminado desde area de cliente',
              'id_user' => $user_id
          );

          AlpClientesHistory::create($data_history);

          \Session::forget('cart');

          \Session::forget('orden');

          \Session::forget('cr');



          //Activity log

          $user = Sentinel::getuser();

          activity($user->full_name)

              ->performedOn($user)

              ->causedBy($user)

              ->log('LoggedOut');

          // Log the user out

          Sentinel::logout();



          }

       
          
    }


    public function activar(Request $request)
    {



           if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('clientes/activar ');

        }else{

          activity()->withProperties($request->all())->log('clientes/activar');


        }

         if (!Sentinel::getUser()->hasAnyAccess(['clientes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }





        
        $user_id = Sentinel::getUser()->id;

        $user=User::where('id', $request->cliente_id)->first();

        $res=AlpClientes::where('cod_oracle_cliente', $request->cod_oracle_cliente)->first();


        if (isset($res->id)) {
            
            return 0;

        }else{


        


          Activation::remove($user);
                        //add new record
            Activation::create($user);

        $activation = Activation::exists($user);

        if ($activation) {

            Activation::complete($user, $activation->code);

        }

        $data = array(
            'estado_masterfile' => 1,
            'cod_oracle_cliente' => $request->cod_oracle_cliente
             );

        $cliente=AlpClientes::where('id_user_client', $user->id)->withTrashed()->first();


            $data_history = array(
                'id_cliente' => $request->cliente_id, 
                'estatus_cliente' => 'activado',
                'notas' => $request->notas,
                'id_user' => $user_id
            );

            AlpClientesHistory::create($data_history);

        $cliente->update($data);

         $clientes =  User::select('users.*','roles.name as name_role','alp_clientes.estado_masterfile as estado_masterfile','alp_clientes.estado_registro as estado_registro','alp_clientes.telefono_cliente as telefono_cliente')
        ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
        ->join('role_users', 'users.id', '=', 'role_users.user_id')
        ->join('roles', 'role_users.role_id', '=', 'roles.id')
        ->where('users.id', '=', $request->cliente_id)
        ->first();


        Mail::to($user->email)->send(new \App\Mail\UserAprobado($user->first_name, $user->last_name));

        Mail::to('crearemosweb@gmail.com')->send(new \App\Mail\UserAprobado($user->first_name, $user->last_name));

        $view= View::make('admin.clientes.trcliente', compact('clientes'));

        $data=$view->render();

        return $data;

        }
          
    }

     public function adddir($id)
    {

        $configuracion=AlpConfiguracion::where('id', 1)->first();

        $states=State::where('config_states.country_id', '47')->get();

        $t_documento = AlpTDocumento::where('estado_registro','=',1)->get();

        $estructura = AlpEstructuraAddress::where('estado_registro','=',1)->get();

        $cities = array();


        $cliente = AlpClientes::where('id_user_client', $id )->first();

        $user = User::where('id', $id )->first();

        $countries = Country::all();

         return view('admin.clientes.adddir', compact( 'cliente', 'user', 'countries', 'states', 't_documento', 'estructura', 'cities', 'configuracion'));
       
    }


    public function editdir($id)
    {


        $configuracion=AlpConfiguracion::where('id', 1)->first();

        $direccion=AlpDirecciones::where('id', $id)->first();

        $ciudad = City::where('id', $direccion->city_id)->first();

        $cities = City::where('state_id', $ciudad->state_id)->get();

        $states=State::where('config_states.country_id', '47')->get();

        $t_documento = AlpTDocumento::where('estado_registro','=',1)->get();

        $estructura = AlpEstructuraAddress::where('estado_registro','=',1)->get();

        

        $cliente = AlpClientes::where('id_user_client', $direccion->id_client)->first();

        $user = User::where('id', $id )->first();

        $countries = Country::all();

         return view('admin.clientes.editdir', compact( 'cliente', 'user', 'countries', 'states', 't_documento', 'estructura', 'cities', 'configuracion', 'ciudad', 'direccion'));
       
    }


     public function storedir(DireccionRequest $request)
    {

         $input = $request->all();




        $user_id = $input['id_user'];



        $input['id_client']=$input['id_user'];
        $input['default_address']=1;
               
         
        $direccion=AlpDirecciones::create($input);

        if (isset($direccion->id)) {

          DB::table('alp_direcciones')->where('id_client', $user_id)->update(['default_address'=>0]);
          DB::table('alp_direcciones')->where('id', $direccion->id)->update(['default_address'=>1]);
          
        }

        $direcciones = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
          ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
          ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
          ->where('alp_direcciones.id_client', $user_id)->get();


        if ($direccion->id) {


             return redirect('admin/clientes/'.$user_id.'/direcciones')->withInput()->with('sucess', trans('Se ha guardado la direccion correctamente'));

        
        }else{

            return redirect('admin/clientes/'.$user_id.'/direcciones')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));

        }
 

    }


     public function upddir(DireccionRequest $request)
    {

         $input = $request->all();


         $direccion=AlpDirecciones::where('id', $input['id_address'])->first();


         $direccion->update($input);
               
        if (isset($direccion->id)) {

          DB::table('alp_direcciones')->where('id_client', $direccion->id_client)->update(['default_address'=>0]);
          DB::table('alp_direcciones')->where('id', $direccion->id)->update(['default_address'=>1]);
          
        }

        $direcciones = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
          ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
          ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
          ->where('alp_direcciones.id_client', $direccion->id_client)->get();


        if ($direccion->id) {


             return redirect('admin/clientes/'.$direccion->id_client.'/direcciones')->withInput()->with('sucess', trans('Se ha Editado la direccion correctamente'));

        
        }else{

            return redirect('admin/clientes/'.$direccion->id_client.'/direcciones')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));

        }
 

    }


      public function setdir( $id)
    {


         $direccion=AlpDirecciones::where('id', $id)->first();


               
        if (isset($direccion->id)) {

          DB::table('alp_direcciones')->where('id_client', $direccion->id_client)->update(['default_address'=>0]);

          DB::table('alp_direcciones')->where('id', $direccion->id)->update(['default_address'=>1]);
          
        }

        $direcciones = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
          ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
          ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
          ->where('alp_direcciones.id_client', $direccion->id_client)->get();


        if ($direccion->id) {


             return redirect('admin/clientes/'.$direccion->id_client.'/direcciones')->withInput()->with('sucess', trans('Se ha Editado la direccion correctamente'));

        
        }else{

            return redirect('admin/clientes/'.$direccion->id_client.'/direcciones')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));

        }

    }




     public function saldo()
    {
        // Grab all the groups


        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        
                        ->log('clientes/index ');

        }else{

          activity()->log('clientes/index');


        }


         if (!Sentinel::getUser()->hasAnyAccess(['clientes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }



      
        $clientes =  User::select('users.*','roles.name as name_role','alp_clientes.estado_masterfile as estado_masterfile','alp_clientes.estado_registro as estado_registro','alp_clientes.telefono_cliente as telefono_cliente','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.cod_alpinista as cod_alpinista')
        ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
        ->join('alp_saldo', 'users.id', '=', 'alp_saldo.id_cliente')
        ->join('role_users', 'users.id', '=', 'role_users.user_id')
        ->join('roles', 'role_users.role_id', '=', 'roles.id')
        ->where('role_users.role_id', '<>', 1)->get();

      //  dd($clientes);


        // Show the page
        return view('admin.clientes.saldo', compact('clientes'));
    }    



     public function datasaldo()
    {
        //$users = User::get(['id', 'first_name', 'last_name', 'email','created_at']);

         if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

            $role=RoleUser::where('user_id', $user_id)->first();

            $id_rol=$role->role_id;
        }
      
        $saldo=$this->getSaldo();

        $clientes =  User::select('users.*','roles.name as name_role','alp_clientes.estado_masterfile as estado_masterfile','alp_clientes.estado_registro as estado_registro','alp_clientes.telefono_cliente as telefono_cliente','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.cod_alpinista as cod_alpinista','alp_clientes.codigo_cliente as codigo_cliente')
        ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
        ->join('alp_saldo', 'users.id', '=', 'alp_saldo.id_cliente')
        ->join('role_users', 'users.id', '=', 'role_users.user_id')
        ->join('roles', 'role_users.role_id', '=', 'roles.id')
        ->where('alp_clientes.origen', '=', '0')
        ->where('role_users.role_id', '<>', 1)
        ->groupBy('users.id')
        ->get();

            $data = array();


          foreach($clientes as $cliente){

             if($cliente->estado_registro == 1){

                $estado= "<span class='label label-sm label-success'>Activo</span>";

              }else{

                $estado= "<span class='label label-sm label-warning'>Inactivo</span>";

              }


              if($cliente->estado_masterfile == 1){

                $masterfile= "<span class='label label-sm label-success'>Activo</span>";

              }else{

                $masterfile= "<span class='label label-sm label-warning'>Inactivo</span>";

              }

              if ($id_rol=='13') {

                 $actions = " 

                 <a href='".secure_url("admin/clientes/".$cliente->id."/detalle" )."'>
                    <i class='fa fa-eye' title='Detalles ' alt='Detalles' ></i>

                 </a>

                 <a href='".secure_url("admin/clientes/".$cliente->id."/direcciones" )."'>

                     <i class='livicon' data-name='location' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='view alpProductos'></i>
                 </a> ";


                # code...
              }else{


                 $actions = " 

                 <a href='".secure_url("admin/clientes/".$cliente->id."/detalle" )."'>
                    <i class='fa fa-eye' title='Detalles ' alt='Detalles' ></i>

                 </a>";

              }

              if (isset($saldo[$cliente->id])) {

                $disponible=$saldo[$cliente->id];

              }else{

                $disponbie=0;


              }


               $data[]= array(
                 $cliente->id, 
                 $cliente->cod_oracle_cliente, 
                 $cliente->first_name.' '.$cliente->last_name, 
                 $cliente->email, 
                 $cliente->telefono_cliente, 
                 $cliente->name_role, 
                 $disponible, 
                 $cliente->codigo_cliente, 
                 $estado, 
                 date("d/m/Y H:i:s", strtotime($cliente->created_at)),
                 $actions
              );



          }


          return json_encode( array('data' => $data ));

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
                
                $inv[$row->id_cliente]= $inv[$row->id_cliente]-$row->cantidad_total;

            }

            return $inv;
      
    }





     public function cargar()
    {

       if (!Sentinel::getUser()->hasAnyAccess(['clientes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }



        // Get all the available groups
        $groups = Sentinel::getRoleRepository()->all();

        $groups = DB::table('roles')->whereIn('roles.id', [1,2,3,4,5,6,7,8,13])->get();

        $countries = $this->countries;

         $almacenes=AlpAlmacenes::where('tipo_almacen', '1')->get();


        // Show the page
        return view('admin.clientes.cargar', compact('groups', 'countries', 'almacenes'));
    }

    /**
     * User create form processing.
     *
     * @return Redirect
     */
    public function import(Request $request)
    {

        $input=$request->all();

        //dd($input);

         $archivo = $request->file('file_alpinistas');

         \Session::put('importalmacen', $request->id_almacen);

        Excel::import(new BucaramangaImport, $archivo);
        
        return redirect('admin/clientes')->with('success', 'Clientes Cargados Exitosamente');

    }


    public function cargarsaldo()
    {

       if (!Sentinel::getUser()->hasAnyAccess(['clientes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }



        // Get all the available groups
        $groups = Sentinel::getRoleRepository()->all();

        $groups = DB::table('roles')->whereIn('roles.id', [1,2,3,4,5,6,7,8,13])->get();

        $countries = $this->countries;
        // Show the page
        return view('admin.clientes.cargarsaldo', compact('groups', 'countries'));
    }

    /**
     * User create form processing.
     *
     * @return Redirect
     */
    public function importsaldo(Request $request)
    {

        $input=$request->all();

        \Session::put('fecha_vencimiento', $request->fecha_vencimiento);

         $archivo = $request->file('file_alpinistas');

        Excel::import(new SaldoImport, $archivo);
        
        return redirect('admin/clientes/saldo')->with('success', 'Clientes Cargados Exitosamente');

    }



   


    public function abono($id)
    {
       
        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])
                        ->log('clientes/edit ');

        }else{

          activity()->withProperties(['id'=>$id])->log('clientes/edit');

        }


        $cliente = DB::table('alp_clientes')
        //->leftJoin('users', 'alp_clientes.id_embajador', '=', 'users.id')
        ->where('alp_clientes.id_user_client', '=', $id)->first();

        $disponible = AlpAbonosDisponible::groupBy('alp_abono_disponible.id_cliente')
              ->select("alp_abono_disponible.*", DB::raw(  "SUM(alp_abono_disponible.valor_abono) as total"))
              ->where('alp_abono_disponible.id_cliente', $id)
              ->first();

        $history=AlpAbonosDisponible::select('alp_abono_disponible.*', 'users.first_name as first_name', 'users.last_name as last_name')->join('users', 'alp_abono_disponible.id_cliente', '=', 'users.id')->where('id_cliente', $id)->get();
        
        foreach($history as $h){

          $h->json=json_decode($h->json);
       }


        $user = User::findOrFail($id);

        return view('admin.clientes.abono', compact('cliente','user', 'disponible', 'history','id'));
    }



    public function postabono(Request $request)
    {
       
        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('clientes/postabono ');

        }else{

          activity()->log('clientes/postabono');

        }


        $a=AlpAbonos::where('codigo_abono', '=', $request->codigo_abono)->where('estado_registro', '1')->first();

        if (isset($a->id)) {


          $data_abono = array(
            'id_abono'=>$a->id,
            'id_cliente'=>$request->id_cliente,
            'operacion'=>1,
            'codigo_abono'=>$a->codigo_abono,
            'valor_abono'=>$a->valor_abono,
            'fecha_final'=>$a->fecha_final,
            'origen'=>'Administrador',
            'token'=>$a->token,
            'json'=>json_encode($a),
            'id_user'=>$user->id
          );

          AlpAbonosDisponible::create($data_abono);

          $data_user = array(
            'id_abono' => $a->id, 
            'id_cliente'=>$request->id_cliente,
            'id_user'=>$user->id
          );

          AlpAbonosUser::create($data_user);

          $a->update(['estado_registro'=>0]);


          # code...
        }


        $cliente = DB::table('alp_clientes')
        //->leftJoin('users', 'alp_clientes.id_embajador', '=', 'users.id')
        ->where('alp_clientes.id_user_client', '=', $request->id_cliente)->first();

        $disponible = AlpAbonosDisponible::groupBy('alp_abono_disponible.id_cliente')
              ->select("alp_abono_disponible.*", DB::raw(  "SUM(alp_abono_disponible.valor_abono) as total"))
              ->where('alp_abono_disponible.id_cliente', $request->id_cliente)
              ->first();

        $history=AlpAbonosDisponible::select('alp_abono_disponible.*', 'users.first_name as first_name', 'users.last_name as last_name')->join('users', 'alp_abono_disponible.id_cliente', '=', 'users.id')->where('id_cliente', $request->id_cliente)->get();
        
        $user = User::findOrFail($request->id_cliente);

        return view('admin.clientes.abono', compact('cliente','user', 'disponible', 'history'));
    }




}