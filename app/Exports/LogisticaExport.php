<?php

namespace App\Exports;

use App\User;
use App\State;
use App\City;
use App\Roles;
use App\RoleUser;


use App\Models\AlpDirecciones;
use App\Models\AlpClientes;
use App\Models\AlpOrdenes;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

use \DB;


class LogisticaExport implements FromView
{
    
    public function __construct(string $origen, string $desde, string $hasta, string $id_almacen)
    {
        $this->origen = $origen;
        $this->desde = $desde;
        $this->hasta = $hasta;
        $this->id_almacen = $id_almacen;
    }



    public function view(): View
    {
        $o=AlpOrdenes::query()->select(
          'alp_ordenes.*',
          DB::raw('count(alp_ordenes_detalle.cantidad) as total_articulos'),
           DB::raw('DATE_FORMAT(alp_ordenes.created_at, "%d/%m/%Y")  as fecha')
           )
          ->join('alp_ordenes_detalle', 'alp_ordenes.id', '=', 'alp_ordenes_detalle.id_orden')
          ->groupBy('alp_ordenes.id')
         # ->where('alp_ordenes.ordencompra', '!=', NULL)
          ->whereIn('alp_ordenes.estatus', [5,3])
          ->where('alp_ordenes.id_forma_pago', '<>', '3')
          ->whereDate('alp_ordenes.created_at', '>=', $this->desde)
          ->whereDate('alp_ordenes.created_at', '<=', $this->hasta);
          //->get();


          if ($this->origen==-1) {
            # code...
          }else{

            $o->where('alp_ordenes.origen', '=', $this->origen);
          }

            if ($this->id_almacen==0) {
            # code...
          }else{

            $o->where('alp_ordenes.id_almacen', '=', $this->id_almacen);
          }


        //  $ordenes=$o->get();
        //  

          #$ordenes = array();
          $ordenes=$o->get();

          foreach ($ordenes as $o) {

            $direccion=AlpDirecciones::where('id', $o->id_address)->first();

            $o->direccion=$direccion;

            #dd($o);
              // code...
          }


          $state=State::pluck('state_name', 'id');
          $city=City::pluck('city_name', 'id');
          $roleuser=RoleUser::pluck('role_id', 'user_id');
          $role=Roles::pluck('name', 'id');
          $telefono=AlpClientes::pluck('telefono_cliente', 'id_user_client');
          $documento=AlpClientes::pluck('doc_cliente', 'id_user_client');
          $nombre=User::pluck('first_name', 'id');
          $apellido=User::pluck('last_name', 'id');

          #dd($ordenes);

        return view('admin.exports.logistica', [
            'ventas' => $ordenes,
            'state' => $state,
            'city' => $city,
            'roleuser' => $roleuser,
            'role' => $role,
            'telefono' => $telefono,
            'documento' => $documento,
            'nombre' => $nombre,
            'apellido' => $apellido,


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