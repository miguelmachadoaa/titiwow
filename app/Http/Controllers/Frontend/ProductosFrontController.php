<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AlpProductos;
use App\Models\AlpCategorias;
use App\Models\AlpMarcas;
use App\Models\AlpClientes;
use App\Models\AlpEmpresas;
use App\Models\AlpPrecioGrupo;
use App\RoleUser;
use App\State;
use DB;
use Sentinel;

class ProductosFrontController extends Controller
{
    public function index()
    {


        $descuento='1'; 

        $precio = array();

        $leche =  DB::table('alp_productos')->select('alp_productos.*','alp_productos_category.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')
        ->where('alp_productos_category.id_categoria','=', 1)
        ->where('alp_productos.estado_registro','=',1)
        ->groupBy('alp_productos.id')
        ->inRandomOrder()
        ->take(4)
        ->get();

        $lacteos =  DB::table('alp_productos')->select('alp_productos.*','alp_productos_category.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')
        ->where('alp_productos_category.id_categoria','=', 2)
        ->where('alp_productos.estado_registro','=',1)
        ->groupBy('alp_productos.id')
        ->inRandomOrder()
        ->take(4)
        ->get();

        $quesos =  DB::table('alp_productos')->select('alp_productos.*','alp_productos_category.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')
        ->where('alp_productos_category.id_categoria','=', 3)
        ->where('alp_productos.estado_registro','=',1)
        ->groupBy('alp_productos.id')
        ->inRandomOrder()
        ->take(4)
        ->get();

        $postres =  DB::table('alp_productos')->select('alp_productos.*','alp_productos_category.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')
        ->where('alp_productos_category.id_categoria','=', 4)
        ->where('alp_productos.estado_registro','=',1)
        ->groupBy('alp_productos.id')
        ->inRandomOrder()
        ->take(4)
        ->get();

        $esparcibles =  DB::table('alp_productos')->select('alp_productos.*','alp_productos_category.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')
        ->where('alp_productos_category.id_categoria','=', 5)
        ->where('alp_productos.estado_registro','=',1)
        ->groupBy('alp_productos.id')
        ->inRandomOrder()
        ->take(4)
        ->get();

        $bebidas =  DB::table('alp_productos')->select('alp_productos.*','alp_productos_category.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')
        ->where('alp_productos_category.id_categoria','=', 6)
        ->where('alp_productos.estado_registro','=',1)
        ->groupBy('alp_productos.id')
        ->inRandomOrder()
        ->take(4)
        ->get();

        $finess =  DB::table('alp_productos')->select('alp_productos.*','alp_productos_category.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')
        ->where('alp_productos_category.id_categoria','=', 7)
        ->where('alp_productos.estado_registro','=',1)
        ->groupBy('alp_productos.id')
        ->inRandomOrder()
        ->take(4)
        ->get();

        $baby =  DB::table('alp_productos')->select('alp_productos.*','alp_productos_category.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')
        ->where('alp_productos_category.id_categoria','=', 8)
        ->where('alp_productos.estado_registro','=',1)
        ->groupBy('alp_productos.id')
        ->inRandomOrder()
        ->take(4)
        ->get();

        $nolacteos =  DB::table('alp_productos')->select('alp_productos.*','alp_productos_category.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')
        ->where('alp_productos_category.id_categoria','=', 9)
        ->where('alp_productos.estado_registro','=',1)
        ->groupBy('alp_productos.id')
        ->inRandomOrder()
        ->take(4)
        ->get();

        
        if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

            $role=RoleUser::where('user_id', $user_id)->first();

            $cliente = AlpClientes::where('id_user_client', $user_id )->first();

            if (isset($cliente) ) {

                if ($cliente->id_empresa!=0) {
                    
                     $empresa=AlpEmpresas::find($cliente->id_empresa);

                    $cliente['nombre_empresa']=$empresa->nombre_empresa;

                    $descuento=(1-($empresa->descuento_empresa/100));
                }
               
            }

            if ($role->role_id) {
               
                foreach ($leche as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;

                    }

                }

                foreach ($lacteos as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;

                    }

                }

