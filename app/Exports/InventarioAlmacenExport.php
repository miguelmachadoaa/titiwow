<?php

namespace App\Exports;

use App\User;
use App\Models\AlpOrdenes;
use App\Models\AlpProductos;
use App\Models\AlpDetalles;
use App\Models\AlpInventarios;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

use \DB;


class InventarioAlmacenExport implements FromView
{
    
    public function __construct(string $id, array $inventario)
    {
        $this->id = $id;
        $this->inventario = $inventario;
    }



    public function view(): View
    {
       
      $productos=AlpProductos::select(
        'alp_productos.id as id',
        'alp_productos.slug as slug',
        'alp_productos.referencia_producto as referencia_producto',
        'alp_productos.nombre_producto as nombre_producto'
      )
      ->join('alp_almacen_producto', 'alp_productos.id','=', 'alp_almacen_producto.id_producto')
      ->whereNull('alp_almacen_producto.deleted_at')
      ->whereNull('alp_productos.deleted_at')
      ->groupBy('alp_productos.id')
      //->limit(550)
      ->get();

     

      $prods = array();

      //dd($this->inventario);

      foreach ($productos as $p) {

 
            if (isset($this->inventario[$p->id][$this->id])) {
              
               $pro = array(
                'slug' => $p->slug, 
                'sku' => $p->referencia_producto, 
                'nombre' => $p->nombre_producto, 
                'inventario' => $this->inventario[$p->id][$this->id], 
                'tipo' => '0' 
              ); 
            }else{

               $pro = array(
                'slug' => $p->slug, 
                'sku' => $p->referencia_producto, 
                'nombre' => $p->nombre_producto, 
                'inventario' => 0, 
                'tipo' => '0' 
              ); 
            }

            $prods[]=$pro;
          

         
      }

        return view('admin.exports.inventario_almacen', [
            'productos' => $prods
        ]);
    }
}



