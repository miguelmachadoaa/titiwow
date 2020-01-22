<?php

namespace App\Exports;

use App\User;
use App\Models\AlpProductos;
use App\Models\AlpOrdenes;
use App\Models\AlpDetalles;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

use \DB;


class ListadoProductosExport implements FromView
{
    
    public function __construct()
    {
       
    }



    public function view(): View
    {
         $productos=AlpProductos::get();
                   //dd($ordenes);

        return view('admin.exports.listadoproductos', [
            'productos' => $productos
        ]);
    }
}




