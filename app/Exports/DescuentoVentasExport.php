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


class DescuentoVentasExport implements FromView
{
    
    public function __construct(string $desde, string $hasta)
    {
        $this->desde = $desde;
        $this->hasta = $hasta;
    }



    public function view(): View
    {
        $ordenes=AlpOrdenes::query()->select(
          'alp_ordenes.id as id',
          
          'alp_ordenes.monto_descuento as monto_descuento',
          'alp_ordenes.base_impuesto as base_impuesto',
          'alp_ordenes.valor_impuesto as valor_impuesto',
          'alp_ordenes.monto_impuesto as monto_impuesto',
          'alp_ordenes.referencia as referencia', 
          'alp_ordenes.monto_total as monto_total',
          'alp_ordenes_descuento.codigo_cupon as codigo_cupon',
          DB::raw('count(alp_ordenes_detalle.cantidad) as total_articulos'),
          'alp_clientes.doc_cliente as doc_cliente',
          'users.id as id_usuario', 
          'users.first_name as first_name', 
          'users.last_name as last_name') 
          
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_clientes', 'alp_ordenes.id_cliente', '=', 'alp_clientes.id_user_client')
          ->join('alp_ordenes_detalle', 'alp_ordenes.id', '=', 'alp_ordenes_detalle.id_orden')
          ->leftJoin('alp_ordenes_descuento', 'alp_ordenes.id', '=', 'alp_ordenes_descuento.id_orden')
          ->groupBy('alp_ordenes.id')
          ->whereDate('alp_ordenes.created_at', '>=', $this->desde)
          ->whereDate('alp_ordenes.created_at', '<=', $this->hasta)
          ->get();

         

        return view('admin.exports.ventasdescuento', [
            'ventas' => $ordenes
        ]);
    }
}

