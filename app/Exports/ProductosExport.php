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
    
    public function __construct(string $desde, string $hasta, int $producto)
    {
        $this->desde = $desde;
        $this->hasta = $hasta;
        $this->producto = $producto;
    }



    public function view(): View
    {
         $productos= AlpDetalles::select(
          'alp_ordenes_detalle.*', 
           DB::raw('DATE_FORMAT(alp_ordenes_detalle.created_at, "%d/%m/%Y")  as fecha'),
          'alp_productos.nombre_producto as nombre_producto'
          )
          ->join('alp_productos', 'alp_ordenes_detalle.id_producto', '=', 'alp_productos.id')
          ->whereDate('alp_ordenes_detalle.created_at', '>=', $this->desde)
          ->whereDate('alp_ordenes_detalle.created_at', '<=', $this->hasta)
          ->where('id_producto','=', $this->producto)->get();

          //dd($ordenes);

        return view('admin.exports.productos', [
            'productos' => $productos
        ]);
    }
}




