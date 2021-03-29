<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Http\Requests\CargaRequest;
use App\Http\Requests\PrecioBaseRequest;
use App\Http\Requests\ProductosRequest;
use App\Http\Requests\ProductosUpdateRequest;

use App\Http\Requests\ExcelUploadRequest;
use App\Http\Requests\AnchetaCategoriasRequest;
use App\Http\Requests\AnchetaProductosRequest;


use App\Models\AlpDestacadoProducto;
use App\Models\AlpAlmacenProducto;
use App\Models\AlpProductos;
use App\Models\AlpProductosRelacionados;
use App\Models\AlpCategorias;
use App\Models\AlpCategoriasProductos;
use App\Models\AlpInventario;
use App\Models\AlpPrecioGrupo;
use App\Models\AlpImpuestos;
use App\Models\AlpEmpresas;
use App\Models\AlpCombosProductos;
use App\Models\AlpUnidades;
use App\Models\AlpAnchetasCategorias;
use App\Models\AlpAnchetasProductos;

use App\Exports\ProductosMasivosExport;
use App\Imports\ProductosMasivosImport;


use App\Imports\ProductosUpdateImport;
use App\Imports\ProductosPrecioBase;
use Maatwebsite\Excel\Facades\Excel;


use App\State;
use App\City;
use App\Roles;
use App\Models\AlpMarcas;
use App\Models\AlpCategoriasDestacado;
use App\Http\Requests;
use Illuminate\Http\Request;
use Response;
use Sentinel;
use Intervention\Image\Facades\Image;
use DOMDocument;
use View;
use DB;

use Redirect;


