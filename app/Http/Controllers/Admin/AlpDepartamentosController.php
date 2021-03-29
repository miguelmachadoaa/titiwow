<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Http\Requests\DepartamentoRequest;
use App\Models\AlpDepartamento;
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
                                             </a>";

             


                                          


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

    

}
