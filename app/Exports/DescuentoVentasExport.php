<?php

namespace App\Exports;

use App\User;
use App\Models\AlpOrdenes;
use App\Models\AlpOrdenesDescuento;
use App\Models\AlpDetalles;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

use \DB;


class DescuentoVentasExport implements FromView
{
    
    public function __construct(string $origen, string $desde, string $hasta, string $id_almacen)
    {
        $this->origen = $origen;
        $this->desde = $desde;
        $this->hasta = $hasta;
        $this->id_almacen = $id_almacen;
    }



    public function view(): View
    {


      $ordenes=AlpOrdenes::select(
        'alp_ordenes.id',
        'alp_ordenes.monto_descuento as monto_descuento',
        'alp_ordenes.base_impuesto as base_impuesto',
        'alp_ordenes.valor_impuesto as valor_impuesto',
        'alp_ordenes.monto_impuesto as monto_impuesto',
        'alp_ordenes.origen as origen', 
        'alp_ordenes.referencia as referencia', 
        'alp_ordenes.monto_total as monto_total',
        DB::raw('DATE_FORMAT(alp_ordenes.created_at, "%d/%m/%Y")  as fecha'),
        'alp_clientes.doc_cliente as doc_cliente',
        'users.id as id_usuario', 
         'users.first_name as first_name', 
        'users.last_name as last_name'

      )
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_clientes', 'alp_ordenes.id_cliente', '=', 'alp_clientes.id_user_client')
          ->groupBy('alp_ordenes.id')
          ->where('alp_ordenes.estatus_pago','=', '2')
          ->where('alp_ordenes.id_forma_pago', '<>', '3')
          ->whereDate('alp_ordenes.created_at', '>=', $this->desde)
          ->whereDate('alp_ordenes.created_at', '<=', $this->hasta)
          ->get();

          foreach ($ordenes as $ord) {
           
            $descuento=AlpOrdenesDescuento::where('id_orden','=', $ord->id)->first();

            if (isset($descuento->id)) {

              $ord->codigo_cupon=$descuento->codigo_cupon;
              # code...
            }

            $detalles=AlpDetalles::where('id_orden', '=', $ord->id)->get();

            $ord->total_articulos=count($detalles);

          }
         
        return view('admin.exports.ventasdescuento', [
            'ventas' => $ordenes
        ]);
    }
}

