<?php

namespace App\Exports;

use App\User;
use App\Models\AlpOrdenes;
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


       $productos = AlpProductos::all();

      
        return view('admin.exports.inventario', [
            'inventario' => $inv, 'productos' => $productos
        ]);
    }
}