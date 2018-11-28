<?php 

namespace App\Http\Controllers\Admin;

use App\Exports\UsersExport;
use App\Exports\VentasExport;
use App\User;
use App\Models\AlpOrdenes;
use App\Imports\UsersImport;
use App\Http\Requests;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;

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

    public function exportventas(Request $request) 
    {

       // dd($request->all());

        return Excel::download(new VentasExport($request->desde, $request->hasta, $request->clientes), 'ventas_desde_'.$request->desde.'_hasta_'.$request->hasta.'_usuario_'.$request->clientes.'.xlsx');
    }

    public function export() 
    {

        return Excel::download(new UsersExport, 'users_test.xlsx');
    }
    
    public function import() 
    {
        return Excel::import(new UsersImport, 'users.xlsx');
    }
}