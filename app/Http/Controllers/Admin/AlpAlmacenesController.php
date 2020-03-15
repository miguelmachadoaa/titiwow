<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Models\AlpTDocumento;
use App\Models\AlpEstructuraAddress;
use App\Models\AlpAlmacenes;
use App\Models\AlpClientes;
use App\Models\AlpAmigos;
use App\Models\AlpPrecioGrupo;
use App\User;
use App\State;
use App\City;

use App\Models\AlpAlmacenesUser;
use App\Http\Requests\EmpresaRequest;
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


class AlpAlmacenesController extends JoshController
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
                        ->log('almacenes/index ');

        }else{

          activity()
          ->log('almacenes/index');


        }
      

        $almacenes = AlpAlmacenes::all();
       


        // Show the page
        return view('admin.almacenes.index', compact('almacenes'));
    }

    public function data()
    {
       
        $almacenes = AlpAlmacenes::all();
         
        $data = array();

        foreach($almacenes as $row){

           if ($row->estado_registro=='1') {

             $estatus=" <div class='estatus_".$row->id."'>
 <button data-url='".secure_url('admin/almacenes/estatus')."' type='buttton' data-id='".$row->id."' data-estatus='0' class='btn btn-xs btn-danger estatus'>Desactivar</button>
</div>";

           }else{

            $estatus="<div class='estatus_".$row->id."'>
<button data-url='".secure_url('admin/almacenes/estatus')."' type='buttton' data-id='".$row->id."' data-estatus='1' class='btn btn-xs btn-success estatus'>Activar</button>
 </div>";

           }

        $actions = "    <a href='".secure_url('admin/almacenes/'.$row->id.'/edit')."'>
                                                <i class='livicon' data-name='edit' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='Editar Empresa'></i>
                                            </a>  <a href='".secure_url('admin/almacenes/'.$row->id.'/confirm-delete')."' data-toggle='modal' data-target='#delete_confirm'>
                                            <i class='livicon' data-name='remove-alt' data-size='18'
                                                data-loop='true' data-c='#f56954' data-hc='#f56954'
                                                title='Eliminar'></i>
                                             </a>";

                


               $data[]= array(
                 $row->id, 
                 $row->nombre_almacen, 
                 $row->descripcion_almacen, 
                 $row->convenio, 
                 $row->descuento_empresa, 
                 $estatus, 
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
                        ->log('almacenes/create ');

        }else{

          activity()
          ->log('almacenes/create');


        }

        $almacen=AlpAlmacenes::get();

        $states=State::where('config_states.country_id', '47')->get();

        $cities=City::get();
      

        // Show the page
        return view ('admin.almacenes.create', compact('almacen', 'states', 'cities'));
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(EmpresaRequest $request)
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
            'nombre_empresa' => $request->nombre_empresa, 
            'descripcion_empresa' => $request->descripcion_empresa, 
            'descuento_empresa' => $request->descuento_empresa, 
            'dominio' => $request->dominio, 
            'convenio' => $request->convenio, 
            'id_user' =>$user_id
        );
         
        $empresas=AlpAlmacenes::create($data);


      

        if ($empresas->id) {

            return redirect('admin/empresas')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/empresas')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
                        ->withProperties(['id'=>$id])->log('empresas/edit ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('empresas/edit');


        }
       
       $empresas = AlpAlmacenes::find($id);

        return view('admin.almacenes.edit', compact('empresas'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(EmpresaRequest $request, $id)
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




        if ($request->hasFile('image')) {
            
            $file = $request->file('image');
            $extension = $file->extension()?: 'png';
            $picture = str_random(10) . '.' . $extension;
            $destinationPath = public_path('uploads/empresas/' . $picture);    
            Image::make($file)->resize(400, 400)->save($destinationPath);            
            $imagen = $picture;

             $data = array(
            'nombre_empresa' => $request->nombre_empresa, 
            'descripcion_empresa' => $request->descripcion_empresa,
            'descuento_empresa' => $request->descuento_empresa,
            'dominio' => $request->dominio, 
            'convenio' => $request->convenio, 
            'imagen' => $imagen
        );



        }else{

                $data = array(
            'nombre_empresa' => $request->nombre_empresa, 
            'descripcion_empresa' => $request->descripcion_empresa,
            'dominio' => $request->dominio, 
            'convenio' => $request->convenio, 
            'descuento_empresa' => $request->descuento_empresa
                );

        }

         
       $empresas = AlpAlmacenes::find($id);
    
        $empresas->update($data);

        if ($empresas->id) {

            return redirect('admin/empresas')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/empresas')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
        $model = 'empresas';
        $confirm_route = $error = null;
        try {
            // Get group inempresastion
            
            $empresas = AlpAlmacenes::find($id);

            $confirm_route = route('admin.almacenes.delete', ['id' => $empresas->id]);

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
           
            $empresas = AlpAlmacenes::find($id);

            // Delete the group
            $empresas->delete();

            // Redirect to the group management page
            return Redirect::route('admin.almacenes.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.almacenes.index')->with('error', trans('Error al eliminar el registro'));
        }
    }

 


}
