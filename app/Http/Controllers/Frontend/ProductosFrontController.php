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

        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
        ->where('alp_almacenes.defecto', '=', 1)
        
        ->whereNull('alp_productos_category.deleted_at')
        ->where('alp_productos_category.id_categoria','=', 1)
        ->where('alp_productos.estado_registro','=',1)
        ->groupBy('alp_productos.id')
        ->inRandomOrder()
        ->take(4)
        ->get();

        $lacteos =  DB::table('alp_productos')->select('alp_productos.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')

        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
        ->where('alp_almacenes.defecto', '=', 1)

        ->whereNull('alp_productos_category.deleted_at')
        ->where('alp_productos_category.id_categoria','=', 2)
        ->where('alp_productos.estado_registro','=',1)
        ->groupBy('alp_productos.id')
        ->inRandomOrder()
        ->take(4)
        ->get();

        $quesos =  DB::table('alp_productos')->select('alp_productos.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')

        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
        ->where('alp_almacenes.defecto', '=', 1)

        ->whereNull('alp_productos_category.deleted_at')
        ->where('alp_productos_category.id_categoria','=', 3)
        ->where('alp_productos.estado_registro','=',1)
        ->groupBy('alp_productos.id')
        ->inRandomOrder()
        ->take(4)
        ->get();

        $postres =  DB::table('alp_productos')->select('alp_productos.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')

        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
        ->where('alp_almacenes.defecto', '=', 1)

        ->whereNull('alp_productos_category.deleted_at')
        ->where('alp_productos_category.id_categoria','=', 4)
        ->where('alp_productos.estado_registro','=',1)
        ->groupBy('alp_productos.id')
        ->inRandomOrder()
        ->take(4)
        ->get();

        $esparcibles =  DB::table('alp_productos')->select('alp_productos.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')

        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
        ->where('alp_almacenes.defecto', '=', 1)

        ->whereNull('alp_productos_category.deleted_at')
        ->where('alp_productos_category.id_categoria','=', 5)
        ->where('alp_productos.estado_registro','=',1)
        ->groupBy('alp_productos.id')
        ->inRandomOrder()
        ->take(4)
        ->get();

        $bebidas =  DB::table('alp_productos')->select('alp_productos.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')

        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
        ->where('alp_almacenes.defecto', '=', 1)

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
        ->where('alp_almacenes.defecto', '=', 1)

        ->whereNull('alp_productos_category.deleted_at')
        ->where('alp_productos_category.id_categoria','=', 7)
        ->where('alp_productos.estado_registro','=',1)
        ->groupBy('alp_productos.id')
        ->inRandomOrder()
        ->take(4)
        ->get();

        $baby =  DB::table('alp_productos')->select('alp_productos.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')

        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
        ->where('alp_almacenes.defecto', '=', 1)

        ->whereNull('alp_productos_category.deleted_at')
        ->where('alp_productos_category.id_categoria','=', 8)
        ->where('alp_productos.estado_registro','=',1)
        ->groupBy('alp_productos.id')
        ->inRandomOrder()
        ->take(4)
        ->get();

        $nolacteos =  DB::table('alp_productos')->select('alp_productos.*')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')

        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
        ->where('alp_almacenes.defecto', '=', 1)


        ->whereNull('alp_productos_category.deleted_at')
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

                } //copioa hasta aqui
                
            }

        }else{

            $r='9';
                

                foreach ($leche as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $r)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;

                    }

                }

                foreach ($lacteos as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $r)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;

                    }

                }

                foreach ($quesos as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $r)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;

                    }

                }
                
                foreach ($postres as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $r)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;

                    }

                }

                foreach ($esparcibles as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $r)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;

                    }

                }

                foreach ($bebidas as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $r)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;

                    }

                }

                foreach ($finess as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $r)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;

                    }

                }

                foreach ($baby as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $r)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;

                    }

                }

                foreach ($nolacteos as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $r)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;

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

        $inventario=$this->inventario();


        return \View::make('frontend.list', compact('leche','lacteos','quesos','postres','esparcibles','bebidas','finess','baby','nolacteos','descuento', 'precio', 'states', 'cart', 'total', 'inventario'));
    }
 
    public function show($slug)
    {

      $descuento='1'; 

        $precio = array(); 

            $producto =  DB::table('alp_productos')->select('alp_productos.*','alp_marcas.nombre_marca','alp_marcas.slug  as marca_slug')

            ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
        ->where('alp_almacenes.defecto', '=', 1)

            ->join('alp_marcas','alp_productos.id_marca' , '=', 'alp_marcas.id')
            ->where('alp_productos.estado_registro','=',1)
            ->where('alp_productos.slug','=', $slug)->first(); 


           if($producto){


            $relacionados =  DB::table('alp_productos')->select('alp_productos.*','alp_marcas.nombre_marca','alp_marcas.slug  as marca_slug')

            ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
        ->where('alp_almacenes.defecto', '=', 1)


            ->join('alp_marcas','alp_productos.id_marca' , '=', 'alp_marcas.id')
            ->where('alp_productos.estado_registro','=',1)
            ->where('alp_productos.id_categoria_default','=', $producto->id_categoria_default)
            ->where('alp_productos.id','!=', $producto->id)
            
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

                    foreach ($relacionados as $r) {

                        $pregiogrupo=AlpPrecioGrupo::where('id_producto', $r->id)->where('id_role', $role->role_id)->first();



                        if (isset($pregiogrupo->id)) {
                           
                            $precio[$r->id]['precio']=$pregiogrupo->precio;
                            $precio[$r->id]['operacion']=$pregiogrupo->operacion;
                            $precio[$r->id]['pum']=$pregiogrupo->pum;

                        }
                        
                    }

            }

        }else{

            $r='9';
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $producto->id)->where('id_role', $r)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$producto->id]['precio']=$pregiogrupo->precio;
                        $precio[$producto->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$producto->id]['pum']=$pregiogrupo->pum;

                    }


                      foreach ($relacionados as $rela) {

                        $pregiogrupo=AlpPrecioGrupo::where('id_producto', $rela->id)->where('id_role', $r)->first();


                        if (isset($pregiogrupo->id)) {
                           
                            $precio[$rela->id]['precio']=$pregiogrupo->precio;
                            $precio[$rela->id]['operacion']=$pregiogrupo->operacion;
                            $precio[$rela->id]['pum']=$pregiogrupo->pum;

                        }
                        
                    }

                
        }

        //dd($precio);

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

    
    $prods=$this->addOferta($relacionados, $precio, $descuento);





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

        
        return \View::make('frontend.producto_single', compact('producto', 'descuento', 'precio','categos', 'states', 'cart', 'total','catprincipal', 'relacionados', 'prods', 'inventario', 'combos'));

    }

    public function categorias($slug)
    {


        $descuento='1'; 

        $precio = array();


        $categoria = AlpCategorias::where('slug','=', $slug)->firstOrFail();

        $cataname = DB::table('alp_categorias')->select('nombre_categoria','seo_titulo','seo_descripcion')->where('id','=', $categoria->id)->where('estado_registro','=', 1)->get();

        $productos =  DB::table('alp_productos')->select('alp_productos.*', 'alp_marcas.order as order')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')

        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
        ->where('alp_almacenes.defecto', '=', 1)


        ->join('alp_marcas','alp_productos.id_marca' , '=', 'alp_marcas.id')
        ->where('alp_productos_category.id_categoria','=', $categoria->id)
        ->whereNull('alp_productos_category.deleted_at')
        ->where('alp_productos.estado_registro','=',1)
        ->groupBy('alp_productos.id')
        ->orderBy('alp_marcas.order') 

        
        ->orderBy('alp_productos.updated_at', 'desc')

        ->paginate(36); 

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

        }else{

            $r='9';
                foreach ($productos as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $r)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;

                    }

                }
                
        }

        //print_r($precio);
       // print_r($role->role_id);

    $prods=$this->addOferta($productos, $precio, $descuento);


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

        return \View::make('frontend.categorias', compact('productos','cataname','slug', 'descuento', 'precio', 'states', 'cart', 'total', 'prods', 'inventario', 'combos'));

    }
    public function marcas($slug)
    {


        $descuento='1'; 

        $precio = array();


        $marca = AlpMarcas::where('slug','=', $slug)->firstOrFail();

        $marcaname = DB::table('alp_marcas')->select('nombre_marca','seo_titulo','seo_descripcion','slug')->where('id','=', $marca->id)->where('estado_registro','=', 1)->get();

        $productos =  DB::table('alp_productos')->select('alp_productos.*', 'alp_marcas.order as order')
        ->join('alp_marcas','alp_productos.id_marca' , '=', 'alp_marcas.id')

        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
        ->where('alp_almacenes.defecto', '=', 1)


        ->where('alp_productos.id_marca','=', $marca->id)
        ->where('alp_productos.estado_registro','=',1)
        ->groupBy('alp_productos.id')
        ->orderBy('alp_marcas.order') 
        
        ->orderBy('alp_productos.updated_at', 'desc')
        ->paginate(36); 

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

        }else{

            $r='9';
                foreach ($productos as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $r)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;

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

        $inventario=$this->inventario();

          $combos=$this->combos();


        return \View::make('frontend.marcas', compact('productos','marcaname','slug', 'descuento', 'precio', 'states', 'cart', 'total', 'prods', 'inventario', 'combos'));

    }

    public function all()
    {


        $descuento='1'; 

        $precio = array();


        $productos =  DB::table('alp_productos')->select('alp_productos.*', 'alp_marcas.order as order')
        ->join('alp_productos_category','alp_productos.id' , '=', 'alp_productos_category.id_producto')
        ->join('alp_marcas','alp_productos.id_marca' , '=', 'alp_marcas.id')

        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
        ->where('alp_almacenes.defecto', '=', 1)


        ->whereNull('alp_productos_category.deleted_at')
        ->where('alp_productos.estado_registro','=',1)
        ->groupBy('alp_productos.id')
        ->orderBy('alp_marcas.order', 'asc')
        ->orderBy('alp_productos.updated_at', 'desc') 
        ->paginate(36); 

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

        }else{

            $r='9';
                foreach ($productos as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $r)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;

                    }

                }
                
        }

        //print_r($precio);
       // print_r($role->role_id);

    $prods=$this->addOferta($productos, $precio, $descuento);


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

        return \View::make('frontend.all', compact('productos', 'descuento', 'precio', 'states', 'cart', 'total', 'prods', 'inventario', 'combos'));

    }


    public function mySearch(Request $request)
    {
 
        $descuento='1'; 

        $precio = array();

        $termino = $request->get('buscar');

        $productos = AlpProductos::
        search($request->get('buscar'))->select('alp_productos.*', 'alp_marcas.order as order')
        ->join('alp_marcas','alp_productos.id_marca' , '=', 'alp_marcas.id')

        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
        ->where('alp_almacenes.defecto', '=', 1)



        ->where('alp_productos.estado_registro','=', 1)
        ->orderBy('alp_marcas.order', 'asc')
        ->orderBy('alp_productos.updated_at', 'desc')
        ->paginate(36); 	
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

        }else{

            $r='9';
                foreach ($productos as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $r)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;

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

        $inventario=$this->inventario();

        $combos=$this->combos();

        //dd($inventario);

         $states=State::where('config_states.country_id', '47')->get();

        return \View::make('frontend.buscar', compact('productos', 'descuento', 'precio', 'states','termino', 'cart', 'total', 'prods', 'inventario', 'combos'));

    }

    /* Pagina CMS */
    public function cms($slug)
    {

        $cms = AlpCms::where('slug','=', $slug)->firstOrFail();
        return \View::make('frontend.pagina_single', compact('cms'));

    }

    /*Fin CMS */


     private function inventario()
    {
       

      $entradas = AlpInventario::groupBy('id_producto')
              ->select("alp_inventarios.*", DB::raw(  "SUM(alp_inventarios.cantidad) as cantidad_total"))
              ->where('alp_inventarios.operacion', '1')
              ->get();

              $inv = array();

              foreach ($entradas as $row) {
                
                $inv[$row->id_producto]=$row->cantidad_total;

              }


            $salidas = AlpInventario::select("alp_inventarios.*", DB::raw(  "SUM(alp_inventarios.cantidad) as cantidad_total"))
              ->groupBy('id_producto')
              ->where('operacion', '2')
              ->get();

              foreach ($salidas as $row) {
                
                $inv[$row->id_producto]= $inv[$row->id_producto]-$row->cantidad_total;

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



}
