<?php

namespace App\Exports;

use App\User;
use App\Models\AlpOrdenes;
use App\Models\AlpDetalles;
use App\Models\AlpCarritoDetalle;
use App\Models\AlpCarrito;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

use \DB;


class LifemilesExport implements FromView
{
    
    public function __construct(object $cliente, object $disponible, object $history)
    {
        $this->cliente = $cliente;
        $this->disponible = $disponible;
        $this->history = $history;
    }



    public function view(): View
    {

      
        return view('admin.exports.bono', [
            'cliente' => $this->cliente,
            'disponible' => $this->disponible,
            'history' => $this->history,
        ]);
    }
}