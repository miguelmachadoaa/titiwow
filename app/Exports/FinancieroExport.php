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


class FinancieroExport implements FromView
{
    
    public function __construct(string $desde, string $hasta)
    {
        $this->desde = $desde;
        $this->hasta = $hasta;
    }



    public function view(): View
    {


        $ordenes=AlpOrdenes::query()->select(
           DB::raw('DATE_FORMAT(alp_ordenes.created_at, "%d/%m/%Y")  as fecha'),
          'alp_ordenes.id as id',
          
          'alp_ordenes.base_impuesto as base_impuesto',
          'alp_ordenes.valor_impuesto as valor_impuesto',
          'alp_ordenes.monto_impuesto as monto_impuesto',
          'alp_ordenes.factura as factura', 
          'alp_ordenes.ordencompra as ordencompra', 
          'alp_ordenes.monto_total as monto_total', 
          'alp_clientes.cod_oracle_cliente as cod_oracle_cliente', 
          'alp_clientes.doc_cliente as doc_cliente', 
          'users.first_name as first_name', 
          'users.last_name as last_name', 
          'users.email as email', 
          'alp_ordenes_pagos.json as json',
          'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago'
          )
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('role_users', 'alp_ordenes.id_cliente', '=', 'role_users.user_id')
          ->join('roles', 'role_users.role_id', '=', 'roles.id')
          ->join('alp_clientes', 'alp_ordenes.id_cliente', '=', 'alp_clientes.id_user_client')
          ->join('alp_ordenes_detalle', 'alp_ordenes.id', '=', 'alp_ordenes_detalle.id_orden')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->leftJoin('alp_ordenes_pagos', 'alp_ordenes.id', '=', 'alp_ordenes_pagos.id_orden')
          ->groupBy('alp_ordenes.id')
          ->where('alp_ordenes.estatus', '=', '6')
          ->whereDate('alp_ordenes.created_at', '>=', $this->desde)
          ->whereDate('alp_ordenes.created_at', '<=', $this->hasta)->get();

          //dd($ordenes);

        return view('admin.exports.financiero', [
            'ventas' => $ordenes
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