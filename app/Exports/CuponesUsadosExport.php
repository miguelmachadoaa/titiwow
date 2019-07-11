<?php

namespace App\Exports;

use App\User;
use App\Models\AlpOrdenes;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

use \DB;


class CuponesUsadosExport implements FromView
{
    
    public function __construct()
    {
       
    }



    public function view(): View
    {
        $cupones=AlpOrdenes::query()->select(
          'alp_ordenes.id as id',
          'alp_ordenes.referencia as referencia',
          'alp_ordenes_descuento.codigo_cupon as codigo_cupon',
          'alp_ordenes_descuento.monto_descuento as monto_descuento',
          'users.first_name as first_name',
          'users.last_name as last_name' )
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_ordenes_descuento', 'alp_ordenes.id', '=', 'alp_ordenes_descuento.id_orden')
          ->get();

          //dd($ordenes);

        return view('admin.exports.cuponesusados', [
            'cupones' => $cupones
        ]);
    }
}

