<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AlpProductos;
use App\Models\AlpClientes;
use App\User;
use App\Models\AlpCategorias;
use Sentinel;
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
            ->where('alp_clientes.id_referido', $user_id)->get();

            $cliente = AlpClientes::where('id_user_client', $user_id )->first();

            $user = User::where('id', $user_id )->first();


            return \View::make('frontend.clientes.index', compact('referidos', 'cliente', 'user'));
    

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
