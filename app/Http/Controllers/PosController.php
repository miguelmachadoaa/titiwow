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
use App\Models\AlpImpuestos;

use App\Models\AlpXml;
use App\Models\AlpTicket;
use App\Models\AlpTicketHistory;
use App\Models\AlpDepartamento;
use App\Models\AlpCarrito;
use App\Models\AlpCarritoDetalle;
use App\Models\AlpFormaspago;
use App\Models\AlpPagos;
use App\Models\AlpCajas;

use App\Models\AlpTransacciones;
use App\Models\AlpBancos;


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

use \Cache;

use Illuminate\Support\Facades\Log;


class PosController extends JoshController
{

    
    public function __construct()
    {

        parent::__construct();

        
        if (!\Session::has('cart')) {

          $inv=$this->inventario();

          \Session::put('cart', ['inventario'=>$inv,'productos'=>[],'pagos'=>[], 'total'=>0, 'base'=>0, 'impuesto'=>0, 'referencia'=>time()]);

        }


    }

  public function dashboard(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/dashboard ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/dashboard');
        }


        if (isset($user->id)) {

          $caja=AlpCajas::where('id_user', $user->id)->where('estado_registro', '1')->first();

          $view= View::make('pos.dashboard', compact('caja'));

          $data=$view->render();

          $res = array('status' => 'dashboard', 'error'=>'0', 'mensaje'=>'', 'data'=>$data );

          return json_encode($res);


        }else{


            $res = array('status' => 'login', 'error'=>'1', 'mensaje'=>'Usuario no logueado', 'data'=>null );

            return json_encode($res);


        }

      

  }


  public function opciones(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/opciones ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/opciones');
        }

        if (isset($user->id)) {

          $caja=AlpCajas::where('id_user', $user->id)->where('estado_registro', '1')->first();

          $view= View::make('pos.opciones', compact('caja'));

          $data=$view->render();

          $res = array('status' => 'dashboard', 'error'=>'0', 'mensaje'=>'', 'data'=>$data );

          return json_encode($res);


        }else{


            $res = array('status' => 'login', 'error'=>'1', 'mensaje'=>'Usuario no logueado', 'data'=>null );

            return json_encode($res);


        }

    

  }


  public function carritos(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/carritos ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/carritos');
        }


        if (isset($user->id)) {

          $caja=AlpCajas::where('id_user', $user->id)->where('estado_registro', '1')->first();

          $carritos=AlpCarrito::with('detalles')->get();

          $view= View::make('pos.carritos', compact('carritos', 'caja'));

          $data=$view->render();

          $res = array('status' => 'dashboard', 'error'=>'0', 'mensaje'=>'', 'data'=>$data );

          return json_encode($res);

        }else{


            $res = array('status' => 'login', 'error'=>'1', 'mensaje'=>'Usuario no logueado', 'data'=>null );

            return json_encode($res);


        }


  }


   public function delcarrito(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/delcarrito ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/delcarrito');
        }

        AlpCarrito::where('id', $request->id)->delete();

        $carritos=AlpCarrito::with('detalles')->get();


    return view('pos.carritos', compact('carritos'));

  }



    public function caja(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/caja ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/caja');
        }


       if (isset($user->id)) {

          $caja=AlpCajas::where('id_user', $user->id)->where('estado_registro', '1')->first();

              $cajas = AlpCajas::where('id_user', $user->id)->with('cajero')->orderBy('id', 'desc')->get();


              $view= View::make('pos.caja', compact('caja','cajas'));

              $data=$view->render();

              $res = array('status' => 'dashboard', 'error'=>'0', 'mensaje'=>'', 'data'=>$data );

              return json_encode($res);
              
            

        }else{

            $res = array('status' => 'login', 'error'=>'1', 'mensaje'=>'Usuario no logueado', 'data'=>null );

            return json_encode($res);

        }

  }




  public function addcaja(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/addcaja ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/addcaja');
        }

      
         $caja=AlpCajas::where('id_user', $user->id)->where('estado_registro', '1')->first();

          $view= View::make('pos.addcaja', compact('caja'));


          $data=$view->render();

          $res = array('status' => 'dashboard', 'error'=>'0', 'mensaje'=>'', 'data'=>$data );

          return json_encode($res);


  }


   public function postcaja(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/caja ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/caja');
        }

        AlpCajas::create([
          'monto_inicial'=>$request->baseinicial,
          'fecha_inicio'=>now(),
          'id_user'=>$user->id
        ]);



        $cajas = AlpCajas::where('id_user', $user->id)->with('cajero')->orderBy('id', 'desc')->get();



    return view('pos.caja', compact('cajas'));

  }


  public function updatecaja(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/caja ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/caja');
        }

        $caja=AlpCajas::where('id', $request->id)->first();



        $caja->update([
          'monto_final'=>$request->basefinal,
          'observaciones'=>$request->observacion,
          'fecha_cierre'=>now(),
          'estado_registro'=>'2',
          'id_user'=>$user->id
        ]);



        $cajas = AlpCajas::where('id_user', $user->id)->with('cajero')->orderBy('id', 'desc')->get();



    return view('pos.caja', compact('cajas'));

  }


     public function detallecaja(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/reportes ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/reportes');
        }

        $caja = AlpCajas::where('id', $request->id)->with('cajero')->first();

        $pagos=AlpPagos::select('alp_ordenes_pagos.*',  DB::raw('SUM(monto_pago) as total_pagos'))
        ->join('alp_ordenes', 'alp_ordenes_pagos.id_orden', '=', 'alp_ordenes.id')
        ->where('alp_ordenes_pagos.id_user', $user->id)
        ->where('alp_ordenes.id_caja', '=', $request->id)
        ->with('formapago')->groupBy('id_forma_pago')
        ->get();



    return view('pos.detallecaja', compact('pagos', 'caja'));

  }


   public function cerrarcaja(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/reportes ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/reportes');
        }

        $caja = AlpCajas::where('id', $request->id)->with('cajero')->first();


    return view('pos.cerrarcaja', compact( 'caja'));

  }



    public function pedidos(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/pedidos ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/pedidos');
        }



         if (isset($user->id)) {

          $caja=AlpCajas::where('id_user', $user->id)->where('estado_registro', '1')->first();

           if (isset($caja->id)) {

              $ordenes = AlpOrdenes::where('id_user', $user->id)->where('id_caja', $caja->id)->with('cliente', 'cajero', 'estado')->orderBy('id', 'desc')->get();

              $view= View::make('pos.pedidos', compact('ordenes', 'caja'));

              $data=$view->render();

              $res = array('status' => 'dashboard', 'error'=>'0', 'mensaje'=>'', 'data'=>$data );

              return json_encode($res);

              // code...
            }else{

              $view= View::make('pos.dashboard', compact('caja'));

              $data=$view->render();

              $res = array('status' => 'dashboard', 'error'=>'0', 'mensaje'=>'', 'data'=>$data );

              return json_encode($res);
            }

          
        }else{


            $res = array('status' => 'login', 'error'=>'1', 'mensaje'=>'Usuario no logueado', 'data'=>null );

            return json_encode($res);


        }

        




  }


    public function buscarpedido(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/pedidos ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/pedidos');
        }

        $keyword=$request->termino;



    return view('pos.pedidos', compact('ordenes'));

  }


   public function buscarcliente(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/pedidos ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/pedidos');
        }

        
        $clientes =  User::select('users.*','roles.name as name_role',
          'alp_clientes.telefono_cliente as telefono_cliente',
          'alp_clientes.doc_cliente as doc_cliente',
          'alp_clientes.genero_cliente as genero_cliente'
        )
        ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
        ->join('role_users', 'users.id', '=', 'role_users.user_id')
        ->join('roles', 'role_users.role_id', '=', 'roles.id')
        ->where('role_users.role_id', '<>', 1)
        ->where('users.first_name', 'like', '%'.$request->termino.'%')
        ->orWhere('users.last_name', 'like', '%'.$request->termino.'%')
        ->orWhere('users.email', 'like', '%'.$request->termino.'%')
        ->orWhere('users.id', 'like', '%'.$request->termino.'%')
        ->orWhere('alp_clientes.telefono_cliente', 'like', '%'.$request->termino.'%')
        ->orWhere('alp_clientes.doc_cliente', 'like', '%'.$request->termino.'%')
        ->get();


    return view('pos.clientes', compact('clientes'));

  }





    public function detalleorden(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/pedidos ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/pedidos');
        }



        $orden = AlpOrdenes::where('id_user', $user->id)->where('id', $request->id)->with('cliente', 'cajero', 'estado', 'detalles', 'pagos')->first();

        foreach($orden->detalles as $d){

          $p=AlpProductos::where('id', $d->id_producto)->first();

          $d->producto=$p;
        }

        foreach($orden->pagos as $pago){

          $f=AlpFormaspago::where('id', $pago->id_forma_pago)->first();

          $pago->formapago=$f;
        }

        #dd(json_encode($orden));

    return view('pos.detallepedido', compact('orden'));

  }


     public function transacciones(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/transacciones ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/transacciones');
        }


        if (isset($user->id)) {


           $caja=AlpCajas::where('id_user', $user->id)->where('estado_registro', '1')->first();

            if(isset($caja->id)){

              $pagos=AlpPagos::join('alp_ordenes', 'alp_ordenes_pagos.id_orden', '=', 'alp_ordenes.id')
              ->where('alp_ordenes.id_caja', $caja->id)
              ->where('alp_ordenes_pagos.id_user', $user->id)->with('formapago')->get();


               $view= View::make('pos.transacciones', compact('caja', 'pagos'));

              $data=$view->render();

              $res = array('status' => 'dashboard', 'error'=>'0', 'mensaje'=>'', 'data'=>$data );

              return json_encode($res);

            }else{

              $view= View::make('pos.dashboard', compact('caja'));

              $data=$view->render();

              $res = array('status' => 'dashboard', 'error'=>'0', 'mensaje'=>'', 'data'=>$data );

              return json_encode($res);

            }

        }else{


            $res = array('status' => 'login', 'error'=>'1', 'mensaje'=>'Usuario no logueado', 'data'=>null );

            return json_encode($res);


        }




  }



     public function reportes(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/reportes ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/reportes');
        }

      

       


        if (isset($user->id)) {

          $caja=AlpCajas::where('id_user', $user->id)->where('estado_registro', '1')->first();



           if(isset($caja->id)){

            $pagos=AlpPagos::select('alp_ordenes_pagos.*',  DB::raw('SUM(monto_pago) as total_pagos'))
            ->join('alp_ordenes', 'alp_ordenes_pagos.id_orden', '=', 'alp_ordenes.id')
              ->where('alp_ordenes.id_caja', $caja->id)
              ->with('formapago')
              ->groupBy('alp_ordenes_pagos.id_forma_pago')
              ->get();


              $view= View::make('pos.reportes', compact('caja', 'pagos'));

              $data=$view->render();

              $res = array('status' => 'dashboard', 'error'=>'0', 'mensaje'=>'', 'data'=>$data );

              return json_encode($res);


            }else{

                $caja=AlpCajas::where('id_user', $user->id)->where('estado_registro', '1')->first();

                $view= View::make('pos.dashboard', compact('caja'));

                $data=$view->render();

                $res = array('status' => 'dashboard', 'error'=>'0', 'mensaje'=>'', 'data'=>$data );

                return json_encode($res);

            }


        }else{


            $res = array('status' => 'login', 'error'=>'1', 'mensaje'=>'Usuario no logueado', 'data'=>null );

            return json_encode($res);

        }


  }


     public function categorias(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/categorias ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/categorias');
        }


       

      if (isset($user->id)) {

          $categorias = AlpCategorias::get();

          $caja=AlpCajas::where('id_user', $user->id)->where('estado_registro', '1')->first();

          $view= View::make('pos.categorias', compact('caja', 'categorias'));

          $data=$view->render();

          $res = array('status' => 'dashboard', 'error'=>'0', 'mensaje'=>'', 'data'=>$data );

          return json_encode($res);


        }else{


            $res = array('status' => 'login', 'error'=>'1', 'mensaje'=>'Usuario no logueado', 'data'=>null );

            return json_encode($res);


        }



  }

   public function detallecategoria(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/productos ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/productos');
        }
        $caja=AlpCajas::where('id_user', $user->id)->where('estado_registro', '1')->first();

         if (isset($caja->id)) {

          $productos=AlpProductos::where('estado_registro', '1')->where('id_categoria_default', $request->id)->get();


          return view('pos.detallecategoria', compact('productos'));

          // code...
        }else{

           $caja=AlpCajas::where('id_user', $user->id)->where('estado_registro', '1')->first();

            return view('pos.dashboard', compact('caja'));
        }


          

  }

  

  public function clientes(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/clientes ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/clientes');
        }



        if (isset($user->id)) {

          $caja=AlpCajas::where('id_user', $user->id)->where('estado_registro', '1')->first();

           if (isset($caja->id)) {

             $clientes =  User::select('users.*','roles.name as name_role',
                'alp_clientes.telefono_cliente as telefono_cliente',
                'alp_clientes.doc_cliente as doc_cliente',
                'alp_clientes.genero_cliente as genero_cliente'
              )
              ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
              ->join('role_users', 'users.id', '=', 'role_users.user_id')
              ->join('roles', 'role_users.role_id', '=', 'roles.id')
              ->where('role_users.role_id', '<>', 1)
              ->orderBy('alp_clientes.id', 'desc')
              ->get();


              $view= View::make('pos.clientes', compact('caja','clientes'));

              $data=$view->render();

              $res = array('status' => 'dashboard', 'error'=>'0', 'mensaje'=>'', 'data'=>$data );

              return json_encode($res);
              
            }else{

                $view= View::make('pos.dashboard', compact('caja'));

                $data=$view->render();

                $res = array('status' => 'dashboard', 'error'=>'0', 'mensaje'=>'', 'data'=>$data );

                return json_encode($res);
            }

        }else{

            $res = array('status' => 'login', 'error'=>'1', 'mensaje'=>'Usuario no logueado', 'data'=>null );

            return json_encode($res);

        }

  }

   public function asignacliente(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/clientes ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/clientes');
        }

        $cliente =  User::select('users.*','roles.name as name_role',
          'alp_clientes.telefono_cliente as telefono_cliente',
          'alp_clientes.doc_cliente as doc_cliente',
          'alp_clientes.genero_cliente as genero_cliente'
        )
        ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
        ->join('role_users', 'users.id', '=', 'role_users.user_id')
        ->join('roles', 'role_users.role_id', '=', 'roles.id')
        ->where('users.id', '=', $request->id)
        ->first();


        if(isset($cliente->id)){

             $cart= \Session::get('cart');

             $cart['cliente']=$cliente;

             \Session::put('cart', $cart);

        }


    return view('pos.barcode', compact('cart'));

  }


  public function removecliente(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/clientes ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/clientes');
        }


       $cart= \Session::get('cart');

       $cart['cliente']=null;

       \Session::put('cart', $cart);


      return view('pos.barcode', compact('cart'));

  }




  public function addproducto(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/addproducto ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/addproducto');
        }

       $categorias=AlpCategorias::where('estado_registro', '1')->get();
       $impuestos=AlpImpuestos::where('estado_registro', '1')->get();


    return view('pos.addproducto', compact( 'categorias', 'impuestos'));

  }


   public function addcliente(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/addcliente ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/addcliente');
        }


        if (isset($user->id)) {

          $caja=AlpCajas::where('id_user', $user->id)->where('estado_registro', '1')->first();

           if (isset($caja->id)) {

            $categorias=AlpCategorias::where('estado_registro', '1')->get();

            $impuestos=AlpImpuestos::where('estado_registro', '1')->get();
      
              $view= View::make('pos.addcliente', compact( 'categorias', 'impuestos'));

              $data=$view->render();

              $res = array('status' => 'dashboard', 'error'=>'0', 'mensaje'=>'', 'data'=>$data );

              return json_encode($res);

              // code...
            }else{

              $view= View::make('pos.dashboard', compact('caja'));

              $data=$view->render();

              $res = array('status' => 'dashboard', 'error'=>'0', 'mensaje'=>'No se encontro caja', 'data'=>$data );

              return json_encode($res);
            }

          
        }else{


            $res = array('status' => 'login', 'error'=>'1', 'mensaje'=>'Usuario no logueado', 'data'=>null );

            return json_encode($res);


        }

      

  }


    public function postcliente(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/addcliente ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/addcliente');
        }

        if(empty($request->email_cliente)){
          $email = time().'@titiwow.com';
        }else{
          $email = $request->email_cliente;
        }

        $usuario=User::create([
          'first_name'=>$request->nombre_cliente,
          'email'=>$email,
          'password'=>md5($request->nombre_cliente),
       ]);


        $cliente=AlpClientes::create(
          [
            'id_user_client'=>$usuario->id,
            'id_type_doc'=>'1',
            'doc_cliente'=>$request->cedula_cliente,
            'telefono_cliente'=>$request->telefono_cliente,
            'genero_cliente'=>'1',
            'id_user'=>$user->id
          ]
        );


          $role = Sentinel::findRoleById(10);

          $role->users()->attach($usuario);

          $activation = Activation::exists($usuario);

           $clientes =  User::select('users.*','roles.name as name_role','alp_clientes.estado_masterfile as estado_masterfile','alp_clientes.estado_registro as estado_registro','alp_clientes.telefono_cliente as telefono_cliente','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.cod_alpinista as cod_alpinista','alp_clientes.doc_cliente as doc_cliente','alp_clientes.genero_cliente as genero_cliente')
        ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
        ->join('role_users', 'users.id', '=', 'role_users.user_id')
        ->join('roles', 'role_users.role_id', '=', 'roles.id')
        ->where('role_users.role_id', '<>', 1)
        ->orderBy('alp_clientes.id', 'desc')
        ->get();


      return view('pos.clientes', compact('clientes'));
    

  }



  public function postaddproducto(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/postaddproducto ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/postaddproducto');
        }

       

       $producto=AlpProductos::create([
          'nombre_producto'=>$request->nombre_producto,
          'id_categoria_default'=>$request->id_categoria,
          'id_impuesto'=>$request->id_impuesto,
          'precio_base'=>$request->precio,
          'slug'=>str_slug($request->nombre_producto),
       ]);

      // dd($producto);


    return view('pos.addproducto', compact( 'categorias', 'impuestos'));

  }


    public function productos(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/productos ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/productos');
        }
          
        $cart= \Session::get('cart');

        if (isset($cart['inventario'])) {
          // code...
        }else{

          $cart['inventario']=$this->inventario();

          \Session::put('cart', $cart);
        }

        $caja=AlpCajas::where('id_user', $user->id)->where('estado_registro', '1')->first();


        if (isset($user->id)) {

           if (isset($caja->id)) {

              $productos=AlpProductos::where('estado_registro', '1')->get();

              $view= View::make('pos.productos', compact('caja','productos', 'cart'));

              $data=$view->render();

              $res = array('status' => 'dashboard', 'error'=>'0', 'mensaje'=>'', 'data'=>$data );

              return json_encode($res);


              
            }else{

                $caja=AlpCajas::where('id_user', $user->id)->where('estado_registro', '1')->first();

                $view= View::make('pos.dashboard', compact('caja'));

                $data=$view->render();

                $res = array('status' => 'dashboard', 'error'=>'0', 'mensaje'=>'', 'data'=>$data );

                return json_encode($res);
            }


        }else{


            $res = array('status' => 'login', 'error'=>'1', 'mensaje'=>'Usuario no logueado', 'data'=>null );

            return json_encode($res);


        }

          

  }




  /**
   *
   * Method Post 
   * uri /pos/buscarproducto
   * data
   *   termino string 
   * 
   */

  public function buscarproducto(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/buscarproducto ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/buscarproducto');
        }

        $cart= \Session::get('cart');

          $caja=AlpCajas::where('id_user', $user->id)->where('estado_registro', '1')->first();

         # dd(substr($request->termino, 8));


          if(substr($request->termino,0, 4)=='1000'){

            $id_termino  = intval(substr($request->termino,1, 7));

            $peso = intval(substr($request->termino,8))/1000;

           
           $p=AlpProductos::
            #where('estado_registro', '1')
            Where('id', '=', $id_termino)
            ->first();

            if (isset($p->id)) {
            

              $cart= \Session::get('cart');


              if(isset($p->id)){

                if(isset($cart['productos'][$p->id])){

                  $p->cantidad=$cart['productos'][$p->id]->cantidad+$peso;

                  $cart['productos'][$p->id]=$p;

                }else{

                  $p->cantidad=$peso;

                  $cart['productos'][$p->id]=$p;

                }

              }else{

                  $error="No se encontro producto";


                  $res = array('status' => 'error', 'error'=>'1', 'mensaje'=>$error, 'data'=>null );

                  return json_encode($res);

              }

              $cart=$this->calculoCart($cart);

              \Session::put('cart', $cart);



              $view= View::make('pos.ordenactual', compact('cart'));

              $data=$view->render();

              $res = array('status' => 'carrito', 'error'=>'0', 'mensaje'=>'Produco agregado al carrito', 'data'=>$data );

              return json_encode($res);









            }else{

                $productos=AlpProductos::
                #where('estado_registro', '1')
                Where('nombre_producto', 'like', '%'.$request->termino.'%')
                ->orWhere('referencia_producto', 'like', '%'.$request->termino.'%')
                ->orWhere('precio_base', 'like', '%'.$request->termino.'%')
                ->get();



                $view= View::make('pos.productos', compact('productos', 'cart', 'caja'));

                $data=$view->render();

                $res = array('status' => 'productos', 'error'=>'0', 'mensaje'=>'productos encontrados', 'data'=>$data );

                return json_encode($res);
            }
           


          }else{


            //no es generado por la balanza 
            //
            

            $productos=AlpProductos::
            #where('estado_registro', '1')
            Where('referencia_producto', 'like', $request->termino)
            ->get();


            if(count($productos)=='1'){

              $p=AlpProductos::
            #where('estado_registro', '1')
            Where('nombre_producto', 'like', '%'.$request->termino.'%')
            ->orWhere('referencia_producto', 'like', '%'.$request->termino.'%')
            ->orWhere('precio_base', 'like', '%'.$request->termino.'%')
            ->first();


              $cart= \Session::get('cart');


              if(isset($p->id)){

                if(isset($cart['productos'][$p->id])){

                  $p->cantidad=$cart['productos'][$p->id]->cantidad+1;

                  $cart['productos'][$p->id]=$p;

                }else{

                  $p->cantidad=1;

                  $cart['productos'][$p->id]=$p;

                }

              }else{

                  $error="No se encontro producto";


                  $res = array('status' => 'error', 'error'=>'1', 'mensaje'=>$error, 'data'=>null );

                  return json_encode($res);

              }

              $cart=$this->calculoCart($cart);

              \Session::put('cart', $cart);



              $view= View::make('pos.ordenactual', compact('cart'));

              $data=$view->render();

              $res = array('status' => 'carrito', 'error'=>'0', 'mensaje'=>'Produco agregado al carrito', 'data'=>$data );

              return json_encode($res);


            }else{



              $productos=AlpProductos::
              #where('estado_registro', '1')
              Where('nombre_producto', 'like', '%'.$request->termino.'%')
              ->orWhere('referencia_producto', 'like', '%'.$request->termino.'%')
              ->orWhere('precio_base', 'like', '%'.$request->termino.'%')
              ->get();



              $view= View::make('pos.productos', compact('productos', 'cart', 'caja'));

              $data=$view->render();

              $res = array('status' => 'productos', 'error'=>'0', 'mensaje'=>'productos encontrados', 'data'=>$data );

              return json_encode($res);

            }




          }



          


  }



  


    public function addtocart(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/addtocart ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/addtocart');
        }


          $cart= \Session::get('cart');

          Log::info('cart  '.json_encode($cart));

           if (isset($cart['inventario'])) {
          // code...
        }else{

          $cart['inventario']=$this->inventario();

          \Session::put('cart', $cart);
        }

          $p=AlpProductos::where('id', $request->id)->first();

          if(isset($p->id)){

            if (isset($cart['inventario'][$p->id])) {

            if (isset($cart['inventario'][$p->id])>0) {

               if(isset($cart['productos'][$p->id])){

                  //if($cart['productos'][$p->id]>($p->cantidad+1)){

                    $p->cantidad=$cart['productos'][$p->id]->cantidad+$request->cantidad;

                    $cart['productos'][$p->id]=$p;

                 // }

                  

                }else{

                  $p->cantidad=$request->cantidad;

                  $cart['productos'][$p->id]=$p;

                }

              }else{

                $error="Producto sin inventario";

                $res = array('status' => 'error', 'error'=>'1', 'mensaje'=>$error, 'data'=>null );

                return json_encode($res);
              }

            }else{

              $error="Producto sin inventario";

              $res = array('status' => 'error', 'error'=>'1', 'mensaje'=>$error, 'data'=>null );

              return json_encode($res);
            }

           

          }else{

              $error="No se encontro producto";

              $res = array('status' => 'error', 'error'=>'1', 'mensaje'=>$error, 'data'=>null );

              return json_encode($res);

          }

        $cart=$this->calculoCart($cart);

        \Session::put('cart', $cart);

        Log::info('cart  enviado '.json_encode($cart));


        $view= View::make('pos.ordenactual', compact('cart'));

        $data=$view->render();

        $res = array('status' => 'carrito', 'error'=>'0', 'mensaje'=>'Produco agregado al carrito', 'data'=>$data );



        return json_encode($res);

  }


   public function deltocart(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/addtocart ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/addtocart');
        }



          $cart= \Session::get('cart');

          $p=AlpProductos::where('id', $request->id)->first();


          if(isset($p->id)){

            if(isset($cart['productos'][$p->id])){


              unset($cart['productos'][$p->id]);

            }else{


            }


          }else{

              $error="No se encontro producto";

          }

        $cart=$this->calculoCart($cart);

        \Session::put('cart', $cart);

        return view('pos.ordenactual', compact('cart'));

  }



  public function vaciarcart(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/vaciarcart ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/vaciarcart');
        }


        $cart= \Session::get('cart');

        $cart['productos']=[];
        $cart['pagos']=[];

        $cart=$this->calculoCart($cart);

        \Session::put('cart', $cart);

        return view('pos.ordenactual', compact('cart'));

  }



  public function savecart(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/vaciarcart ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/vaciarcart');
        }


        $cart= \Session::get('cart');

        $carrito=AlpCarrito::create([
          'referencia'=>time(),
          'id_city'=>'1',
          'id_user'=>$user->id


        ]);


        foreach($cart['productos'] as $p){

          AlpCarritoDetalle::create([
            'id_carrito'=>$carrito->id,
            'id_producto'=>$p->id,
            'cantidad'=>$p->cantidad,
            'id_user'=>$user->id
          ]);


        }


        \Session::put('cart', ['inventario'=>$this->inventario(),'productos'=>[],'pagos'=>[], 'total'=>0, 'base'=>0, 'impuesto'=>0, 'referencia'=>time()]);

        return view('pos.ordenactual', compact('cart'));

  }


  public function setcarrito(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/vaciarcart ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/vaciarcart');
        }



          \Session::put('cart', ['inventario'=>$this->inventario(),'productos'=>[],'pagos'=>[], 'total'=>0, 'base'=>0, 'impuesto'=>0, 'referencia'=>time()]);

        $cart= \Session::get('cart');

        $detalles = AlpCarritoDetalle::where ('id_carrito', $request->id)->with('productos')->get();


        foreach($detalles as $detalle){

          $p=$detalle->productos;

          $p->cantidad=$detalle->cantidad;

          $cart['productos'][$p->id]=$p;
         

        }

        $cart=$this->calculoCart($cart);

        \Session::put('cart', $cart);


        return view('pos.ordenactual', compact('cart'));

  }


   public function pagar(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/setcarrito ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/setcarrito');
        }

       # \Session::put('cart', ['productos'=>[], 'total'=>0, 'base'=>0, 'impuesto'=>0]);
       # 
       
        $caja=AlpCajas::where('id_user', $user->id)->where('estado_registro', '1')->first();

         $cart= \Session::get('cart');

         #dd($cart);

        if(isset($caja->id)){

          $cart= \Session::get('cart');

          if (isset($cart['referencia'])) {
            // code...
          }else{
            $cart['referencia']=time();
            \Session::put('cart', $cart);

          }

          $formaspago=AlpFormaspago::where('estado_registro', '1')->get();
          $bancos=AlpBancos::where('estado_registro', '1')->get();

         
          return view('pos.pagar', compact('cart', 'formaspago', 'cart', 'bancos'));

        }else{

          $caja=AlpCajas::where('id_user', $user->id)->where('estado_registro', '1')->first();

            return view('pos.dashboard', compact('caja'));
        }



        

  }


