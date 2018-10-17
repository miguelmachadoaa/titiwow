<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Models\AlpEnviosEstatus;
use App\Http\Requests;
use App\Http\Requests\EstatusEnviosRequest;
use Illuminate\Http\Request;
use Redirect;
use Sentinel;
use View;


class AlpEstatusEnviosController extends JoshController
{
    /**
     * Show a list of all the groups.
     *
     * @return View
     */
    public function index()
    {
        // Grab all the groups
      

        $estatus = AlpEnviosEstatus::all();
       


        // Show the page
        return view('admin.estatusenvios.index', compact('estatus'));
    }

    /**
     * Group create.
     *
     * @return View
     */
    public function create()
    {
        // Show the page
        return view ('admin.estatusenvios.create');
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(EstatusEnviosRequest $request)
    {
        
         $user_id = Sentinel::getUser()->id;

        //$input = $request->all();

        //var_dump($input);

        $data = array(
            'estatus_envio_nombre' => $request->estatus_envio_nombre, 
            'estatus_envio_descripcion' => $request->estatus_envio_descripcion, 
            'id_user' =>$user_id
        );
         
        $estatus=AlpEnviosEstatus::create($data);

        if ($estatus->id) {

            return redirect('admin/estatusenvios')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/estatusenvios')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
       
       $estatus = AlpEnviosEstatus::find($id);

        return view('admin.estatusenvios.edit', compact('estatus'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(EstatusEnviosRequest $request, $id)
    {
       $data = array(
            'estatus_envio_nombre' => $request->estatus_envio_nombre, 
            'estatus_envio_descripcion' => $request->estatus_envio_descripcion
        );
         
       $estatus = AlpEnviosEstatus::find($id);
    
        $estatus->update($data);

        if ($estatus->id) {

            return redirect('admin/estatusenvios')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/estatusenvios')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
        $model = 'estatus';
        $confirm_route = $error = null;
        try {
            // Get group information
            
            $estatus = AlpEnviosEstatus::find($id);

            $confirm_route = route('admin.estatusenvios.delete', ['id' => $estatus->id]);

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
           
            $estatus = AlpEnviosEstatus::find($id);


            // Delete the group
            $estatus->delete();

            // Redirect to the group management page
            return Redirect::route('admin.estatusenvios.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.estatusenvios.index')->with('error', trans('Error al eliminar el registro'));
        }
    }

    

}
