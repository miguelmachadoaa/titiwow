<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Models\AlpCategorias;
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
        // Grab all the groups
      

        //$categorias = AlpCategorias::all();
        $categorias = AlpCategorias::select('alp_categorias.*')
        ->where('alp_categorias.id_categoria_parent',0)->get(); 


        // Show the page
        return view('admin.categorias.index', compact('categorias'));
    }

    /**
     * Group create.
     *
     * @return View
     */
    public function create()
    {
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


         $imagen='0';

         $picture = "";

        
        if ($request->hasFile('image')) {
            
            $file = $request->file('image');

            #echo $file.'<br>';
            
            $extension = $file->extension()?: 'png';
            

            $picture = str_random(10) . '.' . $extension;

            #echo $picture.'<br>';

            $destinationPath = public_path() . '/uploads/categorias/';

            #echo $destinationPath.'<br>';

            
            $file->move($destinationPath, $picture);
            
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
            'id_user' =>$user_id
        );


         
        $categoria=AlpCategorias::create($data);

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
       
       $categoria = AlpCategorias::find($id);

        return view('admin.categorias.edit', compact('categoria'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(Request $request, $id)
    {
      

        $imagen='0';

        $picture = "";

        
        if ($request->hasFile('image')) {
            
            $file = $request->file('image');

            #echo $file.'<br>';
            
            $extension = $file->extension()?: 'png';
            

            $picture = str_random(10) . '.' . $extension;

            #echo $picture.'<br>';

            $destinationPath = public_path() . '/uploads/categorias/';

            #echo $destinationPath.'<br>';

            
            $file->move($destinationPath, $picture);
            
            $imagen = $picture;

             $data = array(
            'nombre_categoria' => $request->nombre_categoria, 
            'descripcion_categoria' => $request->descripcion_categoria,             
            'css_categoria' =>$request->css_categoria, 
            'order' =>$request->order, 
            'imagen_categoria' =>$imagen, 
            'id_categoria_parent' =>'0',
            'slug' => $request->slug
            );

        }else{

            $data = array(
                'nombre_categoria' => $request->nombre_categoria, 
                'descripcion_categoria' => $request->descripcion_categoria,                 
            'css_categoria' =>$request->css_categoria, 
            'order' =>$request->order, 
                'imagen_categoria' =>$imagen, 
                'id_categoria_parent' =>'0',
                'slug' => $request->slug
            );


        }



       
         
       $categoria = AlpCategorias::find($id);
    
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
       
       $categoria = AlpCategorias::find($id);

      

    $categorias = AlpCategorias::select('alp_categorias.*')
        ->where('alp_categorias.id_categoria_parent',$id)->get(); 



        return view('admin.categorias.detalle', compact('categoria', 'categorias'));

    }

    public function storeson(Request $request, $padre)
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
            'nombre_categoria' => $request->nombre_categoria, 
            'descripcion_categoria' => $request->descripcion_categoria, 
            'referencia_producto_sap' =>$request->referencia_producto_sap, 
            'imagen_categoria' =>$imagen, 
            'id_categoria_parent' =>$padre, 
            'slug' => $request->slug,
            'id_user' =>$user_id
        );
         
        $categoria=AlpCategorias::create($data);

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
       
       $categoria = AlpCategorias::find($id);

        return view('admin.categorias.editson', compact('categoria'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function updson(Request $request, $id)
    {
       
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
            'nombre_categoria' => $request->nombre_categoria, 
            'descripcion_categoria' => $request->descripcion_categoria, 
            'referencia_producto_sap' =>$request->referencia_producto_sap, 
            'imagen_categoria' =>$imagen, 
            'id_categoria_parent' =>$request->id_categoria_parent,
            'slug' => $request->slug
                );

        }else{

                $data = array(
            'nombre_categoria' => $request->nombre_categoria, 
            'descripcion_categoria' => $request->descripcion_categoria, 
            'referencia_producto_sap' =>$request->referencia_producto_sap, 
            'id_categoria_parent' =>$request->id_categoria_parent,
            'slug' => $request->slug
                );

        }


       $categoria = AlpCategorias::find($id);
    
        $categoria->update($data);

        if ($categoria->id) {

            return redirect('admin/categorias/'.$request->id_categoria_parent.'/detalle')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/categorias/'.$request->id_categoria_parent.'/detalle')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
        }  

    }


    public function destacado(Request $request)
    {

        $input = $request->all();

        $categoria=AlpCategorias::find($request->id);

        $data = array('destacado' => $request->destacado );

        

        $categoria->update($data);

        $categoria=AlpCategorias::find($request->id);


        $view= View::make('admin.categorias.destacado', compact('categoria'));

        $data=$view->render();
        
        return $data;
    }

}
