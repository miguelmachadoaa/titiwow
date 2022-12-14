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


          if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('estatusenvios/index ');

        }else{

          activity()
          ->log('estatusenvios/index');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['estatusenvios.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


      

        $estatus = AlpEnviosEstatus::all();
       


        // Show the page
        return view('admin.estatusenvios.index', compact('estatus'));
    }

    public function data()
    {
       
         $estatus = AlpEnviosEstatus::all();

        $data = array();

        foreach($estatus as $row){


        $actions = "  <a href='".secure_url('admin/estatusenvios/'.$row->id.'/edit')."'>
            <i class='livicon' data-name='edit' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='Editar Estado de Envio'></i>
            </a>
                                            
            <a href='".secure_url('admin/estatusenvios/'.$row->id.'/confirm-delete')."' data-toggle='modal' data-target='#delete_confirm'>
            <i class='livicon' data-name='remove-alt' data-size='18' data-loop='true' data-c='#f56954' data-hc='#f56954' title='Eliminar'></i> </a>";


               $data[]= array(
                 $row->id, 
                 $row->estatus_envio_nombre, 
                 $row->estatus_envio_descripcion, 
                 $row->codigo, 
                 date("d/m/Y H:i:s", strtotime($row->created_at)),
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
                        ->log('estatusenvios/create ');

        }else{

          activity()
          ->log('estatusenvios/create');


        }

         if (!Sentinel::getUser()->hasAnyAccess(['estatusenvios.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }




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

          if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('estatusenvios/store ');

        }else{

          activity()
          ->withProperties($request->all())->log('estatusenvios/store');


        }
        
         $user_id = Sentinel::getUser()->id;

        //$input = $request->all();

        //var_dump($input);

        $data = array(
            'estatus_envio_nombre' => $request->estatus_envio_nombre, 
            'estatus_envio_descripcion' => $request->estatus_envio_descripcion, 
            'codigo' => $request->codigo, 
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


          if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('estatusenvios/edit ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('estatusenvios/edit');


        }

         if (!Sentinel::getUser()->hasAnyAccess(['estatusenvios.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }
        

       
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




       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('estatusenvios/update ');

        }else{

          activity()
          ->withProperties($request->all())->log('estatusenvios/update');

        }

       $data = array(
            'estatus_envio_nombre' => $request->estatus_envio_nombre, 
            'codigo' => $request->codigo, 
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

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('estatusenvios/destroy ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('estatusenvios/destroy');


        }

        
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
