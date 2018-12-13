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
use App\Models\AlpCms;
use App\RoleUser;
use App\State;
use DB;
use Sentinel;

class ProductosFrontController extends Controller
{

    private function addOferta($productos, $precio, $descuento){

    $prods = array( );

        foreach ($productos as $producto) {

      if ($descuento=='1') {

        if (isset($precio[$producto->id])) {
          # code...
         
          switch ($precio[$producto->id]['operacion']) {

            case 1:

              $producto->precio_oferta=$producto->precio_base*$descuento;

              break;

            case 2:

              $producto->precio_oferta=$producto->precio_base*(1-($precio[$producto->id]['precio']/100));
              
              break;

            case 3:

              $producto->precio_oferta=$precio[$producto->id]['precio'];
              
              break;
            
            default:
            
             $producto->precio_oferta=$producto->precio_base*$descuento;
              # code...
              break;
          }

        }else{

          $producto->precio_oferta=$producto->precio_base*$descuento;

        }


       }else{

       $producto->precio_oferta=$producto->precio_base*$descuento;


       }


       // $producto->impuesto=$producto->precio_oferta*$producto->valor_impuesto;


      // $cart[$producto->slug]=$producto;

       $prods[]=$producto;
       
      }

      return $prods;


    }



    public function index()
    {


        $descuento='1'; 

        $precio = array();

        $leche =  DB::table('alp_productos')->select('alp_productos.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')
        ->where('alp_productos_category.id_categoria','=', 1)
        ->where('alp_productos.estado_registro','=',1)
        ->groupBy('alp_productos.id')
        ->inRandomOrder()
        ->take(4)
        ->get();

        $lacteos =  DB::table('alp_productos')->select('alp_productos.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')
        ->where('alp_productos_category.id_categoria','=', 2)
        ->where('alp_productos.estado_registro','=',1)
        ->groupBy('alp_productos.id')
        ->inRandomOrder()
        ->take(4)
        ->get();

        $quesos =  DB::table('alp_productos')->select('alp_productos.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')
        ->where('alp_productos_category.id_categoria','=', 3)
        ->where('alp_productos.estado_registro','=',1)
        ->groupBy('alp_productos.id')
        ->inRandomOrder()
        ->take(4)
        ->get();

        $postres =  DB::table('alp_productos')->select('alp_productos.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')
        ->where('alp_productos_category.id_categoria','=', 4)
        ->where('alp_productos.estado_registro','=',1)
        ->groupBy('alp_productos.id')
        ->inRandomOrder()
        ->take(4)
        ->get();

        $esparcibles =  DB::table('alp_productos')->select('alp_productos.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')
        ->where('alp_productos_category.id_categoria','=', 5)
        ->where('alp_productos.estado_registro','=',1)
        ->groupBy('alp_productos.id')
        ->inRandomOrder()
        ->take(4)
        ->get();

        $bebidas =  DB::table('alp_productos')->select('alp_productos.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')
        ->where('alp_productos_category.id_categoria','=', 6)
        ->where('alp_productos.estado_registro','=',1)
        ->groupBy('alp_productos.id')
        ->inRandomOrder()
        ->take(4)
        ->get();

        $finess =  DB::table('alp_productos')->select('alp_productos.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')
        ->where('alp_productos_category.id_categoria','=', 7)
        ->where('alp_productos.estado_registro','=',1)
        ->groupBy('alp_productos.id')
        ->inRandomOrder()
        ->take(4)
        ->get();

        $baby =  DB::table('alp_productos')->select('alp_productos.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')
        ->where('alp_productos_category.id_categoria','=', 8)
        ->where('alp_productos.estado_registro','=',1)
        ->groupBy('alp_productos.id')
        ->inRandomOrder()
        ->take(4)
        ->get();

        $nolacteos =  DB::table('alp_productos')->select('alp_productos.*')
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
                    
                    /* $empresa=AlpEmpresas::find($cliente->id_empresa);

                    $cliente['nombre_empresa']=$empresa->nombre_empresa;

                    $descuento=(1-($empresa->descuento_empresa/100));*/

                    $role->role_id='E'.$cliente->id_empresa.'';
                }
               
            }

            if ($role->role_id) {
               
                foreach ($leche as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;

                    }

                }

                foreach ($lacteos as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;

                    }

                }

                foreach ($quesos as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;

                    }

                }
                
                foreach ($postres as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;

                    }

                }

                foreach ($esparcibles as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;

                    }

                }

                foreach ($bebidas as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;

                    }

                }

                foreach ($finess as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;

                    }

                }

                foreach ($baby as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;

                    }

                }

                foreach ($nolacteos as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;

                    }

                }
                
            }

        }



    $nolacteos=$this->addOferta($nolacteos, $precio, $descuento);
    $baby=$this->addOferta($baby, $precio, $descuento);
    $finess=$this->addOferta($finess, $precio, $descuento);
    $bebidas=$this->addOferta($bebidas, $precio, $descuento);
    $esparcibles=$this->addOferta($esparcibles, $precio, $descuento);
    $postres=$this->addOferta($postres, $precio, $descuento);
    $quesos=$this->addOferta($quesos, $precio, $descuento);
    $lacteos=$this->addOferta($lacteos, $precio, $descuento);
    $leche=$this->addOferta($leche, $precio, $descuento);



        $cart= \Session::get('cart');


        $total=0;

        if($cart!=NULL){

            foreach($cart as $row) {

                $total=$total+($row->cantidad*$row->precio_oferta);

            }
        }

       // dd($cart);


        $states=State::where('config_states.country_id', '47')->get();


        return \View::make('frontend.list', compact('leche','lacteos','quesos','postres','esparcibles','bebidas','finess','baby','nolacteos','descuento', 'precio', 'states', 'cart', 'total'));
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
            
            $catprincipal = DB::table('alp_productos')->select('alp_categorias.nombre_categoria as nombre_categoria','alp_categorias.slug as categ_slug')
            ->join('alp_categorias','alp_productos.id_categoria_default' , '=', 'alp_categorias.id')
            ->where('alp_productos.id','=', $producto->id)->where('alp_productos.estado_registro','=', 1)->get();

           }else{
            abort('404');
           }

        if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

            $role=RoleUser::where('user_id', $user_id)->first();

            $cliente = AlpClientes::where('id_user_client', $user_id )->first();

            if (isset($cliente) ) {

                if ($cliente->id_empresa!=0) {
                    
                     /*$empresa=AlpEmpresas::find($cliente->id_empresa);

                    $cliente['nombre_empresa']=$empresa->nombre_empresa;

                    $descuento=(1-($empresa->descuento_empresa/100));*/

                    $role->role_id='E'.$cliente->id_empresa.'';
                }
               
            }

            if ($role->role_id) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $producto->id)->where('id_role', $role->role_id)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$producto->id]['precio']=$pregiogrupo->precio;
                        $precio[$producto->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$producto->id]['pum']=$pregiogrupo->pum;

                    }

            }

        }

        if ($descuento=='1') {

        if (isset($precio[$producto->id])) {
          # code...
         
          switch ($precio[$producto->id]['operacion']) {

            case 1:

              $producto->precio_oferta=$producto->precio_base*$descuento;

              break;

            case 2:

              $producto->precio_oferta=$producto->precio_base*(1-($precio[$producto->id]['precio']/100));
              
              break;

            case 3:

              $producto->precio_oferta=$precio[$producto->id]['precio'];
              
              break;
            
            default:
            
             $producto->precio_oferta=$producto->precio_base*$descuento;
              # code...
              break;
          }

        }else{

          $producto->precio_oferta=$producto->precio_base*$descuento;

        }


       }else{

       $producto->precio_oferta=$producto->precio_base*$descuento;


       }










         $states=State::where('config_states.country_id', '47')->get();

         $cart= \Session::get('cart');


        $total=0;

        if($cart!=NULL){

            foreach($cart as $row) {

                $total=$total+($row->cantidad*$row->precio_oferta);

            }
        }


        
        return \View::make('frontend.producto_single', compact('producto', 'descuento', 'precio','categos', 'states', 'cart', 'total','catprincipal'));

    }

    public function categorias($slug)
    {


        $descuento='1'; 

        $precio = array();


        $categoria = AlpCategorias::where('slug','=', $slug)->firstOrFail();

        $cataname = DB::table('alp_categorias')->select('nombre_categoria','descripcion_categoria')->where('id','=', $categoria->id)->where('estado_registro','=', 1)->get();

        $productos =  DB::table('alp_productos')->select('alp_productos.*')
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
                    
                    /* $empresa=AlpEmpresas::find($cliente->id_empresa);

                    $cliente['nombre_empresa']=$empresa->nombre_empresa;

                    $descuento=(1-($empresa->descuento_empresa/100));*/

                    $role->role_id='E'.$cliente->id_empresa.'';
                }
               
            }

            if ($role->role_id) {
               
                foreach ($productos as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;
                        $precio[$row->id]['role']=$pregiogrupo->id_role;

                    }

                }
                
            }

        }

        //print_r($precio);
       // print_r($role->role_id);

    $productos=$this->addOferta($productos, $precio, $descuento);


         $states=State::where('config_states.country_id', '47')->get();

         $cart= \Session::get('cart');


        $total=0;

        if($cart!=NULL){

            foreach($cart as $row) {

                $total=$total+($row->cantidad*$row->precio_oferta);

            }
        }



        return \View::make('frontend.categorias', compact('productos','cataname','slug', 'descuento', 'precio', 'states', 'cart', 'total'));

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
                    
                   /*  $empresa=AlpEmpresas::find($cliente->id_empresa);

                    $cliente['nombre_empresa']=$empresa->nombre_empresa;

                    $descuento=(1-($empresa->descuento_empresa/100));*/

                    $role->role_id='E'.$cliente->id_empresa.'';
                }
               
            }

            if ($role->role_id) {
               
                foreach ($productos as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;

                    }

                }
                
            }

        }

        $cart= \Session::get('cart');

    $productos=$this->addOferta($productos, $precio, $descuento);



        $total=0;

        if($cart!=NULL){

            foreach($cart as $row) {

                $total=$total+($row->cantidad*$row->precio_oferta);

            }
        }


         $states=State::where('config_states.country_id', '47')->get();

        return \View::make('frontend.marcas', compact('productos','marcaname','slug', 'descuento', 'precio', 'states', 'cart', 'total'));

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
                    
                    /* $empresa=AlpEmpresas::find($cliente->id_empresa);

                    $cliente['nombre_empresa']=$empresa->nombre_empresa;

                    $descuento=(1-($empresa->descuento_empresa/100));*/

                    $role->role_id='E'.$cliente->id_empresa.'';

                }
               
            }

            if ($role->role_id) {
               
                foreach ($productos as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;

                    }

                }
                
            }

        }

        $cart= \Session::get('cart');

    $prods=$this->addOferta($productos, $precio, $descuento);




        $total=0;

        if($cart!=NULL){

            foreach($cart as $row) {

                $total=$total+($row->cantidad*$row->precio_oferta);

            }
        }


         $states=State::where('config_states.country_id', '47')->get();

        return \View::make('frontend.buscar', compact('productos', 'descuento', 'precio', 'states','termino', 'cart', 'total', 'prods'));

    }

    /* Pagina CMS */
    public function cms($slug)
    {

        $cms = AlpCms::where('slug','=', $slug)->firstOrFail();
        return \View::make('frontend.pagina_single', compact('cms'));

    }

    /*Fin CMS */

}
