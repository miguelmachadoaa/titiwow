<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AlpOrdenes;
use App\Models\AlpProductos;
use App\Models\AlpClientes;
use App\Models\AlpEmpresas;
use App\Models\AlpDirecciones;
use App\Models\AlpCarrito;
use App\Models\AlpDetalles;
use App\Models\AlpAmigos;
use App\Models\AlpPuntos;
use App\Models\AlpConfiguracion;
use App\Models\AlpTDocumento;
use App\Models\AlpEstructuraAddress;
use App\Models\AlpClientesHistory;
use App\User;
use App\Country;
use App\State;
use App\City;
use App\RoleUser;
use Illuminate\Support\Facades\Mail;
use App\Models\AlpCategorias;
use Sentinel;
use View;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use DB;     

class ClientesFrontController extends Controller
{

    public function index()
    {

        /*solo muestra el menu de opciones del cliente 
        verifi si esta logueado        */

        $dt = Carbon::now(); 

// These getters specifically return integers, ie intval()
       //echo  $dt->year;                                         // int(2012)
       // echo $dt->month;  

        if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

            $role=RoleUser::where('user_id', $user_id)->first();


            $cliente = AlpClientes::where('id_user_client', $user_id )->first();

                if (!is_null($cliente)) {

                    if ($cliente->id_empresa!=0) {
                        
                        $empresa=AlpEmpresas::find($cliente->id_empresa);

                        $cliente['nombre_empresa']=$empresa->nombre_empresa;

                    }

                    if ($cliente->id_embajador!=0) {
                        
                        $user_embajador = User::where('id', $cliente->id_embajador )->first();


                        $cliente['nombre_embajador']=$user_embajador->first_name.' '.$user_embajador->last_name;
                        
                    }

                }

            $user = User::where('id', $user_id )->first();

            $states=State::where('config_states.country_id', '47')->get();

            $cart= \Session::get('cart');

            $puntos = array();


            if ($role->role_id=='10') {

            $puntos_cliente =  DB::table('alp_puntos')->select('alp_puntos.*', DB::raw("SUM(alp_puntos.cantidad) as puntos"))
            ->whereYear('created_at', '=', $dt->year)
            ->whereMonth('created_at', '=', $dt->month)
            ->where('alp_puntos.id_cliente', $user_id)
            ->groupBy('alp_puntos.id_cliente')
            ->first();



                if (isset($puntos_cliente->id)) {


                    if ($puntos_cliente->puntos<250000) {
                        $puntos['nivel']='1';
                        $puntos['porcentaje']=0.02;


                    }elseif(250000<$puntos_cliente->puntos && $puntos_cliente->puntos<750000){

                        $puntos['nivel']='2';
                        $puntos['porcentaje']=0.04;

                    }elseif($puntos_cliente->puntos>750000){

                        $puntos['nivel']='3';
                        $puntos['porcentaje']=0.06;

                    }
                    
                    $puntos['puntos']=$puntos_cliente->puntos;

                }else{

                    $puntos['nivel']='1';
                    $puntos['porcentaje']=0.02;
                    $puntos['puntos']='0';

                }

            }

            //dd($puntos);

            return \View::make('frontend.clientes.index', compact('referidos', 'cliente', 'user', 'states', 'cart', 'puntos'));
    
            }else{

                $url='clientes';

                  //return redirect('login');
                return view('frontend.order.login', compact('url'));

        }

       
    }

    /*mmuestra el listado de direcciones del cliente logueado */

    public function misdirecciones()
    {

        if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

             $direcciones = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
          ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
          ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
          ->where('alp_direcciones.id_client', $user_id)->first();


        $editar=0;

        if (isset($direcciones->updated_at)) {

             $dt = new Carbon($direcciones->updated_at);

            

            if ($dt->diffInHours()>24) {

                $editar=1;
            } 
            # code...
        }else{

            $editar=1;
        }

            $states=State::where('config_states.country_id', '47')->get();

            $cities=City::where('state_id', $direcciones->state_id)->get();

            $t_documento = AlpTDocumento::where('estado_registro','=',1)->get();

            $estructura = AlpEstructuraAddress::where('estado_registro','=',1)->get();



            $cliente = AlpClientes::where('id_user_client', $user_id )->first();

            $user = User::where('id', $user_id )->first();

            $countries = Country::all();

             $cart= \Session::get('cart');



            return view('frontend.clientes.misdirecciones', compact('direcciones', 'cliente', 'user', 'countries',  'editar', 'states', 't_documento', 'estructura', 'cities', 'cart'));
    

            }else{


                $url='clientes';

                  //return redirect('login');
                return view('frontend.order.login', compact('url'));


        }

       
    }

    /*muestra los amigos del clientes */

    public function misamigos()
    {

        if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;


            $referidos =  DB::table('alp_clientes')->select('alp_clientes.*','users.first_name as first_name','users.last_name as last_name' ,'users.email as email', DB::raw("SUM(alp_ordenes.monto_total) as puntos"))
            ->join('users','alp_clientes.id_user_client' , '=', 'users.id')
            ->leftJoin('alp_ordenes','users.id' , '=', 'alp_ordenes.id_cliente')
            ->groupBy('alp_clientes.id')
            ->where('alp_clientes.id_embajador', $user_id)->get();

            $cliente = AlpClientes::where('id_user_client', $user_id )->first();

            $user = User::where('id', $user_id )->first();

            $configuracion=AlpConfiguracion::where('id', 1)->first();
            $amigos=AlpAmigos::where('id_cliente', $user_id)->get();
            $clientes_amigos=AlpClientes::where('id_embajador',$user_id )->selectRaw('count(*) as cantidad')->first();
            $amigos_amigos=AlpAmigos::where('id_cliente',$user_id )->selectRaw('count(*) as cantidad')->first();
            $cantidad=$clientes_amigos->cantidad+$amigos_amigos->cantidad;


            $states=State::where('config_states.country_id', '47')->get();

             $cart= \Session::get('cart');




           













            return view('frontend.clientes.misamigos', compact('referidos', 'cliente', 'user', 'configuracion', 'amigos', 'cantidad', 'states', 'cart'));
    

            }else{


                $url='clientes';

                  //return redirect('login');
                return view('frontend.order.login', compact('url'));


        }

       
    }


    public function miestatus()
    {

        $dt = Carbon::now(); 

        if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

             $role=RoleUser::where('user_id', $user_id)->first();


            $referidos =  DB::table('alp_clientes')->select('alp_clientes.*','users.first_name as first_name','users.last_name as last_name' ,'users.email as email', DB::raw("SUM(alp_ordenes.monto_total) as puntos"))
            ->join('users','alp_clientes.id_user_client' , '=', 'users.id')
            ->leftJoin('alp_ordenes','users.id' , '=', 'alp_ordenes.id_cliente')
            ->groupBy('alp_clientes.id')
            ->where('alp_ordenes.estatus', '<>', '4')
            ->where('alp_clientes.id_embajador', $user_id)->get();

            $cliente = AlpClientes::where('id_user_client', $user_id )->first();

            $user = User::where('id', $user_id )->first();

            $configuracion=AlpConfiguracion::where('id', 1)->first();

            $amigos=AlpAmigos::where('id_cliente', $user_id)->get();

            $clientes_amigos=AlpClientes::where('id_embajador',$user_id )->selectRaw('count(*) as cantidad')->first();

            $amigos_amigos=AlpAmigos::where('id_cliente',$user_id )->selectRaw('count(*) as cantidad')->first();

            $cantidad=$clientes_amigos->cantidad+$amigos_amigos->cantidad;

            $states=State::where('config_states.country_id', '47')->get();

             $cart= \Session::get('cart');

                $puntos = array();


            if ($role->role_id=='10') {

            $puntos_cliente =  DB::table('alp_puntos')
            ->select('alp_puntos.*','alp_puntos.*', DB::raw("SUM(alp_puntos.cantidad) as puntos"))
            ->join('alp_ordenes','alp_puntos.id_orden' , '=', 'alp_ordenes.id')
            ->whereYear('alp_puntos.created_at', '=', $dt->year)
            ->whereMonth('alp_puntos.created_at', '=', $dt->month)
            ->where('alp_puntos.id_cliente', $user_id)
            ->where('alp_ordenes.estatus', '<>', '4')            
            ->groupBy('alp_puntos.id_cliente')
            ->first();

            $puntos_list =  DB::table('alp_puntos')
            ->select('alp_puntos.*','users.first_name as first_name','users.last_name as last_name')
            ->join('users','alp_puntos.id_user' , '=', 'users.id')

            ->whereYear('alp_puntos.created_at', '=', $dt->year)
            ->whereMonth('alp_puntos.created_at', '=', $dt->month)
            ->where('alp_puntos.deleted_at', '=', NULL)
            ->where('alp_puntos.id_cliente', $user_id)
            ->get();

            //una para los referidos y otroa pára sus compras

           $puntos_list2 =  DB::table('alp_puntos')
            ->select('alp_puntos.*','users.first_name as first_name','users.last_name as last_name')
            ->join('users','alp_puntos.id_cliente' , '=', 'users.id')

            ->whereYear('alp_puntos.created_at', '=', $dt->year)
            ->whereMonth('alp_puntos.created_at', '=', $dt->month)
             ->where('alp_puntos.deleted_at', '=', NULL)
            ->where('alp_puntos.id_user', $user_id)
            ->get();



                if (isset($puntos_cliente->id)) {


                    if ($puntos_cliente->puntos<250000) {
                        $puntos['nivel']='1';
                        $puntos['porcentaje']=0.02;


                    }elseif(250000<$puntos_cliente->puntos && $puntos_cliente->puntos<750000){

                        $puntos['nivel']='2';
                        $puntos['porcentaje']=0.04;

                    }elseif($puntos_cliente->puntos>750000){

                        $puntos['nivel']='3';
                        $puntos['porcentaje']=0.06;

                    }
                    
                    $puntos['puntos']=$puntos_cliente->puntos;

                }else{

                    $puntos['nivel']='1';
                    $puntos['porcentaje']=0.02;
                    $puntos['puntos']='0';

                }

            }

            return view('frontend.clientes.miestatus', compact('referidos', 'cliente', 'user', 'configuracion', 'amigos', 'cantidad', 'states', 'cart', 'puntos', 'puntos_list', 'puntos_list2'));
    

            }else{

                $url='clientes';
                  //return redirect('login');
                return view('frontend.order.login', compact('url'));

        }

    }
    /**/

    public function storeamigo(Request $request)
    {

        $mensaje = array();

        $user_id = Sentinel::getUser()->id;

        if (Sentinel::check()) {


            $user_email=User::where('email', $request->email)->first();

            if (isset($user_email->id)) {


                $mensaje['tipo']='danger';
                $mensaje['mensaje']='El Correo ya esta registrado en nuestro sistema';


                $amigos=AlpAmigos::where('id_cliente', $user_id)->get();

                $cliente = AlpClientes::where('id_user_client', $user_id )->first();

                $user = User::where('id', $user_id )->first();
                
                $clientes_amigos=AlpClientes::where('id_embajador',$user_id )->selectRaw('count(*) as cantidad')->first();

                $amigos_amigos=AlpAmigos::where('id_cliente',$user_id )->selectRaw('count(*) as cantidad')->first();

                $cantidad=$clientes_amigos->cantidad+$amigos_amigos->cantidad;

            $configuracion = AlpConfiguracion::where('id', '1')->first();


               
            }else{


            $token=substr(md5(time()), 0, 10);

            $data = array(
                'id_cliente' => $user_id, 
                'nombre_amigo' => $request->nombre, 
                'apellido_amigo' => $request->apellido, 
                'email_amigo' => $request->email, 
                'token' => $token, 
                'id_user' => $user_id
            );
        
            AlpAmigos::create($data);

            $amigos=AlpAmigos::where('id_cliente', $user_id)->get();

            $cliente = AlpClientes::where('id_user_client', $user_id )->first();

            $user = User::where('id', $user_id )->first();

            
            $clientes_amigos=AlpClientes::where('id_embajador',$user_id )->selectRaw('count(*) as cantidad')->first();

            $amigos_amigos=AlpAmigos::where('id_cliente',$user_id )->selectRaw('count(*) as cantidad')->first();


            $cantidad=$clientes_amigos->cantidad+$amigos_amigos->cantidad;


            $configuracion = AlpConfiguracion::where('id', '1')->first();

            Mail::to($request->email)->send(new \App\Mail\NotificacionAmigo($request->nombre, $request->apellido, $token, $user->first_name.' '.$user->last_name));

                $mensaje['tipo']='success';

                $mensaje['mensaje']='Solicitud enviada.';


        }


            $view= View::make('frontend.clientes.listamigo', compact('amigos', 'cliente', 'user', 'cantidad', 'configuracion', 'mensaje'));

            $data=$view->render();

            return $data;

            }

       
    }

    /*elimina una invitacion a amigos del cliente  del cliente */

    public function delamigo(Request $request)
    {
            $mensaje = array();
           

            $user_id = Sentinel::getUser()->id;

            $amigo=AlpAmigos::find($request->id);

            $amigo->delete();

            $amigos=AlpAmigos::where('id_cliente', $user_id)->get();

            $cliente = AlpClientes::where('id_user_client', $user_id )->first();

            $user = User::where('id', $user_id )->first();

             $clientes_amigos=AlpClientes::where('id_embajador',$user_id )->selectRaw('count(*) as cantidad')->first();

            $amigos_amigos=AlpAmigos::where('id_cliente',$user_id )->selectRaw('count(*) as cantidad')->first();


            $cantidad=$clientes_amigos->cantidad+$amigos_amigos->cantidad;

            $mensaje['tipo']='danger';

            $mensaje['mensaje']='Se ha eliminado satisfactoriamente';


            $configuracion = AlpConfiguracion::where('id', '1')->first();


            $view= View::make('frontend.clientes.listamigo', compact('amigos', 'cliente', 'user', 'cantidad', 'configuracion', 'mensaje'));

            $data=$view->render();

            return $data;

            

       
    }

    /*muestra un listado de las invitaciones enviadas */
    public function amigos()
    {



        if (Sentinel::check()) {

            $configuracion=AlpConfiguracion::where('id', 1)->first();

            $user_id = Sentinel::getUser()->id;

            $amigos=AlpAmigos::where('id_cliente', $user_id)->get();

            $cliente = AlpClientes::where('id_user_client', $user_id )->first();

            $user = User::where('id', $user_id )->first();


            $clientes_amigos=AlpClientes::where('id_embajador',$user_id )->selectRaw('count(*) as cantidad')->first();

            $amigos_amigos=AlpAmigos::where('id_cliente',$user_id )->selectRaw('count(*) as cantidad')->first();


            $cantidad=$clientes_amigos->cantidad+$amigos_amigos->cantidad;

            $states=State::where('config_states.country_id', '47')->get();

             $cart= \Session::get('cart');


            return \View::make('frontend.clientes.amigos', compact('amigos', 'cliente', 'user', 'configuracion', 'cantidad', 'configuracion', 'states', 'cart'));



    

            }else{


                $url='clientes';

                  //return redirect('login');
                return view('frontend.order.login', compact('url', 'configuracion'));


        }

       
    }

    /*muestra el total de las compras de sus amigos */

    public function compras($id)
    {

        if (Sentinel::check()) {


            $compras =  DB::table('alp_ordenes')->select('alp_ordenes.*','users.first_name as first_name','users.last_name as last_name' ,'users.email as email','alp_formas_envios.nombre_forma_envios as nombre_forma_envios','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago')
            ->join('users','alp_ordenes.id_cliente' , '=', 'users.id')
            ->join('alp_formas_envios','alp_ordenes.id_forma_envio' , '=', 'alp_formas_envios.id')
            ->join('alp_formas_pagos','alp_ordenes.id_forma_pago' , '=', 'alp_formas_pagos.id')
            ->where('alp_ordenes.id_cliente', $id)->get();

            $cliente = AlpClientes::where('id_user_client', $id )->first();

            $user = User::where('id', $id )->first();

            $states=State::where('config_states.country_id', '47')->get();

             $cart= \Session::get('cart');

                return \View::make('frontend.clientes.compras', compact('compras', 'cliente', 'user', 'states', 'cart'));

            }else{

                $url='clientes';

                  //return redirect('login');
                return view('frontend.order.login', compact('url'));

        }

       
    }

    /*muestra un liistado con mis compras*/

    public function miscompras()
    {

        if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

    
            $compras =  DB::table('alp_ordenes')->select('alp_ordenes.*','users.first_name as first_name','users.last_name as last_name' ,'users.email as email','alp_formas_envios.nombre_forma_envios as nombre_forma_envios','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_ordenes_pagos.json as json')
            ->join('users','alp_ordenes.id_cliente' , '=', 'users.id')
            ->join('alp_formas_envios','alp_ordenes.id_forma_envio' , '=', 'alp_formas_envios.id')
            ->join('alp_formas_pagos','alp_ordenes.id_forma_pago' , '=', 'alp_formas_pagos.id')
            ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
            ->leftJoin('alp_ordenes_pagos', 'alp_ordenes.id', '=', 'alp_ordenes_pagos.id_orden')
             ->groupBy('alp_ordenes.id')
            ->where('alp_ordenes.id_cliente', $user_id)->get();

            $pagos = array();

            foreach ($compras as $c) {

                    $p =  DB::table('alp_ordenes_pagos')
                    ->where('alp_ordenes_pagos.id_orden', $c->id)
                    ->where('alp_ordenes_pagos.id_estatus_pago', '2')
                    ->first();

                        if ( isset($p->id)) {
                            $pagos[$c->id]=1;
                        }
            }

            $cliente = AlpClientes::where('id_user_client', $user_id )->first();

            $user = User::where('id', $user_id )->first();

            $states=State::where('config_states.country_id', '47')->get();

             $cart= \Session::get('cart');

            return \View::make('frontend.clientes.miscompras', compact('compras', 'cliente', 'user', 'states', 'cart', 'pagos'));

            }else{

                $url='clientes';

                  //return redirect('login');
                return view('frontend.order.login', compact('url'));

        }

       
    }


     public function pagar( $orden)
    {




        $cart= \Session::forget('cart');

        $cart= array();

       $carrito= \Session::forget('cr');


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

        $detalles=AlpDetalles::where('id_orden', $orden)->get();

        foreach ($detalles as $det) {


            $producto=AlpProductos::select('alp_productos.*', 'alp_impuestos.valor_impuesto as valor_impuesto')
          ->join('alp_impuestos', 'alp_productos.id_impuesto', '=', 'alp_impuestos.id')
          ->where('alp_productos.id', $det->id_producto)
          ->first();

          $producto->cantidad=$det->cantidad;

          if (isset($producto->id)) {

            $cart[$producto->slug]=$producto;

          }

        }

        $ord=AlpOrdenes::where('id', $orden)->first();

        
        $data_update = array(
                      'estatus' =>8, 
                      'estatus_pago' =>4,
                       );

        $ord->update($data_update);

         \Session::put('cart', $cart);

         \Session::put('orden', $orden);

        return redirect('order/detail');

    }

    /*muestra el detalle de una compra*/

    public function detalle($id)
    {

        if (Sentinel::check()) {



            $orden=AlpOrdenes::where('id', $id)->first();





            $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.imagen_producto as imagen_producto','alp_productos.slug as slug')
            ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
            ->where('alp_ordenes_detalle.id_orden', $id)->get();

            
            $view= View::make('frontend.clientes.detalles', compact('detalles', 'orden'));

            $data=$view->render();

            $res = array('data' => $data);

            return $data;

           // return \View::make('frontend.clientes.compras', compact('detalles'));
    

            }else{


                $url='clientes';

                  //return redirect('login');
                return view('frontend.order.login', compact('url'));


        }

       
    }

    /*muetra el formulario para registro de embajadores */

    public function embajadores($id)
    {

        $states=State::where('config_states.country_id', '47')->get();

        $t_documento = AlpTDocumento::where('estado_registro','=',1)->get();

        $estructura = AlpEstructuraAddress::where('estado_registro','=',1)->get();

        $amigo=AlpAmigos::where('token', $id)->first();

        if (!isset($amigo->id)) {

            $mensaje="Su enlace de registro ha vencido, solicite uno nuevo o registrese como cliente";

            $states=State::where('config_states.country_id', '47')->get();

             $cart= \Session::get('cart');

            return view('frontend.clientes.aviso',  compact('mensaje', 'states', 'cart'));

        }
                
        return view('frontend.clientes.registro',  compact('id', 'amigo', 'states','t_documento','estructura'));

    }

    /*- no tiene uso */
 
    public function show($slug)
    {
        $producto = AlpProductos::where('slug','=', $slug)->firstOrFail();

        $states=State::where('config_states.country_id', '47')->get();

        $cart= \Session::get('cart');

        
        return \View::make('frontend.producto_single', compact('producto', 'cliente', 'states', 'cart'));

    }

    /*tampoco */

    public function categorias($slug)
    {
        $categoria = AlpCategorias::where('slug','=', $slug)->firstOrFail();

        $cataname = DB::table('alp_categorias')->select('nombre_categoria')->where('id','=', $categoria->id)->where('estado_registro','=', 1)->get();

        $productos =  DB::table('alp_productos')->select('alp_productos.*','alp_productos_category.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')
        ->where('alp_productos_category.id_categoria','=', $categoria->id)->paginate(8); 

        $states=State::where('config_states.country_id', '47')->get();

        $cart= \Session::get('cart');

        return \View::make('frontend.categorias', compact('productos','cataname','slug', 'states', 'cart'));

    }

    /*elimina uno de los amigos del cliente logeuado */

     public function deleteamigo(Request $request)
    {

        $id=$request->id;

        $cliente = AlpClientes::where('id_user_client', $id )->first();

        $user = User::where('id', $id )->first();

        $role = Sentinel::findRoleById(11);
                $role->users()->detach($user);

        $role = Sentinel::findRoleById(9);
                $role->users()->detach($user);

        $role = Sentinel::findRoleById(9);
                $role->users()->attach($user);

        /*se eliminar la relacion cliente -> amigo */

        $data_update_clinete = array(
            'id_embajador' => 0, 
        );

        $cliente->update($data_update_clinete);

        /*se recupera el usuario logueado para devolver la lista de amigos */

        $user_id = Sentinel::getUser()->id;



        $referidos =  DB::table('alp_clientes')->select('alp_clientes.*','users.first_name as first_name','users.last_name as last_name' ,'users.email as email', DB::raw("SUM(alp_ordenes.monto_total) as puntos"))
            ->join('users','alp_clientes.id_user_client' , '=', 'users.id')
            ->leftJoin('alp_ordenes','users.id' , '=', 'alp_ordenes.id_cliente')
            ->groupBy('alp_clientes.id')
            ->where('alp_clientes.id_embajador', $user_id)->get();


        $view= View::make('frontend.clientes.listamigos', compact('referidos'));

        $data=$view->render();

          return $data;
            
    }

    public function deldir(Request $request)
    {

        $id=$request->id;

      $user_id = Sentinel::getUser()->id;

          $direccion= AlpDirecciones::find($id);

          $direccion->delete();

        $direcciones = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_countries.country_name as country_name')
          ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
          ->where('alp_direcciones.id_client', $user_id)->get();

        $view= View::make('frontend.clientes.direcciones', compact('direcciones'));

        $data=$view->render();

        $res = array('data' => $data);

        //  return json_encode($res);
        return $data;
          
    }

     public function storedir(Request $request)
    {

        $user_id = Sentinel::getUser()->id;

        $user=User::where('id', $user_id)->first();

        $input = $request->all();

        $direccion=AlpDirecciones::where('id', $input['address_id'])->first();

        $direccion->update($input);

        $direcciones = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
          ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
          ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
          ->where('alp_direcciones.id_client', $user_id)->first();

          $editar=0;

        if (isset($direcciones->updated_at)) {

             $dt = new Carbon($direcciones->updated_at);

            if ($dt->diffInHours()>24) {

                $editar=1;
            } 
        }

        if ($direccion->id) {


            Activation::remove($user);
                        //add new record
            Activation::create($user);   



            $data_history = array(
                'id_cliente' => $user->id, 
                'estatus_cliente' => 'Actualización de Dirección',
                'notas' => 'Desactivado temporalmente por actualización de Dirección',
                'id_user' => $user_id
            );


            AlpClientesHistory::create($data_history);

            $data_cliente = array(
            'estado_masterfile' => 0
             );

        $cliente=AlpClientes::where('id_user_client', $user->id)->withTrashed()->first();
        
        $cliente->update($data_cliente);


         $user = Sentinel::getuser();
            activity($user->full_name)
                ->performedOn($user)
                ->causedBy($user)
                ->log('LoggedOut');
            // Log the user out
            Sentinel::logout();



          $view= View::make('frontend.clientes.direcciones', compact('direcciones', 'editar'));

          $data=$view->render();

          return $data;

        } else {

            return Redirect::route('order/detail')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
        }    

    }

   

}
