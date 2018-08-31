<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Models\AlpFormasenvio;
use App\Http\Requests;
use Illuminate\Http\Request;
use Redirect;
use Sentinel;
use View;


class AlpFormasenvioController extends JoshController
{
    /**
     * Show a list of all the groups.
     *
     * @return View
     */
    public function index()
    {
        // Grab all the groups
      

        $formas = AlpFormasenvio::all();
       


        // Show the page
        return view('admin.formasenvio.index', compact('formas'));
    }

    /**
     * Group create.
     *
     * @return View
     */
    public function create()
    {
        // Show the page
        return view ('admin.formasenvio.create');
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(Request $request)
    {
        
         $user_id = Sentinel::getUser()->id;

        //$input = $request->all();

        //var_dump($input);

        $data = array(
            'nombre_forma_envios' => $request->nombre_forma_envios, 
            'descripcion_forma_envios' => $request->descripcion_forma_envios, 
            'id_user' =>$user_id
        );
         
        $forma=AlpFormasenvio::create($data);

        if ($forma->id) {

            return redirect('admin/formasenvio')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/formasenvio')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
       
       $forma = AlpFormasenvio::find($id);

        return view('admin.formasenvio.edit', compact('forma'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(Request $request, $id)
    {
       $data = array(
            'nombre_forma_envios' => $request->nombre_forma_envios, 
            'descripcion_forma_envios' => $request->descripcion_forma_envios
        );
         
       $forma = AlpFormasenvio::find($id);
    
        $forma->update($data);

        if ($forma->id) {

            return redirect('admin/formasenvio')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/formasenvio')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
        $model = 'formasenvio';
        $confirm_route = $error = null;
        try {
            // Get group information
            
            $forma = AlpFormasenvio::find($id);

            $confirm_route = route('admin.formasenvio.delete', ['id' => $forma->id]);

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
           
            $forma = AlpFormasenvio::find($id);


            // Delete the group
            $forma->delete();

            // Redirect to the group management page
            return Redirect::route('admin.formasenvio.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.formasenvio.index')->with('error', trans('Error al eliminar el registro'));
        }
    }

    

}
