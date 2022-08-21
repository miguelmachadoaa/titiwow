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

    
    public function __construct()
    {

        parent::__construct();

        
        if (!\Session::has('cart')) {

          \Session::put('cart', ['productos'=>[],'pagos'=>[], 'total'=>0, 'base'=>0, 'impuesto'=>0]);

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


    return view('pos.dashboard');

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

    return view('pos.opciones');

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

      /*  $carritos=AlpCarrito::select('alp_carrito.*', 'alp_carrito_detalle.id_producto', 'alp_carrito_detalle.cantidad')
        ->join('alp_carrito_detalle', 'alp_carrito.id', '=', 'alp_carrito_detalle.id_carrito')
        ->join('alp_productos', 'alp_carrito_detalle.id_producto', '=', 'alp_productos.id')
        ->where('alp_carrito.id_user', $user->id)
        ->get();*/

        $carritos=AlpCarrito::with('detalles')->get();

     #   dd(json_encode($carritos));


    return view('pos.carritos', compact('carritos'));

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

      /*  $carritos=AlpCarrito::select('alp_carrito.*', 'alp_carrito_detalle.id_producto', 'alp_carrito_detalle.cantidad')
        ->join('alp_carrito_detalle', 'alp_carrito.id', '=', 'alp_carrito_detalle.id_carrito')
        ->join('alp_productos', 'alp_carrito_detalle.id_producto', '=', 'alp_productos.id')
        ->where('alp_carrito.id_user', $user->id)
        ->get();*/

        AlpCarrito::where('id', $request->id)->delete();


        $carritos=AlpCarrito::with('detalles')->get();

     #   dd(json_encode($carritos));


    return view('pos.carritos', compact('carritos'));

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



        $ordenes = AlpOrdenes::where('id_user', $user->id)->with('cliente', 'cajero', 'estado')->get();



    return view('pos.pedidos', compact('ordenes'));

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


        $pagos=AlpPagos::where('id_user', $user->id)->with('formapago')->get();

        #dd(json_encode($pagos));


    return view('pos.transacciones', compact('pagos'));

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

        $pagos=AlpPagos::select('alp_ordenes_pagos.*',  DB::raw('SUM(monto_pago) as total_pagos'))->where('id_user', $user->id)->with('formapago')->groupBy('id_forma_pago')->get();

       # dd(json_encode($pagos));


    return view('pos.reportes', compact('pagos'));

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


        $categorias = AlpCategorias::get();


      return view('pos.categorias', compact('categorias'));

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

        $clientes =  User::select('users.*','roles.name as name_role',
          'alp_clientes.telefono_cliente as telefono_cliente',
          'alp_clientes.doc_cliente as doc_cliente',
          'alp_clientes.genero_cliente as genero_cliente'
        )
        ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
        ->join('role_users', 'users.id', '=', 'role_users.user_id')
        ->join('roles', 'role_users.role_id', '=', 'roles.id')
        ->where('role_users.role_id', '<>', 1)
        ->get();


    return view('pos.clientes', compact('clientes'));

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

       $categorias=AlpCategorias::where('estado_registro', '1')->get();
       $impuestos=AlpImpuestos::where('estado_registro', '1')->get();


    return view('pos.addcliente', compact( 'categorias', 'impuestos'));

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

        $usuario=User::create([
          'first_name'=>$request->nombre_cliente,
          'email'=>$request->email_cliente,
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

          $productos=AlpProductos::where('estado_registro', '1')->get();

    return view('pos.productos', compact('productos'));

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

          $productos=AlpProductos::
          #where('estado_registro', '1')
          Where('nombre_producto', 'like', '%'.$request->termino.'%')
          ->orWhere('referencia_producto', 'like', '%'.$request->termino.'%')
          ->orWhere('precio_base', 'like', '%'.$request->termino.'%')
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

            }

            $cart=$this->calculoCart($cart);

            \Session::put('cart', $cart);



            $view= View::make('pos.ordenactual', compact('cart'));

            $data=$view->render();

            $res = array('status' => 'carrito', 'error'=>'0', 'mensaje'=>'Produco agregado al carrito', 'data'=>$data );

            return json_encode($res);


          }else{
            

            $view= View::make('pos.productos', compact('productos'));

            $data=$view->render();

            $res = array('status' => 'productos', 'error'=>'0', 'mensaje'=>'productos encontrados', 'data'=>$data );

            return json_encode($res);

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

          $p=AlpProductos::where('id', $request->id)->first();

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

          }

        $cart=$this->calculoCart($cart);

        \Session::put('cart', $cart);

        return view('pos.ordenactual', compact('cart'));

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


        \Session::put('cart', ['productos'=>[],'pagos'=>[], 'total'=>0, 'base'=>0, 'impuesto'=>0]);

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



          \Session::put('cart', ['productos'=>[],'pagos'=>[], 'total'=>0, 'base'=>0, 'impuesto'=>0]);

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


        $cart= \Session::get('cart');

        $formaspago=AlpFormaspago::where('estado_registro', '1')->get();

       
        return view('pos.pagar', compact('cart', 'formaspago'));

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

       # \Session::put('cart', ['productos'=>[], 'total'=>0, 'base'=>0, 'impuesto'=>0]);


        $cart= \Session::get('cart');

        $cart['pagos'][]=[
          'id'=>$request->id,
          'name'=>$request->name,
          'monto'=>$request->monto,
        ];

         $cart=$this->calculoCart($cart);

        \Session::put('cart', $cart);

        $formaspago=AlpFormaspago::where('estado_registro', '1')->get();

       
        return view('pos.pagar', compact('cart', 'formaspago'));

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

       
        return view('pos.pagar', compact('cart', 'formaspago'));

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

        


        $cart= \Session::get('cart');

        
        $orden=AlpOrdenes::create([
          'referencia'=>time(),
          'referencia_mp'=>time(),
          'id_cliente'=>9,
          'id_address'=>1,
          'id_forma_envio'=>1,
          'id_forma_pago'=>1,
          'monto_total'=>$cart['total'],
          'monto_total_base'=>$cart['base'],
          'monto_descuento'=>0,
          'base_impuesto'=>$cart['base'],
          'valor_impuesto'=>'0.19',
          'monto_impuesto'=>$cart['impuesto'],
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
          'id_user'=>$user->id
        ]);


        foreach($cart['productos'] as $p){

          AlpDetalles::create([
            'id_orden'=>$orden->id,
            'id_producto'=>$p->id,
            'cantidad'=>$p->cantidad,
            'precio_unitario'=>$p->precio_base,
            'precio_total'=>$p->precio_base*$p->cantidad,
            'precio_base'=>$p->cantidad,
            'precio_total_base'=>$p->precio_base*$p->cantidad,
            'valor_impuesto'=>'0.19',
            'monto_impuesto'=>0,
            'id_combo'=>0,
            'id_user'=>$user->id
          ]);

        }


        foreach($cart['pagos'] as $pago){

          AlpPagos::create([
            'id_orden'=>$orden->id,
            'id_forma_pago'=>$pago['id'],
            'id_estatus_pago'=>'2',
            'monto_pago'=>$pago['monto'],
            'json'=>json_encode($cart['pagos']),
            'estado_registro'=>'1',
            'id_user'=>$user->id
          ]);

        }


        \Session::put('cart', ['productos'=>[], 'total'=>0, 'base'=>0, 'impuesto'=>0, 'cliente'=>null]);

        $cart= \Session::get('cart');

        $cart=$this->calculoCart($cart);

        $orden=AlpOrdenes::where('id', $orden->id)->with('detalles', 'pagos', 'cliente')->first();

        return view('pos.resumen', compact('orden'));

  }




  function calculoCart($cart){

      $total=0;
      $base=0;
      $impuesto=0;
      $descuento=0;
      $pagado=0;

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

          #  dd($pago['monto']);

              $pagado=$pagado+$pago['monto'];

          }

      }

      $cart['total']=number_format($total,2);
      $cart['base']=number_format($base,2);
      $cart['impuesto']=number_format($impuesto,2);
      $cart['pagado']=number_format($pagado,2);
      $cart['resto']=number_format($total-$pagado,2);
      $cart['descuento']=number_format($descuento,2);


      return $cart;

  }



}

    