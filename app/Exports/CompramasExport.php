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
use Carbon\Carbon;
use \DB;


class CompramasExport implements FromView
{
    
    public function __construct(string $desde, string $hasta)
    {
        $this->desde = $desde;
        $this->hasta = $hasta;
    }



    public function view(): View
    {

        $date_desde = Carbon::parse($this->desde.'00:00:00')->subDay()->toDateTimeString();

        $date_hasta = Carbon::parse($this->hasta.' 23:59:59')->toDateTimeString();

         $ordenes = AlpOrdenes::select(
          'alp_ordenes.id as id',
          'alp_ordenes.origen as origen', 
          'alp_ordenes.estado_compramas as estado_compramas',
          'alp_ordenes.estatus as estatus', 
          'alp_ordenes.estatus_pago as estatus_pago', 
          'alp_ordenes.ordencompra as ordencompra', 
          'alp_ordenes.monto_total as monto_total', 
          'alp_ordenes.factura as factura', 
          'alp_ordenes.referencia as referencia', 
          'alp_ordenes.tracking as tracking', 
          'alp_ordenes.id_forma_envio as id_forma_envio', 
          'alp_ordenes.id_forma_pago as id_forma_pago', 
          'alp_ordenes.id_almacen as id_almacen', 
          'alp_ordenes.id_address as id_address', 
          'alp_ordenes.envio_compramas as envio_compramas', 
          'alp_ordenes.created_at as created_at', 
          'alp_clientes.telefono_cliente as telefono_cliente',
          'users.first_name as first_name', 
          'users.last_name as last_name')
          ->join('users', 'alp_ordenes.id_cliente', '=', 'users.id')
          ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
          ->whereNotNull('alp_ordenes.estado_compramas')
          ->where('alp_ordenes.estado_compramas', '<>', '200')
          ->whereDate('alp_ordenes.created_at', '>=', $this->desde)
          ->whereDate('alp_ordenes.created_at', '<=', $this->hasta)
          ->groupBy('alp_ordenes.id')
         ->orderBy('alp_ordenes.id', 'desc')
          ->get();


          foreach ($ordenes as $o) {

            $mensaje='';

            if (isset($o->json)) {

              $data=json_decode($o->json);

              if (isset($data->mensaje)) {
                
                $mensaje=$mensaje.$data->mensaje;
              }

              if (isset($data->causa)) {

                foreach ($data->causa as $key => $value) {

                    if (is_object(($value))) {

                      if(isset($value->mensaje)){

                         $mensaje=$mensaje.' '.$value->mensaje;

                      }
                      # code...
                    }else{

                      $mensaje=$mensaje.' '.$value;
                    }
                  # code...
                }

              }
                
            }


            $o->mensaje=$mensaje;

            
          }

          //dd($ordenes);

        return view('admin.exports.compramas', [
            'ordenes' => $ordenes
        ]);
    }
}




