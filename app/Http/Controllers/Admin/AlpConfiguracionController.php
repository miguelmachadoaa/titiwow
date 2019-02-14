<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Models\AlpConfiguracion;
use App\Models\AlpDespachoCiudad;
use App\Country;
use App\State;
use App\City;
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
      

        $configuracion = AlpConfiguracion::where('id', '1')->first();

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
        return view('admin.configuracion.edit', compact('configuracion', 'ciudades', 'states', 'cities'));
        
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(Request $request, $id)
    {
       $data = array(
            'nombre_tienda' => $request->nombre_tienda,
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
            'registro_publico' => $request->registro_publico, 
            'minimo_compra' => $request->minimo_compra, 
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
        $states['0'] = 'Seleccione Departamento';
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
        $states['0'] = 'Seleccione Ciudad';
        return json_encode($cities);
    }


    public function storecity(Request $request)
    {
        
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
        
          \Session::put('ciudad', $request->city_id);
      
        $cities = AlpDespachoCiudad::select('alp_despacho_ciudad.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name')
          ->join('config_cities', 'alp_despacho_ciudad.id_ciudad', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->where('id_ciudad',$request->city_id)->first();


        if (isset($cities->id)) {

          $data = array(
            'status' => 'true', 
            'city_name' => $cities->city_name, 
            'state_name' => $cities->state_name, 
            'id_ciudad' => $cities->id_ciudad
          );

          return json_encode($data);

        }else{

          $cities = City::select('config_cities.*', 'config_states.state_name as state_name')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->where('config_cities.id',$request->city_id)->first();

          $data = array(
            'status' => 'false', 
            'city_name' =>  $cities->city_name, 
            'state_name' => $cities->state_name,
            'id_ciudad' => $request->city_id
          );

          return json_encode($data);
        }

    }
    
}
