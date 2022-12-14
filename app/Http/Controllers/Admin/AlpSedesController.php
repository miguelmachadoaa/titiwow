<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Http\Requests\SedeRequest;
use App\Models\AlpSedes;
use App\Http\Requests;
use Illuminate\Http\Request;
use Redirect;
use Sentinel;
use View;


class AlpSedesController extends JoshController
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
                        ->log('AlpSedesController/index ');

        }else{

          activity()
          ->log('AlpSedesController/index');


        }


        if (!Sentinel::getUser()->hasAnyAccess(['sedes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }



      

        $sedes = AlpSedes::all();
       


        // Show the page
        return view('admin.sedes.index', compact('sedes'));
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
                        ->log('AlpSedesController/index ');

        }else{

          activity()
          ->log('AlpSedesController/index');


        }


        if (!Sentinel::getUser()->hasAnyAccess(['sedes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }



        return view ('admin.sedes.create');
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(SedeRequest $request)
    {
        

          if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpSedesController/store ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpSedesController/store');


        }


        if (!Sentinel::getUser()->hasAnyAccess(['sedes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }



         $user_id = Sentinel::getUser()->id;

        //$input = $request->all();

        //var_dump($input);

         $coords=str_replace(array('(',')'),'',$request->coords);

        $partes=explode(',', $coords);

        $data = array(
            'nombre_sede' => $request->nombre_sede, 
            'descripcion_sede' => $request->descripcion_sede, 
            'latitud_sede' => $partes[0], 
            'longitud_sede' => $partes[1], 
            'id_user' =>$user_id
        );
         
        $sede=AlpSedes::create($data);

        if ($sede->id) {

            return redirect('admin/sedes')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/sedes')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
       
       $sedes = AlpSedes::find($id);

       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('AlpSedesController/edit ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpSedesController/edit');


        }


        if (!Sentinel::getUser()->hasAnyAccess(['sedes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }



        return view('admin.sedes.edit', compact('sedes'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(SedeRequest $request, $id)
    {


          if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpSedesController/update ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpSedesController/update');


        }


        if (!Sentinel::getUser()->hasAnyAccess(['sedes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }




         $coords=str_replace(array('(',')'),'',$request->coords);

        $partes=explode(',', $coords);

            

       $data = array(
            'nombre_sede' => $request->nombre_sede, 
            'descripcion_sede' => $request->descripcion_sede,
            'latitud_sede' => $partes[0], 
            'longitud_sede' => $partes[1], 
        );
         
       $sede = AlpSedes::find($id);
    
        $sede->update($data);

        if ($sede->id) {

            return redirect('admin/sedes')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/sedes')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
        $model = 'sedes';
        $confirm_route = $error = null;
        try {
            // Get group insedetion
            
            $sede = AlpSedes::find($id);

            $confirm_route = route('admin.sedes.delete', ['id' => $sede->id]);

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
                        ->withProperties(['id'=>$id])->log('AlpSedesController/destroy ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpSedesController/destroy');


        }


        if (!Sentinel::getUser()->hasAnyAccess(['sedes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


        try {
            // Get group insedetion
           
            $sede = AlpSedes::find($id);


            // Delete the group
            $sede->delete();

            // Redirect to the group management page
            return Redirect::route('admin.sedes.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.sedes.index')->with('error', trans('Error al eliminar el registro'));
        }
    }

    

}
