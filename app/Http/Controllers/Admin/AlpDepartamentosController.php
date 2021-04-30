<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Http\Requests\DepartamentoRequest;
use App\Models\AlpDepartamento;
use App\Models\AlpDepartamentoUsuario;
use App\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Redirect;
use Sentinel;
use View;


class AlpDepartamentosController extends JoshController
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
                        ->log('AlpDepartamentosController/index ');

        }else{

          activity()
          ->log('AlpDepartamentosController/index');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['marcas.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


      

        $departamentos = AlpDepartamento::all();
       


        // Show the page
        return view('admin.departamentos.index', compact('departamentos'));
    }





     public function data()
    {
       
        $departamentos = AlpDepartamento::all();
       

            $data = array();


          foreach($departamentos as $row){


           


                 $actions = " <a href='".secure_url('admin/departamentos/'.$row->id.'/edit')."'>
                                                <i class='livicon' data-name='edit' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='editar categoria'></i>
                                            </a>
                                            

                                            <a href='".secure_url('admin/departamentos/'.$row->id.'/confirm-delete')."' data-toggle='modal' data-target='#delete_confirm'>
                                            <i class='livicon' data-name='remove-alt' data-size='18'
                                                data-loop='true' data-c='#f56954' data-hc='#f56954'
                                                title='Eliminar'></i>
                                             </a>
                                             
                                             <!--a href='".secure_url('admin/departamentos/'.$row->id.'/gestionar')."'>
                                                <i class='livicon' data-name='eye' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='Gestionar Departamento'></i>
                                            </a-->

                                             ";

             


                                          


               $data[]= array(
                 $row->id, 
                 $row->nombre_departamento, 
                 $row->descripcion_departamento, 
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
                        ->log('AlpDepartamentosController/create ');

        }else{

          activity()
          ->log('AlpDepartamentosController/create');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['marcas.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


        return view ('admin.departamentos.create');
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(DepartamentoRequest $request)
    {

          if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpDepartamentosController/store ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpDepartamentosController/store');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['marcas.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


        
         $user_id = Sentinel::getUser()->id;

        $input = $request->all();

        //var_dump($input);



         $imagen='0';


        $data = array(
            'nombre_departamento' => $request->nombre_departamento, 
            'descripcion_departamento' => $request->descripcion_departamento, 
            'correos' => $request->correos,
            'id_user' =>$user_id
        );
         
        $departamento=AlpDepartamento::create($data);


        



        if ($departamento->id) {

            return redirect('admin/departamentos')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/departamentos')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
                        ->withProperties(['id'=>$id])->log('AlpDepartamentosController/edit ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpDepartamentosController/edit');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['marcas.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


       
       $departamento = AlpDepartamento::find($id);



        return view('admin.departamentos.edit', compact('departamento'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(DepartamentoRequest $request, $id)
    {

          if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpDepartamentosController/store ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpDepartamentosController/store');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['marcas.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


        $input=$request->all();




        $data = array(
                'nombre_departamento' => $request->nombre_departamento, 
                'descripcion_departamento' => $request->descripcion_departamento,
                'correos' => $request->correos
            );

         
       $departamento = AlpDepartamento::find($id);
    
        $departamento->update($data);


      
     





        if ($departamento->id) {

            return redirect('admin/departamentos')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/departamentos')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
            
            $departamento = AlpDepartamento::find($id);

            $confirm_route = route('admin.departamentos.delete', ['id' => $departamento->id]);

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
                        ->withProperties(['id'=>$id])->log('AlpDepartamentosController/edit ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpDepartamentosController/edit');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['marcas.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        
        try {
            // Get group information
           
            $departamento = AlpDepartamento::find($id);


            // Delete the group
            $departamento->delete();

            // Redirect to the group management page
            return Redirect::route('admin.departamentos.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.departamentos.index')->with('error', trans('Error al eliminar el registro'));
        }
    }




     public function gestionar($id)
    {

      if (!Sentinel::getUser()->hasAnyAccess(['departamentos.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intento acceder');
        }


        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('departamentos/gestionar ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('departamentos/gestionar');

        }

       
       $usuarios =  User::select('users.*')
        ->join('role_users', 'users.id', '=', 'role_users.user_id')
        ->whereIn('role_users.role_id', [1, 2, 3, 4, 5, 6, 7, 8,13, 15])->get();

       $du=AlpDepartamentoUsuario::where('id_departamento', '=', $id)->get();

       $departamento=AlpDepartamento::where('id', $id)->first();


        return view('admin.departamentos.gestionar', compact('usuarios', 'du', 'departamento'));

    }


     public function departamentousuariodata($id)
    {
       
        $departamentos = AlpDepartamentoUsuario::select('alp_departamento_usuario.*', 'users.first_name as first_name', 'users.last_name as last_name', 'users.email as email')
        ->join('users', 'alp_departamento_usuario.id_usuario', '=', 'users.id')
        ->where('alp_departamento_usuario.id_departamento', '=', $id)
        ->get();
       

            $data = array();


          foreach($departamentos as $row){


           


               $eliminar=' <button data-id="'.$row->id.'" type="button" class="btn btn-danger delusuario">
                        Eliminar
                    </button>';

             


                                          


               $data[]= array(
                 $row->id, 
                 $row->first_name.' '.$row->last_name, 
                 $row->email, 
                 $eliminar
              );



          }


          return json_encode( array('data' => $data ));

    }


    




public function addusuario(Request $request)
    {


      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('departamentos/addusuario ');

        }else{

          activity()
          ->withProperties($request->all())
          ->log('departamentos/addusuario');


        }


        $user_id = Sentinel::getUser()->id;


          $p=AlpDepartamentoUsuario::where('id_usuario', $request->id_usuario)->where('id_departamento', '=', $request->id_departamento)->first();


          if (isset($p->id)) {
            return 'false';
          }else{


           $data = array(
              'id_usuario' => $request->id_usuario, 
              'id_departamento' => $request->id_departamento, 
              'id_user' => $user->id, 
            );

            AlpDepartamentoUsuario::create($data);


            return 'true';

          }

    }

 public function delusuario(Request $request){

      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('cupones/deldestacado ');

        }else{

          activity()
          ->withProperties($request->all())
          ->log('cupones/deldestacado');


        }

         $user_id = Sentinel::getUser()->id;

         $dp=AlpDepartamentoUsuario::where('id', $request->id)->first();

         if (isset($dp->id)) {
              $dp->delete();

              return true;
         }else{
            return 'false';
         }


    }


    

}
