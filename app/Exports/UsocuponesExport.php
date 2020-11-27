<?php

namespace App\Exports;

use App\User;
use App\Models\AlpOrdenes;
use App\Models\AlpDetalles;
use App\Models\AlpCarritoDetalle;
use App\Models\AlpCarrito;
use App\Models\AlpInventario;
use App\Models\AlpProductos;
use App\Models\AlpCupones;
use App\Models\AlpOrdenesDescuento;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

use \DB;


class UsocuponesExport implements FromView
{
    
    public function __construct()
    {

    }



    public function view(): View
    {

       $cupones = AlpCupones::get();




       foreach ($cupones as $c) {

          $dc=AlpOrdenesDescuento::where('codigo_cupon', '=', $c->codigo_cupon)->where('aplicado', '=','1')->get();

          //dd($dc);

          $c->usos=$dc;
         # code...
       }

      
        return view('admin.exports.usocupones', [
            'cupones' => $cupones
        ]);
    }
}