public function addpago(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/addpago ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/addpago');
        }

        $cart= \Session::get('cart');

        $configuracion=AlpConfiguracion::first();

        $forma=AlpFormaspago::where('id', $request->id)->first();

        if ($forma->moneda == '2') {

          $cart['pagos'][]=[
            'id'=>$request->id,
            'name'=>$request->name,
            'monto'=>$request->monto,
            'moneda'=>$forma->moneda,
            'valor'=>$request->monto*$configuracion->tasa_dolar,
            'referencia'=>$request->referencia,
            'ticket'=>$request->ticket,
          ];
          
        }else{

           $cart['pagos'][]=[
            'id'=>$request->id,
            'name'=>$request->name,
            'monto'=>$request->monto,
            'moneda'=>$forma->moneda,
            'valor'=>$request->monto,
            'referencia'=>$request->referencia,
            'ticket'=>$request->ticket,
          ];


        }

         $cart=$this->calculoCart($cart);

        \Session::put('cart', $cart);

        $formaspago=AlpFormaspago::where('estado_registro', '1')->get();

         $bancos=AlpBancos::where('estado_registro', '1')->get();
       
        return view('pos.pagar', compact('cart', 'formaspago', 'bancos'));

  }


  public function delpago(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/addpago ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/addpago');
        }

       # \Session::put('cart', ['productos'=>[], 'total'=>0, 'base'=>0, 'impuesto'=>0]);


        $cart= \Session::get('cart');

       # unset($cart['pagos'][$request->id]);

        array_splice($cart['pagos'], $request->id, 1);

         $cart=$this->calculoCart($cart);

        \Session::put('cart', $cart);

        $formaspago=AlpFormaspago::where('estado_registro', '1')->get();

        $bancos=AlpBancos::where('estado_registro', '1')->get();
       
        return view('pos.pagar', compact('cart', 'formaspago', 'bancos'));

  }


  public function getcarrito(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/getcarrito ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/getcarrito');
        }

       # \Session::put('cart', ['productos'=>[], 'total'=>0, 'base'=>0, 'impuesto'=>0]);


        $cart= \Session::get('cart');

       # unset($cart['pagos'][$request->id]);
       
        return view('pos.ordenactual', compact('cart'));

  }


  public function procesar(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/procesar ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/procesar');
        }

        
        $caja=AlpCajas::where('id_user', $user->id)->where('estado_registro', '1')->first();

        if(isset($caja->id)){

        }else{

          $caja=AlpCajas::where('id_user', $user->id)->where('estado_registro', '1')->first();

            return view('pos.dashboard', compact('caja'));
        }

        $cart= \Session::get('cart');

        if(!isset($cart['cliente']['id'])){
          $cart['cliente']['id']='1';
        }

        $configuracion= AlpConfiguracion::first();

        
        $orden=AlpOrdenes::create([
          'referencia'=>$cart['referencia'],
          'referencia_mp'=>$cart['referencia'],
          'id_cliente'=>$cart['cliente']['id']?$cart['cliente']['id']:1,
          'id_address'=>1,
          'id_forma_envio'=>1,
          'id_forma_pago'=>1,
          'monto_total'=>$cart['total']*$configuracion->tasa_dolar,
          'monto_total_base'=>$cart['base']*$configuracion->tasa_dolar,
          'monto_descuento'=>0,
          'base_impuesto'=>$cart['base']*$configuracion->tasa_dolar,
          'valor_impuesto'=>'0.19',
          'monto_impuesto'=>$cart['impuesto']*$configuracion->tasa_dolar,
          'comision_mp'=>0,
          'retencion_fuente_mp'=>0,
          'retencion_iva_mp'=>0,
          'retencion_ica_mp'=>0,
          'cod_oracle_pedido'=>0,
          'ordencompra'=>0,
          'id_almacen'=>1,
          'origen'=>0,
          'token'=>0,
          'tracking'=>0,
          'factura'=>0,
          'ip'=>0,
          'json'=>0,
          'send_json_masc'=>0,
          'json_icg'=>0,
          'estado_compramas'=>0,
          'envio_compramas'=>0,
          'notas'=>0,
          'lifemiles_id'=>0, 
          'estatus'=>1,
          'estatus_pago'=>2,
          'id_user'=>$user->id,
          'id_caja'=>$caja->id,
          'tasa_dolar'=>$configuracion->tasa_dolar
        ]);


        foreach($cart['productos'] as $p){

          $precio_base=$p->precio_base*$configuracion->tasa_dolar;

          AlpDetalles::create([
            'id_orden'=>$orden->id,
            'id_producto'=>$p->id,
            'cantidad'=>$p->cantidad,
            'precio_unitario'=>$precio_base,
            'precio_total'=>$precio_base*$p->cantidad,
            'precio_base'=>$p->cantidad,
            'precio_total_base'=>$precio_base*$p->cantidad,
            'valor_impuesto'=>'0.19',
            'monto_impuesto'=>0,
            'id_combo'=>0,
            'id_user'=>$user->id
          ]);


          AlpInventario::create([
            'id_almacen'=>'1',
            'id_producto'=>$p->id,
            'cantidad'=>$p->cantidad,
            'operacion'=>'2',
            'id_orden'=>$orden->id,
            'notas'=>'Orden '.$orden->id,
            'id_user'=>$user->id
          ]);

        }

        $total_pagos=0;


        foreach($cart['pagos'] as $pago){


          AlpPagos::create([
            'id_orden'=>$orden->id,
            'id_forma_pago'=>$pago['id'],
            'id_estatus_pago'=>'2',
            'monto_pago'=>$pago['monto'],
            'valor_pago'=>$pago['valor'],
            'referencia'=>$pago['referencia'],
            'ticket'=>$pago['ticket'],
            'json'=>json_encode($cart['pagos']),
            'estado_registro'=>'1',
            'id_user'=>$user->id
          ]);

          AlpTransacciones::create([
            'id_orden'=>$orden->id,
            'referencia'=>$pago['referencia'],
            'monto'=>$pago['monto'],
            'valor'=>$pago['valor'],
            'id_forma_pago'=>$pago['id'],
            'tipo'=>'1',
            'moneda'=>$pago['moneda'],
            'estado_registro'=>'1',
            'id_user'=>$user->id
          ]);

          $total_pagos=$total_pagos+$pago['valor'];

        }

        if($total_pagos>$cart['total']){

            $dif=$total_pagos-($cart['total']*$configuracion->tasa_dolar);

            AlpTransacciones::create([
              'id_orden'=>$orden->id,
              'referencia'=>'vuelto',
              'monto'=>$dif,
              'valor'=>$dif,
              'id_forma_pago'=>0,
              'tipo'=>'2',
              'moneda'=>'1',
              'estado_registro'=>'0',
              'id_user'=>$user->id
            ]);

        }

        \Session::put('cart', ['inventario'=>$this->inventario(),'productos'=>[], 'total'=>0, 'base'=>0, 'impuesto'=>0, 'cliente'=>null, 'referencia'=>time()]);

        $cart= \Session::get('cart');

        $cart=$this->calculoCart($cart);

        $orden=AlpOrdenes::where('id', $orden->id)->with('detalles', 'pagos', 'cliente', 'transacciones')->first();

        $formaspago=AlpFormaspago::where('estado_registro', '1')->get();

      #  dd($orden);

        return view('pos.resumen', compact('orden', 'formaspago'));

  }




  function calculoCart($cart){

      $total=0;
      $base=0;
      $impuesto=0;
      $descuento=0;
      $pagado_bs=0;
      $pagado_usd=0;
      $bs=0;
      $usd=0;

      $configuracion=AlpConfiguracion::first();

      if(isset($cart['productos'])){

          foreach ($cart['productos'] as $p) {

              $total_detalle=$p->precio_base*$p->cantidad;

              $total=$total+$total_detalle;

              if($p->id_impuesto=='1'){

                  $base_detalle=($total_detalle/1.19);

                  $impuesto_detalle=$base_detalle*0.19;

                  $base=$base+$base_detalle;

                  $impuesto=$impuesto+$impuesto_detalle;

              }


          }

      }


      if(isset($cart['pagos'])){

          foreach ($cart['pagos'] as $pago) {

              if ($pago['moneda']=='2') {

                 $pagado_usd=$pagado_usd+$pago['monto'];


              }else{

                $pagado_bs=$pagado_bs+$pago['monto'];
               

              }


              
          }

      }

      $cart['total']=number_format($total,2);
      $cart['total_bs']=number_format($total*$configuracion->tasa_dolar,2);
      $cart['base']=number_format($base,2);
      $cart['impuesto']=number_format($impuesto,2);
      $cart['pagado_bs']=number_format($pagado_bs,2);
      $cart['pagado_usd']=number_format($pagado_usd,2);
      $cart['resto']=number_format(($total*$configuracion->tasa_dolar)-$pagado_bs-($pagado_usd*$configuracion->tasa_dolar),2);
      $cart['descuento']=number_format($descuento,2);

      return $cart;

  }

