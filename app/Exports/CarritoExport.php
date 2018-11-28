<?php

namespace App\Exports;

use App\User;
use App\Models\AlpOrdenes;
use App\Models\AlpDetalles;
use App\Models\AlpCarritoDetalle;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

use Carbon\Carbon;



class CarritoExport implements FromQuery
{
    use Exportable;

    public function __construct(string $desde, string $hasta)
    {
        $this->desde = $desde;
        $this->hasta = $hasta;
    }



    public function query()
    {
         /*AlpOrdenes::query()->whereDate('created_at', '>', $this->desde)->whereDate('created_at', '<', $this->hasta)->where('id_cliente','=', $this->user);*/

         return AlpCarritoDetalle::query()->select('alp_carrito_detalle.id as id', 'alp_productos.nombre_producto as nombre_producto', 'alp_carrito_detalle.cantidad as cantidad')
          ->join('alp_productos', 'alp_carrito_detalle.id_producto', '=', 'alp_productos.id')
          
          ->whereDate('alp_carrito_detalle.created_at', '>', $this->desde)
          ->whereDate('alp_carrito_detalle.created_at', '<', $this->hasta);
          
    }

    
}
