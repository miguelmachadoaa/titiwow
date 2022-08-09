<?php

namespace App\Http\Controllers;

use Activation;

use App\Http\Requests\FrontendRequest;

use App\Http\Requests\PasswordResetRequest;

use App\Http\Requests\UserRequest;

use App\Http\Requests\PqrRequest;

use App\Mail\Contact;

use App\Mail\ContactUser;

use App\Mail\ForgotPassword;

use App\Mail\Register;

use App\Models\AlpClientes;

use App\Models\AlpClientesEmbajador;

use App\Models\AlpCategorias;

use App\Models\AlpEmpresas;

use App\Models\AlpPrecioGrupo;

use App\Models\AlpProductos;

use App\Models\AlpTDocumento;

use App\Models\AlpSliders;

use App\Models\AlpEstructuraAddress;

use App\Models\AlpConfiguracion;

use App\Models\AlpDirecciones;

use App\Models\AlpCodAlpinistas;

use App\Models\AlpInventario;

use App\Models\AlpOrdenesDescuento;

use App\Models\AlpCombosProductos;

use App\Models\AlpClientesHistory;

use App\Models\AlpAlmacenes;

use App\Models\AlpAlmacenRol;

use App\Models\AlpAlmacenProducto;

use App\Models\AlpAlmacenDespacho;

use App\Models\AlpRolenvio;

use App\Models\AlpDetalles;

use App\Models\AlpOrdenes;

use App\Models\AlpEnvios;

use App\Models\AlpEnviosEstatus;

use App\Models\AlpEnviosHistory;

use App\Models\AlpXml;
use App\Models\AlpTicket;
use App\Models\AlpTicketHistory;
use App\Models\AlpDepartamento;


use App\User;

use App\State;

use App\City;

use App\Barrio;

use App\RoleUser;

use App\Roles;

use App\Models\AlpMenuDetalle;

use Cartalyst\Sentinel\Checkpoints\NotActivatedException;

use Cartalyst\Sentinel\Checkpoints\ThrottlingException;

use File;

use Hash;

use Illuminate\Http\Request;

use Mail;

use Redirect;

use Reminder;

use Sentinel;

use URL;

use Validator;

use View;

use DB; 

use Exception;

use Carbon\Carbon;

use Intervention\Image\Facades\Image;




class PosController extends JoshController
{

 

  public function dashboard(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('FrontEndController/dashboard ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('FrontEndController/dashboard');
        }


    return view('pos.dashboard');

  }


  public function opciones(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('FrontEndController/opciones ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('FrontEndController/opciones');
        }


    return view('pos.opciones');

  }


  public function carritos(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('FrontEndController/carritos ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('FrontEndController/carritos');
        }


    return view('pos.carritos');

  }


    public function pedidos(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('FrontEndController/pedidos ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('FrontEndController/pedidos');
        }


    return view('pos.pedidos');

  }


     public function transacciones(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('FrontEndController/transacciones ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('FrontEndController/transacciones');
        }


    return view('pos.transacciones');

  }



     public function reportes(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('FrontEndController/reportes ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('FrontEndController/reportes');
        }


    return view('pos.reportes');

  }


     public function categorias(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('FrontEndController/categorias ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('FrontEndController/categorias');
        }


    return view('pos.categorias');

  }

     public function productos(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('FrontEndController/productos ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('FrontEndController/productos');
        }


    return view('pos.productos');

  }

     public function clientes(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('FrontEndController/clientes ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('FrontEndController/clientes');
        }

        $clientes =  User::select('users.*','roles.name as name_role','alp_clientes.estado_masterfile as estado_masterfile','alp_clientes.estado_registro as estado_registro','alp_clientes.telefono_cliente as telefono_cliente','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.cod_alpinista as cod_alpinista','alp_clientes.doc_cliente as doc_cliente','alp_clientes.genero_cliente as genero_cliente')
        ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
        ->join('role_users', 'users.id', '=', 'role_users.user_id')
        ->join('roles', 'role_users.role_id', '=', 'roles.id')
        ->where('role_users.role_id', '<>', 1)
        ->get();


    return view('pos.clientes', compact('clientes'));

  }



}

    