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


class ProductostotalesExport implements FromView
{
    
    public function __construct(string $origen, string $desde, string $hasta, string $almacen)
    {
        $this->origen = $origen;
        $this->desde = $desde;
        $this->hasta = $hasta;
        $this->almacen = $almacen;
    }



    public function view(): View
    {

     // dd($this->desde.'-'.$this->hasta);
         $c= AlpDetalles::select(
          'alp_ordenes_detalle.*', 
           DB::raw('DATE_FORMAT(alp_ordenes_detalle.created_at, "%d/%m/%Y")  as fecha'),
          // DB::raw('sum(alp_ordenes_detalle.cantidad)  as total_cantidad'),
           'alp_productos.presentacion_producto as presentacion_producto',
          'alp_productos.nombre_producto as nombre_producto',
          'alp_productos.referencia_producto as referencia_producto',
          'alp_productos.referencia_producto_sap as referencia_producto_sap',
          'alp_categorias.nombre_categoria as nombre_categoria',
          'alp_almacenes.nombre_almacen as nombre_almacen',
          'alp_ordenes.origen as origen',
          'alp_marcas.nombre_marca as nombre_marca'
          )
          ->join('alp_ordenes', 'alp_ordenes_detalle.id_orden', '=', 'alp_ordenes.id')
          ->join('alp_almacenes', 'alp_ordenes.id_almacen', '=', 'alp_almacenes.id')
          ->join('alp_productos', 'alp_ordenes_detalle.id_producto', '=', 'alp_productos.id')
          ->join('alp_categorias', 'alp_productos.id_categoria_default', '=', 'alp_categorias.id')
          ->join('alp_marcas', 'alp_productos.id_marca', '=', 'alp_marcas.id')
         // ->groupBy('alp_ordenes_detalle.id_producto')
          ->whereDate('alp_ordenes_detalle.created_at', '>=', $this->desde)
          ->whereDate('alp_ordenes_detalle.created_at', '<=', $this->hasta)
          ->whereIn('alp_ordenes.estatus', [1,2,3,5,6,7])
          ->where('alp_ordenes.id_forma_pago', '<>', '3');



          if ($this->origen==-1) {
            # code...
          }else{

            $c->where('alp_ordenes.origen', '=', $this->origen);
          }



           if ($this->almacen==0) {
            # code...
          }else{

            $c->where('alp_ordenes.id_almacen', '=', $this->almacen);

          }


          $productos=$c->get();




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

          //dd($p);

          if (isset($p->num_pedidos)) {

            $producto->num_pedidos=$p->num_pedidos;
            
          }else{

            $producto->num_pedidos=0;


          }

          $pro[]=$producto;


            
          }

          //dd($ordenes);

        return view('admin.exports.productostotales', [
            'productos' => $pro
        ]);
    }
}




