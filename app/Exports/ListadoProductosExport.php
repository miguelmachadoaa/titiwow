<?php

namespace App\Exports;

use App\User;
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

class ListadoProductosExport implements FromView
{
    
    public function __construct(string $estado,string $tproducto)
    {

       $this->estado=$estado;
       $this->tproducto=$tproducto;
       

    }

    public function view(): View
    {

        if ($this->estado=='0') {
         
            if ($this->tproducto=='0') {
         
                $productos=AlpProductos::get();   
            
            }else{
                $productos=AlpProductos::where('tipo_producto', $this->tproducto)->get();
            }
        
        }else{


            if ($this->tproducto=='0') {
         
                $productos=AlpProductos::where('estado_registro', $this->estado)->get();   
            
            }else{
                $productos=AlpProductos::where('estado_registro', $this->estado)->where('tipo_producto', $this->tproducto)->get();
            }

        }

        if ($this->tproducto=='2') {
            
            $combo = array();

            foreach($productos as $row) {
                $combo =  AlpCombosProductos::select(
                    'alp_combos_productos.*', 
                    'alp_productos.nombre_producto as nombre_producto1',
                    'alp_productos.presentacion_producto as presentacion_producto1',
                    'alp_productos.referencia_producto as referencia_producto1',
                    'alp_productos.referencia_producto_sap as referencia_producto_sap1'
                )
                ->join('alp_productos', 'alp_combos_productos.id_producto','=', 'alp_productos.id')
                ->where('alp_combos_productos.id_combo','=',$row->id)
                ->get();
                $row->productos = $combo;
            }
        }


        return view('admin.exports.listadoproductos', [
            'productos' => $productos
        ]);
    }
}