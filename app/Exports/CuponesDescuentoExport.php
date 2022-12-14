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


class CuponesDescuentoExport implements FromView
{
    
  public function __construct(string $desde, string $hasta)
  {
      $this->desde = $desde;
      $this->hasta = $hasta;
  }

    public function view(): View
    {
         $productos= AlpDetalles::select(
          'alp_ordenes_detalle.*', 
          'alp_ordenes_detalle.created_at as fecha_pedido',
          'alp_clientes.doc_cliente as doc_cliente',
          'alp_ordenes.ordencompra as ordencompra',
          'alp_ordenes.monto_descuento as monto_descuento',
          'alp_cupones.origen as origen',
          'alp_cupones.valor_cupon as valor_cupon',
          'alp_cupones.tipo_reduccion as tipo_reduccion',
          'alp_ordenes_descuento.codigo_cupon as codigo_cupon',
          'users.id as id_usuario', 
          'users.first_name as first_name', 
          'users.last_name as last_name', 
          'users.email as email', 
           DB::raw('DATE_FORMAT(alp_ordenes_detalle.created_at, "%d/%m/%Y")  as fecha'),
          // DB::raw('sum(alp_ordenes_detalle.cantidad)  as total_cantidad'),
          'alp_productos.presentacion_producto as presentacion_producto',
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
          ->join('alp_clientes', 'alp_ordenes.id_cliente', '=', 'alp_clientes.id_user_client')
          ->join('alp_categorias', 'alp_productos.id_categoria_default', '=', 'alp_categorias.id')
          ->join('alp_marcas', 'alp_productos.id_marca', '=', 'alp_marcas.id')
          ->join('alp_ordenes_descuento', 'alp_ordenes.id', '=', 'alp_ordenes_descuento.id_orden')
          ->leftJoin('alp_cupones', 'alp_ordenes_descuento.codigo_cupon', '=', 'alp_cupones.codigo_cupon')

         // ->groupBy('alp_ordenes_detalle.id_producto')
          ->whereNull('alp_ordenes.factura')
          ->whereIn('alp_ordenes.estatus', [1,2,3,5,6,7])
          ->where('alp_ordenes.estatus_pago','=', '2')
          ->where('alp_ordenes.id_forma_pago', '<>', '3')
          ->whereDate('alp_ordenes_detalle.created_at', '>=', $this->desde)
          ->whereDate('alp_ordenes_detalle.created_at', '<=', $this->hasta)
          ->get();


          $pro = array();

          foreach ($productos as $producto) {

            $p= AlpDetalles::select(
           DB::raw('count(alp_ordenes_detalle.id_orden)  as num_pedidos')
          )
          ->groupBy('alp_ordenes_detalle.id_orden')
          ->where('alp_ordenes_detalle.id_producto', '=', $producto->id_producto)
          ->first();

          //dd($p);

          if (isset($p->num_pedidos)) {

            $producto->num_pedidos=$p->num_pedidos;
            
          }else{

            $producto->num_pedidos=0;

          }

          $pro[]=$producto;
            
          }

          //dd($ordenes);

        return view('admin.exports.cuponesdescuento', [
            'productos' => $pro
        ]);
    }
}




