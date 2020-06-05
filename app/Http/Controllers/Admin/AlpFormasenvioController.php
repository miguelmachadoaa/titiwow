<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Models\AlpFormasenvio;
use App\Models\AlpFormaCiudad;
use App\State;
use App\City;
use App\Barrio;
use App\Http\Requests\FormaenvioRequest;
use App\Http\Requests;
use Illuminate\Http\Request;
use Redirect;
use Sentinel;
use View;
use DB;


class AlpFormasenvioController extends JoshController
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
                        ->log('AlpFormasenvioController/index ');

        }else{

          activity()
          ->log('AlpFormasenvioController/index');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['formasenvio.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }
      

        $formas = AlpFormasenvio::all();
       
        // Show the page
        return view('admin.formasenvio.index', compact('formas'));
    }


      public function data()
    {
       
      $formas = AlpFormasenvio::all();
         
        $data = array();

        foreach($formas as $row){

           
        $actions = "<a  href='". secure_url('admin/formasenvio').'/'.$row->id.'/ubicacion' ."'><i class='fa fa-map-marker'></i></a>

        <a href='". secure_url('admin/formasenvio/'.$row->id.'/edit' ) ."'>
            <i class='livicon' data-name='edit' data-size='18' data-loop='true' data-c='#428BCA' data-hc='#428BCA' title='editar categoria'></i>
        </a>
        
        <a href='". secure_url('admin/formasenvio/'.$row->id.'/confirm-delete') ."' data-toggle='modal' data-target='#delete_confirm'>
        <i class='livicon' data-name='remove-alt' data-size='18'
            data-loop='true' data-c='#f56954' data-hc='#f56954'
            title='Eliminar'></i>
         </a>
";
$tipo='';

if ($row->tipo=='1') {
    # code...

    $tipo='Fecha variable';
}else{
$tipo='Fecha fija';

}

               $data[]= array(
                 $row->id, 
                 $row->sku, 
                 $row->email, 
                 $row->nombre_forma_envios, 
                 $row->descripcion_forma_envios, 
                 $tipo, 
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

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpFormasenvioController/create ');

        }else{

          activity()
          ->log('AlpFormasenvioController/create');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['formasenvio.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }
      

        // Show the page
        return view ('admin.formasenvio.create');
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(FormaenvioRequest $request)
    {
        

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpFormasenvioController/store ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpFormasenvioController/store');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['formasenvio.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


         $user_id = Sentinel::getUser()->id;

        //$input = $request->all();

        //var_dump($input);

        $data = array(
            'sku' => $request->sku, 
            'email' => $request->email, 
            'nombre_forma_envios' => $request->nombre_forma_envios, 
            'descripcion_forma_envios' => $request->descripcion_forma_envios, 
            'tipo' => $request->tipo, 
            'id_user' =>$user_id
        );
         
        $forma=AlpFormasenvio::create($data);

        if ($forma->id) {

            return redirect('admin/formasenvio')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/formasenvio')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
                        ->withProperties(['id'=>$id])->log('AlpFormasenvioController/edit ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpFormasenvioController/edit');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['formasenvio.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }
       
       $forma = AlpFormasenvio::find($id);

        return view('admin.formasenvio.edit', compact('forma'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(FormaenvioRequest $request, $id)
    {


        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpFormasenvioController/update ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpFormasenvioController/update');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['formasenvio.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


       $data = array(
            'sku' => $request->sku, 
            'email' => $request->email, 
            'nombre_forma_envios' => $request->nombre_forma_envios, 
            'tipo' => $request->tipo, 
            'descripcion_forma_envios' => $request->descripcion_forma_envios
        );
         
       $forma = AlpFormasenvio::find($id);
    
        $forma->update($data);

        if ($forma->id) {

            return redirect('admin/formasenvio')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/formasenvio')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
        $model = 'formasenvio';
        $confirm_route = $error = null;
        try {
            // Get group information
            
            $forma = AlpFormasenvio::find($id);

            $confirm_route = route('admin.formasenvio.delete', ['id' => $forma->id]);

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
                        ->withProperties(['id'=>$id])->log('AlpFormasenvioController/destroy ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpFormasenvioController/destroy');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['formasenvio.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


        try {
            // Get group information
           
            $forma = AlpFormasenvio::find($id);
            // Delete the group
            $forma->delete();

            // Redirect to the group management page
            return Redirect::route('admin.formasenvio.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.formasenvio.index')->with('error', trans('Error al eliminar el registro'));
        }
    }

     public function ubicacion($id)
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties(['id'=>$id])->log('AlpFormasenvioController/ubicacion ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpFormasenvioController/ubicacion');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['formasenvio.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


        // Grab all the groups

        $formas = AlpFormasenvio::where('id', $id)->first();

        $roles = DB::table('roles')->whereIn('id', ['9','10', '11', '12'])->select('id', 'name')->get();

        $states=State::where('config_states.country_id', '47')->get();

        $ciudades=AlpFormaCiudad::select('alp_forma_ciudad.*', 'roles.name as name')
         ->join('roles','alp_forma_ciudad.id_rol' , '=', 'roles.id')
        ->where('alp_forma_ciudad.id_forma', $id)->get();

       // dd($ciudades);

        

       $barrios=Barrio::where('estado_registro', '1')->get();


       foreach ($ciudades as $c) {

                if ($c->id_ciudad=='0') {

                        $c->id_state='0';
                    # code...
                }else{

                    $ci=City::where('id', $c->id_ciudad)->first();

                    $c->id_state=$ci->state_id;
                }
           # code...
       }

        $states=State::where('config_states.country_id', '47')->get();

        $listaestados=State::where('config_states.country_id', '47')->pluck('state_name', 'id');

        $listaestados[0]='Todos';

        $listaciudades=City::pluck('city_name', 'id');

        $listaciudades[0]='Todos';

        $listabarrios=Barrio::pluck('barrio_name', 'id');

        $listabarrios[0]='Todos';

        // Show the page
        return view('admin.formasenvio.ubicacion', compact('formas', 'ciudades', 'states', 'listaciudades', 'listaestados', 'listabarrios', 'roles'));


    }



    public function storecity(Request $request)
    {
        
         $user_id = Sentinel::getUser()->id;


          if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpFormasenvioController/storecity ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpFormasenvioController/storecity');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['formasenvio.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        $input=$request->all();

        $data = array(
            'id_forma' => $request->id_forma, 
            'id_rol' => $request->id_rol, 
            'id_ciudad' => $request->city_id, 
            'id_barrio' => $request->barrio_id, 
            'desde' => $request->desde, 
            'hasta' => $request->hasta, 
            'dias' => $request->dias, 
            'hora' => $request->hora, 
            'costo' => $request->costo, 
            'id_user' =>$user_id
        );

        try {

            $formas=AlpFormaCiudad::create($data);

            
        } catch (Exception $e) {
            
        }

        $formas=AlpFormasenvio::where('id', $request->id_forma)->first();
         

         $ciudades=AlpFormaCiudad::select('alp_forma_ciudad.*', 'roles.name as name')
         ->join('roles','alp_forma_ciudad.id_rol' , '=', 'roles.id')
        ->where('alp_forma_ciudad.id_forma', $request->id_forma)->get();

       foreach ($ciudades as $c) {

                if ($c->id_barrio=='0') {

                        $c->id_state='0';
                    # code...
                }else{

                    $ci=City::where('id', $c->id_ciudad)->first();

                    $c->id_state=$ci->state_id;
                }
           # code...
       }

        $states=State::where('config_states.country_id', '47')->get();

        $listaestados=State::where('config_states.country_id', '47')->pluck('state_name', 'id');

        $listaestados[0]='Todos';

        $listaciudades=City::pluck('city_name', 'id');

        $listaciudades[0]='Todos';

        $listabarrios=Barrio::pluck('barrio_name', 'id');

        $listabarrios[0]='Todos';

        $view= View::make('admin.formasenvio.ciudades', compact('ciudades', 'formas', 'states', 'listaciudades', 'listaestados', 'listabarrios'));

          $data=$view->render();

         return $data;

    }

    public function delcity(Request $request)
    {


           if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpFormasenvioController/delcity ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpFormasenvioController/delcity');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['formasenvio.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        $user_id = Sentinel::getUser()->id;

        $formas=AlpFormaCiudad::where('id', $request->id)->first();

        $id_forma=$formas->id_forma;

        $formas->delete();

          $ciudades=AlpFormaCiudad::select('alp_forma_ciudad.*', 'roles.name as name')
         ->join('roles','alp_forma_ciudad.id_rol' , '=', 'roles.id')
        ->where('alp_forma_ciudad.id_forma', $id_forma)->get();

       foreach ($ciudades as $c) {

                if ($c->id_ciudad=='0') {

                        $c->id_state='0';
                    # code...
                }else{

                    $ci=City::where('id', $c->id_ciudad)->first();

                    $c->id_state=$ci->state_id;
                }
           # code...
       }


        $states=State::where('config_states.country_id', '47')->get();

        $listaestados=State::where('config_states.country_id', '47')->pluck('state_name', 'id');

        $listaestados[0]='Todos';

        $listaciudades=City::pluck('city_name', 'id');

        $listaciudades[0]='Todos';

        $listabarrios=Barrio::pluck('barrio_name', 'id');

        $listabarrios[0]='Todos';


        $view= View::make('admin.formasenvio.ciudades', compact('ciudades', 'formas', 'states', 'listaciudades', 'listaestados', 'listabarrios'));

          $data=$view->render();

         return $data;

    }

}