<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Models\AlpConfiguracion;
use App\Models\AlpDespachoCiudad;
use App\Models\AlpAlmacenDespacho;
use App\Models\AlpAlmacenes;
use App\Country;
use App\State;
use App\City;
use App\Roles;
use App\RoleUser;
use App\Http\Requests;
use App\Http\Requests\ProductosRequest;
use Illuminate\Http\Request;
use Response;
use Sentinel;
use Intervention\Image\Facades\Image;
use DOMDocument;
use DB;
use View;


class AlpConfiguracionController extends JoshController
{

    public function index()
    {
        // Grab all the groups


       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        #->withProperties($request->all())
                        ->log('configuracion/index ');

        }else{

          activity()
          #->withProperties($request->all())
          ->log('configuracion/index');


        }

        $configuracion = AlpConfiguracion::where('id', '1')->first();

        $roles=Roles::where('tipo', '2')->get();

        //dd($roles);

        $ciudades=AlpDespachoCiudad::select('alp_despacho_ciudad.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name')
        ->join('config_cities','alp_despacho_ciudad.id_ciudad' , '=', 'config_cities.id')
        ->join('config_states','config_cities.state_id' , '=', 'config_states.id')
        ->where('config_states.country_id', '47')->get();

        $states=State::where('config_states.country_id', '47')->get();

         $cities = AlpDespachoCiudad::select('alp_despacho_ciudad.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name')
          ->join('config_cities', 'alp_despacho_ciudad.id_ciudad', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->get();
       
        // Show the page
        return view('admin.configuracion.edit', compact('configuracion', 'ciudades', 'states', 'cities', 'roles'));
        
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(Request $request, $id)
    {

      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('configuracion/update ');

        }else{

          activity()
          ->withProperties($request->all())
          ->log('configuracion/update');


        }

        $input=$request->all();


       $data = array(
            'nombre_tienda' => $request->nombre_tienda,
            'base_url' => $request->base_url,
            'limite_amigos' => $request->limite_amigos,
            'id_mercadopago' => $request->id_mercadopago,
            'key_mercadopago' => $request->key_mercadopago, 
            'public_key_mercadopago' => $request->public_key_mercadopago, 
            'public_key_mercadopago_test' => $request->public_key_mercadopago_test, 
            'comision_mp' => $request->comision_mp, 
            'retencion_fuente_mp' => $request->retencion_fuente_mp, 
            'retencion_iva_mp' => $request->retencion_iva_mp, 
            'retencion_ica_mp' => $request->retencion_ica_mp, 
            'mercadopago_sand' => $request->mercadopago_sand, 
            'explicacion_precios' => $request->explicacion_precios, 
            'registro_publico' => $request->registro_publico, 
            'user_activacion' => $request->user_activacion, 
            'editar_direccion' => $request->editar_direccion, 
            //'minimo_compra' => $request->minimo_compra, 
            'maximo_productos' => $request->maximo_productos, 
            'mensaje_bienvenida' => $request->mensaje_bienvenida, 
            'mensaje_promocion' => $request->mensaje_promocion, 
            'correo_admin' => $request->correo_admin, 
            'correo_shopmanager' => $request->correo_shopmanager, 
            'correo_shopmanagercorp' => $request->correo_shopmanagercorp, 
            'correo_masterfile' => $request->correo_masterfile, 
            'correo_sac' => $request->correo_sac, 
            'correo_cedi' => $request->correo_cedi, 
            'correo_logistica' => $request->correo_logistica, 
            'correo_finanzas' => $request->correo_finanzas,
            'seo_title' => $request->seo_title,
            'seo_type' => $request->seo_type,
            'seo_url' => $request->seo_url,
            'seo_image' => $request->seo_image,
            'seo_site_name' => $request->seo_site_name,
            'seo_description' => $request->seo_description,
        );


         
       $configuracion = AlpConfiguracion::find($id);
    
        $configuracion->update($data);


        /* foreach ($input as $key => $value) {

          if (substr($key, 0, 3)=='mc_') {

            $par=explode('_', $key);

            $rol=Roles::where('id', $par[1])->first();

            $data_rol = array(
              'monto_minimo' => $value
            );

            $rol->update($data_rol);

          }

        }*/

        if ($configuracion->id) {

            return redirect('admin/configuracion')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/configuracion')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
        }  

    }



    /**
     * Get Ajax Request and restun Data
     *
     * @return \Illuminate\Http\Response
     */
    public function selectState($id)
    {
        $states = DB::table("config_states")
                    ->where("country_id",$id)
                    ->pluck("state_name","id")->all();
        $states['0'] = 'Seleccione';
        return json_encode($states);
    }

