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

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpEstatusPagosController/index ');

        }else{

          activity()
          ->log('AlpEstatusPagosController/index');


        }

      

        $estatus = AlpEstatusPagos::all();
       


        // Show the page
        return view('admin.estatuspagos.index', compact('estatus'));
    }


         public function data()
    {
       
              $estatus = AlpEstatusPagos::all();

        $data = array();

        foreach($estatus as $row){


            $actions = "    <a href='".secure_url('admin/estatuspagos/'.$row->id.'/edit' )."'>
                                                <i class='livicon' data-name='edit' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='editar categoria'></i>
                                            </a>

                                            
                                            <a href='".secure_url('admin/estatuspagos/'.$row->id.'/confirm-delete')."' data-toggle='modal' data-target='#delete_confirm'>
                                            <i class='livicon' data-name='remove-alt' data-size='18'
                                                data-loop='true' data-c='#f56954' data-hc='#f56954'
                                                title='Eliminar'></i>
                                             </a>";



               $data[]= array(
                 $row->id, 
                 $row->estatus_pago_nombre, 
                 $row->estatus_pago_descripcion, 
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

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpEstatusPagosController/create ');

        }else{

          activity()
          ->log('AlpEstatusPagosController/create');


        }


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
         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpEstatusPagosController/store ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpEstatusPagosController/store');


        }
        
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

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('AlpEstatusPagosController/edit ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpEstatusPagosController/edit');


        }
       
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

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpEstatusPagosController/update ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpEstatusPagosController/update');


        }


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

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('AlpEstatusPagosController/destroy ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpEstatusPagosController/destroy');


        }

        
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
