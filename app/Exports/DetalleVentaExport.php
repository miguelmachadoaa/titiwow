<?php

namespace App\Exports;

use App\User;
use App\Models\AlpOrdenes;
use App\Models\AlpDirecciones;
use App\Models\AlpDetalles;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

use \DB;


class DetalleVentaExport implements FromView
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
          'alp_ordenes.id_address as id_address',
          
          'alp_ordenes.base_impuesto as base_impuesto',
          'alp_ordenes.valor_impuesto as valor_impuesto',
          'alp_ordenes.monto_impuesto as monto_impuesto',
          'alp_ordenes.referencia as referencia', 
          'alp_ordenes.monto_total as monto_total',
          DB::raw('count(alp_ordenes_detalle.cantidad) as total_articulos'),
          'alp_clientes.doc_cliente as doc_cliente',
          'users.id as id_usuario', 
          'users.first_name as first_name', 
          'users.last_name as last_name', 
          'users.email as email', 
          
          'alp_formas_pagos.nombre_forma_pago as nombre_forma_pago',
          'alp_ordenes.created_at as created_at',
           DB::raw('DATE_FORMAT(alp_ordenes.created_at, "%d/%m/%Y")  as fecha'),
          'roles.name as name_rol' )
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('role_users', 'alp_ordenes.id_cliente', '=', 'role_users.user_id')
          ->join('roles', 'role_users.role_id', '=', 'roles.id')
          ->join('alp_clientes', 'alp_ordenes.id_cliente', '=', 'alp_clientes.id_user_client')
          ->join('alp_ordenes_detalle', 'alp_ordenes.id', '=', 'alp_ordenes_detalle.id_orden')
          ->join('alp_formas_pagos', 'alp_ordenes.id_forma_pago', '=', 'alp_formas_pagos.id')
          ->groupBy('alp_ordenes.id')
          //->whereDate('alp_ordenes.created_at', '>=', $this->desde)
         // ->whereDate('alp_ordenes.created_at', '<=', $this->hasta)
          ->whereIn('alp_ordenes.estatus',  ['3','5'])
          ->get();


          foreach ($ordenes as $orden) {
            
              $direccion = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
            ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
            ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
            ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
            ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
            ->where('alp_direcciones.id', $orden->id_address)->withTrashed()->first();

            $orden->direccion=$direccion;

            $detalles = AlpDetalles::select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.imagen_producto as imagen_producto','alp_productos.referencia_producto as referencia_producto')
            ->join('alp_productos', 'alp_ordenes_detalle.id_producto', '=', 'alp_productos.id')
            ->where('alp_ordenes_detalle.id_orden', $orden->id)
            ->get();


            $orden->detalles=$detalles;


          }

         // dd($ordenes);

          //dd($ordenes);

        return view('admin.exports.detalleventa', [
            'ordenes' => $ordenes
        ]);
    }
}

