<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Models\AlpEnvios;
use App\Models\AlpEnviosEstatus;
use App\Models\AlpEnviosHistory;
use App\Models\AlpDetalles;
use App\Http\Requests;
use Illuminate\Http\Request;
use Redirect;
use Response;
use Sentinel;
use View;
use Intervention\Image\Facades\Image;
use DOMDocument;


class AlpEnviosController extends JoshController
{
    /**
     * Show a list of all the groups.
     *
     * @return View
     */
    public function index()
    {
        // Grab all the groups
      

        $estatus_envios = AlpEnviosEstatus::all();


        $envios = AlpEnvios::select('alp_envios.*', 'users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios',   'config_cities.city_name as city_name', 'config_states.state_name as state_name', 'alp_envios_status.estatus_envio_nombre as estatus_envio_nombre')
          ->join('alp_envios_status', 'alp_envios.estatus', '=', 'alp_envios_status.id')
          ->join('alp_ordenes', 'alp_envios.id_orden', '=', 'alp_ordenes.id')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_direcciones', 'alp_ordenes.id_address', '=', 'alp_direcciones.id')
          ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->get();


        
        // Show the page
        return view('admin.envios.index', compact('envios', 'estatus_envios'));

    }

     public function empresas()
    {
        // Grab all the groups
      
        $ordenes = AlpOrdenes::all();

        $estatus_ordenes = AlpEstatusOrdenes::all();

         $ordenes = AlpOrdenes::select('alp_ordenes.*', 'users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre', 'alp_empresas.nombre_empresa as nombre_empresa')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
          ->Join('alp_empresas', 'alp_clientes.id_empresa', '=', 'alp_empresas.id')
          ->get();
       
        // Show the page
        return view('admin.envios.empresas', compact('ordenes', 'estatus_ordenes'));

    }


    /**
     * Group create.
     *
     * @return View
     */
    public function create()
    {
        // Show the page
        return view ('admin.envios.create');
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(Request $request)
    {
        
       
    }


    /**
     * Group update.
     *
     * @param  int $id
     * @return View
     */
    public function edit($id)
    {
     
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(Request $request, $id)
    {
      

       
    }

    /**
     * Delete confirmation for the given group.
     *
     * @param  int $id
     * @return View
     */
    public function getModalDelete($id = null)
    {
        $model = 'ordenes';
        $confirm_route = $error = null;
        try {
            // Get group information
            
            $categoria = AlpOrdenes::find($id);

            $confirm_route = route('admin.envios.delete', ['id' => $categoria->id]);

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
           
            $categoria = AlpOrdenes::find($id);


            // Delete the group
            $categoria->delete();

            // Redirect to the group management page
            return Redirect::route('admin.envios.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.envios.index')->with('error', trans('Error al eliminar el registro'));
        }
    }

    /**
     * Group update.
     *
     * @param  int $id
     * @return View
     */
    public function detalle($id)
    {
       
       $orden = AlpOrdenes::find($id);

      

    $detalles = AlpDetalles::select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.imagen_producto as imagen_producto')
          ->join('alp_productos', 'alp_ordenes_detalle.id_producto', '=', 'alp_productos.id')
          ->where('alp_ordenes_detalle.id_orden', $id)
          ->get();



        return view('admin.envios.detalle', compact('detalles', 'orden'));

    }

    public function storeconfirm(Request $request)
    {

        $user_id = Sentinel::getUser()->id;

        $input = $request->all();

        //var_dump($input);

        $data_history = array(
            'id_orden' => $input['confirm_id'], 
            'id_status' => $input['id_status'], 
            'notas' => $input['notas'], 
            'id_user' => $user_id 
        );

        $data_update_orden = array(
            'cod_oracle_pedido' =>$input['cod_oracle_pedido'], 
            'estatus' =>$input['id_status']
        );

         
        $history=AlpOrdenesHistory::create($data_history);

        $orden=AlpOrdenes::find($input['confirm_id']);


        $orden->update($data_update_orden);


        $orden = AlpOrdenes::select('alp_ordenes.*', 'users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
          ->where('alp_ordenes.id', $input['confirm_id'])
          ->first();


        if ($orden->id) {

          //return redirect('order/detail');
          
        

          $view= View::make('admin.envios.storeconfirm', compact('orden'));

          $data=$view->render();

          $res = array('data' => $data);

        //  return json_encode($res);
          return $data;
            

        } else {

            return 0;
        }       

    }



}
