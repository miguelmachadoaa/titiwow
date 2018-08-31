<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Models\AlpProductos;
use App\Models\AlpCategorias;
use App\Models\AlpInventario;
use App\Models\AlpMarcas;
use App\Http\Requests;
use Illuminate\Http\Request;
use Response;
use Sentinel;
use Intervention\Image\Facades\Image;
use DOMDocument;


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
        $productos = AlpProductos::all();
        // Show the page
        return view('admin.productos.index', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $categorias = AlpCategorias::all();

        $arbol = array();

        foreach ($categorias as $cat) {

            $arbol[$cat->id_categoria_parent][$cat->id]=$cat->id.'-'.$cat->nombre_categoria;

        }

        $tree=json_encode($arbol);
        #$marcas = AlpMarcas::pluck('nombre_marca', 'id');
        $marcas = AlpMarcas::all();



        return view('admin.productos.create', compact('categorias', 'marcas', 'tree'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {

        $user_id = Sentinel::getUser()->id;

        //$input = $request->all();

        //var_dump($input);

        $imagen='0';

         $picture = "";

        
        if ($request->hasFile('image')) {
            
            $file = $request->file('image');

            #echo $file.'<br>';
            
            $extension = $file->extension()?: 'png';
            

            $picture = str_random(10) . '.' . $extension;

            #echo $picture.'<br>';

            $destinationPath = public_path() . '/uploads/blog/';

            #echo $destinationPath.'<br>';

            
            $file->move($destinationPath, $picture);
            
            $imagen = $picture;

        }



        $data = array(
            'nombre_producto' => $request->nombre_producto, 
            'referencia_producto' => $request->referencia_producto, 
            'referencia_producto_sap' =>$request->referencia_producto_sap, 
            'descripcion_corta' =>$request->descripcion_corta, 
            'descripcion_larga' =>$request->descripcion_larga, 
            'imagen_producto' =>$imagen, 
            'seo_titulo' =>$request->seo_titulo, 
            'seo_descripcion' =>$request->seo_descripcion, 
            'seo_url' =>$request->seo_url, 
            'id_categoria_default' =>$request->id_categoria_default, 
            'id_marca' =>$request->id_marca, 
            'id_user' =>$user_id
        );
         
        $producto=AlpProductos::create($data);


        $data_inventario = array(
            'id_producto' => $producto->id, 
            'cantidad' => $request->inventario_inicial, 
            'operacion' => '1', //1: credito, 2: dedito
            'id_user' =>$user_id

        );

        $inventario=AlpInventario::create($data_inventario);


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
        $producto = AlpProductos::find($id);

        return view('admin.productos.show', compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Blog $blog
     * @return view
     */
    public function edit($id)
    {
        $inventario=AlpInventario::where('id_producto', $id)->firstOrFail();

       
        $categorias = AlpCategorias::all();
        #$marcas = AlpMarcas::pluck('nombre_marca', 'id');
        $marcas = AlpMarcas::all();

        $producto = AlpProductos::find($id);


        $producto['inventario_inicial']=$inventario->cantidad;

        return view('admin.productos.edit', compact('producto', 'categorias', 'marcas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Blog $blog
     * @return Response
     */
    public function update(Request $request, $id)
    {

        $producto = AlpProductos::find($id);



        //$input = $request->all();

        //var_dump($input);


        $imagen='0';

         $picture = "";

        
        if ($request->hasFile('image')) {
            
            $file = $request->file('image');

            #echo $file.'<br>';
            
            $extension = $file->extension()?: 'png';
            

            $picture = str_random(10) . '.' . $extension;

            #echo $picture.'<br>';

            $destinationPath = public_path() . '/uploads/blog/';

            #echo $destinationPath.'<br>';

            
            $file->move($destinationPath, $picture);
            
            $imagen = $picture;

             $data = array(
                'nombre_producto' => $request->nombre_producto, 
                'referencia_producto' => $request->referencia_producto, 
                'referencia_producto_sap' =>$request->referencia_producto_sap, 
                'descripcion_corta' =>$request->descripcion_corta, 
                'descripcion_larga' =>$request->descripcion_larga, 
                'imagen_producto' =>$imagen, 
                'seo_titulo' =>$request->seo_titulo, 
                'seo_descripcion' =>$request->seo_descripcion, 
                'seo_url' =>$request->seo_url, 
                'id_categoria_default' =>$request->id_categoria_default, 
                'id_marca' =>$request->id_marca
                );

        }else{

                  $data = array(
                'nombre_producto' => $request->nombre_producto, 
                'referencia_producto' => $request->referencia_producto, 
                'referencia_producto_sap' =>$request->referencia_producto_sap, 
                'descripcion_corta' =>$request->descripcion_corta, 
                'descripcion_larga' =>$request->descripcion_larga, 
                'seo_titulo' =>$request->seo_titulo, 
                'seo_descripcion' =>$request->seo_descripcion, 
                'seo_url' =>$request->seo_url, 
                'id_categoria_default' =>$request->id_categoria_default, 
                'id_marca' =>$request->id_marca
                );

        }





       
         
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
    public function getModalDelete(AlpProductos $producto)
    {
        $model = 'AlpProductos';
        $confirm_route = $error = null;
        try {
            $confirm_route = route('admin.productos.delete', ['id' => $producto->id]);
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
    public function destroy(AlpProductos $producto)
    {
        if ($producto->delete()) {
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
}
