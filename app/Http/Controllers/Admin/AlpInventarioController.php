<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\JoshController;
use App\Http\Requests\InventarioRequest;
use App\Models\AlpProductos;
use App\Models\AlpInventario;
use App\Models\AlpAlmacenes;
use App\Models\AlpAlmacenProducto;
use App\Http\Requests;
use Illuminate\Http\Request;
use Redirect;
use Sentinel;
use View;
use DB;


class AlpInventarioController extends JoshController
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
                        ->log('AlpInventarioController/index ');

        }else{

          activity()
          ->log('AlpInventarioController/index');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['inventario.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }
      

        $productos = AlpProductos::all();

        $inventario=$this->inventario();

        // Show the page
        return view('admin.inventario.index', compact('productos', 'inventario'));
    }


     public function data()
    {
       
        $productos = AlpProductos::select('alp_productos.*', 'alp_almacenes.nombre_almacen as nombre_almacen', 'alp_almacenes.id as id_almacen')
        ->join('alp_almacen_producto', 'alp_productos.id', '=', 'alp_almacen_producto.id_producto')
        ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
        ->groupBy('alp_almacen_producto.id_producto')
        ->groupBy('alp_almacen_producto.id_almacen')
        ->get();

        $inventario=$this->inventario();

            $data = array();

          foreach($productos as $row){

                $actions = "  <a class='btn btn-xs btn-info' href='".secure_url('admin/inventario/'.$row->id.'/edit')."'> Gestionar  </a>";

            if ($row->estado_registro == 1) {
              $estado="<span  class='btn btn-xs btn-primary' style='font-size: 12px !important;' >Activo</span>";
            }

            if ($row->estado_registro == 2) {
              $estado="<span   class='btn btn-xs btn-danger' style='font-size: 12px !important;'>Inactivo</span>";
            }

            if ($row->estado_registro == 0) {
              $estado="<span   class='btn btn-xs btn-danger' style='font-size: 12px !important;'>Inactivo</span>";
            }

            if (isset($inventario[$row->id][$row->id_almacen])) {

             // dd($row->id.'/'.$row->id_almacen);

              $dis=$inventario[$row->id][$row->id_almacen];
            }else{
              $dis=0;
            }

               $data[]= array(
                 $row->id, 
                 $row->nombre_producto, 
                 $row->presentacion_producto, 
                 $row->referencia_producto, 
                 $row->referencia_producto_sap, 
                 $estado,
                 $row->nombre_almacen,  
                 $dis, 
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
        // Show the page

          if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpInventarioController/create ');

        }else{

          activity()
          ->log('AlpInventarioController/create');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['inventario.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        return view ('admin.inventario.create');
    }

    /**
     * Group create form processing.
     *
     * @return Redirect
     */
    public function store(FormapagoRequest $request)
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpInventarioController/store ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpInventarioController/store');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['inventario.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }
        
         $user_id = Sentinel::getUser()->id;

        $data = array(
            'nombre_forma_pago' => $request->nombre_forma_pago, 
            'descripcion_forma_pago' => $request->descripcion_forma_pago, 
            'id_user' =>$user_id
        );
         
        $forma=AlpProductos::create($data);

        if ($forma->id) {

            return redirect('admin/formaspago')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/formaspago')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
                        ->withProperties(['id'=>$id])->log('AlpInventarioController/edit ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpInventarioController/edit');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['inventario.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


       $producto = AlpProductos::find($id);

       $almacenes=AlpAlmacenProducto::select('alp_almacen_producto.*', 'alp_almacenes.nombre_almacen as nombre_almacen')
       ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
       ->where('alp_almacen_producto.id_producto', '=', $id)
       ->get();

       $almacenes=AlpAlmacenes::where('estado_registro','=', 1)->get();

        $inventario=$this->inventario();

        $movimientos=AlpInventario::select('alp_inventarios.*', 'alp_productos.nombre_producto as nombre_producto', 'alp_productos.referencia_producto as referencia_producto', 'alp_productos.referencia_producto_sap as referencia_producto_sap', 'users.first_name as first_name', 'users.last_name as last_name', 'alp_almacenes.nombre_almacen as nombre_almacen')
        ->join('alp_productos', 'alp_inventarios.id_producto','=', 'alp_productos.id')
        ->join('alp_almacenes', 'alp_inventarios.id_almacen','=', 'alp_almacenes.id')
        ->join('users', 'alp_inventarios.id_user','=', 'users.id')
        ->where('alp_inventarios.id_producto', $producto->id)
        ->orderBy('alp_inventarios.id', 'desc')
        ->withTrashed()
        ->get();

        $m = array();

        foreach ($movimientos as $movimiento) {


            $orden=AlpInventario::select('alp_inventarios.*', 'alp_ordenes_detalle.id_orden as id_orden')
            ->join('alp_ordenes_detalle', function ($join) {
            $join->on('alp_ordenes_detalle.id_user', '=', 'alp_inventarios.id_user');
            })
            ->where('alp_ordenes_detalle.id_producto', '=', $movimiento->id_producto)
            ->where('alp_ordenes_detalle.id_user', '=', $movimiento->id_user)
            ->first();

            if (isset($orden->id)) {

                $movimiento->orden=$orden;
                # code...
            }

            $m[]=$movimiento;

        }

        return view('admin.inventario.edit', compact('producto', 'inventario', 'm', 'almacenes'));
    }

    /**
     * Group update form processing page.
     *
     * @param  int $id
     * @return Redirect
     */
    public function update(InventarioRequest $request, $id)
    {


         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpInventarioController/update ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpInventarioController/update');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['inventario.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


         $user_id = Sentinel::getUser()->id;


       $data = array(
            'id_producto' => $id, 
            'id_almacen' => $request->id_almacen, 
            'cantidad' => $request->cantidad, 
            'operacion' => $request->operacion, 
            'notas' => $request->notas, 
            'id_user' => $user_id
        );
         
       $inventario = AlpInventario::create($data);
    
      

        if ($inventario->id) {

            return redirect('admin/inventario')->withInput()->with('success', trans('Se ha creado satisfactoriamente el Registro'));

        } else {
            return Redirect::route('admin/inventario')->withInput()->with('error', trans('Ha ocrrrido un error al crear el registro'));
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
        $model = 'formaspago';
        $confirm_route = $error = null;
        try {
            // Get group information
            
            $forma = AlpProductos::find($id);

            $confirm_route = route('admin.inventario.delete', ['id' => $forma->id]);

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
                        ->withProperties(['id'=>$id])->log('AlpInventarioController/destroy ');

        }else{

          activity()
          ->withProperties(['id'=>$id])->log('AlpInventarioController/destroy');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['inventario.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


        try {
            // Get group information
           
            $forma = AlpProductos::find($id);


            // Delete the group
            $forma->delete();

            // Redirect to the group management page
            return Redirect::route('admin.inventario.index')->with('success', trans('Se ha eliminado el registro satisfactoriamente'));
        } catch (GroupNotFoundException $e) {
            // Redirect to the group management page
            return Redirect::route('admin.inventario.index')->with('error', trans('Error al eliminar el registro'));
        }
    }

    private function inventario()
    {
       

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
              ->performedOn($user)
              ->causedBy($user)
              ->log('AlpInventarioController/inventario ');

        }else{

          activity()
          ->log('AlpInventarioController/inventario');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['inventario.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


            $entradas = AlpInventario::groupBy('id_producto')->groupBy('id_almacen')
              ->select("alp_inventarios.*", DB::raw(  "SUM(alp_inventarios.cantidad) as cantidad_total"))
              ->where('alp_inventarios.operacion', '1')
              ->get();

              $inv = array();
              $inv2 = array();

             foreach ($entradas as $row) {
                
                $inv[$row->id_producto]=$row->cantidad_total;

                $inv2[$row->id_producto][$row->id_almacen]=$row->cantidad_total;

              }


            $salidas = AlpInventario::select("alp_inventarios.*", DB::raw(  "SUM(alp_inventarios.cantidad) as cantidad_total"))
              ->groupBy('id_producto')
              ->groupBy('id_almacen')
              ->where('operacion', '2')
              ->get();

              foreach ($salidas as $row) {
                
             
                //$inv2[$row->id_producto][$row->id_almacen]= $row->cantidad_total;
                //
                if (  isset( $inv2[$row->id_producto][$row->id_almacen])) {
                    $inv2[$row->id_producto][$row->id_almacen]= $inv2[$row->id_producto][$row->id_almacen]-$row->cantidad_total;
                  }else{

                    $inv2[$row->id_producto][$row->id_almacen]= 0;
                  }

            }

            return $inv2;
      
    }



    

    

}
