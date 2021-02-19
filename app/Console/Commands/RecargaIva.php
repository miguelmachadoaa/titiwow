<?php

namespace App\Console\Commands;

use App\User;
use App\Models\AlpOrdenes;
use App\Models\AlpOrdenesIva;
use App\Models\AlpOrdenesDescuento;
use App\Models\AlpDetalles;
use App\Models\AlpInventario;
use App\Models\AlpConfiguracion;
use App\Models\AlpSaldo;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Mail;
use DB;

use Illuminate\Console\Command;

class RecargaIva extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recarga:iva';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'LLevar el saldo vencido a 0';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
       $ordenes=AlpOrdenes::where('id','>', '1788')->get();

       foreach ($ordenes as $o) {


            $detalles=AlpDetalles::select('alp_ordenes_detalle.*', 'alp_productos.id_impuesto as id_impuesto')
            ->join('alp_productos', 'alp_ordenes_detalle.id_producto', '=', 'alp_productos.id')
            ->where('alp_ordenes_detalle.id_orden', $o->id)->get();

            //dd($detalles);

            $total_venta=0;
            $base_impuesto=0;
            $valor_impuesto=0;
            $monto_impuesto=0;
            $total_descuentos=0;


            $descuentos=AlpOrdenesDescuento::where('id_orden', $o->id)->get();

            foreach ($descuentos as $pago) {

              $total_descuentos=$total_descuentos+$pago->monto_descuento;

            }


            foreach ($detalles as $d) {

                $total_venta=$total_venta+($d->precio_unitario*$d->cantidad);

                echo  'id_detalle '.$d->id.' - impuesto '.$d->id_impuesto.'  /  ';

                if($d->id_impuesto=='1'){

                    $valor_impuesto=$d->valor_impuesto;

                    $base_impuesto=$base_impuesto+($d->precio_unitario*$d->cantidad);

                    $monto_impuesto=$monto_impuesto+(($d->precio_unitario*$d->cantidad)/(1+$valor_impuesto))*$valor_impuesto;


                    echo  'base_impuesto '.$base_impuesto.' - monto_impuesto '.$monto_impuesto.'  /  ';

                }
                            
            }   



            $resto=$total_venta-$total_descuentos;

         if ($resto<$base_impuesto) {

            $base_impuesto=$resto;

            $impuesto=($resto/(1+$valor_impuesto))*$valor_impuesto;

          }


            $base_impuesto=$base_impuesto/1.19;         

            $data = array(
                'id_orden' => $o->id,
                'base_impuesto'=>$base_impuesto,
                'valor_impuesto'=>$valor_impuesto,
                'monto_impuesto'=>$monto_impuesto,
                'id_user'=>'1'
            );



            AlpOrdenesIva::create($data);


           
       }

    }


    private function getSaldo()
    {
       
      $entradas = AlpSaldo::groupBy('id_cliente')
              ->select("alp_saldo.*", DB::raw(  "SUM(alp_saldo.saldo) as cantidad_total"))
              ->where('alp_saldo.operacion', '1')
              ->get();

              $inv = array();

              foreach ($entradas as $row) {
                
                $inv[$row->id_cliente]=$row->cantidad_total;

              }

            $salidas = AlpSaldo::groupBy('id_cliente')
              ->select("alp_saldo.*", DB::raw(  "SUM(alp_saldo.saldo) as cantidad_total"))
              ->where('alp_saldo.operacion', '2')
              ->get();

              foreach ($salidas as $row) {
                
                $inv[$row->id_cliente]= $inv[$row->id_cliente]-$row->cantidad_total;

            }

            return $inv;
      
    }

}