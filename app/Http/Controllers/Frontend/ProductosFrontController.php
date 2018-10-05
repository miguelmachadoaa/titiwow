<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AlpProductos;
use App\Models\AlpCategorias;
use App\Models\AlpClientes;
use App\Models\AlpEmpresas;
use DB;
use Sentinel;

class ProductosFrontController extends Controller
{
    public function index()
    {


        $descuento='1'; 

        if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

            $cliente = AlpClientes::where('id_user_client', $user_id )->first();

            if (isset($cliente) ) {

                if ($cliente->id_empresa!=0) {
                    
                     $empresa=AlpEmpresas::find($cliente->id_empresa);

                    $cliente['nombre_empresa']=$empresa->nombre_empresa;

                    $descuento=(1-($empresa->descuento_empresa/100));
                }

               
               
            }

        }

        $productos = AlpProductos::paginate(9);

        return \View::make('frontend.list', compact('productos', 'descuento'));
    }
 
    public function show($slug)
    {

       $descuento='1'; 

        if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

            $cliente = AlpClientes::where('id_user_client', $user_id )->first();

            if (isset($cliente) ) {

                if ($cliente->id_empresa!=0) {
                    
                     $empresa=AlpEmpresas::find($cliente->id_empresa);

                    $cliente['nombre_empresa']=$empresa->nombre_empresa;

                    $descuento=(1-($empresa->descuento_empresa/100));
                }

               
               
            }

        }



        $producto = AlpProductos::where('slug','=', $slug)->firstOrFail();
        
        return \View::make('frontend.producto_single', compact('producto', 'descuento'));

    }

    public function categorias($slug)
    {


        $descuento='1'; 

        if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

            $cliente = AlpClientes::where('id_user_client', $user_id )->first();

            if (isset($cliente) ) {

                if ($cliente->id_empresa!=0) {
                    
                     $empresa=AlpEmpresas::find($cliente->id_empresa);

                    $cliente['nombre_empresa']=$empresa->nombre_empresa;

                    $descuento=(1-($empresa->descuento_empresa/100));
                }

               
               
            }

        }



        $categoria = AlpCategorias::where('slug','=', $slug)->firstOrFail();

        $cataname = DB::table('alp_categorias')->select('nombre_categoria')->where('id','=', $categoria->id)->where('estado_registro','=', 1)->get();

        $productos =  DB::table('alp_productos')->select('alp_productos.*','alp_productos_category.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')
        ->where('alp_productos_category.id_categoria','=', $categoria->id)->paginate(9); 

        return \View::make('frontend.categorias', compact('productos','cataname','slug', 'descuento'));

    }

}
