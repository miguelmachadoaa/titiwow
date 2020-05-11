<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\State;
use App\City;
use App\Models\AlpTDocumento;
use App\Models\AlpEstructuraAddress;
use App\Models\AlpEmpresas;
use App\Models\AlpClientes;
use App\Models\AlpAmigos;
use App\Models\AlpPrecioGrupo;
use App\User;
use App\Barrio;

use App\Models\AlpEmpresasUser;
use App\Http\Requests\BarrioRequest;
use App\Http\Requests;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

use App\Imports\InvitacionesImport;
use Maatwebsite\Excel\Facades\Excel;

use Activation;
use Redirect;
use Sentinel;
use View;


class AlpBarriosController extends JoshController
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
                        ->log('empresas/index ');

        }else{

          activity()
          ->log('empresas/index');


        }
      

        $barrios = Barrio::all();
       


        // Show the page
        return view('admin.barrios.index', compact('barrios'));
    }

    public function data()
    {

       $barrios=Barrio::select('config_barrios.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name')
        ->join('config_cities','config_barrios.city_id' , '=', 'config_cities.id')
        ->join('config_states','config_cities.state_id' , '=', 'config_states.id')
        ->get();
       
         
        $data = array();

        foreach($barrios as $row){

          

        $actions = "   <a href='".secure_url('admin/barrios/'.$row->id.'/edit')."'>
                      <i class='livicon' data-name='edit' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='Editar Empresa'></i>
                  </a> <a href='".secure_url('admin/barrios/'.$row->id.'/confirm-delete')."' data-toggle='modal' data-target='#delete_confirm'>
                  <i class='livicon' data-name='remove-alt' data-size='18'
                      data-loop='true' data-c='#f56954' data-hc='#f56954'
                      title='Eliminar'></i>
                   </a>";


               $data[]= array(
                 $row->id, 
                 $row->state_name, 
                 $row->city_name, 
                 $row->barrio_name, 
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
                        ->log('empresas/create ');

        }else{

          activity()
          ->log('empresas/create');


        }

        $states=State::where('config_states.country_id', '47')->get();


        // Show the page
        return view ('admin.barrios.create', compact('states'));
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(BarrioRequest $request)
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('empresas/store ');

        }else{

          activity()
          ->withProperties($request->all())->log('empresas/store');


        }
        
         $user_id = Sentinel::getUser()->id;


        $data = array(
            'barrio_name' => $request->barrio_name, 
            'city_id' => $request->city_id, 
           
            'id_user' =>$user_id
        );
         
        $barrio=Barrio::create($data);


   

        if ($barrio->id) {

            return redirect('admin/barrios')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/barrios')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
                        ->withProperties(['id'=>$id])->log('barrios/edit ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('barrios/edit');


        }
       
       $barrio=Barrio::select('config_barrios.*', 'config_cities.city_name as city_name', 'config_cities.id as city_id', 'config_states.state_name as state_name', 'config_states.id as state_id')
        ->join('config_cities','config_barrios.city_id' , '=', 'config_cities.id')
        ->join('config_states','config_cities.state_id' , '=', 'config_states.id')
        ->where('config_barrios.id', $id)
        ->first();

       $states=State::where('config_states.country_id', '47')->get();

       $cities=City::where('state_id', $barrio->state_id)->get();

        return view('admin.barrios.edit', compact('barrio', 'states', 'cities'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(BarrioRequest $request, $id)
    {

          if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('empresas/update ');

        }else{

          activity()
          ->withProperties($request->all())->log('empresas/update');


        }



          $user_id = Sentinel::getUser()->id;

          $barrio=Barrio::where('id', $id)->first();


        $data = array(
            'barrio_name' => $request->barrio_name, 
            'city_id' => $request->city_id
        );
         
        $barrio->update($data);



        if ($barrio->id) {

            return redirect('admin/barrios')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/barrios')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
        $model = 'barrios';
        $confirm_route = $error = null;
        try {
            // Get group inempresastion
            
            $barrio = Barrio::find($id);

            $confirm_route = route('admin.barrios.delete', ['id' => $barrio->id]);

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
                        ->withProperties(['id'=>$id])->log('empresas/destroy ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('empresas/destroy');


        }


        try {
            // Get group inempresastion
           
            $barrio = Barrio::find($id);

            // Delete the group
            $barrio->delete();

            // Redirect to the group management page
            return Redirect::route('admin.barrios.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.barrios.index')->with('error', trans('Error al eliminar el registro'));
        }
    }


}
