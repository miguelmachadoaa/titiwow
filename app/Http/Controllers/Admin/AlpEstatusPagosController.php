<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Models\AlpEstatusPagos;
use App\Http\Requests;
use App\Http\Requests\EstatusPagosRequest;
use Illuminate\Http\Request;
use Redirect;
use Sentinel;
use View;


class AlpEstatusPagosController extends JoshController
{
    /**
     * Show a list of all the groups.
     *
     * @return View
     */
    public function index()
    {
        // Grab all the groups
      

        $estatus = AlpEstatusPagos::all();
       


        // Show the page
        return view('admin.estatuspagos.index', compact('estatus'));
    }

    /**
     * Group create.
     *
     * @return View
     */
    public function create()
    {
        // Show the page
        return view ('admin.estatuspagos.create');
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(EstatusPagosRequest $request)
    {
        
         $user_id = Sentinel::getUser()->id;

        //$input = $request->all();

        //var_dump($input);

        $data = array(
            'estatus_pago_nombre' => $request->estatus_pago_nombre, 
            'estatus_pago_descripcion' => $request->estatus_pago_descripcion, 
            'id_user' =>$user_id
        );
         
        $estatus=AlpEstatusPagos::create($data);

        if ($estatus->id) {

            return redirect('admin/estatuspagos')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/estatuspagos')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
       
       $estatus = AlpEstatusPagos::find($id);

        return view('admin.estatuspagos.edit', compact('estatus'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(EstatusPagosRequest $request, $id)
    {
       $data = array(
            'estatus_pago_nombre' => $request->estatus_pago_nombre, 
            'estatus_pago_descripcion' => $request->estatus_pago_descripcion
        );
         
       $estatus = AlpEstatusPagos::find($id);
    
        $estatus->update($data);

        if ($estatus->id) {

            return redirect('admin/estatuspagos')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/estatuspagos')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
            
            $estatus = AlpEstatusPagos::find($id);

            $confirm_route = route('admin.estatuspagos.delete', ['id' => $estatus->id]);

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
           
            $estatus = AlpEstatusPagos::find($id);


            // Delete the group
            $estatus->delete();

            // Redirect to the group management page
            return Redirect::route('admin.estatuspagos.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.estatuspagos.index')->with('error', trans('Error al eliminar el registro'));
        }
    }

    

}
