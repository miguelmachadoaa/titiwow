<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Http\Requests\TransportistasRequest;
use App\Models\AlpTransportistas;
use App\Http\Requests;
use App\Http\Requests\TransportistaRequest;
use Illuminate\Http\Request;
use Redirect;
use Sentinel;
use View;


class AlpTransportistasController extends JoshController
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
                        ->log('AlpTransportistasController/index ');

        }else{

          activity()
          ->log('AlpTransportistasController/index');


        }


      

        $transportistas = AlpTransportistas::all();
       


        // Show the page
        return view('admin.transportistas.index', compact('transportistas'));
    }


 public function data()
    {
       
          $transportistas = AlpTransportistas::all();

        $data = array();

        foreach($transportistas as $row){


        $actions = " <a href='".secure_url('admin/transportistas/'.$row->id.'/edit')."'>
                                                <i class='livicon' data-name='edit' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='editar Documentos'></i>
                                            </a>


                                            
                                            <a href='".secure_url('admin/transportistas/'.$row->id.'/confirm-delete')."' data-toggle='modal' data-target='#delete_confirm'>
                                            <i class='livicon' data-name='remove-alt' data-size='18'
                                                data-loop='true' data-c='#f56954' data-hc='#f56954'
                                                title='Eliminar'></i>
                                             </a>";


               $data[]= array(
                 $row->id, 
                 $row->nombre_transportista, 
                 $row->descripcion_transportista, 
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


         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpTransportistasController/create ');

        }else{

          activity()
          ->log('AlpTransportistasController/create');


        }


        return view ('admin.transportistas.create');
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(TransportistaRequest $request)
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpTransportistasController/store ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpTransportistasController/store');


        }


        
         $user_id = Sentinel::getUser()->id;

        //$input = $request->all();

        //var_dump($input);

        $data = array(
            'nombre_transportista' => $request->nombre_transportista, 
            'descripcion_transportista' => $request->descripcion_transportista, 
            'id_user' =>$user_id
        );
         
        $transportistas=AlpTransportistas::create($data);

        if ($transportistas->id) {

            return redirect('admin/transportistas')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/transportistas')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
                        ->withProperties(['id'=>$id])->log('AlpTransportistasController/edit ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpTransportistasController/edit');


        }


       
       $transportistas = AlpTransportistas::find($id);

        return view('admin.transportistas.edit', compact('transportistas'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(TransportistaRequest $request, $id)
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpTransportistasController/update ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpTransportistasController/update');


        }


       $data = array(
            'nombre_transportista' => $request->nombre_transportista, 
            'descripcion_transportista' => $request->descripcion_transportista
        );
         
       $transportistas = AlpTransportistas::find($id);
    
        $transportistas->update($data);

        if ($transportistas->id) {

            return redirect('admin/transportistas')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/transportistas')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
        $model = 'transportistas';
        $confirm_route = $error = null;
        try {
            // Get group information
            
            $transportistas = AlpTransportistas::find($id);

            $confirm_route = route('admin.transportistas.delete', ['id' => $transportistas->id]);

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
                        ->withProperties(['id'=>$id])->log('AlpTransportistasController/destroy ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpTransportistasController/destroy');


        }

        
        try {
            // Get group information
           
            $transportistas = AlpTransportistas::find($id);


            // Delete the group
            $transportistas->delete();

            // Redirect to the group management page
            return Redirect::route('admin.transportistas.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.transportistas.index')->with('error', trans('Error al eliminar el registro'));
        }
    }

    

}
