<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Models\AlpConfiguracion;
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


class AlpConfiguracionController extends JoshController
{

    public function index()
    {
        // Grab all the groups
      

        $configuracion = AlpConfiguracion::where('id', '1')->first();
       


        // Show the page
        return view('admin.configuracion.edit', compact('configuracion'));
        
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
        $states['0'] = 'Seleccione Región';
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


    

    
}
