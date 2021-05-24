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


class ConsolidadoExport implements FromView
{
    
    public function __construct(string $desde)
    {
        $this->desde = $desde;
        
    }



    public function view(): View
    {
        $ordenes=AlpOrdenes::query()->select(
          'alp_ordenes.id as id',
          'alp_ordenes.ip as ip',
          'alp_ordenes.base_impuesto as base_impuesto',
          'alp_ordenes.valor_impuesto as valor_impuesto',
          'alp_ordenes.monto_impuesto as monto_impuesto',
          'alp_ordenes.referencia as referencia', 
          'alp_ordenes.monto_total as monto_total',
          'alp_ordenes.monto_descuento as monto_total',
          DB::raw('count(alp_ordenes_detalle.cantidad) as total_articulos'),
          'alp_clientes.doc_cliente as doc_cliente',
          'users.id as id_usuario', 
          'users.first_name as first_name', 
          'users.last_name as last_name', 
          'users.email as email', 
          'alp_ordenes.created_at as created_at',
          'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago',
          'alp_ordenes_pagos.json as json',

           DB::raw('DATE_FORMAT(alp_ordenes.created_at, "%d/%m/%Y")  as fecha'),
          'roles.name as name_rol' )
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('role_users', 'alp_ordenes.id_cliente', '=', 'role_users.user_id')
          ->join('roles', 'role_users.role_id', '=', 'roles.id')
          ->join('alp_clientes', 'alp_ordenes.id_cliente', '=', 'alp_clientes.id_user_client')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->leftJoin('alp_ordenes_pagos', 'alp_ordenes.id', '=', 'alp_ordenes_pagos.id_orden')

          ->join('alp_ordenes_detalle', 'alp_ordenes.id', '=', 'alp_ordenes_detalle.id_orden')
          ->groupBy('alp_ordenes.id')
          ->whereIn('alp_ordenes.estatus', [5,6,7,3])
          ->where('alp_ordenes.id_forma_pago', '<>', '3')
          ->whereDate('alp_ordenes.created_at', '>=', $this->desde)
          ->whereDate('alp_ordenes.created_at', '<=', $this->desde)
          ->get();

          //dd($ordenes);

        return view('admin.exports.ventas', [
            'ventas' => $ordenes
        ]);
    }
}