<?php 

namespace App\Http\Controllers\Admin;

use App\Exports\ConsolidadoExport;
use App\Exports\LogisticaExport;
use App\Exports\MasterfileExport;
use App\Exports\FinancieroExport;
use App\Exports\UsersExport;
use App\Exports\VentasExport;
use App\Exports\ProductosExport;
use App\Exports\CarritoExport;
use App\User;
use App\Models\AlpOrdenes;
use App\Models\AlpProductos;
use App\Imports\UsersImport;
use App\Http\Requests;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DB;

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


     public function logistica() 
    {

        return view('admin.reportes.logistica');

    }


    public function exportlogistica(Request $request) 
    {
        return Excel::download(new LogisticaExport($request->desde, $request->hasta), 'logistica_desde_'.$request->desde.'_hasta_'.$request->hasta.'.xlsx');
    }


    public function consolidado() 
    {

        return view('admin.reportes.consolidado');

    }


    public function exportconsolidado(Request $request) 
    {
        return Excel::download(new ConsolidadoExport($request->desde), 'consolidado_desde_'.$request->desde.'_hasta_'.$request->hasta.'.xlsx');
    }


}