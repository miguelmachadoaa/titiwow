<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Http\Requests\AbonoRequest;
use App\Http\Requests\AbonoUpdateRequest;
use App\Models\AlpAbonos;
use App\Models\AlpOrdenes;
use App\Models\AlpClientes;
use App\Models\AlpAbonosTipo;

use App\Models\AlpAlmacenes;

use App\Models\AlpConfiguracion;

use App\Models\AlpAbonosDisponible;

use App\Models\AlpAbonosUser;


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

        if (!Sentinel::getUser()->hasAnyAccess(['abonos.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intento acceder');
        }



        $abonos = AlpAbonos::select('alp_abonos.*', 'alp_almacenes.nombre_almacen as nombre_almacen')
        ->join('alp_almacenes','alp_abonos.id_almacen', '=', 'alp_almacenes.id')
        ->get();

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


        $ordenes=AlpOrdenes::select('alp_ordenes.id as id', 'alp_ordenes.referencia as referencia','users.first_name as first_name', 'users.last_name as last_name')
        ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
        ->orderby('alp_ordenes.id', 'desc')
        ->get();

        $clientes = AlpClientes::select('alp_clientes.*', 'users.first_name as first_name', 'users.last_name as last_name', 'users.email as email')
          ->join('users', 'alp_clientes.id_user_client', '=', 'users.id')
          ->where('alp_clientes.estado_registro', '1')
          ->get();


          $almacenes = AlpAlmacenes::all();

          $tipobono=AlpAbonosTipo::get();

          $configuracion=AlpConfiguracion::where('id', 1)->first();

        return view ('admin.abonos.create', compact('ordenes', 'clientes', 'tipobono', 'configuracion', 'almacenes'));
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

        $input = $request->all();


        //var_dump($input);



        $data = array(
            'codigo_abono' => $request->codigo_abono, 
            'valor_abono' => $request->valor_abono, 
            'fecha_final' => $request->fecha_final,
            'origen' => $request->origen,
            'motivo' => $request->motivo,
            'id_orden' => $request->id_orden,
            'id_almacen' => $request->id_almacen,
            'tipo_abono' => $request->tipo_abono,
            'token' => md5(time()),
            'notas' => $request->notas,
            'id_user' =>$user_id
        );


         
        $abono=AlpAbonos::create($data);

        if (is_null($request->id_cliente)) {
            # code...
        }else{


            $data_abono = array(
            'id_abono'=>$abono->id,
            'id_cliente'=>$request->id_cliente,
            'operacion'=>1,
            'codigo_abono'=>$abono->codigo_abono,
            'valor_abono'=>$abono->valor_abono,
            'fecha_final'=>$abono->fecha_final,
            'id_almacen' => $request->id_almacen,
            'origen'=>$abono->origen,
            'token'=>$abono->token,
            'json'=>json_encode($abono),
            'id_user'=>$user_id
          );

          AlpAbonosDisponible::create($data_abono);

          $data_user = array(
            'id_abono' => $abono->id, 
            'id_cliente'=>$request->id_cliente,
            'id_user'=>$user_id
          );

          AlpAbonosUser::create($data_user);

          $abono->update(['estado_registro'=>0]);


        }

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

        $ordenes=AlpOrdenes::select('alp_ordenes.id as id', 'alp_ordenes.referencia as referencia','users.first_name as first_name', 'users.last_name as last_name', 'users.email as email')
        ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
        ->orderby('alp_ordenes.id', 'desc')
        ->get();

        $clientes = AlpClientes::select('alp_clientes.*', 'users.first_name as first_name', 'users.last_name as last_name', 'users.email as email')
          ->join('users', 'alp_clientes.id_user_client', '=', 'users.id')
          ->where('alp_clientes.estado_registro', '1')
          ->get();

          $tipobono=AlpAbonosTipo::get();

          $almacenes = AlpAlmacenes::all();


        return view('admin.abonos.edit', compact('abono', 'ordenes', 'clientes', 'tipobono', 'almacenes'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(AbonoUpdateRequest $request, $id)
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

        $b=AlpAbonos::where('codigo_abono', '=', $request->codigo_abono)->first();

        if (isset($b->id)) {


            if ($b->id==$id) {
                # code...
            }else{

                return Redirect::route('admin/abonos')->withInput()->with('error', trans('El codigo que intenta usar ya esta siendo usado'));

            }

            
        }


       $data = array(
            'codigo_abono' => $request->codigo_abono, 
            'valor_abono' => $request->valor_abono, 
            'fecha_final' => $request->fecha_final,
            'origen' => $request->origen,
            'motivo' => $request->motivo,
            'id_orden' => $request->id_orden,
            'id_almacen' => $request->id_almacen,
            'tipo_abono' => $request->tipo_abono,
            'token' => md5(time()),
            'notas' => $request->notas,
            'id_user' =>$user->id
        );

      # dd($data);

         
        $abono = AlpAbonos::find($id);
    
        $abono->update($data);

        if (is_null($request->id_cliente)) {
            # code...
        }else{

            if ($abono->estado_registro=='1') {
                
                 $data_abono = array(
                'id_abono'=>$abono->id,
                'id_cliente'=>$request->id_cliente,
                'operacion'=>1,
                'codigo_abono'=>$abono->codigo_abono,
                'valor_abono'=>$abono->valor_abono,
                'fecha_final'=>$abono->fecha_final,
                'id_almacen' => $request->id_almacen,
                'origen'=>$abono->origen,
                'token'=>$abono->token,
                'json'=>json_encode($abono),
                'id_user'=>$user->id
              );

              AlpAbonosDisponible::create($data_abono);

              $data_user = array(
                'id_abono' => $abono->id, 
                'id_cliente'=>$request->id_cliente,
                'id_user'=>$user->id
              );

              AlpAbonosUser::create($data_user);

              $abono->update(['estado_registro'=>0]);

            }

        }


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