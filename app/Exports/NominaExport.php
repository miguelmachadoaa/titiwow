<?php

namespace App\Exports;

use App\User;
use App\Models\AlpProductos;
use App\Models\AlpOrdenes;
use App\Models\AlpDetalles;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use \DB;

class NominaExport implements FromView
{
    
    public function __construct()
    {

    }

    public function view(): View
    {

        
             $ordenes = AlpOrdenes::select(
                'alp_ordenes.*', 
                'alp_ordenes_detalle.cantidad as cantidad',
                'alp_clientes.telefono_cliente as telefono_cliente',
                'alp_clientes.doc_cliente as doc_cliente',
                'alp_clientes.cod_oracle_cliente as cod_oracle_cliente',
                'alp_productos.nombre_producto as nombre_producto',
                'alp_productos.referencia_producto as referencia_producto',
                'users.first_name as first_name', 
                'users.last_name as last_name' )
          ->join('alp_ordenes_detalle', 'alp_ordenes.id', '=', 'alp_ordenes_detalle.id_orden')
          ->join('alp_productos', 'alp_ordenes_detalle.id_producto', '=', 'alp_productos.id')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
          ->where('alp_ordenes.id_forma_pago', '=', '3')
          ->groupBy('alp_ordenes.id')
          ->get();




        return view('admin.exports.nomina', [
            'ordenes' => $ordenes
        ]);
    }
}