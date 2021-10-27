<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;

use App\Models\AlpLifeMiles;

use App\Models\AlpLifeMilesCodigos;

use App\Models\AlpAlmacenes;

use App\Models\AlpConfiguracion;

use App\Models\AlpAbonosDisponible;

use App\Models\AlpAbonosUser;


use App\Http\Requests\LifeMileRequest;

use App\Http\Requests;
use Illuminate\Http\Request;
use Redirect;
use Sentinel;
use View;

use App\Imports\LifemileImport;

use Maatwebsite\Excel\Facades\Excel;

class AlpLifemilesController extends JoshController
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
                        ->log('AlpLifemilesController/index ');

        }else{

          activity()
          ->log('AlpLifemilesController/index');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['abonos.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intento acceder');
        }



        $lifemiles = AlpLifeMiles::select('alp_lifemiles.*', 'alp_almacenes.nombre_almacen as nombre_almacen')
        ->leftJoin('alp_almacenes','alp_lifemiles.id_almacen', '=', 'alp_almacenes.id')
        ->get();


        // Show the page
        return view('admin.lifemiles.index', compact('lifemiles'));
    }

    /**
     * Group create.
     *
     * @return View
     */


     
    public function show($id)
    {
        // Grab all the groups


         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpLifemilesController/show ');

        }else{

          activity()
          ->log('AlpLifemilesController/show');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['abonos.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intento acceder');
        }

        $lifemile = AlpLifemiles::select('alp_lifemiles.*', 'alp_almacenes.nombre_almacen as nombre_almacen')
        ->LeftJoin('alp_almacenes','alp_lifemiles.id_almacen', '=', 'alp_almacenes.id')
        ->where('alp_lifemiles.id', $id)->first();

        $codigos = AlpLifeMilesCodigos::select('alp_lifemiles_codigos.*', 'alp_lifemiles_orden.id_orden')
        ->LeftJoin('alp_lifemiles_orden', 'alp_lifemiles_codigos.id', '=', 'alp_lifemiles_orden.id_codigo')
        ->where('alp_lifemiles_codigos.id_lifemile', $id)->get();  


        // Show the page
        return view('admin.lifemiles.show', compact('lifemile', 'codigos'));

    }


    
    public function create()
    {
        // Show the page


         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpLifemilesController/index ');

        }else{

          activity()
          ->log('AlpLifemilesController/index');


        }


          $almacenes = AlpAlmacenes::all();

          $configuracion=AlpConfiguracion::where('id', 1)->first();

        return view ('admin.lifemiles.create', compact( 'configuracion', 'almacenes'));
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(LifeMileRequest $request)
    {
        

          if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpLifemilesController/store ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpLifemilesController/store');


        }


       


         $user_id = Sentinel::getUser()->id;

        $input = $request->all();


        //var_dump($input);


        $data = array(
            'nombre_lifemile' => $request->nombre_lifemile, 
            'cantidad_millas' => $request->cantidad_millas, 
            'minimo_compra' => $request->minimo_compra,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_final' => $request->fecha_final,
            'id_almacen' => $request->id_almacen,
            'cantidad_cupones' => $request->cantidad_cupones,
            'id_user' =>$user_id
        );


         
        $lm=AlpLifemiles::create($data);


        if ($lm->id) {

            return redirect('admin/lifemiles')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return redirect('admin/lifemiles')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
                        ->withProperties(['id'=>$id])->log('AlpLifemilesController/edit ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpLifemilesController/edit');

        }

       $lifemile = AlpLifeMiles::find($id);



          $almacenes = AlpAlmacenes::all();

        return view('admin.lifemiles.edit', compact( 'almacenes', 'lifemile'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(LifeMileRequest $request, $id)
    {


          if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpLifemilesController/update ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpLifemilesController/update');


        }

      
        $user_id = Sentinel::getUser()->id;

        $data = array(
          'nombre_lifemile' => $request->nombre_lifemile, 
          'cantidad_millas' => $request->cantidad_millas, 
          'minimo_compra' => $request->minimo_compra,
          'fecha_inicio' => $request->fecha_inicio,
          'fecha_final' => $request->fecha_final,
          'id_almacen' => $request->id_almacen,
          'cantidad_cupones' => $request->cantidad_cupones,
          'id_user' =>$user_id
      );

      # dd($data);

         
        $lifemile = AlpLifeMiles::find($id);
    
        $lifemile->update($data);



        if ($lifemile->id) {

            return redirect('admin/lifemiles')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return redirect('admin/lifemiles')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
        $model = 'lifemiles';
        $confirm_route = $error = null;
        try {
            // Get group insedetion
            
            $lifemile = AlpLifeMiles::find($id);

            $confirm_route = route('admin.lifemiles.delete', ['id' => $lifemile->id]);

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
                        ->withProperties(['id'=>$id])->log('AlpLifemilesController/destroy ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpLifemilesController/destroy');


        }


        


        try {
            // Get group insedetion
           
            $lifemile = AlpLifeMiles::find($id);


            // Delete the group
            $lifemile->delete();

            // Redirect to the group management page
            return redirect('admin/lifemiles')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return redirect('admin/lifemiles')->with('error', trans('Error al eliminar el registro'));
        }
    }



    
    public function upload($id)
    {

       if (!Sentinel::getUser()->hasAnyAccess(['almacenes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intento acceder');
        }


        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('almacen/edit ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('almacen/edit');

        }
       

       $lifemile = AlpLifeMiles::where('id', $id)->first();


        return view('admin.lifemiles.upload', compact('lifemile'));
    }


     public function postupload(Request $request, $id)
    {
        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
              ->performedOn($user)
              ->causedBy($user)
              ->withProperties(['id'=>$id])->log('lifemile/postgestionar ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('lifemile/postgestionar');

        }

        $input=$request->all();

        \Session::put('lifemile', $id);

        \Session::put('user_id', $user->id);

         $archivo = $request->file('file_update');

     #   Excel::import(new AlmacenImport, $archivo);

        Excel::import(new lifemileImport, $archivo);
       
        

        return redirect('admin/lifemiles')->with('success', trans('Se han creado los  registro satisfactoriamente'));
    }



    public function activar($id)
    {

       if (!Sentinel::getUser()->hasAnyAccess(['lifemiles.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intento acceder');
        }


        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('lifemiles/activar ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('lifemiles/activar');

        }
       

       $lifemile = AlpLifeMiles::where('id', $id)->first();

       if(isset($lifemile->id)){

          $lifemile->update(['estado_registro'=>0]);

       }

       $view= View::make('admin.lifemiles.btnactivo', compact('lifemile'));
       $data=$view->render();
       return $data;

    }

    public function desactivar($id)
    {

       if (!Sentinel::getUser()->hasAnyAccess(['lifemiles.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intento acceder');
        }


        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('lifemiles/activar ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('lifemiles/activar');

        }
       

       $lifemile = AlpLifeMiles::where('id', $id)->first();

       if(isset($lifemile->id)){

          $lifemile->update(['estado_registro'=>1]);

       }

       $view= View::make('admin.lifemiles.btnactivo', compact('lifemile'));
       $data=$view->render();
       return $data;

    }

    

}