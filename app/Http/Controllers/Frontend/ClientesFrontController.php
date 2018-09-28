<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AlpProductos;
use App\Models\AlpClientes;
use App\Models\AlpAmigos;
use App\User;
use App\Models\AlpCategorias;
use Sentinel;
use View;
use DB;     

class ClientesFrontController extends Controller
{
    public function index()
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


            return \View::make('frontend.clientes.index', compact('referidos', 'cliente', 'user'));
    

            }else{


                $url='clientes.index';

                  //return redirect('login');
                return view('frontend.order.login', compact('url'));


        }

       
    }

    public function storeamigo(Request $request)
    {

        if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

            $data = array(
                'id_cliente' => $user_id, 
                'nombre_amigo' => $request->nombre, 
                'apellido_amigo' => $request->apellido, 
                'email_amigo' => $request->email, 
                'id_user' => $user_id
            );

            AlpAmigos::create($data);

            $amigos=AlpAmigos::where('id_cliente', $user_id)->get();

            $cliente = AlpClientes::where('id_user_client', $user_id )->first();

            $user = User::where('id', $user_id )->first();


            $view= View::make('frontend.clientes.listamigo', compact('amigos', 'cliente', 'user'));

            $data=$view->render();

            return $data;

            }

       
    }

    public function delamigo(Request $request)
    {

            $user_id = Sentinel::getUser()->id;

            $amigo=AlpAmigos::find($request->id);

            $amigo->delete();

            $amigos=AlpAmigos::where('id_cliente', $user_id)->get();

            $cliente = AlpClientes::where('id_user_client', $user_id )->first();

            $user = User::where('id', $user_id )->first();


            $view= View::make('frontend.clientes.listamigo', compact('amigos', 'cliente', 'user'));

            $data=$view->render();

            return $data;

            

       
    }


    public function amigos()
    {

        if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

            $amigos=AlpAmigos::where('id_cliente', $user_id)->get();

            $cliente = AlpClientes::where('id_user_client', $user_id )->first();

            $user = User::where('id', $user_id )->first();


            return \View::make('frontend.clientes.amigos', compact('amigos', 'cliente', 'user'));



    

            }else{


                $url='clientes.index';

                  //return redirect('login');
                return view('frontend.order.login', compact('url'));


        }

       
    }

    public function compras($id)
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

                return \View::make('frontend.clientes.compras', compact('compras', 'cliente', 'user'));

            }else{

                $url='clientes.index';

                  //return redirect('login');
                return view('frontend.order.login', compact('url'));

        }

       
    }

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

                $url='clientes.index';

                  //return redirect('login');
                return view('frontend.order.login', compact('url'));

        }

       
    }

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


                $url='clientes.index';

                  //return redirect('login');
                return view('frontend.order.login', compact('url'));


        }

       
    }

    public function embajadores($id)
    {
                
        return view('frontend.clientes.registro',  compact('id'));
        

    }
 
    public function show($slug)
    {
        $producto = AlpProductos::where('slug','=', $slug)->firstOrFail();
        
        return \View::make('frontend.producto_single', compact('producto', 'cliente'));

    }

    public function categorias($slug)
    {
        $categoria = AlpCategorias::where('slug','=', $slug)->firstOrFail();

        $cataname = DB::table('alp_categorias')->select('nombre_categoria')->where('id','=', $categoria->id)->where('estado_registro','=', 1)->get();

        $productos =  DB::table('alp_productos')->select('alp_productos.*','alp_productos_category.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')
        ->where('alp_productos_category.id_categoria','=', $categoria->id)->paginate(8); 

        return \View::make('frontend.categorias', compact('productos','cataname','slug'));

    }

}
