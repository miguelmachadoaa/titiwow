<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AlpProductos;
use App\Models\AlpCategorias;
use DB;

class ProductosFrontController extends Controller
{
    public function index()
    {
        $productos = AlpProductos::paginate(8);
        return \View::make('frontend.list', compact('productos'));
    }
 
    public function show($slug)
    {
        $producto = AlpProductos::where('slug','=', $slug)->firstOrFail();
        
        return \View::make('frontend.producto_single', compact('producto'));

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
