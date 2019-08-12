<?php

namespace App\Exports;

use App\User;
use App\Models\AlpOrdenes;
use App\Models\AlpDetalles;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

use \DB;


class ProductosRolExportC implements FromView
{
    
    public function __construct(string $desde, string $hasta, string $rol)
    {
        $this->desde = $desde;
        $this->hasta = $hasta;
        $this->rol = $rol;
    }



    public function view(): View
    {
         $productos= AlpDetalles::select(
          'alp_ordenes_detalle.*', 
          'alp_clientes.doc_cliente as doc_cliente',
          'alp_clientes.telefono_cliente as telefono_cliente',
          'alp_ordenes.ordencompra as ordencompra',
          'users.id as id_usuario', 
          'users.first_name as first_name', 
          'users.last_name as last_name', 
          'users.email as email', 


           'alp_ordenes.monto_total as monto_total_orden',
          'alp_ordenes.base_impuesto as base_impuesto_orden',
          'alp_ordenes.monto_impuesto as monto_impuesto_orden',
          'alp_ordenes.valor_impuesto as valor_impuesto_orden',

           'config_cities.city_name as city_name',
        'config_states.state_name as state_name',
        'alp_direcciones.principal_address as principal_address',
        'alp_direcciones.secundaria_address as secundaria_address',
        'alp_direcciones.edificio_address as edificio_address',
        'alp_direcciones.detalle_address as detalle_address',
        'alp_direcciones.barrio_address as barrio_address',
        'alp_direcciones_estructura.nombre_estructura as nombre_estructura',
        'alp_direcciones_estructura.abrevia_estructura as abrevia_estructura',


           DB::raw('DATE_FORMAT(alp_ordenes_detalle.created_at, "%d/%m/%Y")  as fecha'),
          // DB::raw('sum(alp_ordenes_detalle.cantidad)  as total_cantidad'),
          'alp_productos.nombre_producto as nombre_producto',
          'alp_productos.referencia_producto as referencia_producto',
          'alp_productos.referencia_producto_sap as referencia_producto_sap',
          'alp_productos.referencia_producto as referencia_producto',
          'alp_categorias.nombre_categoria as nombre_categoria',
          'alp_marcas.nombre_marca as nombre_marca'
          )
          ->join('alp_ordenes', 'alp_ordenes_detalle.id_orden', '=', 'alp_ordenes.id')
          ->join('alp_productos', 'alp_ordenes_detalle.id_producto', '=', 'alp_productos.id')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('role_users', 'users.id', '=', 'role_users.user_id')
          ->join('alp_clientes', 'alp_ordenes.id_cliente', '=', 'alp_clientes.id_user_client')
          ->join('alp_categorias', 'alp_productos.id_categoria_default', '=', 'alp_categorias.id')
          ->join('alp_marcas', 'alp_productos.id_marca', '=', 'alp_marcas.id')


          ->join('alp_direcciones', 'alp_ordenes.id_address', '=', 'alp_direcciones.id')
          ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
          ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')


         // ->groupBy('alp_ordenes_detalle.id_producto')
          ->whereNull('alp_ordenes.factura')
          ->whereIn('alp_ordenes.estatus', [5])
          ->where('alp_ordenes.estatus_pago','=', '2')
          ->where('role_users.role_id','=', $this->rol)
          //->whereDate('alp_ordenes_detalle.created_at', '>=', $this->desde)
          //->whereDate('alp_ordenes_detalle.created_at', '<=', $this->hasta)
          ->get();

          $pro = array();

          foreach ($productos as $producto) {

            $p= AlpDetalles::select(
           DB::raw('count(alp_ordenes_detalle.id_orden)  as num_pedidos')
          )
          ->groupBy('alp_ordenes_detalle.id_orden')
          ->where('alp_ordenes_detalle.id_producto', '=', $producto->id_producto)
          ->whereDate('alp_ordenes_detalle.created_at', '>=', $this->desde)
          ->whereDate('alp_ordenes_detalle.created_at', '<=', $this->hasta)
          ->first();


          if ($producto->id_combo!=0) {
            
            $producto->nombre_producto='Combo - '.$producto->nombre_producto;
          }

          //dd($p);

          if (isset($p->num_pedidos)) {

            $producto->num_pedidos=$p->num_pedidos;
            
          }else{

            $producto->num_pedidos=0;

          }

          $pro[]=$producto;
            
          }

          //dd($ordenes);

        return view('admin.exports.productosB', [
            'productos' => $pro
        ]);
    }
}




