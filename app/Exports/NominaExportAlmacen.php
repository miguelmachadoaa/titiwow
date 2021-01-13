<?php

namespace App\Exports;

use App\User;
use App\Models\AlpAlmacenes;
use App\Models\AlpProductos;
use App\Models\AlpOrdenes;
use App\Models\AlpDetalles;
use App\Models\AlpDirecciones;
use App\Models\AlpClientes;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use \DB;
use Carbon\Carbon;


class NominaExportAlmacen implements FromView
{
    
    public function __construct(string $desde,string $alm)
    {
        $this->desde = $desde;
        $this->alm = $alm;
    }

    public function view(): View
    {


      $almacen=AlpAlmacenes::where('id', $this->alm)->first();

      $date_desde = Carbon::parse($this->desde.' '.$almacen->hora.':00')->subDay()->toDateTimeString();

      $date_hasta = Carbon::parse($this->desde.' 23:59:59')->toDateTimeString(); 

          $ordenes=AlpOrdenes::where('alp_ordenes.id_almacen', $this->alm)
          ->where('alp_ordenes.created_at', '>=', $date_desde)
          ->where('alp_ordenes.created_at', '<=', $date_hasta)
          ->whereIn('alp_ordenes.estatus', ['1','2','3','5','6','7','8'])
          ->get();

          foreach ($ordenes as $orden) {

             $detalles = AlpDetalles::select('alp_ordenes_detalle.*','alp_productos.nombre_producto as nombre_producto','alp_productos.imagen_producto as imagen_producto','alp_productos.referencia_producto as referencia_producto','alp_productos.referencia_producto_sap as referencia_producto_sap')
              ->join('alp_productos', 'alp_ordenes_detalle.id_producto', '=', 'alp_productos.id')
              ->where('alp_ordenes_detalle.id_orden', $orden->id)
              ->get();

              $orden->detalles=$detalles;


              $direccion = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
          ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
          ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
          ->where('alp_direcciones.id', $orden->id_address)->withTrashed()->first();

            $orden->direccion=$direccion;

            $cliente =  User::select('users.*','roles.name as name_role','alp_clientes.estado_masterfile as estado_masterfile','alp_clientes.estado_registro as estado_registro','alp_clientes.telefono_cliente as telefono_cliente','alp_clientes.cod_oracle_cliente as cod_oracle_cliente','alp_clientes.cod_alpinista as cod_alpinista','alp_clientes.doc_cliente as doc_cliente')
            ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
            ->join('role_users', 'users.id', '=', 'role_users.user_id')
            ->join('roles', 'role_users.role_id', '=', 'roles.id')
            ->where('users.id', '=', $orden->id_user)->first();


            $orden->cliente=$cliente;

            # code...
          }

          //dd(count($ordenes));


        return view('admin.exports.nomina', [
            'ordenes' => $ordenes
        ]);
    }
}