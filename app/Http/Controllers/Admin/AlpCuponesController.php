<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Http\Requests\CuponesRequest;
use App\Models\AlpCupones;
use App\Http\Requests;
use Illuminate\Http\Request;
use Redirect;
use Sentinel;
use View;


class AlpCuponesController extends JoshController
{
    /**
     * Show a list of all the groups.
     *
     * @return View
     */
    public function index()
    {
        // Grab all the groups
      

        $cupones = AlpCupones::all();
       


        // Show the page
        return view('admin.cupones.index', compact('cupones'));
    }

    /**
     * Group create.
     *
     * @return View
     */
    public function create()
    {
        // Show the page
        return view ('admin.cupones.create');
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(CuponesRequest $request)
    {
        
         $user_id = Sentinel::getUser()->id;

        //$input = $request->all();

        //var_dump($input);

        $data = array(
            'codigo_cupon' => $request->codigo_cupon, 
            'valor_cupon' => $request->valor_cupon, 
            'tipo_reduccion' => $request->tipo_reduccion, 
            'limite_uso' => $request->limite_uso, 
            'id_user' =>$user_id
        );
         
        $cupon=AlpCupones::create($data);

        if ($cupon->id) {

            return redirect('admin/cupones')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/cupones')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
       
       $cupon = AlpCupones::find($id);

        return view('admin.cupones.edit', compact('cupon'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(CuponesRequest $request, $id)
    {
       $data = array(
            'codigo_cupon' => $request->codigo_cupon, 
            'valor_cupon' => $request->valor_cupon, 
            'tipo_reduccion' => $request->tipo_reduccion, 
            'limite_uso' => $request->limite_uso, 
        );
         
       $cupon = AlpCupones::find($id);
    
        $cupon->update($data);

        if ($cupon->id) {

            return redirect('admin/cupones')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/cupones')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
        $model = 'cupones';
        $confirm_route = $error = null;
        try {
            // Get group information
            
            $forma = AlpCupones::find($id);

            $confirm_route = route('admin.cupones.delete', ['id' => $forma->id]);

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
           
            $forma = AlpCupones::find($id);


            // Delete the group
            $forma->delete();

            // Redirect to the group management page
            return Redirect::route('admin.cupones.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.cupones.index')->with('error', trans('Error al eliminar el registro'));
        }
    }

    

}
