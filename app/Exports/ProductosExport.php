<?php

namespace App\Exports;

use App\User;
use App\Models\AlpOrdenes;
use App\Models\AlpDetalles;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

use Carbon\Carbon;



class ProductosExport implements FromQuery
{
    use Exportable;

    public function __construct(string $desde, string $hasta, int $producto)
    {
        $this->desde = $desde;
        $this->hasta = $hasta;
        $this->producto = $producto;
    }



    public function query()
    {
         /*AlpOrdenes::query()->whereDate('created_at', '>', $this->desde)->whereDate('created_at', '<', $this->hasta)->where('id_cliente','=', $this->user);*/

        return AlpDetalles::query()
          ->whereDate('created_at', '>', $this->desde)
          ->whereDate('created_at', '<', $this->hasta)
          ->where('id_producto','=', $this->producto);
          
    }

    
}
