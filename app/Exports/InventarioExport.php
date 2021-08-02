<?php

namespace App\Exports;

use App\User;
use App\Models\AlpOrdenes;
use App\Models\AlpAlmacenProducto;
use App\Models\AlpDetalles;
use App\Models\AlpCarritoDetalle;
use App\Models\AlpCarrito;
use App\Models\AlpProductos;
use App\Models\AlpInventario;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

use \DB;


class InventarioExport implements FromView
{
    
    public function __construct()
    {
        
    }



    public function view(): View
    {


       $entradas = AlpInventario::groupBy('id_producto')
              ->select("alp_inventarios.*", DB::raw(  "SUM(alp_inventarios.cantidad) as cantidad_total"))
              ->where('alp_inventarios.operacion', '1')
              ->where('alp_inventarios.id_almacen', '1')
              ->get();

              $inv = array();

              foreach ($entradas as $row) {
                
                $inv[$row->id_producto]=$row->cantidad_total;

              }


            $salidas = AlpInventario::select("alp_inventarios.*", DB::raw(  "SUM(alp_inventarios.cantidad) as cantidad_total"))
              ->groupBy('id_producto')
              ->where('operacion', '2')
              ->where('alp_inventarios.id_almacen', '1')
              ->get();

              foreach ($salidas as $row) {
                
                $inv[$row->id_producto]= $inv[$row->id_producto]-$row->cantidad_total;
            }


             $productos=AlpAlmacenProducto::select( 
        'alp_productos.id as id',
        'alp_productos.nombre_producto as nombre_producto',
        'alp_productos.referencia_producto_sap as referencia_producto_sap',
        'alp_productos.referencia_producto as referencia_producto',
        'alp_productos.presentacion_producto as presentacion_producto',
        'alp_productos.imagen_producto as imagen_producto'
      )
       ->join('alp_productos', 'alp_almacen_producto.id_producto', '=', 'alp_productos.id')
       ->where('alp_almacen_producto.id_almacen','=', '1')
       ->whereNull('alp_almacen_producto.deleted_at')
       ->groupBy('alp_productos.id')
       ->get();

      
        return view('admin.exports.inventario', [
            'inventario' => $inv, 'productos' => $productos
        ]);
    }
}