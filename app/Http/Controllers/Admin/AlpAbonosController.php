<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Http\Requests\AbonoRequest;
use App\Models\AlpAbonos;
use App\Http\Requests;
use Illuminate\Http\Request;
use Redirect;
use Sentinel;
use View;


class AlpAbonosController extends JoshController
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
                        ->log('AlpAbonosController/index ');

        }else{

          activity()
          ->log('AlpAbonosController/index');


        }


      



      

        $abonos = AlpAbonos::all();
       


        // Show the page
        return view('admin.abonos.index', compact('abonos'));
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
                        ->log('AlpAbonosController/index ');

        }else{

          activity()
          ->log('AlpAbonosController/index');


        }





        return view ('admin.abonos.create');
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(AbonoRequest $request)
    {
        

          if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpAbonosController/store ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpAbonosController/store');


        }


       


         $user_id = Sentinel::getUser()->id;

        //$input = $request->all();

        //var_dump($input);



        $data = array(
            'codigo_abono' => $request->codigo_abono, 
            'valor_abono' => $request->valor_abono, 
            'fecha_final' => $request->fecha_final,
            'origen' => 'Administrador',  
            'id_user' =>$user_id
        );
         
        $abono=AlpAbonos::create($data);

        if ($abono->id) {

            return redirect('admin/abonos')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/abonos')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
       
       $abono = AlpAbonos::find($id);

       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('AlpAbonosController/edit ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpAbonosController/edit');


        }




        return view('admin.abonos.edit', compact('abono'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(AbonoRequest $request, $id)
    {


          if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpAbonosController/update ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpAbonosController/update');


        }


       



        $data = array(
            'codigo_abono' => $request->codigo_abono, 
            'valor_abono' => $request->valor_abono, 
            'fecha_final' => $request->fecha_final,
            'origen' => 'Administrador',
            'id_user' =>$user->id
        );
         
       $abono = AlpAbonos::find($id);
    
        $abono->update($data);

        if ($abono->id) {

            return redirect('admin/abonos')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/abonos')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
        $model = 'abonos';
        $confirm_route = $error = null;
        try {
            // Get group insedetion
            
            $abono = AlpAbonos::find($id);

            $confirm_route = route('admin.abonos.delete', ['id' => $abono->id]);

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
                        ->withProperties(['id'=>$id])->log('AlpAbonosController/destroy ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpAbonosController/destroy');


        }


        


        try {
            // Get group insedetion
           
            $abono = AlpAbonos::find($id);


            // Delete the group
            $abono->delete();

            // Redirect to the group management page
            return Redirect::route('admin.abonos.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.abonos.index')->with('error', trans('Error al eliminar el registro'));
        }
    }

    

}