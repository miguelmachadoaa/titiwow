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
    
    public function __construct(string $estado)
    {

       $this->estado=$estado;

    }

    public function view(): View
    {

        if ($this->estado=='0') {
         
            $productos=AlpProductos::get();
        
        }else{

            $productos=AlpProductos::where('estado_registro', $this->estado)->get();

        }

        return view('admin.exports.listadoproductos', [
            'productos' => $productos
        ]);
    }
}