use Intervention\Image\ImageManager;



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

       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpProductosController/index ');

        }else{

          activity()
          ->log('AlpProductosController/index');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['productos.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        $productos = AlpProductos::select('alp_productos.*', 'alp_categorias.nombre_categoria as nombre_categoria')
          ->join('alp_categorias', 'alp_productos.id_categoria_default', '=', 'alp_categorias.id')
          ->get();



          $p=AlpProductos::where('id', '=', 2)->first();


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


            if ($alpProductos->estado_registro == 0) {
              $estado=" <div id='td_destacado_".$alpProductos->id."'><button type='button' data-url='".secure_url('productos/desactivar')."' data-desactivar='1' data-id='".$alpProductos->id ."' class='btn btn-responsive button-alignment btn-danger btn_sizes desactivar' style='font-size: 12px !important;'>Inactivo</button></div>";
            }

                 $actions = "   <!--a href='".secure_url('admin/productos/'.collect($alpProductos)->first().'/show' )."'>
                     <i class='livicon' data-name='info' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='view alpProductos'></i>
                 </a-->
                 <a href='".secure_url('admin/productos/'.collect($alpProductos)->first().'/edit')."'>
                     <i class='livicon' data-name='edit' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='edit alpProductos'></i>
                 </a> 
                 <a href='".secure_url('admin/productos/'.$alpProductos->id.'/confirm-delete' )."' data-toggle='modal' data-target='#delete_confirm'>
                  <i class='livicon' data-name='remove-alt' data-size='18' data-loop='true' data-c='#f56954' data-hc='#f56954'  title='Eliminar'></i>  </a> ";


               /*  if ($alpProductos->destacado == 1) {
              $destacado=" <div style=' display: inline-block; padding: 0; margin: 0;' id='td_".$alpProductos->id."'><button title='Sugerencia' data-url='".secure_url('productos/destacado')."' data-destacado='0' data-id='".$alpProductos->id ."'   class='btn btn-xs btn-link  destacado'>  <span class='glyphicon glyphicon-star' aria-hidden='true'></span>   </button></div>";
            }else{

                   $destacado="  <div style=' display: inline-block; padding: 0; margin: 0;' id='td_".$alpProductos->id."'><button title='Normal' data-url='".secure_url('productos/destacado')."' data-destacado='1' data-id='".$alpProductos->id ."'   class='btn btn-xs btn-link  destacado'>  <span class='glyphicon glyphicon-star-empty' aria-hidden='true'></span>   </button></div>";
            }*/

                if ($alpProductos->sugerencia == 1) {
              $sugerencia=" <div style=' display: inline-block; padding: 0; margin: 0;' id='td_sugerencia_".$alpProductos->id."'>
 <button title='Sugerencia' data-url='".secure_url('productos/sugerencia')."' data-sugerencia='0' data-id='".$alpProductos->id ."'   class='btn btn-xs btn-link  sugerencia'>  <span class='glyphicon glyphicon-ok-sign' aria-hidden='true'></span>   </button></div>";
            }else{

                   $sugerencia="  
<div style=' display: inline-block; padding: 0; margin: 0;' id='td_sugerencia_".$alpProductos->id."'> <button title='Normal' data-url='".secure_url('productos/sugerencia')."' data-sugerencia='1' data-id='".$alpProductos->id ."'   class='btn btn-xs btn-link  sugerencia'>  <span class='glyphicon glyphicon-remove-sign' aria-hidden='true'></span>   </button></div>";

            }

            $imagen="<img src='../uploads/productos/60/".$alpProductos->imagen_producto."' height='60px'>";


               $data[]= array(
                 $alpProductos->id, 
                 $alpProductos->nombre_producto, 
                 $alpProductos->presentacion_producto, 
                 $alpProductos->referencia_producto, 
                 $alpProductos->referencia_producto_sap, 
                 $imagen, 
                 $alpProductos->nombre_categoria, 
                 number_format($alpProductos->precio_base,2), 
                 $estado, 
                 $actions.$sugerencia
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


    public function grid()
    {

       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpProductosController/grid ');

        }else{

          activity()
          ->log('AlpProductosController/grid');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['productos.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        $inventario=$this->inventario();

        $productos=AlpProductos::where('estado_registro', '1')->get();

        return view('admin.productos.grid', compact( 'productos', 'inventario'));
    }


    public function postgrid(Request $request)
    {

       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpProductosController/grid ');

        }else{

          activity()
          ->log('AlpProductosController/grid');

        }

        $input=$request->all();

        //dd($input);

        $productos = array();


        foreach ($input as $key => $value) {

          if (substr($key, 0, 2)=='c_') {

            $p=AlpProductos::where('id', $value)->first();

            $productos[]=$p;

          }

        }




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

                $data='';


      $prods=$this->addOferta($productos, $precio, $descuento);

      $view= View::make('admin.productos.datagrid', compact( 'productos', 'prods'));

      $data=$view->render();




        $nombre_archivo = "uploads/files/plantilla.txt"; 


        if (file_exists($nombre_archivo))
          {
            $borrado = unlink($nombre_archivo);
            
          }
 
         
       
          if($archivo = fopen($nombre_archivo, "a"))
          {
              if(fwrite($archivo, $data))
              {
                 // echo "Se ha ejecutado correctamente";
              }
              else
              {
                  //echo "Ha habido un problema al crear el archivo";
              }
       
              fclose($archivo);
          }

        //  rename ("uploads/files/plantilla.txt", "uploads/files/plantilla.html");


        return view('admin.productos.postgrid', compact( 'productos', 'prods', 'data'));
    }



    public function create()
    {


       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpProductosController/create ');

        }else{

          activity()
          ->log('AlpProductosController/create');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['productos.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


        $categorias = AlpCategorias::where('id_categoria_parent','0')->get();

        $categorias_todas = AlpCategorias::all();

        $empresas = AlpEmpresas::all();

        $unidades = AlpUnidades::all();

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

        $unidades = AlpUnidades::all();


        return view('admin.productos.create', compact('categorias', 'marcas', 'tree', 'check', 'roles', 'states', 'impuestos', 'empresas', 'productos', 'inventario', 'unidades'));
    }

    /**
     * Store a newly created resource in storage.
     *upda
     * @return Response
     */
    public function store(ProductosRequest $request)
    {


        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpProductosController/store ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpProductosController/store');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['productos.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


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
            $destinationPath = public_path('uploads/productos/250/' . $picture);
            Image::make($file)->resize(250, 250)->save($destinationPath);
            $destinationPath = public_path('uploads/productos/60/' . $picture);
            Image::make($file)->resize(60, 60)->save($destinationPath);             
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
            'slug' => str_slug(strtolower ($request->slug)), 
            'id_categoria_default' =>$request->id_categoria_default, 
            'id_marca' =>$request->id_marca, 
            'id_impuesto' =>$request->id_impuesto, 
            'precio_base' =>$request->precio_base,             
            'medida' =>$request->medida,
            'mostrar_descuento' =>$request->mostrar_descuento,
            'enlace_youtube' =>$request->enlace_youtube,
            'cantidad' =>$request->cantidad,
            'unidad' =>$request->unidad,
            'mostrar' =>$request->mostrar,
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
        $mostrar = array();

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


          if (substr($key, 0, 12)=='roldescuento') {

            #echo $key.':'.$value.'<br>';

            $par=explode('_', $key);

            $mostrar[$par[1]][$par[2]]=$value;            
            
          }



        }

       

        foreach ($input as $key => $value) {

          if (substr($key, 0, 9)=='rolprecio') {

            if ($value>0) {

            $par=explode('_', $key);

            $data_precio = array(
              'id_producto' => $producto->id, 
              'id_role' => $par[1], 
              'city_id' => $par[2], 
              'operacion' => $select[$par[1]][$par[2]], 
              'precio' => $value, 
              'pum' => $pum[$par[1]][$par[2]],
              'mostrar_descuento' => $mostrar[$par[1]][$par[2]],
              'id_user' => $user_id
            );

            AlpPrecioGrupo::create($data_precio);

            }


           // print_r($data_precio).'<br>';
            
          }

        }


         
         $precio_combo=0;
        $ban_combo=0;

         foreach ($input as $key => $value) {

          if (substr($key, 0, 5)=='c_pro') {

            $ban_combo=1;

            $data_combo = array(
              'id_combo' => $producto->id, 
              'id_producto' => $value, 
              'cantidad' => $input['c_can_'.$value], 
              'precio' => $input['c_precio_'.$value], 
              'id_user' => $user_id
            );

            $precio_combo=$precio_combo+($input['c_precio_'.$value]*$input['c_can_'.$value]);

            AlpCombosProductos::create($data_combo);

          }

        }


        if ($ban_combo==1) {

          $p=AlpProductos::where('id', $producto->id)->first();
          $p->update(['precio_base'=>$precio_combo]);
        }



        $i=1;

        $robots='';

        foreach ($input as $key => $value) {

            if (substr($key,0,6)=='robots') {

                if ($i==1) {

                   $robots=$value;

                   $i=0;

                }else{

                    $robots=$robots.' ,'.$value;
                }

            }
            # code...
        }

        $data = array('robots' => $robots);

        $producto->update($data);





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

      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('AlpProductosController/show ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpProductosController/show');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['productos.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


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

      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('AlpProductosController/relacionado ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpProductosController/relacionado');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['productos.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }




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

      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('AlpProductosController/edit ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpProductosController/edit');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['productos.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }



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

        if (isset($inventario->cantidad)) {
          $producto['inventario_inicial']=$inventario->cantidad;
        }else{
          $producto['inventario_inicial']=0;
        }

        

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

        //dd($productos_list);


        $roles = DB::table('roles')->select('id', 'name')->where('roles.tipo', 2)->get();


        $robots=explode(' ,', $producto->robots);

        $unidades = AlpUnidades::all();



        return view('admin.productos.edit', compact('producto', 'categorias', 'marcas', 'check', 'tree', 'roles',  'precio_grupo',  'precio_grupo_corporativo', 'states', 'impuestos', 'empresas', 'productos', 'productos_list', 'robots', 'unidades'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Blog $blog
     * @return Response
     */
    public function update(ProductosUpdateRequest $request, $id)
    {

          if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpProductosController/update ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpProductosController/update');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['productos.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


        $producto = AlpProductos::find($id);

        $user_id = Sentinel::getUser()->id;

        $input = $request->all();

        //dd($producto);

        $imagen='0';

         $picture = "";

        
        if ($request->hasFile('image')) {
            
          $file = $request->file('image');
          $extension = $file->extension()?: 'jpg';
          $picture = str_random(10) . '.' . $extension;    
          $destinationPath = public_path('uploads/productos/' . $picture);
          Image::make($file)->resize(600, 600)->save($destinationPath);   

          $destinationPath = public_path('uploads/productos/250/' . $picture);
          Image::make($file)->resize(250, 250)->save($destinationPath);

          $destinationPath = public_path('uploads/productos/60/' . $picture);
          Image::make($file)->resize(60, 60)->save($destinationPath);   

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
                'slug' => str_slug(strtolower ($request->slug)), 
                'id_categoria_default' =>$request->id_categoria_default, 
                'id_marca' =>$request->id_marca,
                'id_impuesto' =>$request->id_impuesto,
                'pum' =>$request->pum,
                'medida' =>$request->medida,
                'mostrar_descuento' =>$request->mostrar_descuento,
                'enlace_youtube' =>$request->enlace_youtube,
                'cantidad' =>$request->cantidad,
                'unidad' =>$request->unidad,
                'mostrar' =>$request->mostrar,
                'precio_base' =>$request->precio_base
                );

        }else{

                  $data = array(
                    'tipo_producto' => $request->tipo_producto, 
                'nombre_producto' => $request->nombre_producto, 
                'presentacion_producto' => $request->presentacion_producto,
                'referencia_producto' => $request->referencia_producto, 
                'referencia_producto_sap' =>$request->referencia_producto_sap, 
                'descripcion_corta' =>$request->descripcion_corta, 
                'descripcion_larga' =>$request->descripcion_larga, 
                'seo_titulo' =>$request->seo_titulo, 
                'seo_descripcion' =>$request->seo_descripcion, 
                'slug' => str_slug(strtolower ($request->slug)), 
                'id_categoria_default' =>$request->id_categoria_default, 
                'precio_base' =>$request->precio_base,
                'id_impuesto' =>$request->id_impuesto,                
                'pum' =>$request->pum,
                'medida' =>$request->medida,
                'mostrar_descuento' =>$request->mostrar_descuento,
                'enlace_youtube' =>$request->enlace_youtube,
                'cantidad' =>$request->cantidad,
                'unidad' =>$request->unidad,
                'mostrar' =>$request->mostrar,
                'id_marca' =>$request->id_marca
                );

        }

       // dd($data);
         
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

        $mostrar = array();

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


          if (substr($key, 0, 12)=='roldescuento') {

            #echo $key.':'.$value.'<br>';

            $par=explode('_', $key);

            $mostrar[$par[1]][$par[2]]=$value;            
            
          }

        }


        //dd($mostrar);

       # print_r($select).'<br><br><br>';

        foreach ($input as $key => $value) {

          if (substr($key, 0, 9)=='rolprecio') {

            if ($value>0) {

            $par=explode('_', $key);

            $data_precio = array(
              'id_producto' => $producto->id, 
              'id_role' => $par[1], 
              'city_id' => $par[2], 
              'operacion' => $select[$par[1]][$par[2]], 
              'precio' => $value, 
              'pum' => $pum[$par[1]][$par[2]],
              'mostrar_descuento' => $mostrar[$par[1]][$par[2]],
              'id_user' => $user_id
            );

           // dd($data_precio);

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

        $cantidad_combo = array();

        //dd($input);


        $precio_combo=0;
        $ban_combo=0;

         foreach ($input as $key => $value) {

          if (substr($key, 0, 5)=='c_pro') {

            $ban_combo=1;

            $data_combo = array(
              'id_combo' => $producto->id, 
              'id_producto' => $value, 
              'cantidad' => $input['c_can_'.$value], 
              'precio' => $input['c_precio_'.$value], 
              'id_user' => $user_id
            );

            $precio_combo=$precio_combo+($input['c_precio_'.$value]*$input['c_can_'.$value]);

            AlpCombosProductos::create($data_combo);

          }

        }


        if ($ban_combo==1) {
          $producto->update(['precio_base'=>$precio_combo]);
        }



         $i=1;

         $robots='';

        foreach ($input as $key => $value) {

            if (substr($key,0,6)=='robots') {

                if ($i==1) {

                   $robots=$value;

                   $i=0;

                }else{

                    $robots=$robots.' ,'.$value;
                }

            }
            # code...
        }

        $data = array('robots' => $robots);

        $producto->update($data);


        

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

      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$producto])->log('AlpProductosController/edit');

        }else{

          activity()
          ->withProperties(['id'=>$producto])->log('AlpProductosController/edit');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['productos.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }



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

       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpProductosController/destacado ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpProductosController/destacado');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['productos.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


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

       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpProductosController/sugerencia ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpProductosController/sugerencia');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['productos.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }



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

       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpProductosController/desactivar ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpProductosController/desactivar');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['productos.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

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

       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpProductosController/referenciasap ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpProductosController/referenciasap');


        }



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

       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpProductosController/referencia ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpProductosController/referencia');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['productos.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


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

       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpProductosController/addrelacionado ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpProductosController/addrelacionado');


        }



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

      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpProductosController/delrelacionado ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpProductosController/delrelacionado');


        }


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



    private function inventarioBogota()
    {



      $entradas = AlpInventario::groupBy('id_producto')
              ->select("alp_inventarios.*", DB::raw(  "SUM(alp_inventarios.cantidad) as cantidad_total"))
              ->where('alp_inventarios.operacion', '1')
              ->where('alp_inventarios.id_almacen', '1')
              ->get();

              $inv = array();

              foreach ($entradas as $row) {
                
                $inv[$row->id_producto]=$row->cantidad_total;

              }

            $salidas = AlpInventario::select("alp_inventarios.*", DB::raw(  "SUM(alp_inventarios.cantidad) as cantidad_total"))
              ->groupBy('id_producto')
              ->where('operacion', '2')
              ->where('alp_inventarios.id_almacen', '1')
              ->get();

              foreach ($salidas as $row) {
                
                $inv[$row->id_producto]= $inv[$row->id_producto]-$row->cantidad_total;

            }

            return $inv;
      
    }





    public function precio()
    {
        // Grab all the blogs



        if (!Sentinel::getUser()->hasAnyAccess(['productos.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }
      $productos_list=AlpProductos::all();
        
      $precio_grupo = array();

      $states = State::where('country_id','47')->get();
      
      $roles = DB::table('roles')->select('id', 'name')->where('roles.tipo', 2)->get();


        // Show the page
        return view('admin.productos.precio', compact('precio_grupo', 'states', 'roles', 'productos_list'));
    }




    





     public function postprecio(Request $request)
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpProductosController/postprecio ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpProductosController/postprecio');


        }


        // Grab all the blogs
        
        $productos_list=AlpProductos::all();






            $query=AlpPrecioGrupo::select('alp_precios_grupos.*','config_cities.city_name as city_name', 'roles.name as name', 'alp_productos.nombre_producto as nombre_producto', 'alp_productos.referencia_producto  as referencia_producto', 'alp_productos.precio_base  as precio_base' , 'alp_productos.presentacion_producto  as presentacion_producto' )
            ->join('alp_productos', 'alp_precios_grupos.id_producto','=', 'alp_productos.id')
            ->join('config_cities', 'alp_precios_grupos.city_id','=', 'config_cities.id')
            ->join('roles', 'alp_precios_grupos.id_role','=', 'roles.id');

            if ($request->cities!=0) {
              $query->where('alp_precios_grupos.city_id', $request->cities);
            }

            if ($request->rol!=0) {
              $query->where('alp_precios_grupos.id_role', $request->rol);
            }

            if ($request->producto!=0) {
              $query->where('alp_precios_grupos.id_producto', $request->producto);
            }

            $precio_grupo=$query->withTrashed()->get();
        

            $states = State::where('country_id','47')->get();
            
            $roles = DB::table('roles')->select('id', 'name')->where('roles.tipo', 2)->get();


        // Show the page
        return view('admin.productos.precio', compact( 'precio_grupo', 'states', 'roles', 'productos_list'));
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
                 $alpProductos->presentacion_producto, 
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


     public function cargarupdate()
    {

      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpProductosController/cargarupdate ');

        }else{

          activity()
          ->log('AlpProductosController/cargarupdate');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['productos.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }




      $ids = array(9,10,11,12, 14);

      $roles = Sentinel::getRoleRepository()->all();
      //$roles=Roles::select('roles.id as id,roles.name as name, ')->whereIn('id', $ids)->get();


       $states = State::where('country_id','47')->get();
            

        return view('admin.productos.cargarupdate', compact('roles', 'ids', 'states'));
    }









    public function importupdate(CargaRequest $request) 
    {

     // dd($request);

       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpProductosController/importupdate  ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpProductosController/importupdate ');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['productos.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        $archivo = $request->file('file_update');

        //$porciones = explode("_", $request->cities);

        \Session::put('rol', $request->rol);

        \Session::put('cities', $request->cities);

        Excel::import(new ProductosUpdateImport, $archivo);
        
        return redirect('admin/productos/cargarupdate')->with('success', 'Productos Actualizados Exitosamente');
    }


     public function cargarpreciobase()
    {

      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpProductosController/cargarpreciobase ');

        }else{

          activity()
          ->log('AlpProductosController/cargarpreciobase');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['productos.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        return view('admin.productos.cargarpreciobase');
    }


    public function postpreciobase(PrecioBaseRequest $request) 
    {

     // dd($request);

       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpProductosController/importupdate  ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpProductosController/importupdate ');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['productos.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        $archivo = $request->file('file_update');

        Excel::import(new ProductosPrecioBase, $archivo);
        
        return redirect('admin/productos/cargarpreciobase')->with('success', 'Productos Actualizados Exitosamente');
    }


    public function generarImagenes(){


      $productos=AlpProductos::get();

        foreach ($productos as $p) {

            if (is_file('uploads/productos/' .$p->imagen_producto)) {

              //dd($p->imagen_producto);

              $intervention = new ImageManager();

              $image = $intervention->make('uploads/productos/' .$p->imagen_producto);

              $image->resize(250, 250);

              $image->save('uploads/productos/250/' .$p->imagen_producto, 90);

              $image->resize(60, 60);

              $image->save('uploads/productos/60/' .$p->imagen_producto, 90);

            }
            
          }



          return redirect('admin/productos');



    }


     public function productosmasivosexport()
    {
        // Grab all the blogs



        if (!Sentinel::getUser()->hasAnyAccess(['productos.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        // Show the page
        return Excel::download(new ProductosMasivosExport(), 'productos.xlsx');
    }




    public function productosmasivos()
    {
        // Grab all the blogs



        if (!Sentinel::getUser()->hasAnyAccess(['productos.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

    

        // Show the page
        return view('admin.productos.productosmasivos');
    }


     public function postproductosmasivos(ExcelUploadRequest $request)
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpProductosController/postprecio ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpProductosController/postprecio');

        }

        $archivo = $request->file('file_update');

         Excel::import(new ProductosMasivosImport, $archivo);


        return Redirect::route('admin.productos.productosmasivos')->with('success', trans('Se ha creado satisfactoriamente'));
    }




    public function ancheta($id)
    {
        // Grab all the blogs



        if (!Sentinel::getUser()->hasAnyAccess(['productos.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }
      

      $producto=AlpProductos::where('id', $id)->first();
        

        if ($producto->tipo_producto==3) {
          # code...
        }else{

          $data = array('tipo_producto' => 3 );

          $producto->update($data);
        }


        $categorias=AlpAnchetasCategorias::where('id_ancheta', $id)->get();

        foreach ($categorias as $c) {

          $productos=AlpAnchetasProductos::where('id_ancheta_categoria', $c->id)->get();

          $c->productos=$productos;

          # code...
        }



        // Show the page
        return view('admin.productos.ancheta', compact('producto', 'categorias'));
    }


     public function storecategoria(AnchetaCategoriasRequest $request)
    {
        // Grab all the blogs



      $data = array(
        'id_ancheta' => $request->id_ancheta, 
        'nombre_categoria' => $request->nombre_categoria, 
        'cantidad_minima' => $request->cantidad_minima
      );



      $c=AlpAnchetasCategorias::create($data);

      //$producto=AlpProductos::where('id', $id)->first();
        

        if (isset($c->id)) {
          return redirect('admin/productos/'.$request->id_ancheta.'/ancheta')->with('success', 'Registro creado satisfactoriamente');
        }else{

           return redirect('admin/productos/'.$request->id_ancheta.'/ancheta')->with('error', 'No Error al guardar intenta nuevamente ');

        }



        // Show the page
        return view('admin.productos.editarcategoria', compact('producto', 'categorias'));
    }





    public function editarcategoria( $id)
    {
        // Grab all the blogs


      $categoria=AlpAnchetasCategorias::where('id', $id)->first();


        // Show the page
        return view('admin.productos.editarcategoria', compact('categoria'));
    }


    public function updatecategoria(AnchetaCategoriasRequest $request, $id)
    {
        // Grab all the blogs


      $categoria=AlpAnchetasCategorias::where('id', $id)->first();

      $data = array(
        'nombre_categoria' => $request->nombre_categoria, 
        'cantidad_minima' => $request->cantidad_minima
      );

      $categoria->update($data);

        // Show the page
      return redirect('admin/productos/'.$request->id_ancheta.'/ancheta')->with('success', 'Registro creado satisfactoriamente');
    }


    public function eliminarcategoria($id)
    {
        // Grab all the blogs

       $categoria=AlpAnchetasCategorias::where('id', $id)->first();

       if (isset($categoria->id)) {

         $id_ancheta=$categoria->id_ancheta;

         $categoria->delete();

          return redirect('admin/productos/'.$id_ancheta.'/ancheta')->with('success', 'Registro eliminado satisfactoriamente');


       }else{

            return back()->with('error', 'No se encontro el registro ');
       }
        


       

   return redirect('admin/productos/'.$request->id_ancheta.'/ancheta')->with('success', 'Registro creado satisfactoriamente');

        
    }





    public function gestionarancheta($id)
    {
        // Grab all the blogs



        $categoria=AlpAnchetasCategorias::where('id', $id)->first();


          $producto_ancheta=AlpAnchetasProductos::select('alp_ancheta_productos.*', 'alp_productos.nombre_producto as nombre_producto', 'alp_productos.imagen_producto as imagen_producto', 'alp_productos.referencia_producto as referencia_producto')
          ->join('alp_productos', 'alp_ancheta_productos.id_producto','=', 'alp_productos.id')
          ->where('id_ancheta_categoria', $categoria->id)->get();





          $productos=AlpProductos::get();


        // Show the page
        return view('admin.productos.gestionarancheta', compact('producto_ancheta', 'categoria', 'productos'));
    }


     public function storeproductoancheta(AnchetaProductosRequest $request)
    {
        // Grab all the blogs



      $data = array(
        'id_ancheta_categoria' => $request->id_ancheta_categoria, 
        'id_producto' => $request->id_producto, 
      );



      $p=AlpAnchetasProductos::create($data);

      //$producto=AlpProductos::where('id', $id)->first();
        

        if (isset($p->id)) {
          return redirect('admin/productos/'.$request->id_ancheta_categoria.'/gestionarancheta')->with('success', 'Registro creado satisfactoriamente');
        }else{

           return redirect('admin/productos/'.$request->id_ancheta_categoria.'/gestionarancheta')->with('error', 'No Error al guardar intenta nuevamente ');

        }


        // Show the page
        return view('admin.productos.editarcategoria', compact('producto', 'categorias'));
    }




    public function eliminarproductoacheta($id)
    {
        // Grab all the blogs

       $categoria=AlpAnchetasProductos::where('id', $id)->first();

       if (isset($categoria->id)) {

         $id_ancheta_categoria=$categoria->id_ancheta_categoria;

         $categoria->delete();

          return redirect('admin/productos/'.$id_ancheta_categoria.'/gestionarancheta')->with('success', 'Registro eliminado satisfactoriamente');


       }else{

            return back()->with('error', 'No se encontro el registro ');
       }
        


       

   return redirect('admin/productos/'.$request->id_ancheta.'/ancheta')->with('success', 'Registro creado satisfactoriamente');

        
    }




     public function destacadoslist()
    {

       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user) ->log('AlpProductosController/destacadolist ');

        }else{

          activity() ->log('AlpProductosController/destacadolist');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['productos.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }



        $productos=AlpProductos::select('alp_productos.id as id', 'alp_productos.nombre_producto as nombre_producto', 'alp_productos.referencia_producto as referencia_producto')
        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->groupBy('alp_productos.id') 
        ->where('alp_productos.estado_registro','=',1)
        ->whereNull('alp_almacen_producto.deleted_at')
        ->whereNull('alp_productos.deleted_at')
        ->get();

        $inv=$this->inventarioBogota();


        $destacados = DB::table('alp_productos')->select('alp_productos.*')
        ->join('alp_destacados_producto', 'alp_productos.id', '=', 'alp_destacados_producto.id_producto')
        ->whereNull('alp_productos.deleted_at')
        ->where('alp_productos.estado_registro','=',1)
        ->orderBy('alp_productos.order', 'asc')
        ->orderBy('alp_productos.updated_at', 'desc')
        ->get();

        return view('admin.productos.destacadoslist', compact('productos', 'destacados', 'inv'));
       

    }


     public function addproductodestacado(Request $request)
    {

       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpProductosController/addproductodestacado ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpProductosController/addproductodestacado');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['productos.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


        $input = $request->all();

        $pd=AlpDestacadoProducto::where('id_producto', $request->id_producto)->where('id_grupo_destacado', $request->id_grupo)->first();


        if (isset($pd->id)) {

          return 'false';
          
        }else{

          $data = array(
            'id_grupo_destacado' => $request->id_grupo, 
            'id_producto' => $request->id_producto, 
            'id_user' => $user->id
          );

          dd($data);

          AlpDestacadoProducto::create($data);

          return 'true';
        }

        
        
    }



      public function eliminarproductodestacado(Request $request)
    {

       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpProductosController/eliminarproductodestacado ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpProductosController/eliminarproductodestacado');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['productos.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


        $input = $request->all();

        $pd=AlpDestacadoProducto::where('id', $request->id)->first();


        if (isset($pd->id)) {

          $pd->delete();

          return 'true';
          
        }else{

         

          return 'false';
        }

        
        
    }




     public function datadestacados()
    {
    
          $productos = DB::table('alp_productos')->select('alp_productos.*', 'alp_destacados_producto.id_grupo_destacado as id_grupo_destacado', 'alp_destacados_producto.id as id_producto_destacado')
        ->join('alp_destacados_producto', 'alp_productos.id', '=', 'alp_destacados_producto.id_producto')
        ->whereNull('alp_productos.deleted_at')
        ->whereNull('alp_destacados_producto.deleted_at')
        ->where('alp_productos.estado_registro','=',1)
        ->orderBy('alp_productos.order', 'asc')
        ->orderBy('alp_productos.updated_at', 'desc')
        ->get();
       

            $data = array();

          foreach($productos as $alpProductos){

            

            $imagen="<img src='".secure_url('uploads/productos/60/'.$alpProductos->imagen_producto)."' height='60px'>";

            if ($alpProductos->id_grupo_destacado==1) {
              $grupo='Index';
            }else{
              $grupo='Qlub';

            }


             if ($alpProductos->estado_registro == 1) {
              $estado=" <div id='td_destacado_".$alpProductos->id."'><button type='button' data-url='".secure_url('productos/desactivar')."' data-desactivar='2' data-id='".$alpProductos->id ."' class='btn btn-responsive button-alignment btn-success btn_sizes desactivar' style='font-size: 12px !important;' >Activo</button></div>";
            }

            if ($alpProductos->estado_registro == 2) {
              $estado=" <div id='td_destacado_".$alpProductos->id."'><button type='button' data-url='".secure_url('productos/desactivar')."' data-desactivar='1' data-id='".$alpProductos->id ."' class='btn btn-responsive button-alignment btn-danger btn_sizes desactivar' style='font-size: 12px !important;'>Inactivo</button></div>";
            }




            $ps=AlpAlmacenProducto::select('alp_almacenes.*')
          ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
          ->where('alp_almacen_producto.id_producto', $alpProductos->id)->get();

          $almacen='';

          foreach ($ps as $pa) {
            
            $almacen=$almacen.' '.$pa->nombre_almacen.',';
          }


             $actions = "   
                 <button class='btn btn-danger eliminarproductodestacado' data-id='".$alpProductos->id_producto_destacado."' >
                  <i class='fa fa-trash'    title='Eliminar'></i>  </button> ";



               $data[]= array(
                 $alpProductos->id_producto_destacado, 
                  $imagen, 
                 $alpProductos->nombre_producto, 
                 $alpProductos->referencia_producto, 
                 $alpProductos->referencia_producto_sap, 
                 $grupo, 
                 $almacen, 
                 $estado, 
                 $actions
              );

          }

          return json_encode( array('data' => $data ));

    }




    
}