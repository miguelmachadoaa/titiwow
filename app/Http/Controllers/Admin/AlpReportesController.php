<?php 

namespace App\Http\Controllers\Admin;

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
use App\Exports\ProductosExport;
use App\Exports\ProductosExportB;
use App\Exports\ProductosExportC;
use App\Exports\ProductostotalesExport;
use App\Exports\CarritoExport;
use App\Exports\DescuentoVentasExport;
use App\Exports\TomaPedidosExport;
use App\User;
use App\Models\AlpOrdenes;
use App\Models\AlpProductos;
use App\Imports\UsersImport;
use App\Http\Requests;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DB;

use Carbon\Carbon;

class AlpReportesController extends Controller 
{
    public function indexreg() 
    {
        return view('admin.reportes.registrados');
    }

    public function ventas() 
    {

       // $clientes=User::all();

       $clientes = AlpOrdenes::select('alp_ordenes.*', 'users.first_name as first_name', 'users.last_name as last_name')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->groupBy('alp_ordenes.id_cliente')
          ->get();


        return view('admin.reportes.ventas', compact('clientes'));

    }

    public function productos() 
    {

        $productos=AlpProductos::all();

        return view('admin.reportes.productos', compact('productos'));

    }

     public function exportproductos(Request $request) 
    {

       // dd($request->all());

        return Excel::download(new ProductosExport($request->desde, $request->hasta), 'ventas_desde_'.$request->desde.'_hasta_'.$request->hasta.'_producto_'.$request->producto.'.xlsx');
    }




    public function productostotales() 
    {

        $productos=AlpProductos::all();

        return view('admin.reportes.productostotales', compact('productos'));

    }

     public function exportproductostotales(Request $request) 
    {

       // dd($request->all());

        return Excel::download(new ProductostotalesExport($request->desde, $request->hasta), 'productos_con_impuesto_desde_'.$request->desde.'_hasta_'.$request->hasta.'_producto_'.$request->producto.'.xlsx');
    }

    public function carrito() 
    {

        

        return view('admin.reportes.carrito');

    }

     public function exportcarrito(Request $request) 
    {

       // dd($request->all());

        return Excel::download(new CarritoExport($request->desde, $request->hasta), 'carrito_desde_'.$request->desde.'_hasta_'.$request->hasta.'.xlsx');
    }


    public function exportventas(Request $request) 
    {

       // dd($request->all());

        return Excel::download(new VentasExport($request->desde, $request->hasta, $request->clientes), 'ventas_desde_'.$request->desde.'_hasta_'.$request->hasta.'_usuario_'.$request->clientes.'.xlsx');
    }

    public function export(Request $request) 
    {

        //dd($request->all());

        return Excel::download(new UsersExport($request->desde, $request->hasta), 'users_.xlsx');
    }
    
    public function import() 
    {
        return Excel::import(new UsersImport, 'users.xlsx');
    }



    public function financiero() 
    {

        

        return view('admin.reportes.financiero');

    }


    public function exportfinanciero(Request $request) 
    {

       // dd($request->all());

        return Excel::download(new FinancieroExport($request->desde, $request->hasta), 'financiero_desde_'.$request->desde.'_hasta_'.$request->hasta.'.xlsx');
    }


     public function masterfile() 
    {

        return view('admin.reportes.masterfile');

    }


    public function exportmasterfile(Request $request) 
    {
        return Excel::download(new MasterfileExport($request->desde, $request->hasta), 'masterfile_desde_'.$request->desde.'_hasta_'.$request->hasta.'.xlsx');
    }

     public function masterfileamigos() 
    {

        return view('admin.reportes.masterfileamigos');

    }


    public function exportmasterfileamigos(Request $request) 
    {
        return Excel::download(new MasterfileAmigosExport($request->desde, $request->hasta), 'masterfile_desde_'.$request->desde.'_hasta_'.$request->hasta.'.xlsx');
    }

     public function masterfileembajadores() 
    {

        return view('admin.reportes.masterfileembajador');

    }


    public function exportmasterfileembajadores(Request $request) 
    {
        return Excel::download(new MasterfileEmbajadoresExport($request->desde, $request->hasta), 'masterfile_desde_'.$request->desde.'_hasta_'.$request->hasta.'.xlsx');
    }


