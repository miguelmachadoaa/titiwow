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
use App\Barrio;
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


        if (!Sentinel::getUser()->hasAnyAccess(['configuracion.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
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


          $robots=explode(' ,', $configuracion->robots);

       
        // Show the page
        return view('admin.configuracion.edit', compact('configuracion', 'ciudades', 'states', 'cities', 'roles','robots'));
        
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
            'compramas_hash' => $request->compramas_hash, 
            'compramas_token' => $request->compramas_token, 
            'compramas_url' => $request->compramas_url, 
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
            'h1_home' => $request->h1_home,
            'h1_categorias' => $request->h1_categorias,
            'h1_marcas' => $request->h1_marcas,
            'h1_terminos' => $request->h1_terminos
        );


         
       $configuracion = AlpConfiguracion::find($id);
    
        $configuracion->update($data);

        $i=1;

        $robots='';

        foreach ($input as $key => $value) {

            if (substr($key,0,6)=='robots') {

                if ($i==1) {

                   $robots=$value;

                   $i=0;

                }else{

                    $robots=$robots.' ,'.$value;
                }

            }
            # code...
        }

        $datar = array('robots' => $robots);

        $configuracion->update($datar);



        if ($configuracion->id) {

            return redirect('admin/configuracion')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {

            return Redirect::route('admin/configuracion')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
        }  

    }





    public function robots()
    {
        // Grab all the groups


       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        #->withProperties($request->all())
                        ->log('configuracion/robost ');

        }else{

          activity()
          #->withProperties($request->all())
          ->log('configuracion/robost');


        }

        $contenido='';


       try
        {
             $file = fopen("robots.txt", "r");

                while(!feof($file)) {

                $contenido=$contenido.fgets($file). "";

                }

                fclose($file);
        }
        catch (Illuminate\Filesystem\FileNotFoundException $exception)
        {
            die("No existe el archivo");
        }


        //dd($contenido);

       
        // Show the page
        return view('admin.configuracion.robots', compact('contenido'));
        
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function postrobots(Request $request)
    {

      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('configuracion/postrobots ');

        }else{

          activity()
          ->withProperties($request->all())
          ->log('configuracion/postrobots');


        }

        $input=$request->all();

       // dd($input);


         $file = fopen("robots.txt", "w");

          fwrite($file, $input['robots'] . PHP_EOL);

          //fwrite($file, "Otra más" . PHP_EOL);

          fclose($file);


            return redirect('admin/configuracion/robots')->withInput()->with('success', trans('Se ha creado actualizado el Registro'));


    }

  public function htaccess()
    {
        // Grab all the groups


       if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        #->withProperties($request->all())
                        ->log('configuracion/htaccess ');

        }else{

          activity()
          #->withProperties($request->all())
          ->log('configuracion/htaccess');


        }

        $contenido='';


       try
        {
             $file = fopen(".htaccess", "r");

                while(!feof($file)) {

                $contenido=$contenido.fgets($file). "";

                }

                fclose($file);
        }
        catch (Illuminate\Filesystem\FileNotFoundException $exception)
        {
            die("No existe el archivo");
        }


        //dd($contenido);

       
        // Show the page
        return view('admin.configuracion.htaccess', compact('contenido'));
        
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function posthtaccess(Request $request)
    {

      if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())
                        ->log('configuracion/posthtaccess ');

        }else{

          activity()
          ->withProperties($request->all())
          ->log('configuracion/posthtaccess');


        }

        $input=$request->all();

       // dd($input);


         $file = fopen(".htaccess", "w");

          fwrite($file, $input['robots'] . PHP_EOL);

          //fwrite($file, "Otra más" . PHP_EOL);

          fclose($file);


            return redirect('admin/configuracion/htaccess')->withInput()->with('success', trans('Se ha creado actualizado el Registro'));


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

    public function selectStateModal($id)
    {
        
        $ad=AlpAlmacenDespacho::where('id_state', 0)->first();

        if (isset($ad->id)) {
          
          $states = DB::table("config_states")
                    ->where("country_id",$id)
                    ->pluck("state_name","id")->all();

        }else{

          $states = DB::table("config_states")
                    ->join('alp_almacen_despacho', 'config_states.id', '=', 'alp_almacen_despacho.id_state')
                    ->where("config_states.country_id",$id)
                    ->pluck("config_states.state_name","config_states.id")->all();

        }

        $states['0'] = 'Seleccione';

        return json_encode($states);

    }

     public function selectCityModal($id)
    {


      $ad=AlpAlmacenDespacho::where('id_state', 0)->where('id_city', 0)->first();


      if (isset($ad->id)) {

        $cities = DB::table("config_cities")
                    ->where("state_id",$id)
                    ->pluck("city_name","id")->all();

        
      }else{

        $ad=AlpAlmacenDespacho::where('id_state', $id)->where('id_city', 0)->first();

        if (isset($ad->id)) {

          $cities = DB::table("config_cities")
            ->where("state_id",$id)
            ->pluck("city_name","id")->all();

        }else{

          $cities = DB::table("config_cities")
            ->join('alp_almacen_despacho', 'config_cities.id', '=', 'alp_almacen_despacho.id_city')
            ->where("config_cities.state_id",$id)
            ->pluck("config_cities.city_name","config_cities.id")->all();


        }


      }


        
        $cities['0'] = 'Seleccione';
        return json_encode($cities);
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
        $cities[''] = 'Seleccione Ciudad';
        $cities['0'] = 'Todas';
        return json_encode($cities);
    }



    public function selectBarrioTodos($id)
    {
        $barrios = DB::table("config_barrios")
                    ->where("city_id",$id)
                    ->pluck("barrio_name","id")->all();
        $barrios['0'] = 'Todas';
        return json_encode($barrios);
    }


      public function selectBarrio($id)
    {
        $barrios = DB::table("config_barrios")
                    ->where("city_id",$id)
                    ->pluck("barrio_name","id")->all();
        $barrios['0'] = 'Seleccione Barrio';
        return json_encode($barrios);
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
                ->where('alp_almacenes.estado_registro', '=', '1')
                ->first();

                if (isset($ad->id)) {
                # code...
                }else{

                  $c=City::where('id', $request->city_id)->first();

                  if (isset($c->id)) {

                        $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')
                      ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')
                      ->where('alp_almacenes.tipo_almacen', '=', $tipo)
                      ->where('alp_almacen_despacho.id_city', '0')
                      ->where('alp_almacen_despacho.id_state', $c->state_id)
                      ->where('alp_almacenes.estado_registro', '=', '1')
                      ->first();
                    # code...
                  }

              

                  if (isset($ad->id)) {
                    
                  }else{

                    $ad=AlpAlmacenDespacho::select('alp_almacen_despacho.*')
                  ->join('alp_almacenes', 'alp_almacen_despacho.id_almacen', '=', 'alp_almacenes.id')
                  ->where('alp_almacenes.tipo_almacen', '=', $tipo)
                  ->where('alp_almacen_despacho.id_city', '0')
                  ->where('alp_almacen_despacho.id_state', '0')->where('alp_almacenes.estado_registro', '=', '1')->first();

                  }

                }


                 if (isset($ad->id)) {

                  $almacen=AlpAlmacenes::where('id', $ad->id_almacen)->where('alp_almacenes.estado_registro', '=', '1')->first();

                  $id_almacen='true';

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


                        $data = array(
                          'status' => $id_almacen
                        );

                        return json_encode($data);
                      }



                  
                  # code...
                }else{

                   $almacen=AlpAlmacenes::where('defecto', '1')->where('alp_almacenes.estado_registro', '=', '1')->first();

                    if (isset($almacen->id)) {

                     $id_almacen='false';

                     $data = array(
                      'status' => $id_almacen
                    );

                    return json_encode($data);



                    }else{

                      $id_almacen='false';


                      $data = array(
                        'status' => $id_almacen
                      );

                      return json_encode($data);

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


          $data = array(
            'status' => $id_almacen
          );

          return json_encode($data);
        }

    }

    
    
}
