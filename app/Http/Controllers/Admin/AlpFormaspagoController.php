<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Http\Requests\FormapagoRequest;
use App\Models\AlpFormaspago;
use App\Http\Requests;
use Illuminate\Http\Request;
use Redirect;
use Sentinel;
use View;


class AlpFormaspagoController extends JoshController
{
    /**
     * Show a list of all the groups.
     *
     * @return View
     */
    public function index()
    {
        // Grab all the groups
      

        $formas = AlpFormaspago::all();
       


        // Show the page
        return view('admin.formaspago.index', compact('formas'));
    }

    public function data()
    {
       
        
       $formas = AlpFormaspago::all();
         
        $data = array();

        foreach($formas as $row){

           
        $actions = "
                                            <a href='".route('admin.formaspago.edit', $row->id)."'>
                                                <i class='livicon' data-name='edit' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='editar categoria'></i>
                                            </a>



                                            <!-- let's not delete 'Admin' group by accident -->
                                            
                                            <a href='".route('admin.formaspago.confirm-delete', $row->id)."' data-toggle='modal' data-target='#delete_confirm'>
                                            <i class='livicon' data-name='remove-alt' data-size='18'
                                                data-loop='true' data-c='#f56954' data-hc='#f56954'
                                                title='Eliminar'></i>
                                             </a>";

                


               $data[]= array(
                 $row->id, 
                 $row->nombre_forma_pago, 
                 $row->descripcion_forma_pago, 
                 $row->created_at->diffForHumans(), 
                 $actions
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
        // Show the page
        return view ('admin.formaspago.create');
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(FormapagoRequest $request)
    {
        
         $user_id = Sentinel::getUser()->id;

        //$input = $request->all();

        //var_dump($input);

        $data = array(
            'nombre_forma_pago' => $request->nombre_forma_pago, 
            'descripcion_forma_pago' => $request->descripcion_forma_pago, 
            'id_user' =>$user_id
        );
         
        $forma=AlpFormaspago::create($data);

        if ($forma->id) {

            return redirect('admin/formaspago')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/formaspago')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
       
       $forma = AlpFormaspago::find($id);

        return view('admin.formaspago.edit', compact('forma'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(FormapagoRequest $request, $id)
    {
       $data = array(
            'nombre_forma_pago' => $request->nombre_forma_pago, 
            'descripcion_forma_pago' => $request->descripcion_forma_pago
        );
         
       $forma = AlpFormaspago::find($id);
    
        $forma->update($data);

        if ($forma->id) {

            return redirect('admin/formaspago')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/formaspago')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
        $model = 'formaspago';
        $confirm_route = $error = null;
        try {
            // Get group information
            
            $forma = AlpFormaspago::find($id);

            $confirm_route = route('admin.formaspago.delete', ['id' => $forma->id]);

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
           
            $forma = AlpFormaspago::find($id);


            // Delete the group
            $forma->delete();

            // Redirect to the group management page
            return Redirect::route('admin.formaspago.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.formaspago.index')->with('error', trans('Error al eliminar el registro'));
        }
    }

    

}