                foreach ($quesos as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;

                    }

                }
                
                foreach ($postres as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;

                    }

                }

                foreach ($esparcibles as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;

                    }

                }

                foreach ($bebidas as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;

                    }

                }

                foreach ($finess as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;

                    }

                }

                foreach ($baby as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;

                    }

                }

                foreach ($nolacteos as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;

                    }

                }
                
            }

        }

        $cart= \Session::get('cart');

        $states=State::where('config_states.country_id', '47')->get();


        return \View::make('frontend.list', compact('leche','lacteos','quesos','postres','esparcibles','bebidas','finess','baby','nolacteos','descuento', 'precio', 'states', 'cart'));
    }
 
    public function show($slug)
    {

      $descuento='1'; 

        $precio = array(); 

            $producto =  DB::table('alp_productos')->select('alp_productos.*','alp_marcas.nombre_marca')
            ->join('alp_marcas','alp_productos.id_marca' , '=', 'alp_marcas.id')
            ->where('alp_productos.estado_registro','=',1)
            ->where('alp_productos.slug','=', $slug)->first(); 

           if($producto){
            $categos = DB::table('alp_categorias')->select('alp_categorias.nombre_categoria as nombre_categoria','alp_categorias.slug as categ_slug')
            ->join('alp_productos_category','alp_categorias.id' , '=', 'alp_productos_category.id_categoria')
            ->where('id_producto','=', $producto->id)->where('alp_categorias.estado_registro','=', 1)->groupBy('alp_categorias.id')->get();

           }else{
            abort('404');
           }

        if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

            $role=RoleUser::where('user_id', $user_id)->first();

            $cliente = AlpClientes::where('id_user_client', $user_id )->first();

            if (isset($cliente) ) {

                if ($cliente->id_empresa!=0) {
                    
                     $empresa=AlpEmpresas::find($cliente->id_empresa);

                    $cliente['nombre_empresa']=$empresa->nombre_empresa;

                    $descuento=(1-($empresa->descuento_empresa/100));
                }
               
            }

            if ($role->role_id) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $producto->id)->where('id_role', $role->role_id)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$producto->id]['precio']=$pregiogrupo->precio;
                        $precio[$producto->id]['operacion']=$pregiogrupo->operacion;

                    }

            }

        }

         $states=State::where('config_states.country_id', '47')->get();
        
        return \View::make('frontend.producto_single', compact('producto', 'descuento', 'precio','categos', 'states'));

    }

    public function categorias($slug)
    {


        $descuento='1'; 

        $precio = array();


        $categoria = AlpCategorias::where('slug','=', $slug)->firstOrFail();

        $cataname = DB::table('alp_categorias')->select('nombre_categoria','descripcion_categoria')->where('id','=', $categoria->id)->where('estado_registro','=', 1)->get();

        $productos =  DB::table('alp_productos')->select('alp_productos.*','alp_productos_category.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')
        ->where('alp_productos_category.id_categoria','=', $categoria->id)
        ->where('alp_productos.estado_registro','=',1)
        ->groupBy('alp_productos.id')
        ->paginate(12); 

         if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

            $role=RoleUser::where('user_id', $user_id)->first();

            $cliente = AlpClientes::where('id_user_client', $user_id )->first();

            if (isset($cliente) ) {

                if ($cliente->id_empresa!=0) {
                    
                     $empresa=AlpEmpresas::find($cliente->id_empresa);

                    $cliente['nombre_empresa']=$empresa->nombre_empresa;

                    $descuento=(1-($empresa->descuento_empresa/100));
                }
               
            }

            if ($role->role_id) {
               
                foreach ($productos as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;

                    }

                }
                
            }

        }

         $states=State::where('config_states.country_id', '47')->get();


        return \View::make('frontend.categorias', compact('productos','cataname','slug', 'descuento', 'precio', 'states'));

    }
    public function marcas($slug)
    {


        $descuento='1'; 

        $precio = array();


        $marca = AlpMarcas::where('slug','=', $slug)->firstOrFail();

        $marcaname = DB::table('alp_marcas')->select('nombre_marca','descripcion_marca','slug')->where('id','=', $marca->id)->where('estado_registro','=', 1)->get();

        $productos =  DB::table('alp_productos')->select('alp_productos.*')
        ->where('alp_productos.id_marca','=', $marca->id)
        ->where('alp_productos.estado_registro','=',1)
        ->groupBy('alp_productos.id')
        ->paginate(12); 


         if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

            $role=RoleUser::where('user_id', $user_id)->first();

            $cliente = AlpClientes::where('id_user_client', $user_id )->first();

            if (isset($cliente->id) ) {

                if ($cliente->id_empresa!=0) {
                    
                     $empresa=AlpEmpresas::find($cliente->id_empresa);

                    $cliente['nombre_empresa']=$empresa->nombre_empresa;

                    $descuento=(1-($empresa->descuento_empresa/100));
                }
               
            }

            if ($role->role_id) {
               
                foreach ($productos as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;

                    }

                }
                
            }

        }

         $states=State::where('config_states.country_id', '47')->get();


        return \View::make('frontend.marcas', compact('productos','marcaname','slug', 'descuento', 'precio', 'states'));

    }


    public function mySearch(Request $request)
    {
 
        $descuento='1'; 

        $precio = array();

        $termino = $request->get('buscar');

        $productos = AlpProductos::search($request->get('buscar'))->where('alp_productos.estado_registro','=', 1)->orderBy('id', 'asc')->paginate(12); 	
        $productos->appends(['buscar' => $request->get('buscar')]);

        if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

            $role=RoleUser::where('user_id', $user_id)->first();

            $cliente = AlpClientes::where('id_user_client', $user_id )->first();

            if (isset($cliente) ) {

                if ($cliente->id_empresa!=0) {
                    
                     $empresa=AlpEmpresas::find($cliente->id_empresa);

                    $cliente['nombre_empresa']=$empresa->nombre_empresa;

                    $descuento=(1-($empresa->descuento_empresa/100));
                }
               
            }

            if ($role->role_id) {
               
                foreach ($productos as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;

                    }

                }
                
            }

        }

         $states=State::where('config_states.country_id', '47')->get();

        return \View::make('frontend.buscar', compact('productos', 'descuento', 'precio', 'states','termino'));

    }

}
