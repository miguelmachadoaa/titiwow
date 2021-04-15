<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Models\AlpProductos;
use App\Models\AlpAlmacenes;
use App\Models\AlpAlmacenProducto;
use App\Models\AlpCategorias;
use App\Models\AlpCategoriasDestacado;
use App\Http\Requests;
use Illuminate\Http\Request;
use Redirect;
use Response;
use Sentinel;
use View;
use Intervention\Image\Facades\Image;
use DOMDocument;


class AlpCategoriasController extends JoshController
{
    /**
     * Show a list of all the groups.
     *
     * @return View
     */
    public function index()
    {

         if (!Sentinel::getUser()->hasAnyAccess(['categorias.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intento acceder');
        }


        // Grab all the groups

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpCategoriasController/index ');

        }else{

          activity()->log('AlpCategoriasController/index');


        }
      

        //$categorias = AlpCategorias::all();
        $categorias = AlpCategorias::select('alp_categorias.*')
        ->where('alp_categorias.id_categoria_parent',0)->get(); 


        // Show the page
        return view('admin.categorias.index', compact('categorias'));
    }




     public function data()
    {
       
    
          $categorias = AlpCategorias::select('alp_categorias.*')
        ->where('alp_categorias.id_categoria_parent',0)->get(); 
       

            $data = array();


          foreach($categorias as $row){


           


                 $actions = "  <a href='".secure_url('admin/categorias/'.$row->id.'/detalle' )."'>
                                                <i class='livicon' data-name='plus' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='Detalle'></i>
                                            </a> <a href='".secure_url('admin/categorias/'.$row->id.'/edit')."'>
                                                <i class='livicon' data-name='edit' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='editar categoria'></i>
                                            </a> <a href='".secure_url('admin/categorias/'.$row->id.'/confirm-delete' )."' data-toggle='modal' data-target='#delete_confirm'>
                                            <i class='livicon' data-name='remove-alt' data-size='18'
                                                data-loop='true' data-c='#f56954' data-hc='#f56954'
                                                title='Eliminar'></i>
                                             </a> <a href='".secure_url('admin/categorias/'.$row->id.'/gestionar')."'>
                                                Gestionar</i>
                                            </a>  ";

                if ($row->destacado=='1') {
                    
                    $destacado="<div style='display: inline-block; padding: 0; margin: 0;' id='td_".$row->id."'> <button title='Destacado' data-url='".secure_url('categorias/destacado')."' data-destacado='0' data-id='".$row->id ."'   class='btn btn-xs btn-link     destacado'>  <span class='glyphicon glyphicon-star' aria-hidden='true'></span>   </button> </div>";

                }else{

                    $destacado="<div style='display: inline-block; padding: 0; margin: 0;' id='td_".$row->id."'> <button title='Normal' data-url='".secure_url('categorias/destacado')."' data-destacado='1' data-id='".$row->id ."'   class='btn btn-xs btn-link    destacado'>  <span class='glyphicon glyphicon-star-empty' aria-hidden='true'></span>   </button> </div>";

                }


               $data[]= array(
                 $row->id, 
                 $row->nombre_categoria, 
                 $row->descripcion_categoria, 
                 $row->css_categoria, 
                 $row->order, 
                 date("d/m/Y H:i:s", strtotime($row->created_at)),
                 $actions.$destacado
              );



          }


          return json_encode( array('data' => $data ));

    }





    /**
     * Group create.
     *
     * @return View
     */
    public function create()
    {

           if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpCategoriasController/create ');

        }else{

          activity()->log('AlpCategoriasController/create');


        }

         if (!Sentinel::getUser()->hasAnyAccess(['categorias.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intento acceder');
        }





        // Show the page
        return view ('admin.categorias.create');
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(Request $request)
    {
        
         $user_id = Sentinel::getUser()->id;


              if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('categorias/store ');

        }else{

          activity()->withProperties($request->all())
                        ->log('categorias/store');


        }


        $input=$request->all();



         $imagen='0';

         $picture = "";

        
        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $extension = $file->extension()?: 'png';
            $picture = str_random(10) . '.' . $extension;
            $destinationPath = public_path('uploads/categorias/' . $picture);    
            Image::make($file)->resize(800, 800)->save($destinationPath);            
            $imagen = $picture;

        }

