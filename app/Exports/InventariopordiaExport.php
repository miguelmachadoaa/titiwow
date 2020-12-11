<?php

namespace App\Exports;

use App\User;
use App\Models\AlpOrdenes;
use App\Models\AlpDetalles;
use App\Models\AlpCarritoDetalle;
use App\Models\AlpCarrito;
use App\Models\AlpInventario;
use App\Models\AlpProductos;
use App\Models\AlpAlmacenes;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

use \DB;


class InventariopordiaExport implements FromView
{
    
    public function __construct(string $desde, string $id_almacen)
    {
        $this->desde = $desde;
        $this->id_almacen = $id_almacen;
    }



    public function view(): View
    {

      if ($this->id_almacen=='0') {

        $almacenes=AlpAlmacenes::where('estado_registro', '=', '1')->get();

      }else{

         $almacenes=AlpAlmacenes::where('id', '=', $this->id_almacen)->get();

      }


      foreach ($almacenes as $a) {

        
        $producto=AlpProductos::select('alp_productos.*')
        ->join('alp_inventarios', 'alp_productos.id', '=', 'alp_inventarios.id_producto')
        ->where('alp_inventarios.id_almacen', '=', $a->id)
        ->whereDate('alp_inventarios.created_at', '=', $this->desde)
        ->groupBy('alp_productos.id')
        ->get();

        $a->producto=$producto;

        foreach ($a->producto as $p) {

          $inventario = AlpInventario:: select("alp_inventarios.*")
          ->whereDate('alp_inventarios.created_at', '=', $this->desde)
          ->where('alp_inventarios.id_producto', '=', $p->id)
          ->where('alp_inventarios.id_almacen', '=', $a->id)
          ->withTrashed()->get();

          $p->inventario=$inventario;

          # code...
        }



      }




      

     // dd($producto);

      
        return view('admin.exports.inventariopordia', [
           'almacenes' => $almacenes
        ]);
    }
}