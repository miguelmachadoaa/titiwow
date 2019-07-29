<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\State;
use App\Models\AlpTDocumento;
use App\Models\AlpEstructuraAddress;
use App\Models\AlpEmpresas;
use App\Models\AlpClientes;
use App\Models\AlpAmigos;
use App\Models\AlpPrecioGrupo;
use App\User;

use App\Models\AlpEmpresasUser;
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


class AlpEmpresasController extends JoshController
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
      

        $empresas = AlpEmpresas::all();
       


        // Show the page
        return view('admin.empresas.index', compact('empresas'));
    }

    public function data()
    {
       
        $empresas = AlpEmpresas::all();
         
        $data = array();

        foreach($empresas as $row){

           if ($row->estado_registro=='1') {

             $estatus=" <div class='estatus_".$row->id."'>
 <button data-url='".secure_url('admin/empresas/estatus')."' type='buttton' data-id='".$row->id."' data-estatus='0' class='btn btn-xs btn-danger estatus'>Desactivar</button>
</div>";

           }else{

            $estatus="<div class='estatus_".$row->id."'>
<button data-url='".secure_url('admin/empresas/estatus')."' type='buttton' data-id='".$row->id."' data-estatus='1' class='btn btn-xs btn-success estatus'>Activar</button>
 </div>";

           }

        $actions = "   <a href='".secure_url('admin/empresas/'.$row->id.'/invitaciones')."'>
                                                <i class='livicon' data-name='plus' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='Invitaciones'></i>
                                            </a>


                                            <a href='".secure_url('admin/empresas/'.$row->id.'/edit')."'>
                                                <i class='livicon' data-name='edit' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='Editar Empresa'></i>
                                            </a>

                                          

                                            
                                            <a href='".secure_url('admin/empresas/'.$row->id.'/confirm-delete')."' data-toggle='modal' data-target='#delete_confirm'>
                                            <i class='livicon' data-name='remove-alt' data-size='18'
                                                data-loop='true' data-c='#f56954' data-hc='#f56954'
                                                title='Eliminar'></i>
                                             </a>";

                $imagen="<img style='width:  80px;' src='".secure_url('uploads/empresas/'.$row->imagen)."' class='img-responsive' alt='Image'>";


               $data[]= array(
                 $row->id, 
                 $imagen, 
                 $row->nombre_empresa, 
                 $row->descripcion_empresa, 
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
                        ->log('empresas/create ');

        }else{

          activity()
          ->log('empresas/create');


        }

        $empresas=AlpEmpresas::get();
      


        // Show the page
        return view ('admin.empresas.create', compact('empresas'));
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

        //$input = $request->all();

        //var_dump($input);


          $imagen='default.png';

         $picture = "";

        
        if ($request->hasFile('image')) {
            
            $file = $request->file('image');
            $extension = $file->extension()?: 'png';
            $picture = str_random(10) . '.' . $extension;
            $destinationPath = public_path('uploads/empresas/' . $picture);    
            Image::make($file)->resize(400, 400)->save($destinationPath);            
            $imagen = $picture;

        }



        $data = array(
            'nombre_empresa' => $request->nombre_empresa, 
            'descripcion_empresa' => $request->descripcion_empresa, 
            'descuento_empresa' => $request->descuento_empresa, 
            'dominio' => $request->dominio, 
            'imagen' => $imagen, 
            'id_user' =>$user_id
        );
         
        $empresas=AlpEmpresas::create($data);


        if ($request->precio_empresa!=0) {
            
          $precios=AlpPrecioGrupo::where('id_role','E'.$request->precio_empresa )->get();

          foreach ($precios as $pg) {
            $pge = array(
              'id_producto' => $pg->id_producto, 
              'id_role' =>'E'.$empresas->id, 
              'operacion' => $pg->operacion, 
              'city_id' => $pg->city_id, 
              'precio' => $pg->precio, 
              'pum' => $pg->pum, 
              'id_user' => $user->id
            );

            AlpPrecioGrupo::create($pge);
          }


        }

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
       
       $empresas = AlpEmpresas::find($id);

        return view('admin.empresas.edit', compact('empresas'));
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
            
            'imagen' => $imagen
        );



        }else{

                $data = array(
            'nombre_empresa' => $request->nombre_empresa, 
            'descripcion_empresa' => $request->descripcion_empresa,
            'dominio' => $request->dominio, 
            'descuento_empresa' => $request->descuento_empresa
                );

        }

         
       $empresas = AlpEmpresas::find($id);
    
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
            
            $empresas = AlpEmpresas::find($id);

            $confirm_route = route('admin.empresas.delete', ['id' => $empresas->id]);

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
           
            $empresas = AlpEmpresas::find($id);

            // Delete the group
            $empresas->delete();

            // Redirect to the group management page
            return Redirect::route('admin.empresas.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.empresas.index')->with('error', trans('Error al eliminar el registro'));
        }
    }

     public function invitaciones($id)
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('empresas/invitaciones ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('empresas/invitaciones');


        }


        $empresa = AlpEmpresas::find($id);

        $invitaciones = AlpAmigos::where('id_cliente', 'E'.$id)->get();
        // Show the page
        return view('admin.empresas.invitaciones', compact('empresa','invitaciones'));
    }



    public function storeamigo(Request $request)
    {

          if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('empresas/storeamigo ');

        }else{

          activity()
          ->withProperties($request->all())->log('empresas/storeamigo');


        }



        if (Sentinel::check()) {

            $user_id = Sentinel::getUser()->id;

            $token=substr(md5(time()), 0, 10);

            $data = array(
                'id_cliente' => 'E'.$request->id_empresa, 
                'nombre_amigo' => $request->nombre, 
                'apellido_amigo' => $request->apellido, 
                'email_amigo' => $request->email, 
                'token' => $token, 
                'id_user' => $user_id
            );

            AlpAmigos::create($data);

            $invitaciones=AlpAmigos::where('id_cliente', 'E'.$request->id_empresa)->get();

            $empresa = AlpEmpresas::find($request->id_empresa);

            Mail::to($request->email)->send(new \App\Mail\NotificacionAfiliado($request->nombre, $request->apellido, $token, $empresa->nombre_empresa));


            $view= View::make('admin.empresas.listamigo', compact('invitaciones', 'empresa'));

            $data=$view->render();

            return $data;

            }

       
    }

    public function delamigo(Request $request)
    {


           if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('empresas/delamigo ');

        }else{

          activity()
          ->withProperties($request->all())->log('empresas/delamigo');


        }

            $user_id = Sentinel::getUser()->id;

            $amigo=AlpAmigos::find($request->id);

            $empresa=$amigo->id_cliente;

            $amigo->delete();

            $invitaciones=AlpAmigos::where('id_cliente', $empresa)->get();

            $empresa = AlpEmpresas::find($request->id_empresa);

            $view= View::make('admin.empresas.listamigo', compact('invitaciones', 'empresa'));

            $data=$view->render();

            return $data;
       
    }


     public function afiliado($id)
    {

             if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('empresas/afiliado ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('empresas/afiliado');


        }




        $amigo=AlpAmigos::where('token', $id)->first();

        $states=State::where('config_states.country_id', '47')->get();

        $t_documento = AlpTDocumento::where('estado_registro','=',1)->get();

        $estructura = AlpEstructuraAddress::where('estado_registro','=',1)->get();

 
        if (!isset($amigo->id)) {

                $mensaje="Su enlace de registro ha vencido, solicite uno nuevo o registrese como cliente";

                return view('frontend.clientes.aviso',  compact('mensaje'));

        }
                
        return view('frontend.empresas.registro',  compact('id', 'amigo', 'states','t_documento','estructura'));
        
    }

    public function invitacionesmasiv()
    {

           if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('empresas/invitacionesmasiv ');

        }else{

          activity()
          ->log('empresas/invitacionesmasiv');


        }

        $empresa = AlpEmpresas::all();

        /*$invitaciones = AlpAmigos::where('id_cliente', 'E'.$id)->get();*/
        // Show the page
        return view('admin.empresas.cargar', compact('empresa'));
    }

    public function import(Request $request) 
    {
        $archivo = $request->file('file_invitaciones');
        Excel::import(new InvitacionesImport, $archivo);
        
        return redirect('admin/empresas/invitacionesmasiv')->with('success', 'Invitaciones Cargadas Exitosamente');
    }



     public function estatus(Request $request)
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('empresas/estatus ');

        }else{

          activity()
          ->withProperties($request->all())->log('empresas/estatus');

        }

        $input = $request->all();

        $empresa=AlpEmpresas::find($request->id);

        $data = array('estado_registro' => $request->estatus );

        $empresa->update($data);


             if ($request->estatus==0) {

              $clientes=AlpClientes::Where('id_empresa', $empresa->id)->get();

                foreach ($clientes as $cliente) {

                  $usuario=User::where('id', $cliente->id_user_client)->first();

                   $role = Sentinel::findRoleById(12);

                  $role->users()->detach($usuario);

                  $role = Sentinel::findRoleById(9);

                  $role->users()->detach($usuario);

                  $role->users()->attach($usuario);

                  $c=AlpClientes::where('id', $cliente->id)->first();

                      $data_cliente_update = array(
                      'id_empresa' =>'0'
                       );

                    $c->update($data_cliente_update);

                    $data_empresa = array(
                      'id_empresa' => $empresa->id, 
                      'id_cliente' => $cliente->id_user_client, 
                      'id_user' => $user->id
                    );

                    AlpEmpresasUser::create($data_empresa);

                }

            }else{

              $clientes=AlpEmpresasUser::Where('id_empresa', $empresa->id)->get();

              //dd($clientes);  

                foreach ($clientes as $cliente) {

                  $c=AlpClientes::where('id_user_client', $cliente->id_cliente)->first();

                  $usuario=User::where('id', $c->id_user_client)->first();

                  $role = Sentinel::findRoleById(9);

                  $role->users()->detach($usuario);

                  $role = Sentinel::findRoleById(12);

                  $role->users()->detach($usuario);

                  $role->users()->attach($usuario);

                  //$c=AlpClientes::where('id', $cliente->id)->first();

                      $data_cliente_update = array(
                      'id_empresa' =>$cliente->id_empresa
                       );

                    $c->update($data_cliente_update);

                    AlpEmpresasUser::where('id_cliente', $cliente->id_cliente)->delete();

                }

            }


        $view= View::make('admin.empresas.estatus', compact('empresa'));

        $data=$view->render();
        
        return $data;
    }



}
