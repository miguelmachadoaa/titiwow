<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Http\Requests\ProductosRequest;
use App\Http\Requests\ProductosUpdateRequest;
use App\Models\AlpProductos;
use App\Models\AlpProductosRelacionados;
use App\Models\AlpCategorias;
use App\Models\AlpCategoriasProductos;
use App\Models\AlpInventario;
use App\Models\AlpPrecioGrupo;
use App\Models\AlpImpuestos;
use App\Models\AlpEmpresas;
use App\Models\AlpCombosProductos;
use App\State;
use App\City;
use App\Roles;
use App\Models\AlpMarcas;
use App\Http\Requests;
use Illuminate\Http\Request;
use Response;
use Sentinel;
use Intervention\Image\Facades\Image;
use DOMDocument;
use View;
use DB;


class AlpProductosController extends JoshController
{


    private $tags;

    public function __construct()
    {
        parent::__construct();
       
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // Grab all the blogs
        

        $productos = AlpProductos::select('alp_productos.*', 'alp_categorias.nombre_categoria as nombre_categoria')
          ->join('alp_categorias', 'alp_productos.id_categoria_default', '=', 'alp_categorias.id')
          ->get();

        // Show the page
        return view('admin.productos.index', compact('productos'));
    }





     public function data()
    {
       
    
          $productos = AlpProductos::select('alp_productos.*', 'alp_categorias.nombre_categoria as nombre_categoria')
          ->join('alp_categorias', 'alp_productos.id_categoria_default', '=', 'alp_categorias.id')
          ->get();
       

            $data = array();


          foreach($productos as $alpProductos){


            if ($alpProductos->estado_registro == 1) {
              $estado=" <div id='td_destacado_".$alpProductos->id."'><button type='button' data-url='".secure_url('productos/desactivar')."' data-desactivar='2' data-id='".$alpProductos->id ."' class='btn btn-responsive button-alignment btn-success btn_sizes desactivar' style='font-size: 12px !important;' >Activo</button></div>";
            }

            if ($alpProductos->estado_registro == 2) {
              $estado=" <div id='td_destacado_".$alpProductos->id."'><button type='button' data-url='".secure_url('productos/desactivar')."' data-desactivar='1' data-id='".$alpProductos->id ."' class='btn btn-responsive button-alignment btn-danger btn_sizes desactivar' style='font-size: 12px !important;'>Inactivo</button></div>";
            }


                 $actions = " 
                  
                  <a href='".secure_url('admin/productos/'.collect($alpProductos)->first().'/show' )."'>
                     <i class='livicon' data-name='info' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='view alpProductos'></i>
                 </a>
                 <a href='".secure_url('admin/productos/'.collect($alpProductos)->first().'/edit')."'>
                     <i class='livicon' data-name='edit' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='edit alpProductos'></i>
                 </a>";


                 if ($alpProductos->destacado == 1) {
              $destacado=" <div style=' display: inline-block; padding: 0; margin: 0;' id='td_".$alpProductos->id."'><button title='Sugerencia' data-url='".secure_url('productos/destacado')."' data-destacado='0' data-id='".$alpProductos->id ."'   class='btn btn-xs btn-link  destacado'>  <span class='glyphicon glyphicon-star' aria-hidden='true'></span>   </button></div>";
            }else{

                   $destacado="  <div style=' display: inline-block; padding: 0; margin: 0;' id='td_".$alpProductos->id."'><button title='Normal' data-url='".secure_url('productos/destacado')."' data-destacado='1' data-id='".$alpProductos->id ."'   class='btn btn-xs btn-link  destacado'>  <span class='glyphicon glyphicon-star-empty' aria-hidden='true'></span>   </button></div>";


            }

                if ($alpProductos->sugerencia == 1) {
              $sugerencia=" <div style=' display: inline-block; padding: 0; margin: 0;' id='td_sugerencia_".$alpProductos->id."'>
 <button title='Sugerencia' data-url='".secure_url('productos/sugerencia')."' data-sugerencia='0' data-id='".$alpProductos->id ."'   class='btn btn-xs btn-link  sugerencia'>  <span class='glyphicon glyphicon-ok-sign' aria-hidden='true'></span>   </button></div>";
            }else{

                   $sugerencia="  
<div style=' display: inline-block; padding: 0; margin: 0;' id='td_sugerencia_".$alpProductos->id."'> <button title='Normal' data-url='".secure_url('productos/sugerencia')."' data-sugerencia='1' data-id='".$alpProductos->id ."'   class='btn btn-xs btn-link  sugerencia'>  <span class='glyphicon glyphicon-remove-sign' aria-hidden='true'></span>   </button></div>";

            }


            $imagen="<img src='../uploads/productos/".$alpProductos->imagen_producto."' height='60px'>";

               $data[]= array(
                 $alpProductos->id, 
                 $alpProductos->nombre_producto, 
                 $alpProductos->referencia_producto, 
                 $imagen, 
                 $alpProductos->nombre_categoria, 
                 number_format($alpProductos->precio_base,2), 
                 $estado, 
                 $actions.$destacado.$sugerencia
              );

          }

          return json_encode( array('data' => $data ));

    }

