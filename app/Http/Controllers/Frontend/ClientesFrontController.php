<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AlpProductos;
use App\Models\AlpClientes;
use App\Models\AlpEmpresas;
use App\Models\AlpDirecciones;
use App\Models\AlpAmigos;
use App\Models\AlpConfiguracion;
use App\User;
use App\Country;
use App\State;
use App\City;
use App\RoleUser;
use Illuminate\Support\Facades\Mail;
use App\Models\AlpCategorias;
use Sentinel;
use View;
use DB;     

class ClientesFrontController extends Controller
{

    public function index()
    {

        /*solo muestra el menu de opciones del cliente 
        verifi si esta logueado        */

        if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

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

            return \View::make('frontend.clientes.index', compact('referidos', 'cliente', 'user'));
    
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

             $direcciones = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_countries.country_name as country_name')
          ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
          ->where('alp_direcciones.id_client', $user_id)->get();

            $cliente = AlpClientes::where('id_user_client', $user_id )->first();

            $user = User::where('id', $user_id )->first();

            $countries = Country::all();

            return view('frontend.clientes.misdirecciones', compact('direcciones', 'cliente', 'user', 'countries'));
    

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

            return view('frontend.clientes.misamigos', compact('referidos', 'cliente', 'user'));
    

            }else{


                $url='clientes';

                  //return redirect('login');
                return view('frontend.order.login', compact('url'));


        }

       
    }

    /**/

    public function storeamigo(Request $request)
    {

        if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

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

         /*   Mail::to($request->email)->send(new \App\Mail\NotificacionAmigo($request->nombre, $request->apellido, $token, $user->first_name.' '.$user->last_name));*/




            $view= View::make('frontend.clientes.listamigo', compact('amigos', 'cliente', 'user', 'cantidad', 'configuracion'));

            $data=$view->render();

            return $data;

            }

       
    }

    /*elimina una invitacion a amigos del cliente  del cliente */

    public function delamigo(Request $request)
    {

            $user_id = Sentinel::getUser()->id;

            $amigo=AlpAmigos::find($request->id);

            $amigo->delete();

            $amigos=AlpAmigos::where('id_cliente', $user_id)->get();

            $cliente = AlpClientes::where('id_user_client', $user_id )->first();

            $user = User::where('id', $user_id )->first();

             $clientes_amigos=AlpClientes::where('id_embajador',$user_id )->selectRaw('count(*) as cantidad')->first();

            $amigos_amigos=AlpAmigos::where('id_cliente',$user_id )->selectRaw('count(*) as cantidad')->first();


            $cantidad=$clientes_amigos->cantidad+$amigos_amigos->cantidad;


            $configuracion = AlpConfiguracion::where('id', '1')->first();


            $view= View::make('frontend.clientes.listamigo', compact('amigos', 'cliente', 'user', 'cantidad', 'configuracion'));

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


            $configuracion = AlpConfiguracion::where('id', '1')->first();

            $clientes_amigos=AlpClientes::where('id_embajador',$user_id )->selectRaw('count(*) as cantidad')->first();

            $amigos_amigos=AlpAmigos::where('id_cliente',$user_id )->selectRaw('count(*) as cantidad')->first();


            $cantidad=$clientes_amigos->cantidad+$amigos_amigos->cantidad;


            return \View::make('frontend.clientes.amigos', compact('amigos', 'cliente', 'user', 'configuracion', 'cantidad', 'configuracion'));



    

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

                return \View::make('frontend.clientes.compras', compact('compras', 'cliente', 'user'));

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


            $compras =  DB::table('alp_ordenes')->select('alp_ordenes.*','users.first_name as first_name','users.last_name as last_name' ,'users.email as email','alp_formas_envios.nombre_forma_envios as nombre_forma_envios','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago')
            ->join('users','alp_ordenes.id_cliente' , '=', 'users.id')
            ->join('alp_formas_envios','alp_ordenes.id_forma_envio' , '=', 'alp_formas_envios.id')
            ->join('alp_formas_pagos','alp_ordenes.id_forma_pago' , '=', 'alp_formas_pagos.id')
            ->where('alp_ordenes.id_cliente', $user_id)->get();

            $cliente = AlpClientes::where('id_user_client', $user_id )->first();

            $user = User::where('id', $user_id )->first();

            return \View::make('frontend.clientes.miscompras', compact('compras', 'cliente', 'user'));

            }else{

                $url='clientes';

                  //return redirect('login');
                return view('frontend.order.login', compact('url'));

        }

       
    }

    /*muestra el detalle de una compra*/

    public function detalle($id)
    {

        if (Sentinel::check()) {

            $detalles =  DB::table('alp_ordenes_detalle')->select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.referencia_producto as referencia_producto' ,'alp_productos.imagen_producto as imagen_producto')
            ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
            ->where('alp_ordenes_detalle.id_orden', $id)->get();

            
            $view= View::make('frontend.clientes.detalles', compact('detalles'));

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

         $amigo=AlpAmigos::where('token', $id)->first();

        

        if (!isset($amigo->id)) {

                $mensaje="Su enlace de registro a vencido, solicite uno nuevo o registrese como cliente";

                return view('frontend.clientes.aviso',  compact('mensaje'));


        }
                
        return view('frontend.clientes.registro',  compact('id'));
        

    }

    /*- no tiene uso */
 
    public function show($slug)
    {
        $producto = AlpProductos::where('slug','=', $slug)->firstOrFail();
        
        return \View::make('frontend.producto_single', compact('producto', 'cliente'));

    }

    /*tampoco */

    public function categorias($slug)
    {
        $categoria = AlpCategorias::where('slug','=', $slug)->firstOrFail();

        $cataname = DB::table('alp_categorias')->select('nombre_categoria')->where('id','=', $categoria->id)->where('estado_registro','=', 1)->get();

        $productos =  DB::table('alp_productos')->select('alp_productos.*','alp_productos_category.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')
        ->where('alp_productos_category.id_categoria','=', $categoria->id)->paginate(8); 

        return \View::make('frontend.categorias', compact('productos','cataname','slug'));

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

          //return redirect('order/detail');
                 

          $view= View::make('frontend.clientes.direcciones', compact('direcciones'));

          $data=$view->render();

          $res = array('data' => $data);

        //  return json_encode($res);
          return $data;
            

        } else {

            return Redirect::route('order/detail')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
        }       

    }


}
