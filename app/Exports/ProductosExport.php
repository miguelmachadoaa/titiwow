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


class ProductosExport implements FromView
{
    
    public function __construct(string $desde, string $hasta, string $producto, string $marca, string $categoria, string $almacen)
    {
        $this->desde = $desde;
        $this->hasta = $hasta;
        $this->producto = $producto;
        $this->marca = $marca;
        $this->categoria = $categoria;
        $this->almacen = $almacen;
    }



    public function view(): View
    {
         $p= AlpDetalles::select(
          'alp_ordenes_detalle.*', 
           DB::raw('DATE_FORMAT(alp_ordenes_detalle.created_at, "%d/%m/%Y")  as fecha'),
          // DB::raw('sum(alp_ordenes_detalle.cantidad)  as total_cantidad'),
          'alp_productos.nombre_producto as nombre_producto',
          'alp_productos.presentacion_producto as presentacion_producto',
          'alp_direcciones.barrio_address as barrio_address',
          'alp_productos.referencia_producto as referencia_producto',
          'alp_categorias.nombre_categoria as nombre_categoria',
          'alp_marcas.nombre_marca as nombre_marca',
          'alp_almacenes.nombre_almacen as nombre_almacen',
          'users.first_name as first_name',
          'users.last_name as last_name',
          'users.email as email'
          )
          ->join('alp_productos', 'alp_ordenes_detalle.id_producto', '=', 'alp_productos.id')
          ->join('alp_ordenes', 'alp_ordenes_detalle.id_orden', '=', 'alp_ordenes.id')
          ->join('alp_almacenes', 'alp_ordenes.id_almacen', '=', 'alp_almacenes.id')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_direcciones', 'alp_ordenes.id_address', '=', 'alp_direcciones.id')
          ->join('alp_categorias', 'alp_productos.id_categoria_default', '=', 'alp_categorias.id')
          ->join('alp_marcas', 'alp_productos.id_marca', '=', 'alp_marcas.id')
         // ->whereIn('alp_ordenes.estatus', ['1','2','3','5','6','7'])
          ->where('alp_productos.tipo_producto', '=', '1' )
          ->where('alp_ordenes_detalle.id_combo', '=', '0' )
          ->groupBy('alp_ordenes_detalle.id')

          ->whereDate('alp_ordenes_detalle.created_at', '>=', $this->desde)
          ->whereDate('alp_ordenes_detalle.created_at', '<=', $this->hasta);

          if($this->producto!=0){
            $p->where('alp_productos.id', '=', $this->producto);
          }


          if($this->categoria!=0){
            $p->where('alp_productos.id_categoria_default', '=', $this->categoria);
          }


          if($this->marca!=0){
            $p->where('alp_productos.id_marca', '=', $this->marca);
          }

          if($this->almacen!=0){
            $p->where('alp_ordenes.id_almacen', '=', $this->almacen);
          }



          /*if($this->agrupar==0){
            $p->groupBy('alp_ordenes_detalle.id_producto');
          }*/

       # dd($p->toSql());

          $productos=$p->get();

     #    dd(json_encode($productos));


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

        return view('admin.exports.productos', [
            'productos' => $pro
        ]);
    }
}