     public function recargaNodes($id_padre, $categorias)
    {
        // Grab all the blogs

        $arbol=array();

        
        foreach ($categorias as $cat) {

            
           
            if ($cat->id_categoria_parent==$id_padre) {
                
                $elemento = array(
                'text' => $cat->id.'-'.$cat->nombre_categoria, 
                'href' => '#'.$cat->nombre_categoria, 
                'tags' => '0', 
                'nodes' => $this->recargaNodes($cat->id, $categorias), 

                );

                $arbol[]=$elemento;

            }

        }

         return $arbol;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $categorias = AlpCategorias::where('id_categoria_parent','0')->get();

        $categorias_todas = AlpCategorias::all();

        $empresas = AlpEmpresas::all();

        $arbol = array();

        foreach ($categorias_todas as $cat) {

            if ($cat->id_categoria_parent=='0') {

                  $elemento = array(
                    'text' => $cat->id.'-'.$cat->nombre_categoria, 
                    'href' => '#'.$cat->nombre_categoria, 
                    'tags' => '0', 
                    'nodes' => $this->recargaNodes($cat->id, $categorias_todas), 

                );

                $arbol[]=$elemento;
            }

        }

        $tree=json_encode($arbol);

        $check='';
        #$marcas = AlpMarcas::pluck('nombre_marca', 'id');
        $marcas = AlpMarcas::all();

        $states = State::where('country_id','47')->get();

       // $roles = DB::table('roles')->select('id', 'name')->get();

        $roles = DB::table('roles')->select('id', 'name')->where('roles.tipo', 2)->get();

        $empresas = AlpEmpresas::all();


        $impuestos = AlpImpuestos::all();

        $inventario=$this->inventario();


        $productos=AlpProductos::where('estado_registro', '1')->get();


        return view('admin.productos.create', compact('categorias', 'marcas', 'tree', 'check', 'roles', 'states', 'impuestos', 'empresas', 'productos', 'inventario'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(ProductosRequest $request)
    {

        $user_id = Sentinel::getUser()->id;

        $input = $request->all();

      

        $imagen='default.png';

         $picture = "";

        
        if ($request->hasFile('image')) {
            
            $file = $request->file('image');
            $extension = $file->extension()?: 'jpg';
            $picture = str_random(10) . '.' . $extension;    
            $destinationPath = public_path('uploads/productos/' . $picture);
            Image::make($file)->resize(600, 600)->save($destinationPath);            
            $imagen = $picture;

        }
               

        $data = array(
            'tipo_producto' => $request->tipo_producto, 
            'nombre_producto' => $request->nombre_producto, 
            'presentacion_producto' => $request->presentacion_producto,
            'referencia_producto' => $request->referencia_producto, 
            'referencia_producto_sap' =>$request->referencia_producto_sap, 
            'descripcion_corta' =>$request->descripcion_corta, 
            'descripcion_larga' =>$request->descripcion_larga, 
            'imagen_producto' =>$imagen, 
            'seo_titulo' =>$request->seo_titulo, 
            'seo_descripcion' =>$request->seo_descripcion, 
            'slug' => $request->slug, 
            'id_categoria_default' =>$request->id_categoria_default, 
            'id_marca' =>$request->id_marca, 
            'id_impuesto' =>$request->id_impuesto, 
            'precio_base' =>$request->precio_base,             
            'pum' =>$request->pum,
            'medida' =>$request->medida,
            'id_user' =>$user_id
        );
         
        $producto=AlpProductos::create($data);

        /*se guarda inventario*/

        $data_inventario = array(
            'id_producto' => $producto->id, 
            'cantidad' => $request->inventario_inicial, 
            'operacion' => '1', //1: credito, 2: dedito
            'id_user' =>$user_id

        );

        $inventario=AlpInventario::create($data_inventario);

        /*se cargan las categorias seleccionadas*/

        $cats=explode(',', $request->categorias_prod);

         foreach ($cats as $cat ) {
            
            $data_cat = array(
                'id_producto' => $producto->id, 
                'id_categoria' => $cat, 
                'id_user' => $user_id
            );


           AlpCategoriasProductos::create($data_cat);

        }

        $select = array();
        $pum = array();

        foreach ($input as $key => $value) {

          //echo substr($key, 0, 6).'<br>';

          if (substr($key, 0, 6)=='select') {

            //echo $key.':'.$value.'<br>';

            $par=explode('_', $key);

            $select[$par[1]][$par[2]]=$value;            
            
          }



          if (substr($key, 0, 6)=='rolpum') {

            #echo $key.':'.$value.'<br>';

            $par=explode('_', $key);

            $pum[$par[1]][$par[2]]=$value;            
            
          }

        }

       

        foreach ($input as $key => $value) {

          if (substr($key, 0, 9)=='rolprecio') {

           // echo $key.':'.$value.'<br>';

            if ($value>0) {
              # code...

            $par=explode('_', $key);

            $data_precio = array(
              'id_producto' => $producto->id, 
              'id_role' => $par[1], 
              'city_id' => $par[2], 
              'operacion' => $select[$par[1]][$par[2]], 
              'precio' => $value, 
              'pum' => $pum[$par[1]][$par[2]],
              'id_user' => $user_id
            );

            AlpPrecioGrupo::create($data_precio);

            }


           // print_r($data_precio).'<br>';
            
          }

        }


         foreach ($input as $key => $value) {

          if (substr($key, 0, 2)=='c_') {


            $data_combo = array(
              'id_combo' => $producto->id, 
              'id_producto' => $value, 
              'id_user' => $user_id
            );

            AlpCombosProductos::create($data_combo);

           // print_r($data_precio).'<br>';
            
          }

        }


        if ($producto->id) {

            return redirect('admin/productos');

        } else {
            return Redirect::route('admin/productos')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
        }   

    }

    /**
     * Display the specified resource.
     *
     * @param  Blog $blog
     * @return view
     */
    public function show($id)
    {
         $categorias = AlpCategoriasProductos::select('alp_productos_category.*', 'alp_categorias.nombre_categoria as nombre_categoria')
          ->join('alp_categorias', 'alp_productos_category.id_categoria', '=', 'alp_categorias.id')
         
          ->where('alp_productos_category.id_producto', $id)
          ->get();


          $producto = AlpProductos::select('alp_productos.*', 'alp_categorias.nombre_categoria as nombre_categoria', 'alp_marcas.nombre_marca as nombre_marca')
          ->join('alp_categorias', 'alp_productos.id_categoria_default', '=', 'alp_categorias.id')
          ->join('alp_marcas', 'alp_productos.id_marca', '=', 'alp_marcas.id')
          ->where('alp_productos.id', $id)
          ->first();



        $precioGrupo = AlpPrecioGrupo::where('id_producto', $id)->get();

        $drole = array();

        $dprecio = array();

        /*se rellena un arra con los precios por roles con el key igual al id del rol */

          $precio_grupo = array();

          $precio_grupo_corporativo = array();

         # print_r($precioGrupo);

         foreach ($precioGrupo as $pre) {

          $nc=City::where('id',$pre->city_id)->first();


          if ($pre->operacion==1) {
            $pre->precio_seleccion=$producto->precio_base;
            
          }

          if ($pre->operacion==2) {
            $pre->precio_seleccion=$producto->precio_base*(1-($pre->precio/100));
            
          }

          if ($pre->operacion==3) {
            $pre->precio_seleccion=$pre->precio;
            
          }


          if (substr($pre->id_role, 0, 1)=='E') {



            $nr = DB::table('alp_empresas')->select('id', 'nombre_empresa')->where('id',substr($pre->id_role, 1))->first();

            if (isset($nr->id)) {

              $pre->id_role='E'.$nr->id;

              $pre->role_name=$nr->nombre_empresa;
              
            }else{

              $pre->role_name='';

            }


           
          }else{

            $nr = DB::table('roles')->select('id', 'name')->where('id',$pre->id_role)->first();

            $pre->role_name=$nr->name;

           

          }

           $pre->city_name=$nc->city_name;

            $precio_grupo[]=$pre;
                
          

        }

        return view('admin.productos.show', compact('producto', 'precio_grupo', 'categorias'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Blog $blog
     * @return view
     */
    public function relacionado($id){

      $producto=AlpProductos::where('id', $id)->first();

      $productos=AlpProductos::get();


      $relacionados = AlpProductos::select('alp_productos.nombre_producto as nombre_producto', 'alp_productos_relacionados.id as id')
          ->join('alp_productos_relacionados', 'alp_productos.id', '=', 'alp_productos_relacionados.id_relacionado')
          ->whereNull('alp_productos_relacionados.deleted_at')
          ->where('alp_productos_relacionados.id_producto', '=',$id)->get();



        return view('admin.productos.relacionados', compact('producto', 'productos', 'relacionados'));



    }

    public function edit($id)
    {
        $inventario=AlpInventario::where('id_producto', $id)->first();

        $cats=AlpCategoriasProductos::where('id_producto', $id)->get();

         $check='';

         $i=0;

         //print_r($cats);

         //esto es para las categorias ya seleccionadas de productyi

         if (!$cats->isEmpty()) {
           # code...
         
          foreach ($cats as $cat) {

            if ($cat->id_categoria!=0) {

              $catego = AlpCategorias::find($cat->id_categoria);

                if ($i==0) {

                  $check=$check.$cat->id_categoria.'-'.$catego->nombre_categoria;

                }else{

                  $check=$check.','.$cat->id_categoria.'-'.$catego->nombre_categoria;

                }

              $i++;

          }

        }
        
        }


        //esto es para montar el arbol de categorias 

        $categorias = AlpCategorias::all();

        $arbol = array();

        foreach ($categorias as $cat) {

            if ($cat->id_categoria_parent=='0') {

                  $elemento = array(
                    'text' => $cat->id.'-'.$cat->nombre_categoria, 
                    'href' => '#'.$cat->nombre_categoria, 
                    'tags' => '0', 
                    'nodes' => $this->recargaNodes($cat->id, $categorias), 

                );

                $arbol[]=$elemento;
            }

        }

        $tree=json_encode($arbol);
        
        #$marcas = AlpMarcas::pluck('nombre_marca', 'id');

        $categorias = AlpCategorias::where('id_categoria_parent','0')->get();

        $marcas = AlpMarcas::all();

        $producto = AlpProductos::find($id);

        $producto['inventario_inicial']=$inventario->cantidad;

        $roles = DB::table('roles')->select('id', 'name')->get();

        $precioGrupo = AlpPrecioGrupo::where('id_producto', $id)->get();

        $drole = array();

        $dprecio = array();

        /*se rellena un arra con los precios por roles con el key igual al id del rol */

          $precio_grupo = array();

          $precio_grupo_corporativo = array();

         # print_r($precioGrupo);

         foreach ($precioGrupo as $pre) {

          $nc=City::where('id',$pre->city_id)->first();


          if ($pre->operacion==1) {
            $pre->precio_seleccion=$producto->precio_base;
            
          }

          if ($pre->operacion==2) {
            $pre->precio_seleccion=$producto->precio_base*(1-($pre->precio/100));
            
          }

          if ($pre->operacion==3) {
            $pre->precio_seleccion=$pre->precio;
            
          }


          if (substr($pre->id_role, 0, 1)=='E') {



            $nr = DB::table('alp_empresas')->select('id', 'nombre_empresa')->where('id',substr($pre->id_role, 1))->first();

            if (isset($nr->id)) {

              $pre->id_role='E'.$nr->id;

              $pre->role_name=$nr->nombre_empresa;
              
            }else{

              $pre->role_name='';

            }


           
          }else{

            $nr = DB::table('roles')->select('id', 'name')->where('id',$pre->id_role)->first();

            $pre->role_name=$nr->name;

           

          }

           $pre->city_name=$nc->city_name;

            $precio_grupo[]=$pre;
                
          

        }

        //print_r($precio_grupo);

        $states = State::where('country_id','47')->get();

        $impuestos = AlpImpuestos::all();

        $empresas = AlpEmpresas::all();

        $productos=AlpProductos::where('estado_registro', '1')->get();


        $productos_list=AlpCombosProductos::select('alp_combos_productos.*', 'alp_productos.nombre_producto as nombre_producto', 'alp_productos.referencia_producto as referencia_producto')->join('alp_productos', 'alp_combos_productos.id_producto', '=', 'alp_productos.id')->where('id_combo', $producto->id)->get();



        $roles = DB::table('roles')->select('id', 'name')->where('roles.tipo', 2)->get();

        return view('admin.productos.edit', compact('producto', 'categorias', 'marcas', 'check', 'tree', 'roles',  'precio_grupo',  'precio_grupo_corporativo', 'states', 'impuestos', 'empresas', 'productos', 'productos_list'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Blog $blog
     * @return Response
     */
    public function update(ProductosUpdateRequest $request, $id)
    {

        $producto = AlpProductos::find($id);


        $user_id = Sentinel::getUser()->id;

        $input = $request->all();



        $imagen='0';

         $picture = "";

        
        if ($request->hasFile('image')) {
            
          $file = $request->file('image');
          $extension = $file->extension()?: 'jpg';
          $picture = str_random(10) . '.' . $extension;    
          $destinationPath = public_path('uploads/productos/' . $picture);
          Image::make($file)->resize(600, 600)->save($destinationPath);            
          $imagen = $picture;

             $data = array(
                'tipo_producto' => $request->tipo_producto, 
                'nombre_producto' => $request->nombre_producto, 
                'presentacion_producto' => $request->presentacion_producto,
                'referencia_producto' => $request->referencia_producto, 
                'referencia_producto_sap' =>$request->referencia_producto_sap, 
                'descripcion_corta' =>$request->descripcion_corta, 
                'descripcion_larga' =>$request->descripcion_larga, 
                'imagen_producto' =>$imagen, 
                'seo_titulo' =>$request->seo_titulo, 
                'seo_descripcion' =>$request->seo_descripcion, 
                'slug' => $request->slug, 
                'id_categoria_default' =>$request->id_categoria_default, 
                'id_marca' =>$request->id_marca,
                'id_impuesto' =>$request->id_impuesto,
                'pum' =>$request->pum,
                'medida' =>$request->medida,
                'precio_base' =>$request->precio_base
                );

        }else{

                  $data = array(
                'nombre_producto' => $request->nombre_producto, 
                'presentacion_producto' => $request->presentacion_producto,
                'referencia_producto' => $request->referencia_producto, 
                'referencia_producto_sap' =>$request->referencia_producto_sap, 
                'descripcion_corta' =>$request->descripcion_corta, 
                'descripcion_larga' =>$request->descripcion_larga, 
                'seo_titulo' =>$request->seo_titulo, 
                'seo_descripcion' =>$request->seo_descripcion, 
                'slug' => $request->slug, 
                'id_categoria_default' =>$request->id_categoria_default, 
                'precio_base' =>$request->precio_base,
                'id_impuesto' =>$request->id_impuesto,                
                'pum' =>$request->pum,
                'medida' =>$request->medida,
                'id_marca' =>$request->id_marca
                );

        }
       
         
        $producto->update($data);


        $cats=explode(',', $request->categorias_prod);

        AlpCategoriasProductos::where('id_producto', $id)->delete();

         foreach ($cats as $cat ) {
            
            $data_cat = array(
                'id_producto' => $producto->id, 
                'id_categoria' => $cat, 
                'id_user' => $user_id
            );


            AlpCategoriasProductos::create($data_cat);

        }


        $ids = array();

        $eliminar=AlpPrecioGrupo::where('id_producto', $id)->get();

        foreach ($eliminar as $eli) {
          
          $ids[]=$eli->id;


        }

        AlpPrecioGrupo::destroy($ids);

        
        $select = array();
        $pum = array();

        foreach ($input as $key => $value) {

         # echo substr($key, 0, 6).'<br>';

          if (substr($key, 0, 6)=='select') {

            #echo $key.':'.$value.'<br>';

            $par=explode('_', $key);

            $select[$par[1]][$par[2]]=$value;            
            
          }

          if (substr($key, 0, 6)=='rolpum') {

            #echo $key.':'.$value.'<br>';

            $par=explode('_', $key);

            $pum[$par[1]][$par[2]]=$value;            
            
          }

        }

       # print_r($select).'<br><br><br>';

      

        foreach ($input as $key => $value) {

         # echo substr($key, 0, 9).'<br>';


          if (substr($key, 0, 9)=='rolprecio') {

           # echo $key.':'.$value.'<br>';

            if ($value>0) {
              # code...

            $par=explode('_', $key);

            $data_precio = array(
              'id_producto' => $producto->id, 
              'id_role' => $par[1], 
              'city_id' => $par[2], 
              'operacion' => $select[$par[1]][$par[2]], 
              'precio' => $value, 
              'pum' => $pum[$par[1]][$par[2]],
              'id_user' => $user_id
            );

            AlpPrecioGrupo::create($data_precio);

            }
            

            #print_r($data_precio).'<br>';
            
          }

        }

        $combos=AlpCombosProductos::where('id_combo', $producto->id)->get();

        $ids = array( );

        foreach ($combos as $com ) {

          $ids[]=$com->id;

        }

        AlpCombosProductos::whereIn('id', $ids)->delete();

         foreach ($input as $key => $value) {

          if (substr($key, 0, 2)=='c_') {

            $data_combo = array(
              'id_combo' => $producto->id, 
              'id_producto' => $value, 
              'id_user' => $user_id
            );

            AlpCombosProductos::create($data_combo);

          }

        }

        if ($producto->id) {

            return redirect('admin/productos');

        } else {

            return Redirect::route('admin/productos')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));

        }


      
    }

    /**
     * Remove blog.
     *
     * @param Blog $blog
     * @return Response
     */
    public function getModalDelete( $producto)
    {
        
       // dd($producto);

        $model = 'AlpProductos';
        $confirm_route = $error = null;
        try {

            $confirm_route = route('admin.productos.delete', ['id' => $producto]);

            return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));

        } catch (GroupNotFoundException $e) {

            $error = trans('Error al eliminar Registro', compact('id'));

            return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Blog $blog
     * @return Response
     */
    public function destroy( $producto)
    {


      $id_producto=$producto;

      $producto=AlpProductos::where('id', $id_producto)->first();

        if ($producto->delete()) {

          $categorias=AlpCategoriasProductos::where('id_producto', $id_producto)->get();

          foreach ($categorias as $categoria) {
            
            $cat=AlpCategoriasProductos::where('id', $categoria->id)->first();

            $cat->delete();

          }

          $inventarios=AlpInventario::where('id_producto', $id_producto)->get();

          foreach ($inventarios as $inventario) {
            
            $inv=AlpInventario::where('id', $inventario->id)->first();

            $inv->delete();

          }

          $precios=AlpPrecioGrupo::where('id_producto', $id_producto)->get();

          foreach ($precios as $precio) {
            
            $pre=AlpPrecioGrupo::where('id', $precio->id)->first();

            $pre->delete();

          }


            return redirect('admin/productos')->with('success', trans('Registro Eliminado Satisfactoriamente'));


        } else {


            return Redirect::route('admin/productos')->withInput()->with('error', trans('Error al eliminar Registro'));


        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BlogCommentRequest $request
     * @param Blog $blog
     *
     * @return Response
     */
    public function storeComment(BlogCommentRequest $request, Blog $blog)
    {
        $blogcooment = new BlogComment($request->all());
        $blogcooment->blog_id = $blog->id;
        $blogcooment->save();
        return redirect('admin/blog/' . $blog->id );
    }


    public function destacado(Request $request)
    {

        $input = $request->all();

        $producto=AlpProductos::find($request->id);

        $data = array('destacado' => $request->destacado );



        $producto->update($data);

        $producto=AlpProductos::find($request->id);


        $view= View::make('admin.productos.destacado', compact('producto'));

        $data=$view->render();
        
        return $data;
    }

    public function sugerencia(Request $request)
    {

        $input = $request->all();

        $producto=AlpProductos::find($request->id);

        $data = array('sugerencia' => $request->sugerencia );



        $producto->update($data);

        $producto=AlpProductos::find($request->id);


        $view= View::make('admin.productos.sugerencia', compact('producto'));

        $data=$view->render();
        
        return $data;
    }

    public function desactivar(Request $request)
    {

        $input = $request->all();
        
        $producto=AlpProductos::find($request->id);

        $data = array('estado_registro' => $request->desactivar );



        $producto->update($data);

        $producto=AlpProductos::find($request->id);


        $view= View::make('admin.productos.desactivar', compact('producto'));

        $data=$view->render();
        
        return $data;
    }

    public function referenciasap(Request $request)
    {

        $input = $request->all();
        
        //dd($input['referencia_producto_sap']);

        $ref=AlpProductos::where('referencia_producto_sap', $input['referencia_producto_sap'])->first();

        if (isset($ref->id)) {
          return json_encode( array('valid' => false ));
        }else{
          return json_encode( array('valid' => true ));

        }
    }

    public function referencia(Request $request)
    {

        $input = $request->all();
        
        //dd($input['referencia_producto_sap']);

        $ref=AlpProductos::where('referencia_producto', $input['referencia_producto'])->first();

        if (isset($ref->id)) {

          return json_encode( array('valid' => false ));

        }else{
          
          return json_encode( array('valid' => true ));

        }
    }

     public function addrelacionado(Request $request)
    {

        $input = $request->all();

        $user_id = Sentinel::getUser()->id;

        
        //dd($input['referencia_producto_sap']);

        $data = array(
          'id_producto' => $input['id_producto'], 
          'id_relacionado' => $input['id_relacionado'], 
          'id_user' => $user_id, 
        );

        AlpProductosRelacionados::create($data);

       

        $relacionados = AlpProductos::select('alp_productos.nombre_producto as nombre_producto', 'alp_productos_relacionados.id as id')
          ->join('alp_productos_relacionados', 'alp_productos.id', '=', 'alp_productos_relacionados.id_relacionado')
          ->whereNull('alp_productos_relacionados.deleted_at')
          ->where('alp_productos_relacionados.id_producto', '=',$input['id_producto'])->get();



        $view= View::make('admin.productos.tabla_relacionados', compact('relacionados'));

        $data=$view->render();

        return $data;

    }

    public function delrelacionado(Request $request)
    {

        $input = $request->all();

        $relacionado=AlpProductosRelacionados::where('id', $input['id'])->first();

        //dd($relacionado);

        $id_producto=$relacionado->id_producto;

        $relacionado->delete();
       

        $relacionados = AlpProductos::select('alp_productos.nombre_producto as nombre_producto', 'alp_productos_relacionados.id as id')
          ->join('alp_productos_relacionados', 'alp_productos.id', '=', 'alp_productos_relacionados.id_relacionado')
          ->whereNull('alp_productos_relacionados.deleted_at')
          ->where('alp_productos_relacionados.id_producto', '=',$id_producto)->get();

        $view= View::make('admin.productos.tabla_relacionados', compact('relacionados'));

        $data=$view->render();

        return $data;

    }


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



        public function precio()
    {
        // Grab all the blogs
        

       $productos = AlpProductos::select('alp_productos.*', 'alp_categorias.nombre_categoria as nombre_categoria')
          ->join('alp_categorias', 'alp_productos.id_categoria_default', '=', 'alp_categorias.id')
          ->get();

           $descuento='1'; 

          $precio = array();

          $r='12';
          
                foreach ($productos as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::select('alp_precios_grupos.*','config_cities.city_name as city_name', 'roles.name as name' )
                    ->join('config_cities', 'alp_precios_grupos.city_id','=', 'config_cities.id')
                    ->join('roles', 'alp_precios_grupos.id_role','=', 'roles.id')
                    ->where('id_producto', $row->id)
                    ->where('id_role', $r)
                    ->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;
                        $precio[$row->id]['city_id']=$pregiogrupo->city_name;
                        $precio[$row->id]['id_role']=$pregiogrupo->name;

                    }

                }


            $prods=$this->addOferta($productos, $precio, $descuento);



        // Show the page
        return view('admin.productos.precio', compact('productos', 'precio', 'prods'));
    }


     public function dataprecio()
    {
       
    
          $productos = AlpProductos::select('alp_productos.*', 'alp_categorias.nombre_categoria as nombre_categoria')
          ->join('alp_categorias', 'alp_productos.id_categoria_default', '=', 'alp_categorias.id')
          ->get();

           $descuento='1'; 

          $precio = array();

          $r='9';
          
                foreach ($productos as  $row) {
                    
                    $pregiogrupo=AlpPrecioGrupo::where('id_producto', $row->id)->where('id_role', $r)->first();

                    if (isset($pregiogrupo->id)) {
                       
                        $precio[$row->id]['precio']=$pregiogrupo->precio;
                        $precio[$row->id]['operacion']=$pregiogrupo->operacion;
                        $precio[$row->id]['pum']=$pregiogrupo->pum;

                    }

                }


            $prods=$this->addOferta($productos, $precio, $descuento);
       

            $data = array();


          foreach($productos as $alpProductos){


            if ($alpProductos->estado_registro == 1) {
              $estado=" <div id='td_destacado_".$alpProductos->id."'><button type='button' data-url='".secure_url('productos/desactivar')."' data-desactivar='2' data-id='".$alpProductos->id ."' class='btn btn-responsive button-alignment btn-success btn_sizes desactivar' style='font-size: 12px !important;' >Activo</button></div>";
            }

            if ($alpProductos->estado_registro == 2) {
              $estado=" <div id='td_destacado_".$alpProductos->id."'><button type='button' data-url='".secure_url('productos/desactivar')."' data-desactivar='1' data-id='".$alpProductos->id ."' class='btn btn-responsive button-alignment btn-danger btn_sizes desactivar' style='font-size: 12px !important;'>Inactivo</button></div>";
            }


                 $actions = " 
                  
                  <a href='".secure_url('admin/productos/'.collect($alpProductos)->first().'/show' )."'>
                     <i class='livicon' data-name='info' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='view alpProductos'></i>
                 </a>
                 <a href='".secure_url('admin/productos/'.collect($alpProductos)->first().'/edit')."'>
                     <i class='livicon' data-name='edit' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='edit alpProductos'></i>
                 </a>";


                 if ($alpProductos->destacado == 1) {
              $destacado=" <div style=' display: inline-block; padding: 0; margin: 0;' id='td_".$alpProductos->id."'><button title='Sugerencia' data-url='".secure_url('productos/destacado')."' data-destacado='0' data-id='".$alpProductos->id ."'   class='btn btn-xs btn-link  destacado'>  <span class='glyphicon glyphicon-star' aria-hidden='true'></span>   </button></div>";
            }else{

                   $destacado="  <div style=' display: inline-block; padding: 0; margin: 0;' id='td_".$alpProductos->id."'><button title='Normal' data-url='".secure_url('productos/destacado')."' data-destacado='1' data-id='".$alpProductos->id ."'   class='btn btn-xs btn-link  destacado'>  <span class='glyphicon glyphicon-star-empty' aria-hidden='true'></span>   </button></div>";


            }

                if ($alpProductos->sugerencia == 1) {
              $sugerencia=" <div style=' display: inline-block; padding: 0; margin: 0;' id='td_sugerencia_".$alpProductos->id."'>
 <button title='Sugerencia' data-url='".secure_url('productos/sugerencia')."' data-sugerencia='0' data-id='".$alpProductos->id ."'   class='btn btn-xs btn-link  sugerencia'>  <span class='glyphicon glyphicon-ok-sign' aria-hidden='true'></span>   </button></div>";
            }else{

                   $sugerencia="  
<div style=' display: inline-block; padding: 0; margin: 0;' id='td_sugerencia_".$alpProductos->id."'> <button title='Normal' data-url='".secure_url('productos/sugerencia')."' data-sugerencia='1' data-id='".$alpProductos->id ."'   class='btn btn-xs btn-link  sugerencia'>  <span class='glyphicon glyphicon-remove-sign' aria-hidden='true'></span>   </button></div>";

            }


           // $imagen="<img src='../uploads/productos/".$alpProductos->imagen_producto."' height='60px'>";

               $data[]= array(
                 $alpProductos->id, 
                 $alpProductos->nombre_producto, 
                 $alpProductos->referencia_producto, 
              //   $imagen, 
                 $alpProductos->nombre_categoria, 
                 number_format($precio[$alpProductos->id],2), 
                 $estado, 
                 $actions.$destacado.$sugerencia
              );

          }

          return json_encode( array('data' => $data ));

    }


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

    
}
