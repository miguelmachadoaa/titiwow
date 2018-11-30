<?php

namespace App\Exports;

use App\User;
use App\Models\AlpOrdenes;
use App\Models\AlpDetalles;
use App\Models\AlpCarritoDetalle;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

use \DB;


class CarritoExport implements FromView
{
    
    public function __construct(string $desde, string $hasta)
    {
        $this->desde = $desde;
        $this->hasta = $hasta;
    }



    public function view(): View
    {
        $carrito= AlpCarritoDetalle::select(
          'alp_carrito_detalle.id as id', 
          'alp_carrito_detalle.id_producto as id_producto', 
          'alp_productos.nombre_producto as nombre_producto',
           DB::raw('DATE_FORMAT(alp_carrito_detalle.created_at, "%d/%m/%Y")  as fecha'),
          'alp_carrito_detalle.cantidad as cantidad')
          ->join('alp_productos', 'alp_carrito_detalle.id_producto', '=', 'alp_productos.id')
          
          ->whereDate('alp_carrito_detalle.created_at', '>=', $this->desde)
          ->whereDate('alp_carrito_detalle.created_at', '<=', $this->hasta)->get();

          //dd($ordenes);

        return view('admin.exports.carrito', [
            'carrito' => $carrito
        ]);
    }
}