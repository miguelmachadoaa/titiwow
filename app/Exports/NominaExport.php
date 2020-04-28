<?php

namespace App\Exports;

use App\User;
use App\Models\AlpAlmacenes;
use App\Models\AlpProductos;
use App\Models\AlpOrdenes;
use App\Models\AlpDetalles;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use \DB;
use Carbon\Carbon;


class NominaExport implements FromView
{
    
    public function __construct(string $desde,string $alm)
    {
        $this->desde = $desde;
        $this->alm = $alm;
    }

    public function view(): View
    {


      $date_desde = Carbon::parse($this->desde.' 16:00:00')->subDay()->toDateTimeString();

      $date_hasta = Carbon::parse($this->desde.' 23:59:59')->toDateTimeString(); 

      //dd($date_hasta); 



        
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
                //)
             ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
          ->join('alp_ordenes_detalle', 'alp_ordenes.id', '=', 'alp_ordenes_detalle.id_orden')
          ->join('alp_productos', 'alp_ordenes_detalle.id_producto', '=', 'alp_productos.id')
          
          ->where('alp_ordenes.id_forma_pago', '=', '3')
          #->where('alp_ordenes.created_at', '>=', $date_desde)
          #->where('alp_ordenes.created_at', '<=', $date_hasta)
          //->groupBy('alp_ordenes.id')
          ->get();

          //dd($ordenes);




        return view('admin.exports.nomina', [
            'ordenes' => $ordenes
        ]);
    }
}