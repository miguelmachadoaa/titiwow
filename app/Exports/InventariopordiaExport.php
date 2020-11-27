<?php

namespace App\Exports;

use App\User;
use App\Models\AlpOrdenes;
use App\Models\AlpDetalles;
use App\Models\AlpCarritoDetalle;
use App\Models\AlpCarrito;
use App\Models\AlpInventario;
use App\Models\AlpProductos;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

use \DB;


class InventariopordiaExport implements FromView
{
    
    public function __construct(string $desde, string $hasta)
    {
        $this->desde = $desde;
        $this->hasta = $hasta;
    }



    public function view(): View
    {

     $entradas = AlpInventario::groupBy('id_producto')
              ->select("alp_inventarios.*", DB::raw(  "SUM(alp_inventarios.cantidad) as cantidad_total"))
              ->where('alp_inventarios.operacion', '1')
              ->whereDate('alp_inventarios.created_at', '<=', $this->hasta)
              ->get();

              $inv = array();

              foreach ($entradas as $row) {
                
                $inv[$row->id_producto]=$row->cantidad_total;

              }


            $salidas = AlpInventario::select("alp_inventarios.*", DB::raw(  "SUM(alp_inventarios.cantidad) as cantidad_total"))
              ->groupBy('id_producto')
              ->where('operacion', '2')
              ->whereDate('alp_inventarios.created_at', '<=', $this->hasta)
              ->get();

              foreach ($salidas as $row) {

                if (isset( $inv[$row->id_producto])) {
                  $inv[$row->id_producto]= $inv[$row->id_producto]-$row->cantidad_total;
                }else{

                  $inv[$row->id_producto]= 0;
                }
                
                
            }


       $productos = AlpProductos::all();

      
        return view('admin.exports.inventariopordia', [
            'inventario' => $inv, 'productos' => $productos
        ]);
    }
}