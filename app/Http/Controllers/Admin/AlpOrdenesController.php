<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Http\Requests\OrdenesRequest;
use App\Models\AlpOrdenes;
use App\Models\AlpEstatusOrdenes;
use App\Models\AlpOrdenesHistory;
use App\Models\AlpDetalles;
use App\Models\AlpPagos;
use App\Models\AlpPuntos;
use App\Models\AlpConfiguracion;
use App\Models\AlpEnvios;
use App\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use Mail;
use Redirect;
use Response;
use Sentinel;
use View;
use DB;
use Intervention\Image\Facades\Image;
use DOMDocument;
use Carbon\Carbon;



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

        $estatus_ordenes = AlpEstatusOrdenes::all();

         $ordenes = AlpOrdenes::select('alp_ordenes.*', 'users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre', 'alp_ordenes_pagos.json as json')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->leftJoin('alp_ordenes_pagos', 'alp_ordenes.id', '=', 'alp_ordenes_pagos.id_orden')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
          ->groupBy('alp_ordenes.id')
          ->get();

        //  dd($ordenes);
       
        // Show the page
        return view('admin.ordenes.index', compact('ordenes', 'estatus_ordenes'));

    }

     public function descuento()
    {
        // Grab all the groups
      
        $ordenes = AlpOrdenes::all();

        $estatus_ordenes = AlpEstatusOrdenes::all();

         $ordenes = AlpOrdenes::select('alp_ordenes.*', 'users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre', 'alp_ordenes_pagos.json as json')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->leftJoin('alp_ordenes_pagos', 'alp_ordenes.id', '=', 'alp_ordenes_pagos.id_orden')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
           ->groupBy('alp_ordenes.id')
          ->get();

        //  dd($ordenes);
       
        // Show the page
        return view('admin.ordenes.descuento', compact('ordenes', 'estatus_ordenes'));

    }

    public function consolidado()
    {
        // Grab all the groups

      

        $dt = Carbon::now();


        $date_inicio = Carbon::create($dt->year, $dt->month, $dt->day, 5, 59, 0); 
        $date_inicio->subDay();


        $date_fin = Carbon::create($dt->year, $dt->month, $dt->day, 6, 0, 0); 

         
/*echo 'dt: '.$dt;
echo '<br>inicio: '.$date_inicio;
echo '<br>fin: '.$date_fin;*/



      
        $ordenes = AlpOrdenes::all();



        $estatus_ordenes = AlpEstatusOrdenes::all();

         $ordenes = AlpOrdenes::select('alp_ordenes.*', 'users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre', 'alp_ordenes_pagos.json as json')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
           ->leftJoin('alp_ordenes_pagos', 'alp_ordenes.id', '=', 'alp_ordenes_pagos.id_orden')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
          ->where('alp_ordenes.created_at', '>=', $date_inicio)
          ->where('alp_ordenes.created_at', '<=', $date_fin)

          ->get();
       
        // Show the page
        return view('admin.ordenes.consolidado', compact('ordenes', 'estatus_ordenes'));

    }

    public function espera()
    {
        // Grab all the groups
      
        $ordenes = AlpOrdenes::all();

        $estatus_ordenes = AlpEstatusOrdenes::all();

         $ordenes = AlpOrdenes::select('alp_ordenes.*', 'users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre', 'alp_ordenes_pagos.json as json')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
           ->leftJoin('alp_ordenes_pagos', 'alp_ordenes.id', '=', 'alp_ordenes_pagos.id_orden')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
          ->where('alp_ordenes.estatus', '8')
          ->groupBy('alp_ordenes.id')
          ->get();
       
        // Show the page
        return view('admin.ordenes.espera', compact('ordenes', 'estatus_ordenes'));

    }

    public function aprobados()
    {
        // Grab all the groups
      
        $ordenes = AlpOrdenes::all();

        $estatus_ordenes = AlpEstatusOrdenes::all();

         $ordenes = AlpOrdenes::select('alp_ordenes.*', 'users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre', 'alp_ordenes_pagos.json as json')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
           ->leftJoin('alp_ordenes_pagos', 'alp_ordenes.id', '=', 'alp_ordenes_pagos.id_orden')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
          ->where('alp_ordenes.estatus', '5')
          ->groupBy('alp_ordenes.id')
          ->get();
       
        // Show the page
        return view('admin.ordenes.aprobados', compact('ordenes', 'estatus_ordenes'));

    }

    public function recibidos()
    {
        // Grab all the groups
      
        $ordenes = AlpOrdenes::all();

        $estatus_ordenes = AlpEstatusOrdenes::all();

         $ordenes = AlpOrdenes::select('alp_ordenes.*', 'users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre', 'alp_ordenes_pagos.json as json')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->leftJoin('alp_ordenes_pagos', 'alp_ordenes.id', '=', 'alp_ordenes_pagos.id_orden')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
          ->where('alp_ordenes.estatus', '1')
          ->groupBy('alp_ordenes.id')
          ->get();
       
        // Show the page
        return view('admin.ordenes.recibidos', compact('ordenes', 'estatus_ordenes'));

    }

    public function facturados()
    {
        // Grab all the groups
      
        $ordenes = AlpOrdenes::all();

        $estatus_ordenes = AlpEstatusOrdenes::all();

         $ordenes = AlpOrdenes::select('alp_ordenes.*', 'users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre', 'alp_ordenes_pagos.json as json')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->leftJoin('alp_ordenes_pagos', 'alp_ordenes.id', '=', 'alp_ordenes_pagos.id_orden')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
          ->where('alp_ordenes.estatus', '6')
          ->groupBy('alp_ordenes.id')
          ->get();
       
        // Show the page
        return view('admin.ordenes.facturados', compact('ordenes', 'estatus_ordenes'));

    }

    public function enviados()
    {
        // Grab all the groups
      
        $ordenes = AlpOrdenes::all();

        $estatus_ordenes = AlpEstatusOrdenes::all();

        $ordenes = AlpOrdenes::select('alp_ordenes.*', 'users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre', 'alp_ordenes_pagos.json as json')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
           ->leftJoin('alp_ordenes_pagos', 'alp_ordenes.id', '=', 'alp_ordenes_pagos.id_orden')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
          ->where('alp_ordenes.estatus', '7')
          ->groupBy('alp_ordenes.id')
          ->get();
       
        // Show the page
        return view('admin.ordenes.enviados', compact('ordenes', 'estatus_ordenes'));

    }

    public function cancelados()
    {
        // Grab all the groups
      
        $ordenes = AlpOrdenes::all();

        $estatus_ordenes = AlpEstatusOrdenes::all();

        $ordenes = AlpOrdenes::select('alp_ordenes.*', 'users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre', 'alp_ordenes_pagos.json as json')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
           ->leftJoin('alp_ordenes_pagos', 'alp_ordenes.id', '=', 'alp_ordenes_pagos.id_orden')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
          ->where('alp_ordenes.estatus', '4')
          ->groupBy('alp_ordenes.id')
          ->get();
       
        // Show the page
        return view('admin.ordenes.cancelados', compact('ordenes', 'estatus_ordenes'));

    }



     public function empresas()
    {
        // Grab all the groups
      
        $ordenes = AlpOrdenes::all();

        $estatus_ordenes = AlpEstatusOrdenes::all();

         $ordenes = AlpOrdenes::select('alp_ordenes.*', 'users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre', 'alp_empresas.nombre_empresa as nombre_empresa', 'alp_ordenes_pagos.json as json')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
           ->leftJoin('alp_ordenes_pagos', 'alp_ordenes.id', '=', 'alp_ordenes_pagos.id_orden')
          
          ->Join('alp_empresas', 'alp_clientes.id_empresa', '=', 'alp_empresas.id')
          ->groupBy('alp_ordenes.id')
          ->get();
       
        // Show the page
        return view('admin.ordenes.empresas', compact('ordenes', 'estatus_ordenes'));

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

    $pago = AlpPagos::select('alp_ordenes_pagos.*','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago')
          ->join('alp_formas_pagos', 'alp_ordenes_pagos.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->where('alp_ordenes_pagos.id_orden', $id)
          ->first();

        $pagos = AlpPagos::select('alp_ordenes_pagos.*','alp_formas_pagos.nombre_forma_pago as nombre_forma_pago')
          ->join('alp_formas_pagos', 'alp_ordenes_pagos.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->where('alp_ordenes_pagos.id_orden', $id)
          ->get();

          //dd($pago);


    $history = AlpOrdenesHistory::select('alp_ordenes_history.*', 'users.first_name as first_name', 'users.last_name as last_name', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre' )
          ->join('alp_ordenes_estatus', 'alp_ordenes_history.id_status', '=', 'alp_ordenes_estatus.id')
          ->join('users', 'alp_ordenes_history.id_user', '=', 'users.id')
          ->where('alp_ordenes_history.id_orden', $id)
          ->get();



        return view('admin.ordenes.detalle', compact('detalles', 'orden', 'history', 'pago', 'pagos'));

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
            'estatus' =>$input['id_status']
        );

        if ($input['id_status']=='4') {

          $punto=AlpPuntos::where('id_orden',$input['confirm_id'])->first();

          if (isset($punto->id)) {

             $data_estatus_puntos = array('estado_registro' => $input['id_status'] );

            $punto->update($data_estatus_puntos);

            $punto->delete();


          
          }

         

        }

         
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

          $view= View::make('admin.ordenes.storeconfirm', compact('orden'));

          $data=$view->render();

          $res = array('data' => $data);

          return $data;

        } else {

            return 0;
        }     


    }


       public function recibir(Request $request)
    {

        $user_id = Sentinel::getUser()->id;

        $input = $request->all();

        $configuracion = AlpConfiguracion::where('id','1')->first();

    //    dd($configuracion->correo_cedi);

        //var_dump($input);

        $data_history = array(
            'id_orden' => $input['id'], 
            'id_status' => '1', 
            'notas' => $input['notas'], 
            'id_user' => $user_id 
        );

        $data_update_orden = array(
            'estatus' =>'1'
        );

         
        $history=AlpOrdenesHistory::create($data_history);

        $orden=AlpOrdenes::find($input['id']);

        $orden->update($data_update_orden);

        $orden = AlpOrdenes::select(
          'alp_ordenes.*', 'users.first_name as first_name', 
          'users.last_name as last_name', 
          'users.email as email', 
          'alp_clientes.doc_cliente as doc_cliente', 
          'alp_clientes.cod_oracle_cliente as cod_oracle_cliente', 
          'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 
          'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 
          'alp_ordenes_estatus.estatus_nombre as estatus_nombre',
          'alp_envios.fecha_envio as fecha_envio',
          'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre'
        )
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_clientes', 'alp_ordenes.id_cliente', '=', 'alp_clientes.id_user_client')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_envios', 'alp_ordenes.id', '=', 'alp_envios.id_orden')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
          ->where('alp_ordenes.id', $input['id'])
          ->first();

          ///dd($orden);

        $detalles =  DB::table('alp_ordenes_detalle')->select(
          'alp_ordenes_detalle.*',
          'alp_productos.referencia_producto as referencia_producto',
          'alp_productos.nombre_producto as nombre_producto',
          'alp_productos.referencia_producto_sap as referencia_producto_sap' ,
          'alp_productos.imagen_producto as imagen_producto' ,
          'alp_productos.slug as slug'
        )
          ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
          ->where('alp_ordenes_detalle.id_orden', $orden->id)->get();

        $envio=AlpEnvios::where('id_orden', $orden->id)->first();



        if ($orden->id) {


          //$user_cliente=Users::where('id', $orden->id_cliente)->first();

          $texto="La orden ".$orden->id.", el pago ha sido notificado y espera por el proceso de aprobación!";

           //return $texto;

          Mail::to($configuracion->correo_cedi)->send(new \App\Mail\NotificacionOrden($orden->id, $texto));

          
          //Mail::to($orden->email)->send(new \App\Mail\CompraAprobada($orden, $detalles, $envio->fecha_envio));


          //Mail::to($configuracion->correo_cedi)->send(new \App\Mail\CompraSac($orden, $detalles, $envio->fecha_envio));


         // Mail::to($user_cliente->email)->send(new \App\Mail\NotificacionOrden($orden->id, $texto));

          $view= View::make('admin.ordenes.recibir', compact('orden'));

          $data=$view->render();

          $res = array('data' => $data);

          return $data;

        } else {

            return 'error orden';
        }       

    }


     public function aprobar(Request $request)
    {

        $user_id = Sentinel::getUser()->id;

        $input = $request->all();

        $configuracion = AlpConfiguracion::where('id','1')->first();

    //    dd($configuracion->correo_cedi);

        //var_dump($input);

        $data_history = array(
            'id_orden' => $input['id'], 
            'id_status' => '5', 
            'notas' => $input['notas'], 
            'id_user' => $user_id 
        );

        $data_update_orden = array(
            'ordencompra' =>$input['codigo'], 
            'estatus' =>'5'
        );

         
        $history=AlpOrdenesHistory::create($data_history);

        $orden=AlpOrdenes::find($input['id']);

        $orden->update($data_update_orden);

        $orden = AlpOrdenes::select(
          'alp_ordenes.*', 'users.first_name as first_name', 
          'users.last_name as last_name', 
          'users.email as email', 
          'alp_clientes.doc_cliente as doc_cliente', 
          'alp_clientes.cod_oracle_cliente as cod_oracle_cliente', 
          'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 
          'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 
          'alp_ordenes_estatus.estatus_nombre as estatus_nombre',
          'alp_envios.fecha_envio as fecha_envio',
          'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre'
        )
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_clientes', 'alp_ordenes.id_cliente', '=', 'alp_clientes.id_user_client')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_envios', 'alp_ordenes.id', '=', 'alp_envios.id_orden')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
          ->where('alp_ordenes.id', $input['id'])
          ->first();

          ///dd($orden);

        $detalles =  DB::table('alp_ordenes_detalle')->select(
          'alp_ordenes_detalle.*',
          'alp_productos.referencia_producto as referencia_producto',
          'alp_productos.nombre_producto as nombre_producto',
          'alp_productos.referencia_producto_sap as referencia_producto_sap' ,
          'alp_productos.imagen_producto as imagen_producto' ,
          'alp_productos.slug as slug'
        )
          ->join('alp_productos','alp_ordenes_detalle.id_producto' , '=', 'alp_productos.id')
          ->where('alp_ordenes_detalle.id_orden', $input['id'])->get();

        $envio=AlpEnvios::where('id_orden', $input['id'])->first();

        if ($orden->id) {

          //$user_cliente=Users::where('id', $orden->id_cliente)->first();

          $texto="La orden ".$orden->id." Ha sido aprobada y espera para ser facturada!";

           //return $texto;

          //Mail::to($configuracion->correo_cedi)->send(new \App\Mail\NotificacionOrden($orden->id, $texto));

          
          Mail::to($orden->email)->send(new \App\Mail\CompraAprobada($orden, $detalles, $envio->fecha_envio));


          Mail::to($configuracion->correo_cedi)->send(new \App\Mail\CompraSac($orden, $detalles, $envio->fecha_envio));


         // Mail::to($user_cliente->email)->send(new \App\Mail\NotificacionOrden($orden->id, $texto));

          $view= View::make('admin.ordenes.aprobar', compact('orden'));

          $data=$view->render();

          $res = array('data' => $data);

          return $data;

        } else {

            return 'error orden';
        }       

    }

    public function facturar(Request $request)
    {

        $user_id = Sentinel::getUser()->id;

        $input = $request->all();

         $configuracion = AlpConfiguracion::where('id','1')->first();

        //var_dump($input);

        $data_history = array(
            'id_orden' => $input['id'], 
            'id_status' => '6', 
            'notas' => $input['notas'], 
            'id_user' => $user_id 
        );

        $data_update_orden = array(
            'factura' =>$input['codigo'], 
            'estatus' =>'6'
        );

         
        $history=AlpOrdenesHistory::create($data_history);

        $orden=AlpOrdenes::find($input['id']);

        $orden->update($data_update_orden);

        $orden = AlpOrdenes::select('alp_ordenes.*', 'users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
          ->where('alp_ordenes.id', $input['id'])
          ->first();

        if ($orden->id) {

          $texto="La orden ".$orden->id." Ha sido Facturada  y espera para ser Enviada!";

         //  $user_cliente=Users::where('id', $orden->id_cliente)->first();
          
          //Mail::to($user_cliente->email)->send(new \App\Mail\NotificacionOrden($orden->id, $texto));

          Mail::to($configuracion->correo_logistica)->send(new \App\Mail\NotificacionOrden($orden->id, $texto));


          $view= View::make('admin.ordenes.facturar', compact('orden'));

          $data=$view->render();

          $res = array('data' => $data);

          return $data;

        } else {

            return 0;
        }       

    }


    public function enviar(Request $request)
    {

        $user_id = Sentinel::getUser()->id;

         $configuracion = AlpConfiguracion::where('id','1')->first();

        $input = $request->all();

        //var_dump($input);

        $data_history = array(
            'id_orden' => $input['id'], 
            'id_status' => '7', 
            'notas' => $input['notas'], 
            'id_user' => $user_id 
        );

        $data_update_orden = array(
            'tracking' =>$input['codigo'], 
            'estatus' =>'7'
        );

         
        $history=AlpOrdenesHistory::create($data_history);

        $orden=AlpOrdenes::find($input['id']);

        $orden->update($data_update_orden);

        $orden = AlpOrdenes::select('alp_ordenes.*', 'users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
          ->where('alp_ordenes.id', $input['id'])
          ->first();

        if ($orden->id) {

          $texto="La orden ".$orden->id." Ha sido Enviada  !";

          $user_cliente=User::where('id', $orden->id_cliente)->first();
          
          //Mail::to($user_cliente->email)->send(new \App\Mail\NotificacionOrden($orden->id, $texto));

          Mail::to($configuracion->correo_shopmanager)->send(new \App\Mail\NotificacionOrden($orden->id, $texto));

          $view= View::make('admin.ordenes.enviar', compact('orden'));

          $data=$view->render();

          $res = array('data' => $data);

          return $data;

        } else {

            return 0;
        }       

    }




}