    /**
     * Get Ajax Request and restun Data
     *
     * @return \Illuminate\Http\Response
     */
    public function selectCity($id)
    {
        $cities = DB::table("config_cities")
                    ->where("state_id",$id)
                    ->pluck("city_name","id")->all();
        $cities['0'] = 'Seleccione';
        return json_encode($cities);
    }


     public function selectStateTodos($id)
    {
        $states = DB::table("config_states")
                    ->where("country_id",$id)
                    ->pluck("state_name","id")->all();
        $states['0'] = 'Todos';
        return json_encode($states);
    }

    /**
     * Get Ajax Request and restun Data
     *
     * @return \Illuminate\Http\Response
     */
    public function selectCityTodos($id)
    {
        $cities = DB::table("config_cities")
                    ->where("state_id",$id)
                    ->pluck("city_name","id")->all();
        $cities['0'] = 'Todas';
        return json_encode($cities);
    }




    public function storecity(Request $request)
    {


      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('configuracion/storecity ');

        }else{

          activity()
          ->withProperties($request->all())
          ->log('configuracion/storecity');


        }


        
        $user_id = Sentinel::getUser()->id;

        $ciudad=explode('_', $request->city_id);

        $data = array(
            'id_ciudad' => $ciudad[0] ,
            'id_user' => $user_id 
        );

        AlpDespachoCiudad::create($data);

        $cities = AlpDespachoCiudad::select('alp_despacho_ciudad.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name')
          ->join('config_cities', 'alp_despacho_ciudad.id_ciudad', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->get();

        $view= View::make('admin.configuracion.ciudades', compact('cities'));

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
                        ->withProperties($request->all())
                        ->log('configuracion/delcity ');

        }else{

          activity()
          ->withProperties($request->all())
          ->log('configuracion/delcity');


        }

        
        $user_id = Sentinel::getUser()->id;

        

        $city=AlpDespachoCiudad::find($request->id);

        $city->delete();

       

        $cities = AlpDespachoCiudad::select('alp_despacho_ciudad.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name')
          ->join('config_cities', 'alp_despacho_ciudad.id_ciudad', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->get();

        $view= View::make('admin.configuracion.ciudades', compact('cities'));

        $data=$view->render();

        return $data;

    }


    public function verificarciudad(Request $request)
    {


      
      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('configuracion/verificarciudad ');

        }else{

          activity()
          ->withProperties($request->all())
          ->log('configuracion/verificarciudad');


        }
        
          \Session::put('ciudad', $request->city_id);

          $tipo=0;


        if (isset(Sentinel::getUser()->id)) {

            $user_id = Sentinel::getUser()->id;

            $role=RoleUser::select('role_id')->where('user_id', $user_id)->first();

             if ($role->role_id=='14') {
              
              $tipo=1;

            }

        }


          $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')
                ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')
                ->where('alp_almacenes.tipo_almacen', '=', $tipo)
                ->where('alp_almacen_despacho.id_city', $request->city_id)
                ->first();

                if (isset($ad->id)) {
                # code...
                }else{

                  $c=City::where('id', $request->city_id)->first();

                  $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')
                ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')
                ->where('alp_almacenes.tipo_almacen', '=', $tipo)
                ->where('alp_almacen_despacho.id_city', '0')
                ->where('alp_almacen_despacho.id_state', $c->state_id)
                ->first();

                  if (isset($ad->id)) {
                    
                  }else{

                    $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')
                  ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')
                  ->where('alp_almacenes.tipo_almacen', '=', $tipo)
                  ->where('alp_almacen_despacho.id_city', '0')
                  ->where('alp_almacen_despacho.id_state', '0')->first();

                  }

                }


                 if (isset($ad->id)) {

                  $almacen=AlpAlmacenes::where('id', $ad->id_almacen)->first();

                  $id_almacen='true';
                  # code...
                }else{

                   $almacen=AlpAlmacenes::where('defecto', '1')->first();

                    if (isset($almacen->id)) {

                     $id_almacen='false';

                    }else{

                      $id_almacen='false';

                    }

                }



      
       $cities = City::select('config_cities.*', 'config_states.state_name as state_name', 'config_states.id as id_state')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->where('config_cities.id',$request->city_id)->first();


        if (isset($cities->id)) {

          $data = array(
            'status' => $id_almacen, 
            'city_name' => $cities->city_name, 
            'state_name' => $cities->state_name, 
            'id_ciudad' => $cities->id,
            'id_state' => $cities->id_state,
          );

          return json_encode($data);

        }else{

          $cities = City::select('config_cities.*', 'config_states.state_name as state_name', 'config_states.id as id_state')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->where('config_cities.id',$request->city_id)->first();

          $data = array(
            'status' => $id_almacen, 
            'city_name' =>  $cities->city_name, 
            'state_name' => $cities->state_name,
            'id_ciudad' => $request->city_id,
            'id_state' => $cities->id_state
          );

          return json_encode($data);
        }

    }
    
}
