<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Http\Requests\TransportistasRequest;
use App\Models\AlpFeriados;
use App\Http\Requests;
use App\Http\Requests\FeriadosRequest;
use Illuminate\Http\Request;
use Redirect;
use Sentinel;
use View;


class AlpFeriadosController extends JoshController
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
                        ->log('AlpFeriadosController/edit ');

        }else{

          activity()
          ->log('AlpFeriadosController/edit');


        }
      

        $feriados = AlpFeriados::all();
       


        // Show the page
        return view('admin.feriados.index', compact('feriados'));
    }

    public function data()
    {
       
        
        $feriados = AlpFeriados::all();
         
        $data = array();

        foreach($feriados as $row){

           
        $actions = "   
                                            <a href='". secure_url('admin/feriados/'.$row->id.'/edit') ."'>
                                                <i class='livicon' data-name='edit' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='editar categoria'></i>
                                            </a>



                                            
                                            
                                            <a href='". secure_url('admin/feriados/'.$row->id.'/confirm-delete') ."' data-toggle='modal' data-target='#delete_confirm'>
                                            <i class='livicon' data-name='remove-alt' data-size='18'
                                                data-loop='true' data-c='#f56954' data-hc='#f56954'
                                                title='Eliminar'></i>
                                             </a>";

                


               $data[]= array(
                 $row->id, 
                 $row->feriado, 
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
                        ->log('AlpFeriadosController/create ');

        }else{

          activity()
          ->log('AlpFeriadosController/create');


        }


        // Show the page
        return view ('admin.feriados.create');
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(FeriadosRequest $request)
    {


         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpFeriadosController/store ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpFeriadosController/store');


        }

        
         $user_id = Sentinel::getUser()->id;

        //$input = $request->all();

        //var_dump($input);

        $data = array(
            'feriado' => $request->feriado, 
            'id_user' =>$user_id
        );
         
        $feriado=AlpFeriados::create($data);

        if ($feriado->id) {

            return redirect('admin/feriados')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/feriados')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
                        ->withProperties(['id'=>$id])->log('AlpFeriadosController/edit ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpFeriadosController/edit');


        }


       $feriados = AlpFeriados::find($id);

        return view('admin.feriados.edit', compact('feriados'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(FeriadosRequest $request, $id)
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpFeriadosController/update ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpFeriadosController/update');


        }


       
       $data = array(
            'feriado' => $request->feriado, 
        );
         
       $feriados = AlpFeriados::find($id);
    
        $feriados->update($data);

        if ($feriados->id) {

            return redirect('admin/feriados')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/feriados')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
        $model = 'feriados';
        $confirm_route = $error = null;
        try {
            // Get group information
            
            $feriados = AlpFeriados::find($id);

            $confirm_route = route('admin.feriados.delete', ['id' => $feriados->id]);

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
                        ->withProperties(['id'=>$id])->log('AlpFeriadosController/destroy ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpFeriadosController/destroy');


        }

        
        try {
            // Get group information
           
            $feriados = AlpFeriados::find($id);


            // Delete the group
            $feriados->delete();

            // Redirect to the group management page
            return Redirect::route('admin.feriados.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.feriados.index')->with('error', trans('Error al eliminar el registro'));
        }
    }

    

}
