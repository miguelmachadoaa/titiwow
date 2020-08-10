<?php 

namespace App\Http\Controllers\Admin;

use App\Exports\NominaExport;
use App\Exports\NominaExportAlmacen;
use App\Exports\ListadoProductosExport;
use App\Exports\UsersActivarExport;
use App\Exports\ConsolidadoExport;
use App\Exports\LogisticaExport;
use App\Exports\CronLogisticaExport;
use App\Exports\MasterfileExport;
use App\Exports\MasterfileAmigosExport;
use App\Exports\MasterfileEmbajadoresExport;
use App\Exports\FinancieroExport;
use App\Exports\UsersExport;
use App\Exports\VentasExport;
use App\Exports\VentastotalesExport;
use App\Exports\DetalleVentaExport;
use App\Exports\DetalleClientesExport;
use App\Exports\ProductosExport;
use App\Exports\ProductosExportB;
use App\Exports\ProductosExportC;
use App\Exports\ProductostotalesExport;
use App\Exports\CarritoExport;
use App\Exports\DescuentoVentasExport;
use App\Exports\TomaPedidosExport;
use App\Exports\CuponesDescuentoExport;
use App\Exports\CuponesUsadosExport;
use App\Exports\InventarioExport;
use App\Exports\ClientesExport;
use App\Exports\FormatoSolicitudPedidoAlpinista;


use App\Http\Requests\FinancieroRequest;


use App\User;
use App\State;
use App\Models\AlpOrdenes;
use App\Models\AlpProductos;
use App\Models\AlpAlmacenes;
use App\Imports\UsersImport;
use App\Http\Requests;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DB;
use Sentinel;

use Carbon\Carbon;

