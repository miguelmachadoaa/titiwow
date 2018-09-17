<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Http\Requests\ImpuestoRequest;
use App\Models\AlpImpuestos;
use App\Http\Requests;
use Illuminate\Http\Request;
use Redirect;
use Sentinel;
use View;


class AlpImpuestosController extends JoshController
{
    /**
     * Show a list of all the groups.
     *
     * @return View
     */
    public function index()
    {
        // Grab all the groups
      

        $impuestos = AlpImpuestos::all();
       


        // Show the page
        return view('admin.impuestos.index', compact('impuestos'));
    }

    /**
     * Group create.
     *
     * @return View
     */
    public function create()
    {
        // Show the page
        return view ('admin.impuestos.create');
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(ImpuestoRequest $request)
    {
        
         $user_id = Sentinel::getUser()->id;

        //$input = $request->all();

        //var_dump($input);

        $data = array(
            'nombre_impuesto' => $request->nombre_impuesto, 
            'valor_impuesto' => $request->valor_impuesto, 
            'id_user' =>$user_id
        );
         
        $impuestos=AlpImpuestos::create($data);

        if ($impuestos->id) {

            return redirect('admin/impuestos')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/impuestos')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
       
       $impuestos = AlpImpuestos::find($id);

        return view('admin.impuestos.edit', compact('impuestos'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(ImpuestoRequest $request, $id)
    {
       $data = array(
            'nombre_impuesto' => $request->nombre_impuesto, 
            'valor_impuesto' => $request->valor_impuesto
        );
         
       $impuestos = AlpImpuestos::find($id);
    
        $impuestos->update($data);

        if ($impuestos->id) {

            return redirect('admin/impuestos')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/impuestos')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
        $model = 'impuestos';
        $confirm_route = $error = null;
        try {
            // Get group information
            
            $impuestos = AlpImpuestos::find($id);

            $confirm_route = route('admin.impuestos.delete', ['id' => $impuestos->id]);

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
           
            $impuestos = AlpImpuestos::find($id);


            // Delete the group
            $impuestos->delete();

            // Redirect to the group management page
            return Redirect::route('admin.impuestos.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.impuestos.index')->with('error', trans('Error al eliminar el registro'));
        }
    }

    

}
