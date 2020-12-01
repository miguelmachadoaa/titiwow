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
    
    public function __construct(string $desde, string $id_almacen, string $id_producto)
    {
        $this->desde = $desde;
        $this->id_almacen = $id_almacen;
        $this->id_producto = $id_producto;
    }



    public function view(): View
    {

      if ($this->id_almacen=='0') {

        $almacenes=AlpAlmacenws::where('estado_registro', '=', '1')->get();

      }else{

         $almacenes=AlpAlmacenes::where('id', '=', $this->id_almacen)->get();

      }


      foreach ($almacenes as $a) {

        
        if ($this->id_producto=='0') {

          $producto=AlpProductos::get();

        }else{

           $producto=AlpProductos::where('id', '=', $this->id_producto)->get();

        }

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