private function inventario()
    {


     #  $id_almacen=$this->getAlmacen();
       $id_almacen='1';

        $entradas = AlpInventario::groupBy('id_producto')
          ->select("alp_inventarios.*", DB::raw(  "SUM(alp_inventarios.cantidad) as cantidad_total"))
          ->join('alp_almacen_producto', 'alp_inventarios.id_producto', '=', 'alp_almacen_producto.id_producto')
          ->where('alp_inventarios.operacion', '1')
          ->where('alp_inventarios.id_almacen', '=', $id_almacen)
          ->where('alp_almacen_producto.id_almacen', '=', $id_almacen)
          ->whereNull('alp_almacen_producto.deleted_at')
          ->groupBy('alp_inventarios.id_almacen')
              #->whereNull('alp_inventarios.deleted_at')
          ->get();



              $inv = array();

              foreach ($entradas as $row) {

                $inv[$row->id_producto]=$row->cantidad_total;

              }

            $salidas = AlpInventario::select("alp_inventarios.*", DB::raw(  "SUM(alp_inventarios.cantidad) as cantidad_total"))
              ->groupBy('id_producto')
              ->where('operacion', '2')
              ->where('alp_inventarios.id_almacen', '=', $id_almacen)
              ->groupBy('alp_inventarios.id_almacen')
              #->whereNull('alp_inventarios.deleted_at')
              ->get();


              foreach ($salidas as $row) {



                if (isset($inv[$row->id_producto])) {

                  $inv[$row->id_producto]=$inv[$row->id_producto]-$row->cantidad_total;

                }else{

                  $inv[$row->id_producto]=0;

                }

                #$inv[$row->id_producto]=$inv[$row->id_producto]-$row->cantidad_total;

            }

            return $inv;

    }



   public function puntodeventa(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/puntodeventa ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/puntodeventa');
        }


        if (isset($user->id)) {

          $caja=AlpCajas::where('id_user', $user->id)->where('estado_registro', '1')->first();

          $view= View::make('pos.puntodeventa', compact('caja'));

          $data=$view->render();

          $res = array('status' => 'dashboard', 'error'=>'0', 'mensaje'=>'', 'data'=>$data );

          return json_encode($res);


        }else{


            $res = array('status' => 'login', 'error'=>'1', 'mensaje'=>'Usuario no logueado', 'data'=>null );

            return json_encode($res);


        }

      

  }


   public function imprimir(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/imprimir ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/imprimir');
        }


        $configuracion= AlpConfiguracion::first();

        $orden = AlpOrdenes::where('id_user', $user->id)->where('id', $request->id)->with('cliente', 'cajero', 'estado', 'detalles', 'pagos')->first();

        foreach($orden->detalles as $d){

          $p=AlpProductos::where('id', $d->id_producto)->first();

          $d->producto=$p;
        }

        foreach($orden->pagos as $pago){

          $f=AlpFormaspago::where('id', $pago->id_forma_pago)->first();

          $pago->formapago=$f;

        }

        $orden->configuracion=$configuracion;

        $data =  json_encode($orden);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,            "http://localhost:8000/" );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_POST,           1 );
        curl_setopt($ch, CURLOPT_POSTFIELDS,     $data); 
        curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain')); 

        $result = curl_exec($ch);

        return $result;

    #return view('pos.detallepedido', compact('orden'));

  }



   public function imprimircierrecaja(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/imprimir ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/imprimir');
        }


        $configuracion= AlpConfiguracion::first();

        $caja = AlpCajas::where('id', $request->id)->with('cajero')->first();

        $pagos=AlpPagos::select('alp_ordenes_pagos.*',  DB::raw('SUM(monto_pago) as total_pagos'))
        ->join('alp_ordenes', 'alp_ordenes_pagos.id_orden', '=', 'alp_ordenes.id')
        ->where('alp_ordenes_pagos.id_user', $user->id)
        ->where('alp_ordenes.id_caja', '=', $request->id)
        ->with('formapago')->groupBy('id_forma_pago')
        ->get();

        $caja->pagos=$pagos;

        $caja->configuracion=$configuracion;

        $data =  json_encode($caja);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,            "http://localhost:8000/cierrecaja.php" );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_POST,           1 );
        curl_setopt($ch, CURLOPT_POSTFIELDS,     $data); 
        curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain')); 

        $result = curl_exec($ch);

        return $result;

    #return view('pos.detallepedido', compact('orden'));

  }



   public function imprimirpunto(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/imprimir ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/imprimir');
        }


        $configuracion= AlpConfiguracion::first();

        $caja = AlpCajas::where('id', $request->id)->with('cajero')->first();

        $pagos=AlpPagos::select('alp_ordenes_pagos.*',  DB::raw('SUM(monto_pago) as total_pagos'))
        ->join('alp_ordenes', 'alp_ordenes_pagos.id_orden', '=', 'alp_ordenes.id')
        ->where('alp_ordenes_pagos.id_user', $user->id)
        ->where('alp_ordenes.id_caja', '=', $request->id)
        ->with('formapago')->groupBy('id_forma_pago')
        ->get();

        $pago = AlpPagos::where('id', $request->id)->first();

        $data =  json_encode($pago);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,            "http://localhost:8000/punto.php" );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_POST,           1 );
        curl_setopt($ch, CURLOPT_POSTFIELDS,     $data); 
        curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain')); 

        $result = curl_exec($ch);

        return $result;

    #return view('pos.detallepedido', compact('orden'));

  }



     public function cart(Request $request)
  {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

          activity($user->full_name)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($request->getContent())->log('PosController/cart ');

        }else{

          activity()
          ->withProperties($request->getContent())->log('PosController/cart');
        }


        $cart= \Session::get('cart');

          $view= View::make('pos.cart', compact('cart'));

          $data=$view->render();

          $res = array('status' => 'dashboard', 'error'=>'0', 'mensaje'=>'', 'data'=>$data );

          return json_encode($res);

  }


  