     public function logistica() 
    {

        return view('admin.reportes.logistica');

    }


    public function exportlogistica(Request $request) 
    {

        Excel::store(new LogisticaExport($request->desde, $request->hasta), 'logistica_desde_'.$request->desde.'_hasta_'.$request->hasta.'.xlsx', 'public');


        return Excel::download(new LogisticaExport($request->desde, $request->hasta), 'logistica_desde_'.$request->desde.'_hasta_'.$request->hasta.'.xlsx');
    }

    public function storeexportlogistica(Request $request) 
    {
         Excel::store(new LogisticaExport($request->desde, $request->hasta), 'logistica_desde_'.$request->desde.'_hasta_'.$request->hasta.'.xlsx');

         return true;
    }


    public function consolidado() 
    {

        return view('admin.reportes.consolidado');

    }


    public function exportconsolidado(Request $request) 
    {
        return Excel::download(new ConsolidadoExport($request->desde), 'consolidado_desde_'.$request->desde.'_hasta_'.$request->hasta.'.xlsx');
    }

     public function ventastotales() 
    {

        return view('admin.reportes.ventastotales');

    }


    public function exportventastotales(Request $request) 
    {
        return Excel::download(new VentastotalesExport($request->desde, $request->hasta), 'ventastotales_desde_'.$request->desde.'_hasta_'.$request->hasta.'.xlsx');
    }

    public function ventasdescuento() 
    {

        return view('admin.reportes.ventasdescuento');

    }


    public function exportventasdescuento(Request $request) 
    {
        return Excel::download(new DescuentoVentasExport($request->desde, $request->hasta), 'ventasdescuento_desde_'.$request->desde.'_hasta_'.$request->hasta.'.xlsx');
    }




    /*********************funciones para descarga de reporte************************/

    public function exportcronlogisticaexport(Request $request) 
    {
        $date = Carbon::now();

        $hoy=$date->format('Y-m-d');

        $archivo='logistica_'.$hoy.'.xlsx';


       return Excel::download(new CronLogisticaExport(), $archivo);

    }

    public function cronnuevosusuariosexport(Request $request) 
    {
        $date = Carbon::now();

        $hoy=$date->format('Y-m-d');

        $archivo='nuevos_usuarios_'.$hoy.'.xlsx';


       return Excel::download(new UsersActivarExport(), $archivo);

    }



     public function productosb() 
    {

        $productos=AlpProductos::all();

        return view('admin.reportes.productosb', compact('productos'));

    }

     public function exportproductosb(Request $request) 
    {

       // dd($request->all());

        $date = Carbon::now();

        $hoy=$date->format('Y-m-d');

        return Excel::download(new ProductosExportB($hoy, $hoy), 'ventas_desde_'.$hoy.'_hasta_'.$hoy.'_producto.xlsx');
    }

     public function productosc() 
    {

        $productos=AlpProductos::all();

        return view('admin.reportes.productosc', compact('productos'));

    }


     public function exportproductosc(Request $request) 
    {

       // dd($request->all());

        $date = Carbon::now();

        $hoy=$date->format('Y-m-d');

        return Excel::download(new ProductosExportC($hoy, $hoy), 'ventas_desde_'.$hoy.'_hasta_'.$hoy.'_producto.xlsx');
    }


    public function cronexportproductosb(Request $request) 
    {
        $date = Carbon::now();

        $hoy=$date->format('Y-m-d');

        $archivo='ventas_productos'.$hoy.'.xlsx';

         return Excel::download(new ProductosExportB($hoy, $hoy), $archivo);

    }

      public function cronexportproductosc(Request $request) 
    {
        $date = Carbon::now();

        $hoy=$date->format('Y-m-d');

        $archivo='ventas_productos'.$hoy.'.xlsx';

         return Excel::download(new ProductosExportC($hoy, $hoy), $archivo);

    }
    public function cronexporttomapedidos(Request $request) 
    {
        $date = Carbon::now();

        $hoy=$date->format('Y-m-d');

        $archivo='tomapedidos_'.$hoy.'.xlsx';

         return Excel::download(new TomaPedidosExport(), $archivo);

    }


}