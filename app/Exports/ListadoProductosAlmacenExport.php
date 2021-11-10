<?php

namespace App\Exports;

use App\User;
use App\Models\AlpAlmacenes;
use App\Models\AlpProductos;
use App\Models\AlpOrdenes;
use App\Models\AlpDetalles;
use App\Models\AlpCombosProductos;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use \DB;

class ListadoProductosAlmacenExport implements FromView
{
    
    public function __construct(string $estado,string $tproducto,string $almacen,array $inventario)
    {

       $this->estado=$estado;
       $this->tproducto=$tproducto;
       $this->inventario=$inventario;
       $this->almacen=AlpAlmacenes::where('id', $almacen)->first();
       

    }

    public function view(): View
    {

        if ($this->estado=='0') {
         
            if ($this->tproducto=='0') {
         
                $productos=AlpProductos::select(
                    'alp_productos.id', 
                    'alp_almacenes.codigo_almacen as codigo_almacen',
                    'alp_productos.nombre_producto as nombre_producto1',
                    'alp_productos.precio_base as precio_base1',
                    'alp_productos.descripcion_larga as descripcion_larga1',

                    'alp_productos.descripcion_corta as descripcion_corta1',

                    'alp_productos.presentacion_producto as presentacion_producto1',
                    'alp_productos.referencia_producto as referencia_producto1',
                    'alp_productos.referencia_producto_sap as referencia_producto_sap1'
                )
                ->join('alp_almacen_producto', 'alp_productos.id','=', 'alp_almacen_producto.id_producto')
                ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
                ->where('alp_almacenes.id','=', $this->almacen->id)
                ->groupBy('alp_almacen_producto.id')
                ->get();   
            
            }else{

                $productos=AlpProductos::select(
                    'alp_productos.id', 
                    'alp_almacenes.codigo_almacen as codigo_almacen',
                    'alp_productos.nombre_producto as nombre_producto1',
                    'alp_productos.precio_base as precio_base1',
                    'alp_productos.descripcion_larga as descripcion_larga1',

                    'alp_productos.descripcion_corta as descripcion_corta1',

                    'alp_productos.presentacion_producto as presentacion_producto1',
                    'alp_productos.referencia_producto as referencia_producto1',
                    'alp_productos.referencia_producto_sap as referencia_producto_sap1'
                )
                ->join('alp_almacen_producto', 'alp_productos.id','=', 'alp_almacen_producto.id_producto')
                ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
                ->where('alp_almacenes.id','=', $this->almacen->id)
                ->groupBy('alp_almacen_producto.id')
                ->where('tipo_producto', $this->tproducto)->get();
            }
        
        }else{


            if ($this->tproducto=='0') {
         
                $productos=AlpProductos::select(
                    'alp_productos.id', 
                    'alp_almacenes.codigo_almacen as codigo_almacen',
                    'alp_productos.nombre_producto as nombre_producto1',
                    'alp_productos.precio_base as precio_base1',
                    'alp_productos.descripcion_larga as descripcion_larga1',

                    'alp_productos.descripcion_corta as descripcion_corta1',

                    'alp_productos.presentacion_producto as presentacion_producto1',
                    'alp_productos.referencia_producto as referencia_producto1',
                    'alp_productos.referencia_producto_sap as referencia_producto_sap1'
                )
                ->join('alp_almacen_producto', 'alp_productos.id','=', 'alp_almacen_producto.id_producto')
                ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
                ->where('alp_almacenes.id','=', $this->almacen->id)
                ->groupBy('alp_almacen_producto.id')
                ->where('estado_registro', $this->estado)->get();   
            
            }else{
                
                $productos=AlpProductos::select(
                    'alp_productos.id', 
                    'alp_almacenes.codigo_almacen as codigo_almacen',
                    'alp_productos.nombre_producto as nombre_producto1',
                    'alp_productos.precio_base as precio_base1',
                    'alp_productos.descripcion_larga as descripcion_larga1',

                    'alp_productos.descripcion_corta as descripcion_corta1',

                    'alp_productos.presentacion_producto as presentacion_producto1',
                    'alp_productos.referencia_producto as referencia_producto1',
                    'alp_productos.referencia_producto_sap as referencia_producto_sap1'
                )
                ->join('alp_almacen_producto', 'alp_productos.id','=', 'alp_almacen_producto.id_producto')
                ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
                ->where('alp_almacenes.id','=', $this->almacen->id)
                ->groupBy('alp_almacen_producto.id')
                ->where('estado_registro', $this->estado)
                ->where('tipo_producto', $this->tproducto)->get();
            }

        }

        if ($this->tproducto=='2') {
            
            $combo = array();

            foreach($productos as $row) {

                $combo =  AlpCombosProductos::select(
                    'alp_combos_productos.*', 
                    'alp_almacenes.codigo_almacen as codigo_almacen',
                    'alp_productos.nombre_producto as nombre_producto1',
                    'alp_productos.precio_base as precio_base1',
                    'alp_productos.descripcion_larga as descripcion_larga1',

                    'alp_productos.descripcion_corta as descripcion_corta1',

                    'alp_productos.presentacion_producto as presentacion_producto1',
                    'alp_productos.referencia_producto as referencia_producto1',
                    'alp_productos.referencia_producto_sap as referencia_producto_sap1'
                )
                ->join('alp_productos', 'alp_combos_productos.id_producto','=', 'alp_productos.id')
                ->join('alp_almacen_producto', 'alp_productos.id','=', 'alp_almacen_producto.id_producto')
                ->join('alp_almacenes', 'alp_almacen_producto.id_almacen', '=', 'alp_almacenes.id')
                ->where('alp_combos_productos.id_combo','=',$row->id)
                ->where('alp_almacenes.id','=', $this->almacen->id)
                ->groupBy('alp_almacen_producto.id')
                ->get();

                $row->productos = $combo;
            }
        }

        #dd(json_encode($productos));


        return view('admin.exports.listadoalmacen', [
            'productos' => $productos,
            'inventario' => $this->inventario,
            'almacen' => $this->almacen
        ]);
    }
}