public function pagomoviltoken()
  {

      if (Sentinel::check()) {

        $user = Sentinel::getUser();

        activity($user->full_name)
          ->performedOn($user)
          ->causedBy($user)->log('PosController/pagomoviltoken ');

      }else{

        activity()->log('PosController/pagomoviltoken');
      }

      $configuracion = AlpConfiguracion::first();

      $datos = [];
      $datos['username']='titiwao';
      $datos['password']='61ab3dace693f40d37f4d9a4ab1d9ce9';

      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, 'http://20.231.111.55:8098/sitef/apiToken');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));

      $headers = array();
      $headers[] = 'Content-Type: application/json';
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

      $result = curl_exec($ch);
      if (curl_errno($ch)) {
          echo 'Error:' . curl_error($ch);
      }
      curl_close($ch);

      $res =   json_decode($result);

      if(isset($res->data->token)){
        return $res->data->token;
      }else{
        return null;
      }

  }



 

   public function buscarpago(Request $request)
  {

      if (Sentinel::check()) {

        $user = Sentinel::getUser();

        activity($user->full_name)
          ->performedOn($user)
          ->causedBy($user)->log('PosController/buscarpago ');

      }else{

        activity()->log('PosController/buscarpago');
      }

      $configuracion = AlpConfiguracion::first();

      $token = $this->pagomoviltoken();

      $telefono = $request->telefono;

      #dd()

      if (substr($request->telefono, 0, 1)=='0') {
        $telefono= substr($request->telefono, 1);
      }

      $datos = [];
      $datos['username']='titiwao';
      $datos['token']=md5($token);
      $datos['idBranch']='33';
      $datos['codeStall']='001';
      $datos['amount']='2.00';
      $datos['telefonoDebito']='58'.$telefono;
      $datos['paymentReference']=$request->referencia;
     // $datos['trxDate']='2023-01-29';
      $datos['trxDate']=date('Y-m-d');

      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, 'http://20.231.111.55:8098/sitefAuth/getBusqueda');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));

      $headers = array();
      $headers[] = 'Content-Type: application/json';
      $headers[] = 'Authorization: Bearer '.$token;
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

      $result = curl_exec($ch);
      if (curl_errno($ch)) {
          echo 'Error:' . curl_error($ch);
      }
      curl_close($ch);

      $res =   json_decode($result);

     # dd($res);

      if (isset($res->data->transaction_list)) {

        $res = array('status' => true, 'error'=>'0', 'mensaje'=>'', 'data'=>$res->data->transaction_list[0] );

         #dd($res->data->transaction_list[0]);

       }else{

        #dd($res->data->error_list[0]->description);
         $res = array('status' => false, 'error'=>'0', 'mensaje'=>$res->data->error_list[0]->description, 'data'=>[] );

       }

       return $res;

  }


   public function sendvuelto(Request $request)
  {

      if (Sentinel::check()) {

        $user = Sentinel::getUser();

        activity($user->full_name)
          ->performedOn($user)
          ->causedBy($user)->log('PosController/sendvuelto ');

      }else{

        activity()->log('PosController/sendvuelto');
      }

      $configuracion = AlpConfiguracion::first();

      $token = $this->pagomoviltoken();


    
      

    #  dd($request->all());

      $monto = $request->monto*-1;

      $telefono = $request->telefono;

      #dd()

      if (substr($request->telefono, 0, 1)=='0') {
        $telefono= substr($request->telefono, 1);
      }

      $datos = [];
      $datos['username']='titiwao';
      $datos['token']=md5($token);
      $datos['idBranch']='33';
      $datos['codeStall']='001';
      $datos['amount']=$monto;
      $datos['destinationId']=$request->tipodoc.$request->cedula;
      $datos['destinationMobileNumber']='58'.$telefono;
      $datos['destinationBank']=substr($request->banco, 1);
     # $datos['invoiceNumber']=$request->referencia;
      $datos['invoiceNumber']=time();

    #  dd($datos);

      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, 'http://20.231.111.55:8098/sitefAuth/setVuelto');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));

      $headers = array();
      $headers[] = 'Content-Type: application/json';
      $headers[] = 'Authorization: Bearer '.$token;
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

      $result = curl_exec($ch);
      if (curl_errno($ch)) {
          echo 'Error:' . curl_error($ch);
      }
      curl_close($ch);

      $res =   json_decode($result);

     
      #$res = array('status' => true, 'error'=>'0', 'mensaje'=>'Transacción Procesada', 'data'=>['authorization_code'=>'182951','amount'=>'1.85' ] );
    
     # return $res;

    /*return json_decode(json_encode('{"status":true,"error":"0","mensaje":"Transacci\u00f3n Procesada","data":{"processing_date":"2023-01-29 06:11:58 VET","trx_status":"approved","trx_internal_status":"0000","trx_type":"vuelto","authorization_code":"182951","payment_method":"p2p","payment_reference":502939200239,"invoice_number":"1674987510","amount":1.85,"service_fee":0,"currency":"ves"}}'), true); */
     

      if (isset($res->data->transaction_c2p_response)) {

        $res = array('status' => true, 'error'=>'0', 'mensaje'=>'Transacción Procesada', 'data'=>$res->data->transaction_c2p_response );

       # dd($res->data->transaction_c2p_response);

       }else{

        if (isset($res->data->error_list)) {
          $res = array('status' => false, 'error'=>'0', 'mensaje'=>$res->data->error_list[0]->description, 'data'=>[] );
        }else{
          $res = array('status' => false, 'error'=>'0', 'mensaje'=>'Trasanccion Fallida: '.json_encode($res, true), 'data'=>[] );
        }

        #dd($res->data->error_list[0]->description);
         

       }

       return $res;

  }


}

    