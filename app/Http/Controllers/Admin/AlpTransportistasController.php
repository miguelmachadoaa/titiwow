<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Models\AlpTransportistas;
use App\Http\Requests;
use Illuminate\Http\Request;
use Redirect;
use Sentinel;
use View;


class AlpTransportistasController extends JoshController
{
    /**
     * Show a list of all the groups.
     *
     * @return View
     */
    public function index()
    {
        // Grab all the groups
      

        $transportistas = AlpTransportistas::all();
       


        // Show the page
        return view('admin.transportistas.index', compact('transportistas'));
    }

    /**
     * Group create.
     *
     * @return View
     */
    public function create()
    {
        // Show the page
        return view ('admin.transportistas.create');
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
            'nombre_transportista' => $request->nombre_transportista, 
            'descripcion_transportista' => $request->descripcion_transportista, 
            'id_user' =>$user_id
        );
         
        $transportistas=AlpTransportistas::create($data);

        if ($transportistas->id) {

            return redirect('admin/transportistas')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/transportistas')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
       
       $transportistas = AlpTransportistas::find($id);

        return view('admin.transportistas.edit', compact('transportistas'));
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
            'nombre_transportista' => $request->nombre_transportista, 
            'descripcion_transportista' => $request->descripcion_transportista
        );
         
       $transportistas = AlpTransportistas::find($id);
    
        $transportistas->update($data);

        if ($transportistas->id) {

            return redirect('admin/transportistas')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/transportistas')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
        $model = 'transportistas';
        $confirm_route = $error = null;
        try {
            // Get group information
            
            $transportistas = AlpTransportistas::find($id);

            $confirm_route = route('admin.transportistas.delete', ['id' => $transportistas->id]);

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
           
            $transportistas = AlpTransportistas::find($id);


            // Delete the group
            $transportistas->delete();

            // Redirect to the group management page
            return Redirect::route('admin.transportistas.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.transportistas.index')->with('error', trans('Error al eliminar el registro'));
        }
    }

    

}
