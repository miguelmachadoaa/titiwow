<?php

namespace App\Exports;

use App\User;
use App\Models\AlpOrdenes;
use App\Models\AlpLifeMiles;
use App\Models\AlpCarrito;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

use \DB;


class AbandonadosExport implements FromView
{
    
    public function __construct(string $desde, string $hasta)
    {
        $this->desde = $desde;
        $this->hasta = $hasta;
    }

    public function view(): View
    {
          /*  $abandonados=AlpCarrito::select(
            'alp_carrito.*', 
            'users.first_name as first_name', 
            'users.last_name as last_name', 
            'users.email as email',
            )
            ->join('users', 'alp_carrito.id_user', '=', 'users.id')
          ->whereDate('alp_carrito.created_at', '>=', $this->desde)
          ->whereDate('alp_carrito.created_at', '<=', $this->hasta)
          ->get();*/


          $abandonados=AlpOrdenes::query()->select(
            'alp_ordenes.id as id',
            'alp_ordenes.base_impuesto as base_impuesto',
            'alp_ordenes.valor_impuesto as valor_impuesto',
            'alp_ordenes.monto_impuesto as monto_impuesto',
            'alp_ordenes.referencia as referencia', 
            'alp_ordenes.monto_total as monto_total',
           # DB::raw('count(alp_ordenes_detalle.cantidad) as total_articulos'),
            'alp_clientes.doc_cliente as doc_cliente',
            'users.id as id_usuario', 
            'users.first_name as first_name', 
            'users.last_name as last_name', 
            'users.email as email', 
            'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago',
            'alp_almacenes.nombre_almacen as nombre_almacen',
            'alp_ordenes.created_at as created_at',
             DB::raw('DATE_FORMAT(alp_ordenes.created_at, "%d/%m/%Y")  as fecha'),
             )
            ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
            ->join('alp_almacenes', 'alp_ordenes.id_almacen', '=', 'alp_almacenes.id')
            ->join('alp_clientes', 'alp_ordenes.id_cliente', '=', 'alp_clientes.id_user_client')
           # ->join('alp_ordenes_detalle', 'alp_ordenes.id', '=', 'alp_ordenes_detalle.id_orden')
            ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
            ->groupBy('alp_ordenes.id')
            ->whereDate('alp_ordenes.created_at', '>=', $this->desde)
            ->whereDate('alp_ordenes.created_at', '<=', $this->hasta)
            ->where('alp_ordenes.estatus', '=', '4')
            ->get();




         # dd($lifemiles);

        return view('admin.exports.abandonados', [
            'abandonados' => $abandonados
        ]);
    }
}



/*

class VentasExport implements FromQuery
{
    use Exportable;

    public function __construct(string $desde, string $hasta, int $user)
    {
        $this->desde = $desde;
        $this->hasta = $hasta;
        $this->user = $user;
    }

    public function headings(): array
    {
        return [
            '#',
            'Referencia',
            'Monto Total',
            'Codigo Oracle',
            'Numero Factura',
            'Tracking ',
            'Creado',
            'Nombre',
            'Apellido',
            'Forma de Envio',
            'Forma de Pago',
            'Estatus de Orden',
        ];
    }


    public function query()
    {
         /*AlpOrdenes::query()->whereDate('created_at', '>', $this->desde)->whereDate('created_at', '<', $this->hasta)->where('id_cliente','=', $this->user);*/

      /*  return AlpOrdenes::query()->select('alp_ordenes.id as id','alp_ordenes.referencia as referencia', 'alp_ordenes.monto_total as monto_total','alp_ordenes.ordencompra as ordencompra','alp_ordenes.factura as factura','alp_ordenes.tracking as tracking','users.first_name as first_name', 'users.last_name as last_name', 'alp_formas_envios.nombre_forma_envios as nombre_forma_envios', 'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago', 'alp_ordenes_estatus.estatus_nombre as estatus_nombre', 'alp_pagos_status.estatus_pago_nombre as estatus_pago_nombre','alp_ordenes.created_at as created_at')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_formas_envios', 'alp_ordenes.id_forma_envio', '=', 'alp_formas_envios.id')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->leftJoin('alp_ordenes_pagos', 'alp_ordenes.id', '=', 'alp_ordenes_pagos.id_orden')
          ->join('alp_ordenes_estatus', 'alp_ordenes.estatus', '=', 'alp_ordenes_estatus.id')
          ->join('alp_pagos_status', 'alp_ordenes.estatus_pago', '=', 'alp_pagos_status.id')
          ->whereDate('alp_ordenes.created_at', '>=', $this->desde)
          ->whereDate('alp_ordenes.created_at', '<=', $this->hasta)
          ->where('alp_ordenes.id_cliente','=', $this->user);
          
    }

    
}
*/