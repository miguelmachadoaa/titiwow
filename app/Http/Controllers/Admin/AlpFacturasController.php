<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Imports\FacturasImport;
use Maatwebsite\Excel\Facades\Excel;

use App\User;
use App\Models\AlpFacturas;
use App\Models\AlpOrdenes;
use DB;

class AlpFacturasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (!Sentinel::getUser()->hasAnyAccess(['facturasmasivas.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }
        $facturas =  AlpFacturas::select('alp_cod_facturas.*')
        ->get();

        return view('admin.facturas.index', compact('facturas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        if (!Sentinel::getUser()->hasAnyAccess(['facturasmasivas.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }
        return view('admin.facturas.cargar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (!Sentinel::getUser()->hasAnyAccess(['facturasmasivas.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {

        if (!Sentinel::getUser()->hasAnyAccess(['facturasmasivas.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }
   
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        if (!Sentinel::getUser()->hasAnyAccess(['facturasmasivas.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        if (!Sentinel::getUser()->hasAnyAccess(['facturasmasivas.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function import(Request $request) 
    {

        if (!Sentinel::getUser()->hasAnyAccess(['facturasmasivas.*'])) {

           return redirect('admin')->with('aviso', 'No tiene acceso a la pagina que intenta acceder');
        }
        $archivo = $request->file('file_facturas');
        Excel::import(new FacturasImport, $archivo);
        
        return redirect('admin/facturasmasivas')->with('success', 'Facturas Cargadas Exitosamente');
    }
  
}
