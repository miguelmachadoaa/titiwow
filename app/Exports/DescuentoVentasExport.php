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
          'alp_ordenes.id as id',
          
          'alp_ordenes.monto_descuento as monto_descuento',
          'alp_ordenes.base_impuesto as base_impuesto',
          'alp_ordenes.valor_impuesto as valor_impuesto',
          'alp_ordenes.monto_impuesto as monto_impuesto',
          'alp_ordenes.origen as origen', 
          'alp_ordenes.referencia as referencia', 
          'alp_ordenes.monto_total as monto_total',
          'alp_ordenes_descuento.codigo_cupon as codigo_cupon',
          'alp_productos.referencia_producto as referencia_producto',
          'alp_productos.referencia_producto_sap as referencia_producto_sap',
          DB::raw('count(alp_ordenes_detalle.cantidad) as total_articulos'),
          DB::raw('DATE_FORMAT(alp_ordenes.created_at, "%d/%m/%Y")  as fecha'),
          'alp_clientes.doc_cliente as doc_cliente',
          'users.id as id_usuario', 
          'users.first_name as first_name', 
          'users.last_name as last_name') 
          
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_clientes', 'alp_ordenes.id_cliente', '=', 'alp_clientes.id_user_client')
          ->join('alp_ordenes_detalle', 'alp_ordenes.id', '=', 'alp_ordenes_detalle.id_orden')
          ->join('alp_productos', 'alp_ordenes_detalle.id_producto', '=', 'alp_productos.id')
          ->leftJoin('alp_ordenes_descuento', 'alp_ordenes.id', '=', 'alp_ordenes_descuento.id_orden')
          ->groupBy('alp_ordenes.id')
          ->where('alp_ordenes.estatus_pago','=', '2')
          ->where('alp_ordenes.id_forma_pago', '<>', '3')
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


          $ordenes=$o->get();
         

        return view('admin.exports.ventasdescuento', [
            'ventas' => $ordenes
        ]);
    }
}

