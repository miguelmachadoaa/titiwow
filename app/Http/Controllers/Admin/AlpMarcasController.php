<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Http\Requests\MarcaRequest;
use App\Models\AlpMarcas;
use App\Http\Requests;
use Illuminate\Http\Request;
use Redirect;
use Sentinel;
use View;


class AlpMarcasController extends JoshController
{
    /**
     * Show a list of all the groups.
     *
     * @return View
     */
    public function index()
    {
        // Grab all the groups
      

        $marcas = AlpMarcas::all();
       


        // Show the page
        return view('admin.marcas.index', compact('marcas'));
    }

    /**
     * Group create.
     *
     * @return View
     */
    public function create()
    {
        // Show the page
        return view ('admin.marcas.create');
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(MarcaRequest $request)
    {
        
         $user_id = Sentinel::getUser()->id;

        //$input = $request->all();

        //var_dump($input);

        $data = array(
            'nombre_marca' => $request->nombre_marca, 
            'descripcion_marca' => $request->descripcion_marca, 
            'id_user' =>$user_id
        );
         
        $forma=AlpMarcas::create($data);

        if ($forma->id) {

            return redirect('admin/marcas')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/marcas')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
       
       $marca = AlpMarcas::find($id);

        return view('admin.marcas.edit', compact('marca'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(MarcaRequest $request, $id)
    {
       $imagen='0';
       $picture = "";

       if ($request->hasFile('image')) {
            
        $file = $request->file('image');

        #echo $file.'<br>';
        
        $extension = $file->extension()?: 'png';
        

        $picture = str_random(10) . '.' . $extension;

        #echo $picture.'<br>';

        $destinationPath = public_path() . '/uploads/marcas/';

        #echo $destinationPath.'<br>';

        
        $file->move($destinationPath, $picture);
        
        $imagen = $picture;

        $data = array(
            'nombre_marca' => $request->nombre_marca, 
            'descripcion_marca' => $request->descripcion_marca,
            'imagen_marca' =>$imagen, 
        );

    }else{

        $data = array(
            'nombre_marca' => $request->nombre_marca, 
            'descripcion_marca' => $request->descripcion_marca,
            'imagen_marca' =>'default.png',
        );


    }

         
       $marca = AlpMarcas::find($id);
    
        $marca->update($data);

        if ($marca->id) {

            return redirect('admin/marcas')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/marcas')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
        $model = 'marcas';
        $confirm_route = $error = null;
        try {
            // Get group information
            
            $marca = AlpMarcas::find($id);

            $confirm_route = route('admin.marcas.delete', ['id' => $marca->id]);

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
           
            $marca = AlpMarcas::find($id);


            // Delete the group
            $marca->delete();

            // Redirect to the group management page
            return Redirect::route('admin.marcas.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.marcas.index')->with('error', trans('Error al eliminar el registro'));
        }
    }

    

}
