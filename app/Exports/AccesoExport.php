<?php

namespace App\Exports;

use App\User;
use App\Models\AlpOrdenes;
use App\Models\AlpLifeMiles;
use App\Models\AlpLog;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

use \DB;


class AccesoExport implements FromView
{
    
    public function __construct(string $desde, string $hasta)
    {
        $this->desde = $desde;
        $this->hasta = $hasta;
    }

    public function view(): View
    {
            $acceso=AlpLog::select(
            'activity_log.*', 
            'users.first_name as first_name', 
            'users.last_name as last_name', 
            'users.email as email',
            'roles.name as name_rol'
            )
            ->join('users', 'activity_log.subject_id', '=', 'users.id')
            ->join('role_users', 'users.id', '=', 'role_users.user_id')
            ->join('roles', 'role_users.role_id', '=', 'roles.id')
            ->whereDate('activity_log.created_at', '>=', $this->desde)
            ->whereDate('activity_log.created_at', '<=', $this->hasta)
            ->where('activity_log.description', '=', 'LoggedIn')
            ->whereIn('roles.id',  ['1','2','3','4','5','6','7','8','13','15'])
            ->get();

         # dd($lifemiles);

        return view('admin.exports.acceso', [
            'acceso' => $acceso
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