<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Http\Requests\UrgenciaRequest;
use App\Models\AlpUrgencia;
use App\Http\Requests;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Redirect;
use Sentinel;
use View;


class AlpUrgenciaController extends JoshController
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
                        ->log('AlpUrgenciaController/index ');

        }else{

          activity()
          ->log('AlpUrgenciaController/index');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['urgencias.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


      

        $urgencias = AlpUrgencia::all();
       


        // Show the page
        return view('admin.urgencias.index', compact('urgencias'));
    }





     public function data()
    {
       
        $urgencias = AlpUrgencia::all();
       

            $data = array();


          foreach($urgencias as $row){


           


                 $actions = " <a href='".secure_url('admin/urgencias/'.$row->id.'/edit')."'>
                                                <i class='livicon' data-name='edit' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='editar categoria'></i>
                                            </a>
 <a href='".secure_url('admin/urgencias/'.$row->id.'/confirm-delete')."' data-toggle='modal' data-target='#delete_confirm'>
                                            <i class='livicon' data-name='remove-alt' data-size='18'
                                                data-loop='true' data-c='#f56954' data-hc='#f56954'
                                                title='Eliminar'></i>
                                             </a>";

             


                                          


               $data[]= array(
                 $row->id, 
                 $row->nombre_urgencia, 
                 $row->descripcion_urgencia, 
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
        // Show the page

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpUrgenciaController/create ');

        }else{

          activity()
          ->log('AlpUrgenciaController/create');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['urgencias.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


        return view ('admin.urgencias.create');
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(UrgenciaRequest $request)
    {

          if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpUrgenciaController/store ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpUrgenciaController/store');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['urgencias.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


        
         $user_id = Sentinel::getUser()->id;

        $input = $request->all();

        //var_dump($input);



         $imagen='0';


        $data = array(
            'nombre_urgencia' => $request->nombre_urgencia, 
            'descripcion_urgencia' => $request->descripcion_urgencia, 
            'id_user' =>$user_id
        );
         
        $urgencia=AlpUrgencia::create($data);


        



        if ($urgencia->id) {

            return redirect('admin/urgencias')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/urgencias')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
                        ->withProperties(['id'=>$id])->log('AlpUrgenciaController/edit ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpUrgenciaController/edit');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['urgencias.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


       
       $urgencia = AlpUrgencia::find($id);



        return view('admin.urgencias.edit', compact('urgencia'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(UrgenciaRequest $request, $id)
    {

          if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpUrgenciaController/store ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpUrgenciaController/store');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['urgencias.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


        $input=$request->all();




    $data = array(
            'nombre_urgencia' => $request->nombre_urgencia, 
            'descripcion_urgencia' => $request->descripcion_urgencia,
        );

         
       $urgencia = AlpUrgencia::find($id);
    
        $urgencia->update($data);


      
     





        if ($urgencia->id) {

            return redirect('admin/urgencias')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/urgencias')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
        $model = 'marcas';
        $confirm_route = $error = null;
        try {
            // Get group information
            
            $urgencia = AlpUrgencia::find($id);

            $confirm_route = route('admin.urgencias.delete', ['id' => $urgencia->id]);

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
                        ->withProperties(['id'=>$id])->log('AlpUrgenciaController/edit ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpUrgenciaController/edit');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['urgencias.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        
        try {
            // Get group information
           
            $urgencia = AlpUrgencia::find($id);


            // Delete the group
            $urgencia->delete();

            // Redirect to the group management page
            return Redirect::route('admin.urgencias.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.urgencias.index')->with('error', trans('Error al eliminar el registro'));
        }
    }

    

}