class AlpReportesController extends Controller 
{
    public function indexreg() 
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpReportesController/indexreg ');

        }else{

          activity()
          ->log('AlpReportesController/indexreg');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        $almacenes=AlpAlmacenes::get();


        return view('admin.reportes.registrados', compact('almacenes'));
    }

    public function ventas() 
    {

          if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpReportesController/ventas ');

        }else{

          activity()
          ->log('AlpReportesController/ventas');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

       // $clientes=User::all();

       $clientes = AlpOrdenes::select('alp_ordenes.*', 'users.first_name as first_name', 'users.last_name as last_name')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->groupBy('alp_ordenes.id_cliente')
          ->get();

        return view('admin.reportes.ventas', compact('clientes'));

    }

    public function productos() 
    {

          if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpReportesController/productos ');

        }else{

          activity()
          ->log('AlpReportesController/productos');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


        $productos=AlpProductos::all();

        return view('admin.reportes.productos', compact('productos'));

    }

     public function exportproductos(Request $request) 
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpReportesController/exportproductos ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpReportesController/exportproductos');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }
        

       // dd($request->all());

        return Excel::download(new ProductosExport($request->desde, $request->hasta), 'ventas_desde_'.$request->desde.'_hasta_'.$request->hasta.'_producto_'.$request->producto.'.xlsx');
    }




    public function productostotales() 
    {

          if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpReportesController/productostotales');

        }else{

          activity()
          ->log('AlpReportesController/productostotales');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


        $productos=AlpProductos::all();

        $almacenes=AlpAlmacenes::where('estado_registro', '=', '1')->get();

        return view('admin.reportes.productostotales', compact('productos', 'almacenes'));

    }

     public function exportproductostotales(FinancieroRequest $request) 
    {


         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpReportesController/exportproductostotales ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpReportesController/exportproductostotales');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        if ($request->almacen==0) {
          $a='todos';
        }else{
          $alm=AlpAlmacenes::where('id', $request->almacen)->first();

          $a=$alm->nombre_almacen;
        }


        return Excel::download(new ProductostotalesExport($request->desde, $request->hasta, $request->almacen), 'productos_con_impuesto_'.$a.'.xlsx');
    }

    public function carrito() 
    {

        
          if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpReportesController/carrito ');

        }else{

          activity()
          ->log('AlpReportesController/carrito');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        return view('admin.reportes.carrito');

    }

     public function exportcarrito(Request $request) 
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpReportesController/exportcarrito ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpReportesController/exportcarrito');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }
        



       // dd($request->all());

        return Excel::download(new CarritoExport($request->desde, $request->hasta), 'carrito_desde_'.$request->desde.'_hasta_'.$request->hasta.'.xlsx');
    }


    public function exportventas(Request $request) 
    {


            if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpReportesController/exportventas ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpReportesController/exportventas');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


       // dd($request->all());

        return Excel::download(new VentasExport($request->desde, $request->hasta, $request->clientes), 'ventas_desde_'.$request->desde.'_hasta_'.$request->hasta.'_usuario_'.$request->clientes.'.xlsx');
    }

    public function export(Request $request) 
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpReportesController/export ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpReportesController/export');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }



        //dd($request->all());

        return Excel::download(new UsersExport($request->desde, $request->hasta, $request->id_almacen), 'users_.xlsx');
    }
    
    public function import() 
    {
        return Excel::import(new UsersImport, 'users.xlsx');
    }



    public function financiero() 
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpReportesController/financiero ');

        }else{

          activity()
          ->log('AlpReportesController/financiero');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        $almacenes=AlpAlmacenes::where('estado_registro', '=', '1')->get();
        

        return view('admin.reportes.financiero', compact('almacenes'));

    }


    public function exportfinanciero(FinancieroRequest $request) 
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpReportesController/exportfinanciero ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpReportesController/exportfinanciero');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        $input=$request->all();

       // dd($input);

        if ($request->almacen==0) {
          $a='todos';
        }else{
          $alm=AlpAlmacenes::where('id', $request->almacen)->first();

          $a=$alm->nombre_almacen;
        }


        return Excel::download(new FinancieroExport($request->desde, $request->hasta, $request->almacen), 'financiero_desde_'.$request->desde.'_hasta_'.$request->hasta.'_'.$a.'.xlsx');
    }




     public function masterfile() 
    {


         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpReportesController/masterfile ');

        }else{

          activity()
          ->log('AlpReportesController/masterfile');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }



        return view('admin.reportes.masterfile');

    }


    public function exportmasterfile(Request $request) 
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpReportesController/exportmasterfile ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpReportesController/exportmasterfile');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }



        return Excel::download(new MasterfileExport($request->desde, $request->hasta), 'masterfile_desde_'.$request->desde.'_hasta_'.$request->hasta.'.xlsx');
    }

     public function masterfileamigos() 
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpReportesController/masterfileamigos ');

        }else{

          activity()
          ->log('AlpReportesController/masterfileamigos');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }



        return view('admin.reportes.masterfileamigos');

    }


    public function exportmasterfileamigos(Request $request) 
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpReportesController/exportmasterfileamigos ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpReportesController/exportmasterfileamigos');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }



        return Excel::download(new MasterfileAmigosExport($request->desde, $request->hasta), 'masterfile_desde_'.$request->desde.'_hasta_'.$request->hasta.'.xlsx');
    }

     public function masterfileembajadores() 
    {

          if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpReportesController/masterfileembajadores ');

        }else{

          activity()
          ->log('AlpReportesController/masterfileembajadores');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


        return view('admin.reportes.masterfileembajador');

    }


    public function exportmasterfileembajadores(Request $request) 
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpReportesController/exportmasterfileembajadores ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpReportesController/exportmasterfileembajadores');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


        return Excel::download(new MasterfileEmbajadoresExport($request->desde, $request->hasta), 'masterfile_desde_'.$request->desde.'_hasta_'.$request->hasta.'.xlsx');
    }


     public function logistica() 
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpReportesController/logistica ');

        }else{

          activity()
          ->log('AlpReportesController/logistica');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


        $almacenes=AlpAlmacenes::get();




        return view('admin.reportes.logistica', compact('almacenes'));

    }


    public function exportlogistica(Request $request) 
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpReportesController/exportlogistica ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpReportesController/exportlogistica');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }



        Excel::store(new LogisticaExport($request->desde, $request->hasta, $request->id_almacen), 'logistica_desde_'.$request->desde.'_hasta_'.$request->hasta.'.xlsx', 'public');


        return Excel::download(new LogisticaExport($request->desde, $request->hasta, $request->id_almacen), 'logistica_desde_'.$request->desde.'_hasta_'.$request->hasta.'.xlsx');
    }

    public function storeexportlogistica(Request $request) 
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpReportesController/storeexportlogistica ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpReportesController/storeexportlogistica');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


         Excel::store(new LogisticaExport($request->desde, $request->hasta,'1'), 'logistica_desde_'.$request->desde.'_hasta_'.$request->hasta.'.xlsx');

         return true;
    }


    public function consolidado() 
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpReportesController/consolidado ');

        }else{

          activity()
          ->log('AlpReportesController/consolidado');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }



        return view('admin.reportes.consolidado');

    }


    public function exportconsolidado(Request $request) 
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpReportesController/exportconsolidado ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpReportesController/exportconsolidado');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }


         $date = Carbon::now();

        $hoy=$date->format('Y-m-d');


        return Excel::download(new ConsolidadoExport($request->desde), 'consolidado_desde_'.$request->desde.'_hasta_'.$request->hasta.'.xlsx');
    }

     public function ventastotales() 
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpReportesController/ventastotales ');

        }else{

          activity()
          ->log('AlpReportesController/ventastotales');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        return view('admin.reportes.ventastotales');

    }

    public function exportventastotales(Request $request) 
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpReportesController/exportventastotales ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpReportesController/exportventastotales');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        return Excel::download(new VentastotalesExport($request->desde, $request->hasta), 'ventastotales_desde_'.$request->desde.'_hasta_'.$request->hasta.'.xlsx');
    }

     public function cuponesdescuento() 
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpReportesController/cuponesdescuento ');

        }else{

          activity()
          ->log('AlpReportesController/cuponesdescuento');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        return view('admin.reportes.cuponesdescuento');

    }

    public function exportcuponesdescuento(Request $request) 
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpReportesController/exportcuponesdescuento ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpReportesController/exportcuponesdescuento');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        return Excel::download(new CuponesDescuentoExport(), 'cuponesdescuento.xlsx');
    }

    public function ventasdescuento() 
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpReportesController/ventasdescuento ');

        }else{

          activity()
          ->log('AlpReportesController/ventasdescuento');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        $almacenes=AlpAlmacenes::get();

        return view('admin.reportes.ventasdescuento', compact('almacenes'));

    }

    public function exportventasdescuento(Request $request) 
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpReportesController/exportventasdescuento ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpReportesController/exportventasdescuento');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        return Excel::download(new DescuentoVentasExport($request->desde, $request->hasta, $request->id_almacen), 'ventasdescuento_desde_'.$request->desde.'_hasta_'.$request->hasta.'.xlsx');
    }

    /*********************funciones para descarga de reporte************************/

    public function exportcronlogisticaexport(Request $request) 
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpReportesController/exportcronlogisticaexport ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpReportesController/exportcronlogisticaexport');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

      $date = Carbon::now();

      $hoy=$date->format('Y-m-d');

      $archivo='logistica_'.$hoy.'.xlsx';

      return Excel::download(new CronLogisticaExport(), $archivo);

    }

    public function cronnuevosusuariosexport(Request $request) 
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpReportesController/cronnuevosusuariosexport ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpReportesController/cronnuevosusuariosexport');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        $date = Carbon::now();

        $hoy=$date->format('Y-m-d');

        $archivo='nuevos_usuarios_'.$hoy.'.xlsx';

       return Excel::download(new UsersActivarExport(), $archivo);

    }

     public function productosb() 
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpReportesController/productosb ');

        }else{

          activity()
          ->log('AlpReportesController/productosb');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        $productos=AlpProductos::all();

        return view('admin.reportes.productosb', compact('productos'));

    }

     public function exportproductosb(Request $request) 
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpReportesController/exportproductosb ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpReportesController/exportproductosb');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        $date = Carbon::now();

        $hoy=$date->format('Y-m-d');

        return Excel::download(new ProductosExportB($hoy, $hoy), 'ventas_desde_'.$hoy.'_hasta_'.$hoy.'_producto.xlsx');
    }

     public function productosc() 
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpReportesController/productosc ');

        }else{

          activity()
          ->log('AlpReportesController/productosc');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        $productos=AlpProductos::all();

        return view('admin.reportes.productosc', compact('productos'));

    }

     public function exportproductosc(Request $request) 
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpReportesController/exportproductos ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpReportesController/exportproductos');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        $date = Carbon::now();

        $hoy=$date->format('Y-m-d');

        return Excel::download(new ProductosExportC($hoy, $hoy), 'ventas_desde_'.$hoy.'_hasta_'.$hoy.'_producto.xlsx');
    }

    public function cronexportproductosb(Request $request) 
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpReportesController/cronexportproductosb ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpReportesController/cronexportproductosb');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        $date = Carbon::now();

        $hoy=$date->format('Y-m-d');

        $archivo='ventas_productos'.$hoy.'.xlsx';

         return Excel::download(new ProductosExportB($hoy, $hoy), $archivo);

    }

    public function cronexportproductosbc(Request $request) 
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpReportesController/cronexportproductosb ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpReportesController/cronexportproductosb');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        $date = Carbon::now();

        $hoy=$date->format('Y-m-d');

        $archivo='ventas_productos'.$hoy.'.xlsx';

         //return Excel::download(new ProductosExportB($hoy, $hoy), $archivo);
         $archivo= Excel::store(new ProductosExportB($hoy, $hoy), $archivo, 'excel');

        // dd($archivo);

    }

      public function cronexportproductosc(Request $request) 
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpReportesController/cronexportproductosc ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpReportesController/cronexportproductosc');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        $date = Carbon::now();

        $hoy=$date->format('Y-m-d');

        $archivo='ventas_productos'.$hoy.'.xlsx';

         return Excel::download(new ProductosExportC($hoy, $hoy), $archivo);

    }
    public function cronexporttomapedidos(Request $request) 
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpReportesController/cronexporttomapedidos ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpReportesController/cronexporttomapedidos');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        $date = Carbon::now();

        $hoy=$date->format('Y-m-d');

        $archivo='tomapedidos_'.$hoy.'.xlsx';

         return Excel::download(new TomaPedidosExport(), $archivo);

    }

    public function cronexportcuponesdescuento(Request $request) 
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpReportesController/cronexportcuponesdescuento ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpReportesController/cronexportcuponesdescuento');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        $date = Carbon::now();

        $hoy=$date->format('Y-m-d');

        $archivo='cuponesdescuento_'.$hoy.'.xlsx';

         return Excel::download(new CuponesDescuentoExport(), $archivo);

    }

    public function cronexportcuponesusados(Request $request) 
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpReportesController/cronexportcuponesusados ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpReportesController/cronexportcuponesusados');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        $date = Carbon::now();

        $hoy=$date->format('Y-m-d');

        $archivo='cuponesusados_'.$hoy.'.xlsx';

         return Excel::download(new CuponesUsadosExport(), $archivo);

    }

    public function exportinventario(Request $request) 
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpReportesController/exportinventario ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpReportesController/exportinventario');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        $date = Carbon::now();

        $hoy=$date->format('Y-m-d');

        $archivo='invenatrio_'.$hoy.'.xlsx';

         return Excel::download(new InventarioExport(), $archivo);

    }

     public function listadoproductos() 
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpReportesController/listadoproductos ');

        }else{

          activity()
          ->log('AlpReportesController/listadoproductos');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        return view('admin.reportes.listadoproductos');

    }


     public function exportlistadoproductos(Request $request) 
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpReportesController/exportlistadoproductos ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpReportesController/exportlistadoproductos');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        return Excel::download(new ListadoProductosExport($request->estado), 'Listado_de_productos.xlsx');
    }




     public function nomina() 
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpReportesController/nomina ');

        }else{

          activity()
          ->log('AlpReportesController/nomina');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        $almacenes=AlpAlmacenes::get();

        return view('admin.reportes.nomina', compact('almacenes'));

    }


     public function exportnomina(Request $request) 
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpReportesController/exportnomina ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpReportesController/exportnomina');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

       //dd($request->all());

         $date = Carbon::now();

        $hoy=$date->format('Y-m-d');

        return Excel::download(new NominaExport($request->desde, $request->hasta, $request->id_almacen), 'Listado_de_ventas_almacen.xlsx');
    }




 public function primeracompra() 
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpReportesController/nomina ');

        }else{

          activity()
          ->log('AlpReportesController/nomina');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        $almacenes=AlpAlmacenes::get();

        return view('admin.reportes.primeracompra', compact('almacenes'));

    }


     public function exportprimeracompra(Request $request) 
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpReportesController/exportnomina ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpReportesController/exportnomina');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

       // dd($request->all());

         $date = Carbon::now();

        $hoy=$date->format('Y-m-d');

        return Excel::download(new NominaExportAlmacen($hoy, $request->id_almacen), 'Listado_de_descuento_primeracompra.xlsx');
    }




     public function detalleventa() 
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpReportesController/ventastotales ');

        }else{

          activity()
          ->log('AlpReportesController/ventastotales');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        return view('admin.reportes.detalleventa');

    }

    public function exportdetalleventa(Request $request) 
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpReportesController/exportventastotales ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpReportesController/exportventastotales');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        $input=$request->all();

       // dd($input);

        return Excel::download(new DetalleVentaExport($request->desde, $request->hasta), 'detalleventa_desde_'.$request->desde.'_hasta_'.$request->hasta.'.xlsx');
    }



     public function detalleclientes() 
    {

         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpReportesController/detalleclientes ');

        }else{

          activity()
          ->log('AlpReportesController/detalleclientes');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        return view('admin.reportes.detalleclientes');

    }

    public function exportdetalleclientes(Request $request) 
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpReportesController/exportdetalleclientes ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpReportesController/exportdetalleclientes');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        $input=$request->all();

       // dd($input);

        return Excel::download(new DetalleClientesExport($request->desde, $request->hasta), 'detalle_clientes_desde_'.$request->desde.'_hasta_'.$request->hasta.'.xlsx');
    }



      public function clientes() 
    {


         if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpReportesController/masterfile ');

        }else{

          activity()
          ->log('AlpReportesController/masterfile');


        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        $states=State::where('config_states.country_id', '47')->get();


        return view('admin.reportes.clientes', compact( 'states'));

    }


    public function exportclientes(Request $request) 
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpReportesController/exportmasterfile ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpReportesController/exportmasterfile');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        return Excel::download(new ClientesExport($request->city_id), 'clientes_desde_'.$request->desde.'_hasta_'.$request->hasta.'.xlsx');
    }


public function formato() 
    {

         if (Sentinel::check()) {
          $user = Sentinel::getUser();
           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->log('AlpReportesController/formato ');

        }else{
          activity()
          ->log('AlpReportesController/formato');
        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {
           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

        return view('admin.reportes.formato');

    }




         public function exportformato(Request $request) 
    {

        if (Sentinel::check()) {

          $user = Sentinel::getUser();

           activity($user->full_name)
                        ->performedOn($user)
                        ->causedBy($user)
                        ->withProperties($request->all())->log('AlpReportesController/exportnomina ');

        }else{

          activity()
          ->withProperties($request->all())->log('AlpReportesController/exportnomina');

        }

        if (!Sentinel::getUser()->hasAnyAccess(['reportes.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }

       // dd($request->all());

         $date = Carbon::now();

        $hoy=$date->format('Y-m-d');

        return Excel::download(new FormatoSolicitudPedidoAlpinista(1864), 'Listado_de_ventas_almacen.xlsx');
    }




}