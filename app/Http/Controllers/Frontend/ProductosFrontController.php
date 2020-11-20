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
use App\Models\AlpInventario;
use App\Models\AlpCms;
use App\Models\AlpCombosProductos;

use App\Models\AlpAlmacenes;
use App\Models\AlpAlmacenDespacho;
use App\Models\AlpAlmacenRol;
use App\Models\AlpAlmacenProducto;
use App\Models\AlpDirecciones;
use App\Models\AlpRolenvio;
use App\Models\AlpBannerBusqueda;

use App\Models\AlpAnchetasCategorias;
use App\Models\AlpAnchetasProductos;




use App\RoleUser;
use App\State;
use App\City;
use App\User;
use App\Roles;
use DB;
use Sentinel;

class ProductosFrontController extends Controller
{

    private function addOferta($productos){


        $descuento='1'; 

        $precio = array();

        $ciudad= \Session::get('ciudad');

        if (Sentinel::check()) {



            $user_id = Sentinel::getUser()->id;

            $user=Sentinel::getUser();
             
            $role=RoleUser::where('user_id', $user_id)->first();

            $rol=$role->role_id;

            $d = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
              ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
              ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
              ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
              ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
              ->where('alp_direcciones.id_client', $user_id)
              ->where('alp_direcciones.default_address', '=', '1')
              ->first();

            if (isset($d->id)) {

            }else{

                  $d = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
                ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
                ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
                ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
                ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
                ->where('alp_direcciones.id_client', $user_id)
                ->first();
            }

            if (isset($d->id)) {
              $ciudad=$d->city_id;
            }



            $cliente = AlpClientes::where('id_user_client', $user_id )->first();

            if (isset($cliente) ) {

                if ($cliente->id_empresa!=0) {
                    
                    $role->role_id='E'.$role->role_id.'';
                }
               
            }

            if ($role->role_id) {

               
                foreach ($productos as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->where('city_id', $ciudad)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;
                        $precio[$row->id]['mostrar']=$pregiogrupo->mostrar_descuento;

                    }else{


                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $role->role_id)->first();

                      if (isset($pregiogrupo->id)) {
                         
                          $precio[$row->id]['precio']=$pregiogrupo->precio;
                          $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                          $precio[$row->id]['pum']=$pregiogrupo->pum;
                          $precio[$row->id]['mostrar']=$pregiogrupo->mostrar_descuento;


                      }

                    

                    }

                }
                
            }

        }else{

        

          $role = array( );

            $r='9';

                foreach ($productos as  $row) {

                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $r)->where('city_id', $ciudad)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;
                        $precio[$row->id]['mostrar']=$pregiogrupo->mostrar_descuento;


                    }else{

                      $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $r)->first();

                      if (isset($pregiogrupo->id)) {
                       
                          $precio[$row->id]['precio']=$pregiogrupo->precio;
                          $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                          $precio[$row->id]['pum']=$pregiogrupo->pum;
                          $precio[$row->id]['mostrar']=$pregiogrupo->mostrar_descuento;


                      }

                      

                    }

                }
                
        }



      // dd($precio);

        $prods = array( );

        foreach ($productos as $producto) {

          if ($descuento=='1') {

            if (isset($precio[$producto->id])) {
              # code...
             
              switch ($precio[$producto->id]['operacion']) {

                case 1:

                  $producto->precio_oferta=$producto->precio_base*$descuento;
                  $producto->pum=$precio[$producto->id]['pum'];
                  $producto->mostrar=$precio[$producto->id]['mostrar'];

                  break;

                case 2:

                  $producto->precio_oferta=$producto->precio_base*(1-($precio[$producto->id]['precio']/100));
                  $producto->pum=$precio[$producto->id]['pum'];
                  $producto->mostrar=$precio[$producto->id]['mostrar'];
                  
                  break;

                case 3:

                  $producto->precio_oferta=$precio[$producto->id]['precio'];
                  $producto->pum=$precio[$producto->id]['pum'];
                  $producto->mostrar=$precio[$producto->id]['mostrar'];
                  
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


           $prods[]=$producto;
           
          }

        return $prods;


    }



    public function index()
    {

       $id_almacen=$this->getAlmacen();



        $descuento='1'; 

        $precio = array();

        $leche =  DB::table('alp_productos')->select('alp_productos.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')

        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
        ->where('alp_almacen_producto.id_almacen', '=', $id_almacen)
        ->whereNull('alp_almacen_producto.deleted_at')

        ->whereNull('alp_productos_category.deleted_at')
        ->where('alp_productos_category.id_categoria','=', 1)
        ->where('alp_productos.estado_registro','=',1)
        ->where('alp_productos.mostrar','=',1)
        ->whereNull('alp_productos.deleted_at')
        ->groupBy('alp_productos.id')
        ->inRandomOrder()
        ->take(4)
        ->get();

        $lacteos =  DB::table('alp_productos')->select('alp_productos.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')

        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
       ->where('alp_almacen_producto.id_almacen', '=', $id_almacen)
       ->whereNull('alp_almacen_producto.deleted_at')
       ->whereNull('alp_productos.deleted_at')
        ->whereNull('alp_productos_category.deleted_at')
        ->where('alp_productos_category.id_categoria','=', 2)
        ->where('alp_productos.estado_registro','=',1)
        ->where('alp_productos.mostrar','=',1)
        ->groupBy('alp_productos.id')
        ->inRandomOrder()
        ->take(4)
        ->get();

        $quesos =  DB::table('alp_productos')->select('alp_productos.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')

        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
       ->where('alp_almacen_producto.id_almacen', '=', $id_almacen)
       ->whereNull('alp_almacen_producto.deleted_at')
       ->whereNull('alp_productos.deleted_at')
        ->whereNull('alp_productos_category.deleted_at')
        ->where('alp_productos_category.id_categoria','=', 3)
        ->where('alp_productos.estado_registro','=',1)
        ->where('alp_productos.mostrar','=',1)
        ->groupBy('alp_productos.id')
        ->inRandomOrder()
        ->take(4)
        ->get();

        $postres =  DB::table('alp_productos')->select('alp_productos.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')

        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
       ->where('alp_almacen_producto.id_almacen', '=', $id_almacen)
       ->whereNull('alp_almacen_producto.deleted_at')
       ->whereNull('alp_productos.deleted_at')
        ->whereNull('alp_productos_category.deleted_at')
        ->where('alp_productos_category.id_categoria','=', 4)
        ->where('alp_productos.estado_registro','=',1)
        ->where('alp_productos.mostrar','=',1)
        ->groupBy('alp_productos.id')
        ->inRandomOrder()
        ->take(4)
        ->get();

        $esparcibles =  DB::table('alp_productos')->select('alp_productos.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')

        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
       ->where('alp_almacen_producto.id_almacen', '=', $id_almacen)
       ->whereNull('alp_almacen_producto.deleted_at')
        ->whereNull('alp_productos.deleted_at')
        ->whereNull('alp_productos_category.deleted_at')
        ->where('alp_productos_category.id_categoria','=', 5)
        ->where('alp_productos.estado_registro','=',1)
        ->where('alp_productos.mostrar','=',1)
        ->groupBy('alp_productos.id')
        ->inRandomOrder()
        ->take(4)
        ->get();

        $bebidas =  DB::table('alp_productos')->select('alp_productos.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')

        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
       ->where('alp_almacen_producto.id_almacen', '=', $id_almacen)
       ->whereNull('alp_almacen_producto.deleted_at')
       ->whereNull('alp_productos.deleted_at')
        ->whereNull('alp_productos_category.deleted_at')
        ->where('alp_productos_category.id_categoria','=', 6)
        ->where('alp_productos.estado_registro','=',1)
        ->groupBy('alp_productos.id')
        ->inRandomOrder()
        ->take(4)
        ->get();

        $finess =  DB::table('alp_productos')->select('alp_productos.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')

        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
       ->where('alp_almacen_producto.id_almacen', '=', $id_almacen)
       ->whereNull('alp_almacen_producto.deleted_at')
       ->whereNull('alp_productos.deleted_at')
        ->whereNull('alp_productos_category.deleted_at')
        ->where('alp_productos_category.id_categoria','=', 7)
        ->where('alp_productos.estado_registro','=',1)
        ->where('alp_productos.mostrar','=',1)
        ->groupBy('alp_productos.id')
        ->inRandomOrder()
        ->take(4)
        ->get();

        $baby =  DB::table('alp_productos')->select('alp_productos.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')

        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
       ->where('alp_almacen_producto.id_almacen', '=', $id_almacen)
       ->whereNull('alp_almacen_producto.deleted_at')

       ->whereNull('alp_productos.deleted_at')
        ->whereNull('alp_productos_category.deleted_at')
        ->where('alp_productos_category.id_categoria','=', 8)
        ->where('alp_productos.estado_registro','=',1)
        ->where('alp_productos.mostrar','=',1)
        ->groupBy('alp_productos.id')
        ->inRandomOrder()
        ->take(4)
        ->get();

        $nolacteos =  DB::table('alp_productos')->select('alp_productos.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')

        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
       ->where('alp_almacen_producto.id_almacen', '=', $id_almacen)
       ->whereNull('alp_almacen_producto.deleted_at')

       ->whereNull('alp_productos.deleted_at')
        ->whereNull('alp_productos_category.deleted_at')
        ->where('alp_productos_category.id_categoria','=', 9)
        ->where('alp_productos.estado_registro','=',1)
        ->where('alp_productos.mostrar','=',1)
        ->groupBy('alp_productos.id')
        ->inRandomOrder()
        ->take(4)
        ->get();


        $nolacteos=$this->addOferta($nolacteos);
        $baby=$this->addOferta($baby);
        $finess=$this->addOferta($finess);
        $bebidas=$this->addOferta($bebidas);
        $esparcibles=$this->addOferta($esparcibles);
        $postres=$this->addOferta($postres);
        $quesos=$this->addOferta($quesos);
        $lacteos=$this->addOferta($lacteos);
        $leche=$this->addOferta($leche);

        $cart= \Session::get('cart');

        $total=0;

        if($cart!=NULL){

            foreach($cart as $row) {

                $total=$total+($row->cantidad*$row->precio_oferta);

            }
        }


        $states=State::where('config_states.country_id', '47')->get();

        $inventario=$this->inventario();

       //$role=Roles::where('id', $rol)->first();

        $almacen=AlpAlmacenes::where('id', $id_almacen)->first();

        $url=secure_url('/categorias');



        return \View::make('frontend.list', compact('leche','lacteos','quesos','postres','esparcibles','bebidas','finess','baby','nolacteos','descuento', 'precio', 'states', 'cart', 'total', 'inventario', 'almacen', 'url'));
    }
 
    public function show($slug)
    {

        $id_almacen=$this->getAlmacen();

        $rol='9';


      $descuento='1'; 

        $precio = array(); 

            $producto =  DB::table('alp_productos')->select('alp_productos.*','alp_marcas.nombre_marca','alp_marcas.slug  as marca_slug')

            ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
            ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
           ->where('alp_almacen_producto.id_almacen', '=', $id_almacen)
           ->whereNull('alp_almacen_producto.deleted_at')
           ->whereNull('alp_productos.deleted_at')
            ->join('alp_marcas','alp_productos.id_marca' , '=', 'alp_marcas.id')
            ->where('alp_productos.estado_registro','=',1)
            ->where('alp_productos.mostrar','=',1)
            ->where('alp_productos.slug','=', $slug)->first(); 


            if (isset($producto->tipo_producto)) {
              if ($producto->tipo_producto=='3') {
                \Session::put('producto_ancheta',  $producto);
              }
            }




           if($producto){


            $relacionados =  DB::table('alp_productos')->select('alp_productos.*','alp_marcas.nombre_marca','alp_marcas.slug  as marca_slug')

            ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
       ->where('alp_almacen_producto.id_almacen', '=', $id_almacen)
       ->whereNull('alp_almacen_producto.deleted_at')
       ->whereNull('alp_productos.deleted_at')

            ->join('alp_marcas','alp_productos.id_marca' , '=', 'alp_marcas.id')
            ->where('alp_productos.estado_registro','=',1)
            ->where('alp_productos.id_categoria_default','=', $producto->id_categoria_default)
            ->where('alp_productos.id','!=', $producto->id)
            ->groupBy('alp_productos.id')
        ->orderBy('alp_productos.updated_at', 'desc')
           // ->inRandomOrder()
          ->take(4)->get();


            $categos = DB::table('alp_categorias')->select('alp_categorias.nombre_categoria as nombre_categoria','alp_categorias.slug as categ_slug')


            ->join('alp_productos_category','alp_categorias.id' , '=', 'alp_productos_category.id_categoria')
            ->whereNull('alp_productos_category.deleted_at')

            ->where('id_producto','=', $producto->id)->where('alp_categorias.estado_registro','=', 1)->groupBy('alp_categorias.id')->get();
            
            $catprincipal = DB::table('alp_productos')->select('alp_categorias.nombre_categoria as nombre_categoria','alp_categorias.slug as categ_slug')
            ->join('alp_categorias','alp_productos.id_categoria_default' , '=', 'alp_categorias.id')
            ->where('alp_productos.id','=', $producto->id)->where('alp_productos.estado_registro','=', 1)->get();

           }else{

            abort('404');

           }


           $ciudad= \Session::get('ciudad');



        if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

            $role=RoleUser::where('user_id', $user_id)->first();

            $rol=$role->role_id;

            $cliente = AlpClientes::where('id_user_client', $user_id )->first();


            $d = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
              ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
              ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
              ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
              ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
              ->where('alp_direcciones.id_client', $user_id)
              ->where('alp_direcciones.default_address', '=', '1')
              ->first();

            if (isset($d->id)) {

            }else{

                  $d = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
                ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
                ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
                ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
                ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
                ->where('alp_direcciones.id_client', $user_id)
                ->first();
            }

            if (isset($d->id)) {
              $ciudad=$d->city_id;
            }



            if (isset($cliente) ) {

                if ($cliente->id_empresa!=0) {
                    

                    $role->role_id='E'.$cliente->id_empresa.'';
                }
               
            }

            if ($role->role_id) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $producto->id)->where('id_role', $role->role_id)->where('city_id', $ciudad)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$producto->id]['precio']=$pregiogrupo->precio;
                        $precio[$producto->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$producto->id]['pum']=$pregiogrupo->pum;

                    }else{


                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $producto->id)->where('id_role', $role->role_id)->first();

                      if (isset($pregiogrupo->id)) {
                         
                          $precio[$producto->id]['precio']=$pregiogrupo->precio;
                          $precio[$producto->id]['operacion']=$pregiogrupo->operacion;
                          $precio[$producto->id]['pum']=$pregiogrupo->pum;

                      }
                      }

                   

            }

        }else{

          

            $r='9';
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $producto->id)->where('id_role', $r)->where('city_id', $ciudad)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$producto->id]['precio']=$pregiogrupo->precio;
                        $precio[$producto->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$producto->id]['pum']=$pregiogrupo->pum;

                    }else{


                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $producto->id)->where('id_role', $r)->first();

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

       
    //   dd($precio);

    
    $prods=$this->addOferta($relacionados);





        $states=State::where('config_states.country_id', '47')->get();

        $cart= \Session::get('cart');

        $total=0;
        if($cart!=NULL){

            foreach($cart as $row) {

                $total=$total+($row->cantidad*$row->precio_oferta);

            }
        }

        $inventario=$this->inventario();

        //dd($inventario[$producto->id]);

        $combos=$this->combos();

       // dd($combos);


        if ($producto->precio_base<=0 || $producto->precio_oferta<=0) {

        return redirect('/');

      }

      $role=Roles::where('id', $rol)->first();

     
      $url=secure_url('producto/'.$slug);

      $almacen=AlpAlmacenes::where('id', $id_almacen)->first();


      if ($producto->enlace_youtube==null) {
        # code...
      }else{

        $ytarray=explode("/", $producto->enlace_youtube);
        $ytendstring=end($ytarray);
        $ytendarray=explode("?v=", $ytendstring);
        $ytendstring=end($ytendarray);
        $ytendarray=explode("&", $ytendstring);
        $ytcode=$ytendarray[0];

        $producto->enlace_youtube=$ytcode;

      }



      if (!\Session::has('cartancheta')) {

          \Session::put('cartancheta',   array());

        }

       $cartancheta= \Session::get('cartancheta');





      if ($producto->tipo_producto==3) {

        $producto->cantidad=1;

        $cartancheta[$producto->slug]=$producto;

         \Session::put('cartancheta',  $cartancheta);

        $anchetas_categorias=AlpAnchetasCategorias::where('id_ancheta', $producto->id)->get();

        foreach ($anchetas_categorias as $c) {

          $productos=AlpProductos::select('alp_productos.*')
          ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
          ->join('alp_ancheta_productos', 'alp_productos.id', '=', 'alp_ancheta_productos.id_producto')
          ->where('alp_ancheta_productos.id_ancheta_categoria', $c->id)
          ->where('alp_almacen_producto.id_almacen', '=', 1)
          ->whereNull('alp_ancheta_productos.deleted_at')
          ->whereNull('alp_almacen_producto.deleted_at')
          ->groupBy('alp_productos.id')
          ->get();

           $productos=$this->addOferta($productos);

          $c->productos=$productos;
          
        }


        return \View::make('frontend.ancheta', compact('producto', 'descuento', 'precio','categos', 'states', 'cart', 'total','catprincipal', 'relacionados', 'prods', 'inventario', 'combos', 'role', 'almacen', 'url', 'anchetas_categorias', 'cartancheta'));
      


      }else{

        return \View::make('frontend.producto_single', compact('producto', 'descuento', 'precio','categos', 'states', 'cart', 'total','catprincipal', 'relacionados', 'prods', 'inventario', 'combos', 'role', 'almacen', 'url'));


      }

        
        

    }

    public function categorias($slug)
    {

        $id_almacen=$this->getAlmacen();

        $almacen=AlpAlmacenes::where('id', $id_almacen)->first();

        $rol=9;

        $descuento='1'; 

        $precio = array();


        $categoria = AlpCategorias::where('slug','=', $slug)->firstOrFail();

        $cataname = DB::table('alp_categorias')->select('nombre_categoria','seo_titulo','seo_descripcion')->where('id','=', $categoria->id)->where('estado_registro','=', 1)->get();


        $productos =  DB::table('alp_productos')->select('alp_productos.*', 'alp_marcas.order as order')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')
        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
       ->where('alp_almacen_producto.id_almacen', '=', $id_almacen)
       ->whereNull('alp_almacen_producto.deleted_at')
       ->whereNull('alp_productos.deleted_at')
        ->join('alp_marcas','alp_productos.id_marca' , '=', 'alp_marcas.id')
        ->where('alp_productos_category.id_categoria','=', $categoria->id)
        ->whereNull('alp_productos_category.deleted_at')
        ->where('alp_productos.estado_registro','=',1)
        ->where('alp_productos.mostrar','=',1)
        ->groupBy('alp_productos.id')
        ->orderBy('alp_marcas.order') 
        ->orderBy('alp_productos.updated_at', 'desc')
        ->paginate(36); 
        //->get();


        //dd($productos);


        $prods=$this->addOferta($productos);

        $states=State::where('config_states.country_id', '47')->get();

        $cart= \Session::get('cart');


        $destacados =  DB::table('alp_productos')->select('alp_productos.*', 'alp_marcas.order as order')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')
        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
        ->join('alp_categoria_destacado', 'alp_productos.id', '=', 'alp_categoria_destacado.id_producto')
       ->where('alp_categoria_destacado.id_almacen', '=', $id_almacen)
       ->where('alp_categoria_destacado.id_categoria', '=', $categoria->id)
       ->whereNull('alp_categoria_destacado.deleted_at')
       ->whereNull('alp_almacen_producto.deleted_at')
       ->whereNull('alp_productos.deleted_at')
        ->join('alp_marcas','alp_productos.id_marca' , '=', 'alp_marcas.id')
        ->whereNull('alp_productos_category.deleted_at')
        ->where('alp_productos.estado_registro','=',1)
        ->where('alp_productos.mostrar','=',1)
        ->groupBy('alp_productos.id')
        ->orderBy('alp_marcas.order') 
        ->orderBy('alp_productos.updated_at', 'desc')
        ->paginate(36); 

        $destacados=$this->addOferta($destacados);

        $total=0;

        if($cart!=NULL){

            foreach($cart as $row) {

                $total=$total+($row->cantidad*$row->precio_oferta);

            }
        }

        $inventario=$this->inventario();

        $combos=$this->combos();

        $url=secure_url('categoria/'.$slug);

        

        return \View::make('frontend.categorias', compact('productos','cataname','slug', 'descuento', 'precio', 'states', 'cart', 'total', 'prods', 'inventario', 'combos','url', 'categoria', 'almacen', 'destacados'));

    }
    public function marcas($slug)
    {


         $id_almacen=$this->getAlmacen();


         $rol=9;

        $descuento='1'; 

        $precio = array();


        $marca = AlpMarcas::where('slug','=', $slug)->firstOrFail();

        $marcaname = DB::table('alp_marcas')->select('nombre_marca','seo_titulo','seo_descripcion','slug')->where('id','=', $marca->id)->where('estado_registro','=', 1)->get();

        $productos =  DB::table('alp_productos')->select('alp_productos.*', 'alp_marcas.order as order')
        ->join('alp_marcas','alp_productos.id_marca' , '=', 'alp_marcas.id')

        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
       ->where('alp_almacen_producto.id_almacen', '=', $id_almacen)
       ->whereNull('alp_almacen_producto.deleted_at')
       ->whereNull('alp_productos.deleted_at')

        ->where('alp_productos.id_marca','=', $marca->id)
        ->where('alp_productos.estado_registro','=',1)
        ->where('alp_productos.mostrar','=',1)
        ->groupBy('alp_productos.id')
        ->orderBy('alp_marcas.order') 
        
        ->orderBy('alp_productos.updated_at', 'desc')
        ->paginate(36); 


        $cart= \Session::get('cart');

    $prods=$this->addOferta($productos);


        $total=0;

        if($cart!=NULL){

            foreach($cart as $row) {

                $total=$total+($row->cantidad*$row->precio_oferta);

            }
        }


         $states=State::where('config_states.country_id', '47')->get();

        $inventario=$this->inventario();

          $combos=$this->combos();

          $role=Roles::where('id', $rol)->first();

         

           $almacen=AlpAlmacenes::where('id', $id_almacen)->first();

           $url=secure_url('marcas/'.$slug);

           $marcaun = AlpMarcas::where('slug','=', $slug)->firstOrFail();

           //dd($precio);



        return \View::make('frontend.marcas', compact('productos','marcaname','slug', 'descuento', 'precio', 'states', 'cart', 'total', 'prods', 'inventario', 'combos', 'role', 'almacen', 'url', 'marcaun'));

    }

    public function all()
    {

         $id_almacen=$this->getAlmacen();

       // dd($id_almacen  );
         $rol=9;

        $descuento='1'; 

        $precio = array();


        $productos =  DB::table('alp_productos')->select('alp_productos.*', 'alp_marcas.order as order', 'alp_almacenes.id as id_almacen')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')
        ->join('alp_marcas','alp_productos.id_marca' , '=', 'alp_marcas.id')

        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
       ->where('alp_almacen_producto.id_almacen', '=', $id_almacen)
        ->whereNull('alp_productos.deleted_at')
        ->whereNull('alp_productos_category.deleted_at')
        ->whereNull('alp_almacen_producto.deleted_at')
        ->where('alp_productos.estado_registro','=',1)
        ->where('alp_productos.mostrar','=',1)
        ->groupBy('alp_productos.id')
        ->orderBy('alp_marcas.order', 'asc')
        ->orderBy('alp_productos.updated_at', 'desc') 
        ->paginate(36); 


        

    $prods=$this->addOferta($productos);


         $states=State::where('config_states.country_id', '47')->get();

         $cart= \Session::get('cart');

        $total=0;

        if($cart!=NULL){

            foreach($cart as $row) {

                $total=$total+($row->cantidad*$row->precio_oferta);

            }
        }

        $inventario=$this->inventario();

          $combos=$this->combos();

          $role=Roles::where('id', $rol)->first();


           $almacen=AlpAlmacenes::where('id', $id_almacen)->first();


           $url=secure_url('/productos');


        return \View::make('frontend.all', compact('productos', 'descuento', 'precio', 'states', 'cart', 'total', 'prods', 'inventario', 'combos', 'role', 'almacen', 'url'));

    }


    public function mySearch(Request $request)
    {


       $id_almacen=$this->getAlmacen();

        
        $rol=9;

        $descuento='1'; 

        $precio = array();

        $termino = $request->get('buscar');

        $banner=AlpBannerBusqueda::where('termino', '=', $termino)->first();

       // dd($termino);

        $productos = AlpProductos::
        search($request->get('buscar'))->select('alp_productos.*', 'alp_marcas.order as order')
        ->join('alp_marcas','alp_productos.id_marca' , '=', 'alp_marcas.id')

        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
       ->where('alp_almacen_producto.id_almacen', '=', $id_almacen)
       ->whereNull('alp_almacen_producto.deleted_at')


       ->whereNull('alp_productos.deleted_at')
        ->where('alp_productos.estado_registro','=', 1)
        ->where('alp_productos.mostrar','=',1)
        ->groupBy('alp_productos.id')
        ->orderBy('alp_marcas.order', 'asc')
        ->orderBy('alp_productos.updated_at', 'desc')
        ->paginate(36); 	
        //$productos->appends(['buscar' => $request->get('buscar')]);

        

        $cart= \Session::get('cart');

    $prods=$this->addOferta($productos);

        $total=0;

        if($cart!=NULL){

            foreach($cart as $row) {

                $total=$total+($row->cantidad*$row->precio_oferta);

            }
        }

        $inventario=$this->inventario();

        $combos=$this->combos();

        $role=Roles::where('id', $rol)->first();

         $states=State::where('config_states.country_id', '47')->get();

        

          $almacen=AlpAlmacenes::where('id', $id_almacen)->first();

          $url=secure_url('buscar?buscar='.$termino);

        return \View::make('frontend.buscar', compact('productos', 'descuento', 'precio', 'states','termino', 'cart', 'total', 'prods', 'inventario', 'combos', 'role', 'almacen', 'url', 'banner'));

    }

    /* Pagina CMS */
    public function cms($slug)
    {

        $cms = AlpCms::where('slug','=', $slug)->firstOrFail();


        $url=secure_url('paginas/'.$slug);

        return \View::make('frontend.pagina_single', compact('cms', 'url'));
        

    }

    /*Fin CMS */


    private function inventario()
    {
       
       $id_almacen=$this->getAlmacen();

      $entradas = AlpInventario::groupBy('id_producto')
              ->select("alp_inventarios.*", DB::raw(  "SUM(alp_inventarios.cantidad) as cantidad_total"))
              ->where('alp_inventarios.operacion', '1')
              ->where('alp_inventarios.id_almacen', '=', $id_almacen)
              ->get();

              $inv = array();

              foreach ($entradas as $row) {
                
                $inv[$row->id_producto]=$row->cantidad_total;

              }


            $salidas = AlpInventario::select("alp_inventarios.*", DB::raw(  "SUM(alp_inventarios.cantidad) as cantidad_total"))
              ->groupBy('id_producto')
              ->where('operacion', '2')
              ->where('alp_inventarios.id_almacen', '=', $id_almacen)
              ->get();

              foreach ($salidas as $row) {

                if (isset($inv[$row->id_producto])) {
                    $inv[$row->id_producto]= $inv[$row->id_producto]-$row->cantidad_total;
                }else{

                    $inv[$row->id_producto]=0;
                }
                
                

            }

            return $inv;
      
    }


    private function combos()
    {

      $c=AlpProductos::where('tipo_producto', '2')->get();
      
      $inventario=$this->inventario();

      $combos = array();

      foreach ($c as $co) {

        $ban=0;
        
        $lista=AlpCombosProductos::select('alp_combos_productos.*', 'alp_productos.slug as slug', 'alp_productos.nombre_producto as nombre_producto', 'alp_productos.imagen_producto as imagen_producto')
        ->join('alp_productos', 'alp_combos_productos.id_producto', '=', 'alp_productos.id')
        ->whereNull('alp_productos.deleted_at')
        ->where('id_combo', $co->id)
        ->get();

        foreach ($lista as $l) {

            if (isset($inventario[$l->id_producto])) {
                
                if($inventario[$l->id_producto]>0){

                }else{

                $ban=1;

                }

            }else{

                $ban=1;
            }
            
        }


        if ($ban==0) {

            $combos[$co->id]=$lista;
        }

      }

      return $combos;
    }


 private function getAlmacen(){



    $tipo=0;


        //if (isset(Sentinel::getUser()->id)) {
        if (1==0) {

            # code...
            $user_id = Sentinel::getUser()->id;

            $usuario=User::where('id', $user_id)->first();

            $user_cliente=User::where('id', $user_id)->first();

            $role=RoleUser::select('role_id')->where('user_id', $user_id)->first();

             $d = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
              ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
              ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
              ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
              ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
              ->where('alp_direcciones.id_client', $user_id)
              ->where('alp_direcciones.default_address', '=', '1')
              ->first();

            if (isset($d->id)) {

            }else{

                  $d = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
                ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
                ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
                ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
                ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
                ->where('alp_direcciones.id_client', $user_id)
                ->first();
            }

            if (isset($d->id)) {

              $tipo=0;

            if ($role->role_id=='14') {
              
              $tipo=1;
            }


                if ($d->id_barrio==0) {
                     $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')
                    ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')
                    ->where('alp_almacenes.tipo_almacen', '=', $tipo)
                    ->where('alp_almacen_despacho.id_city', $d->city_id)
                    ->where('alp_almacenes.estado_registro', '=', '1')
                    ->first();
                    
                }else{

                     $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')
                    ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')
                    ->where('alp_almacenes.tipo_almacen', '=', $tipo)
                    ->where('alp_almacen_despacho.id_barrio', $d->id_barrio)
                    ->where('alp_almacenes.estado_registro', '=', '1')
                    ->first();

                    if (isset($ad->id)) {
                        # code...
                    }else{


                        $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')
                        ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')
                        ->where('alp_almacenes.tipo_almacen', '=', $tipo)
                        ->where('alp_almacen_despacho.id_city', $d->city_id)
                        ->where('alp_almacenes.estado_registro', '=', '1')
                        ->first();
                    }


                }



               

                if (isset($ad->id)) {
                # code...
                }else{

                  $c=City::where('id', $d->city_id)->first();

                  $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')
                ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')
                ->where('alp_almacenes.tipo_almacen', '=', $tipo)
                ->where('alp_almacen_despacho.id_city', '0')
                ->where('alp_almacen_despacho.id_state', $c->state_id)
                ->where('alp_almacenes.estado_registro', '=', '1')
                ->first();

                  if (isset($ad->id)) {
                    
                  }else{

                    $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')
                  ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')
                  ->where('alp_almacenes.tipo_almacen', '=', $tipo)
                  ->where('alp_almacen_despacho.id_city', '0')
                  ->where('alp_almacen_despacho.id_state', '0')->where('alp_almacenes.estado_registro', '=', '1')->first();

                  }

                }

                if (isset($ad->id)) {

                  $almacen=AlpAlmacenes::where('id', $ad->id_almacen)->first();

                  $id_almacen=$almacen->id;
                  # code...
                }else{

                   $almacen=AlpAlmacenes::where('defecto', '1')->first();

                    if (isset($almacen->id)) {
                      $id_almacen=$almacen->id;
                    }else{
                      $id_almacen='1';
                    }

                }


















            }else{

              $almacen=AlpAlmacenes::where('defecto', '1')->first();

                if (isset($almacen->id)) {
                  $id_almacen=$almacen->id;
                }else{
                  $id_almacen='1';
                }
                   
            }

        }else{ //no esta logueado 


            $ciudad= \Session::get('ciudad');



            if (isset($ciudad)) {




              $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')
                ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')
                ->where('alp_almacenes.tipo_almacen', '=', $tipo)
                ->where('alp_almacen_despacho.id_city', $ciudad)
                ->where('alp_almacenes.estado_registro', '=', '1')
                ->first();

                if (isset($ad->id)) {
                # code...
                }else{

                  $c=City::where('id', $ciudad)->first();

                  if (isset($c->id)) {
                      $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')
                  ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')
                  ->where('alp_almacenes.tipo_almacen', '=', $tipo)
                  ->where('alp_almacen_despacho.id_city', '0')
                  ->where('alp_almacen_despacho.id_state', $c->state_id)
                  ->where('alp_almacenes.estado_registro', '=', '1')
                  ->first();
                  }

                  

                  if (isset($ad->id)) {
                    
                  }else{

                    $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')
                  ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')
                  ->where('alp_almacenes.tipo_almacen', '=', $tipo)
                  ->where('alp_almacen_despacho.id_city', '0')
                  ->where('alp_almacenes.estado_registro', '=', '1')
                  ->where('alp_almacen_despacho.id_state', '0')->first();

                  }

                }

                if (isset($ad->id)) {

                  $almacen=AlpAlmacenes::where('id', $ad->id_almacen)->where('alp_almacenes.estado_registro', '=', '1')->first();

                  $id_almacen=$almacen->id;
                  # code...
                }else{

                   $almacen=AlpAlmacenes::where('defecto', '1')->where('alp_almacenes.estado_registro', '=', '1')->first();

                    if (isset($almacen->id)) {

                      $id_almacen=$almacen->id;

                    }else{

                      $id_almacen='1';

                    }

                }













              
            }else{


               $almacen=AlpAlmacenes::where('defecto', '1')->where('alp_almacenes.estado_registro', '=', '1')->first();

              if (isset($almacen->id)) {
                  $id_almacen=$almacen->id;
                }else{
                  $id_almacen='1';
                }



            }







           
        
        }

       // dd($id_almacen);

      return $id_almacen;

    }

    




}
