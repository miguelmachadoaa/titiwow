<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Http\Requests\OrdenesRequest;
use App\Models\AlpOrdenes;
use App\Models\AlpDetalles;
use App\Http\Requests;
use Illuminate\Http\Request;
use Redirect;
use Response;
use Sentinel;
use View;
use Intervention\Image\Facades\Image;
use DOMDocument;


class AlpOrdenesController extends JoshController
{
    /**
     * Show a list of all the groups.
     *
     * @return View
     */
    public function index()
    {
        // Grab all the groups
      
        $ordenes = AlpOrdenes::all();

         $ordenes = AlpOrdenes::select('alp_ordenes.*', 'users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->get();
       
        // Show the page
        return view('admin.ordenes.index', compact('ordenes'));

    }

    /**
     * Group create.
     *
     * @return View
     */
    public function create()
    {
        // Show the page
        return view ('admin.ordenes.create');
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

            $confirm_route = route('admin.ordenes.delete', ['id' => $categoria->id]);

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
            return Redirect::route('admin.ordenes.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.ordenes.index')->with('error', trans('Error al eliminar el registro'));
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



        return view('admin.ordenes.detalle', compact('detalles', 'orden'));

    }


}