        $data = array(
            'nombre_categoria' => $request->nombre_categoria, 
            'descripcion_categoria' => $request->descripcion_categoria, 
            'css_categoria' =>$request->css_categoria, 
            'order' =>$request->order, 
            'imagen_categoria' =>$imagen, 
            'id_categoria_parent' =>'0', 
            'slug' => $request->slug,
            'seo_titulo' =>$request->seo_titulo, 
            'seo_descripcion' =>$request->seo_descripcion, 
            'id_user' =>$user_id
        );


         
        $categoria=AlpCategorias::create($data);


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

        $categoria->update($data);







        if ($categoria->id) {

            return redirect('admin/categorias')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/categorias')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
        } 

    }


    /**
     * Group update.
     *
     * @param  int $id
     * @return View
     */
    public function edit($id)
    {

             if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])
                        ->log('categorias/edit ');

        }else{

          activity()->withProperties(['id'=>$id])
                        ->log('categorias/edit');


        }

         if (!Sentinel::getUser()->hasAnyAccess(['categorias.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intento acceder');
        }




       
       $categoria = AlpCategorias::find($id);


         $robots=explode(' ,', $categoria->robots);
       

        return view('admin.categorias.edit', compact('categoria', 'robots'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(Request $request, $id)
    {

             if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('categorias/update ');

        }else{

          activity()->withProperties($request->all())
                        ->log('categorias/update');


        }


        $input=$request->all();

        $imagen='0';

        $picture = "";

        
        if ($request->hasFile('image')) {
            
            
            $file = $request->file('image');
            $extension = $file->extension()?: 'png';
            $picture = str_random(10) . '.' . $extension;    
            $destinationPath = public_path('uploads/categorias/' . $picture);    
            Image::make($file)->resize(800, 800)->save($destinationPath);            
            $imagen = $picture;


            $data = array(
            'nombre_categoria' => $request->nombre_categoria, 
            'descripcion_categoria' => $request->descripcion_categoria,             
            'css_categoria' =>$request->css_categoria, 
            'order' =>$request->order, 
            'imagen_categoria' =>$imagen, 
            'id_categoria_parent' =>'0',
            'slug' => $request->slug,
            'seo_titulo' =>$request->seo_titulo, 
            'seo_descripcion' =>$request->seo_descripcion
            );

        }else{

            $data = array(
                'nombre_categoria' => $request->nombre_categoria, 
                'descripcion_categoria' => $request->descripcion_categoria,                 
                'css_categoria' =>$request->css_categoria, 
                'order' =>$request->order, 
                'imagen_categoria' =>$imagen, 
                'id_categoria_parent' =>'0',
                'seo_titulo' =>$request->seo_titulo, 
                'seo_descripcion' =>$request->seo_descripcion, 
                'slug' => $request->slug
            );


        }



       
         
       $categoria = AlpCategorias::find($id);
    
        $categoria->update($data);



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

        $categoria->update($data);





        if ($categoria->id) {

            return redirect('admin/categorias')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/categorias')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
        }  

    }

    /**
     * Delete confirmation for the given group.
     *
     * @param  int $id
     * @return View
     */
    public function getModalDelete($id = null)
    {
        $model = 'categorias';
        $confirm_route = $error = null;
        try {
            // Get group information
            
            $categoria = AlpCategorias::find($id);

            $confirm_route = route('admin.categorias.delete', ['id' => $categoria->id]);

            return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {
            $error = trans('Ha ocurrido un error al eliminar registro');
            return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }

    /**
     * Delete the given group.
     *
     * @param  int $id
     * @return Redirect
     */
    public function destroy($id)
    {

             if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])
                        ->log('categorias/destroy ');

        }else{

          activity()->withProperties(['id'=>$id])
                        ->log('categorias/destroy');


        }



        try {
            // Get group information
           
            $categoria = AlpCategorias::find($id);


            // Delete the group
            $categoria->delete();

            // Redirect to the group management page
            return Redirect::route('admin.categorias.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.categorias.index')->with('error', trans('Error al eliminar el registro'));
        }
    }

    /**
     * Group update.
     *
     * @param  int $id
     * @return View
     */
    public function detalle($id)
    {

            
        
            if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])
                        ->log('categorias/detalle ');

        }else{

          activity()->withProperties(['id'=>$id])
                        ->log('categorias/detalle');


        }


 if (!Sentinel::getUser()->hasAnyAccess(['categorias.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intento acceder');
        }




       
       $categoria = AlpCategorias::find($id);

      

    $categorias = AlpCategorias::select('alp_categorias.*')
        ->where('alp_categorias.id_categoria_parent',$id)->get(); 



        return view('admin.categorias.detalle', compact('categoria', 'categorias'));

    }

    public function storeson(Request $request, $padre)
    {


        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$padre, 'request'=>$request->all()])
                        ->log('categorias/storeson ');

        }else{

          activity()->withProperties(['id'=>$padre, 'request'=>$request->all()])
                        ->log('categorias/storeson');


        }
        
         $user_id = Sentinel::getUser()->id;

        $input = $request->all();

        //var_dump($input);

          $imagen='0';

         $picture = "";

        
        if ($request->hasFile('image')) {
            
            $file = $request->file('image');
            $extension = $file->extension()?: 'png';
            $picture = str_random(10) . '.' . $extension;    
            $destinationPath = public_path('uploads/categorias/' . $picture);    
            Image::make($file)->resize(800, 800)->save($destinationPath);            
            $imagen = $picture;

        }

        $data = array(
            'nombre_categoria' => $request->nombre_categoria, 
            'descripcion_categoria' => $request->descripcion_categoria, 
            'referencia_producto_sap' =>$request->referencia_producto_sap, 
            'imagen_categoria' =>$imagen, 
            'id_categoria_parent' =>$padre, 
            'slug' => $request->slug,
            'seo_titulo' =>$request->seo_titulo, 
            'seo_descripcion' =>$request->seo_descripcion, 
            'id_user' =>$user_id
        );
         
        $categoria=AlpCategorias::create($data);




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

        $categoria->update($data);




        if ($categoria->id) {

            return redirect('admin/categorias/'.$padre.'/detalle')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/categorias/'.$padre.'/detalle')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
        }  

    }

    /**
     * Group update.
     *
     * @param  int $id
     * @return View
     */
    public function editson($id)
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])
                        ->log('categorias/editson ');

        }else{

          activity()->withProperties(['id'=>$id])
                        ->log('categorias/editson');


        }

         if (!Sentinel::getUser()->hasAnyAccess(['categorias.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intento acceder');
        }



       
       $categoria = AlpCategorias::find($id);


       $robots=explode(' ,', $categoria->robots);

        return view('admin.categorias.editson', compact('categoria', 'robots'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function updson(Request $request, $id)
    {

if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id, 'request'=>$request->all()])
                        ->log('categorias/updson ');

        }else{

          activity()->withProperties(['id'=>$id, 'request'=>$request->all()])
                        ->log('categorias/updson');


        }


        $input=$request->all();


       
         $imagen='0';

         $picture = "";

        
        if ($request->hasFile('image')) {
            
            $file = $request->file('image');
            $extension = $file->extension()?: 'png';
            $picture = str_random(10) . '.' . $extension;    
            $destinationPath = public_path('uploads/categorias/' . $picture);    
            Image::make($file)->resize(800, 800)->save($destinationPath);            
            $imagen = $picture;

            $data = array(
            'nombre_categoria' => $request->nombre_categoria, 
            'descripcion_categoria' => $request->descripcion_categoria, 
            'referencia_producto_sap' =>$request->referencia_producto_sap, 
            'imagen_categoria' =>$imagen, 
            'id_categoria_parent' =>$request->id_categoria_parent,
            'seo_titulo' =>$request->seo_titulo, 
            'seo_descripcion' =>$request->seo_descripcion, 
            'slug' => $request->slug
                );

        }else{

                $data = array(
            'nombre_categoria' => $request->nombre_categoria, 
            'descripcion_categoria' => $request->descripcion_categoria, 
            'referencia_producto_sap' =>$request->referencia_producto_sap, 
            'id_categoria_parent' =>$request->id_categoria_parent,
            'seo_titulo' =>$request->seo_titulo, 
            'seo_descripcion' =>$request->seo_descripcion, 
            'slug' => $request->slug
                );

        }


       $categoria = AlpCategorias::find($id);

       $categoria->update($data);


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

        $categoria->update($data);

        

        if ($categoria->id) {

            return redirect('admin/categorias/'.$request->id_categoria_parent.'/detalle')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/categorias/'.$request->id_categoria_parent.'/detalle')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
        }  

    }


    public function destacado(Request $request)
    {


        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('categorias/destacado ');

        }else{

          activity()->withProperties($request->all())
                        ->log('categorias/destacado');


        }

        $input = $request->all();

        $categoria=AlpCategorias::find($request->id);

        $data = array('destacado' => $request->destacado );

        

        $categoria->update($data);

        $categoria=AlpCategorias::find($request->id);


        $view= View::make('admin.categorias.destacado', compact('categoria'));

        $data=$view->render();
        
        return $data;
    }



     public function gestionar($id)
    {

             if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])
                        ->log('categorias/gestionar ');

        }else{

          activity()->withProperties(['id'=>$id])
                        ->log('categorias/gestionar');


        }

         if (!Sentinel::getUser()->hasAnyAccess(['categorias.gestionar'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intento acceder');
        }



        $almacenes=AlpAlmacenes::get();

        $productos=AlpProductos::get();

       
       $categoria = AlpCategorias::find($id);



       $destacados_list=AlpCategoriasDestacado::select('alp_categoria_destacado.*', 'alp_productos.nombre_producto as nombre_producto', 'alp_almacenes.nombre_almacen as nombre_almacen' )
       ->join('alp_productos', 'alp_categoria_destacado.id_producto', '=', 'alp_productos.id')
       ->join('alp_almacenes', 'alp_categoria_destacado.id_almacen', '=', 'alp_almacenes.id')
       ->where('alp_categoria_destacado.id_categoria', '=', $id)
       ->get();

        return view('admin.categorias.gestionar', compact('categoria', 'productos', 'almacenes', 'destacados_list'));
    }



 public function adddestacado(Request $request)
    {


      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cupones/adddestacado ');

        }else{

          activity()
          ->withProperties($request->all())
          ->log('cupones/adddestacado');


        }



         $user_id = Sentinel::getUser()->id;


         $d=AlpCategoriasDestacado::where('id_categoria', $request->id_categoria)->where('id_almacen', $request->id_almacen)->where('id_producto', $request->id_producto)->first();


         if (isset($d->id)) {
             # code...
         }else{

             $data = array(
            'id_categoria' => $request->id_categoria, 
            'id_almacen' => $request->id_almacen, 
            'id_producto' => $request->id_producto, 
            'user_id' => $request->user_id
        );



        AlpCategoriasDestacado::create($data);


         }

       
       

        

         $destacados_list = AlpCategoriasDestacado::select('alp_categoria_destacado.*', 'alp_almacenes.nombre_almacen as nombre_almacen', 'alp_productos.nombre_producto as nombre_producto')
          ->join('alp_almacenes', 'alp_categoria_destacado.id_almacen', '=', 'alp_almacenes.id')
          ->join('alp_productos', 'alp_categoria_destacado.id_producto', '=', 'alp_productos.id')
          ->where('alp_categoria_destacado.id_categoria', $request->id_categoria)
          ->get();

          $view= View::make('admin.categorias.listdestacado', compact('destacados_list'));

      $data=$view->render();

      return $data;


    }

 public function deldestacado(Request $request)
    {

      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cupones/deldestacado ');

        }else{

          activity()
          ->withProperties($request->all())
          ->log('cupones/deldestacado');


        }

         $user_id = Sentinel::getUser()->id;


         $cat=AlpCategoriasDestacado::where('id', $request->id)->first();

         $cat->delete();
       

       $destacados_list = AlpCategoriasDestacado::select('alp_categoria_destacado.*', 'alp_almacenes.nombre_almacen as nombre_almacen', 'alp_productos.nombre_producto as nombre_producto')
          ->join('alp_almacenes', 'alp_categoria_destacado.id_almacen', '=', 'alp_almacenes.id')
          ->join('alp_productos', 'alp_categoria_destacado.id_producto', '=', 'alp_productos.id')
          ->where('alp_categoria_destacado.id_categoria', $cat->id_categoria)
          ->get();

          $view= View::make('admin.categorias.listdestacado', compact('destacados_list'));

      $data=$view->render();

      return $data;


    }


    public function getproductos($almacen, $categoria)
    {
        

         $producto=AlpAlmacenProducto::select('alp_almacen_producto.*','alp_productos.nombre_producto as nombre_producto' )
        ->join('alp_productos', 'alp_almacen_producto.id_producto', '=', 'alp_productos.id')
        ->where('alp_almacen_producto.id_almacen','=', $almacen)
        ->where('alp_productos.id_categoria_default','=', $categoria)
        ->pluck("alp_productos.nombre_producto","alp_almacen_producto.id_producto")->all();

       // dd($alm);


        if (count($producto)) {

            $producto['0'] = 'Seleccione Producto';

        }else{

            $producto = array();

            $producto['0'] = 'Seleccione Producto';

        }

       return json_encode($producto);


    }





}
