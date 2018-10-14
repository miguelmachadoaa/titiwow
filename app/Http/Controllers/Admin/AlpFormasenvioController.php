<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Models\AlpFormasenvio;
use App\Models\AlpFormaCiudad;
use App\State;
use App\Http\Requests\FormaenvioRequest;
use App\Http\Requests;
use Illuminate\Http\Request;
use Redirect;
use Sentinel;
use View;


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
      

        $formas = AlpFormasenvio::all();
       
        // Show the page
        return view('admin.formasenvio.index', compact('formas'));
    }

    


    /**
     * Group create.
     *
     * @return View
     */
    public function create()
    {
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
        
         $user_id = Sentinel::getUser()->id;

        //$input = $request->all();

        //var_dump($input);

        $data = array(
            'nombre_forma_envios' => $request->nombre_forma_envios, 
            'descripcion_forma_envios' => $request->descripcion_forma_envios, 
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
       $data = array(
            'nombre_forma_envios' => $request->nombre_forma_envios, 
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
        // Grab all the groups

        $formas = AlpFormasenvio::where('id', $id)->first();

        
         $ciudades=AlpFormaCiudad::select('alp_forma_ciudad.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name')
        ->join('config_cities','alp_forma_ciudad.id_ciudad' , '=', 'config_cities.id')
        ->join('config_states','config_cities.state_id' , '=', 'config_states.id')
        ->where('alp_forma_ciudad.id_forma', $id)->get();

        $states=State::where('config_states.country_id', '47')->get();

        // Show the page
        return view('admin.formasenvio.ubicacion', compact('formas', 'ciudades', 'states'));

    }



    public function storecity(Request $request)
    {
        
         $user_id = Sentinel::getUser()->id;

       
         

        $data = array(
            'id_forma' => $request->id_forma, 
            'id_ciudad' => $request->city_id, 
            'dias' => $request->dias, 
            'hora' => $request->hora, 
            'id_user' =>$user_id
        );
         
        $formas=AlpFormaCiudad::create($data);

         $ciudades=AlpFormaCiudad::select('alp_forma_ciudad.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name')
        ->join('config_cities','alp_forma_ciudad.id_ciudad' , '=', 'config_cities.id')
        ->join('config_states','config_cities.state_id' , '=', 'config_states.id')
        ->where('alp_forma_ciudad.id_forma', $request->id_forma)->get();


        $view= View::make('admin.formasenvio.ciudades', compact('ciudades', 'forma'));

          $data=$view->render();


         return $data;

    }

    

}
