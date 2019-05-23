<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Models\AlpEstatusOrdenes;
use App\Http\Requests;
use App\Http\Requests\EstatusOrdenesRequest;
use Illuminate\Http\Request;
use Redirect;
use Sentinel;
use View;


class AlpEstatusOrdenesController extends JoshController
{
    /**
     * Show a list of all the groups.
     *
     * @return View
     */
    public function index()
    {
        // Grab all the groups
      

        $estatus = AlpEstatusOrdenes::all();
       


        // Show the page
        return view('admin.estatus.index', compact('estatus'));
    }


      public function data()
    {
       
            $estatus = AlpEstatusOrdenes::all();

        $data = array();

        foreach($estatus as $row){


            $actions = "    <a href='".route('admin.estatus.edit', $row->id)."'>
                                                <i class='livicon' data-name='edit' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='editar categoria'></i>
                                            </a>



                                            <!-- let's not delete 'Admin' group by accident -->
                                            
                                            <a href='".secure_url('admin/estatus/'.$row->id.'/confirm-delete')."' data-toggle='modal' data-target='#delete_confirm'>
                                            <i class='livicon' data-name='remove-alt' data-size='18'
                                                data-loop='true' data-c='#f56954' data-hc='#f56954'
                                                title='Eliminar'></i>
                                             </a>
";



               $data[]= array(
                 $row->id, 
                 $row->estatus_nombre, 
                 $row->descripcion_estatus, 
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
        return view ('admin.estatus.create');
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(EstatusOrdenesRequest $request)
    {
        
         $user_id = Sentinel::getUser()->id;

        //$input = $request->all();

        //var_dump($input);

        $data = array(
            'estatus_nombre' => $request->estatus_nombre, 
            'descripcion_estatus' => $request->descripcion_estatus, 
            'id_user' =>$user_id
        );
         
        $estatus=AlpEstatusOrdenes::create($data);

        if ($estatus->id) {

            return redirect('admin/estatus')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/estatus')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
       
       $estatus = AlpEstatusOrdenes::find($id);

        return view('admin.estatus.edit', compact('estatus'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(EstatusOrdenesRequest $request, $id)
    {
       $data = array(
            'estatus_nombre' => $request->estatus_nombre, 
            'descripcion_estatus' => $request->descripcion_estatus
        );
         
       $estatus = AlpEstatusOrdenes::find($id);
    
        $estatus->update($data);

        if ($estatus->id) {

            return redirect('admin/estatus')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/estatus')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
            
            $estatus = AlpEstatusOrdenes::find($id);

            $confirm_route = route('admin.estatus.delete', ['id' => $estatus->id]);

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
           
            $estatus = AlpEstatusOrdenes::find($id);


            // Delete the group
            $estatus->delete();

            // Redirect to the group management page
            return Redirect::route('admin.estatus.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.estatus.index')->with('error', trans('Error al eliminar el registro'));
        }
    }

    

}
