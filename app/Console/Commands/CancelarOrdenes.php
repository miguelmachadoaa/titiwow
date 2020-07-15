<?php

namespace App\Console\Commands;

use App\User;
use App\Models\AlpOrdenes;
use App\Models\AlpOrdenesDescuento;
use App\Models\AlpDetalles;
use App\Models\AlpInventario;
use App\Models\AlpConfiguracion;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Mail;
use DB;
use Illuminate\Support\Facades\Log;

use Illuminate\Console\Command;

class CancelarOrdenes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cancelar:ordenes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancelar ordenes que esten vencidas segun tiempo establecido en configiracion ';

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
        //
        $configuracion=AlpConfiguracion::where('id', '1')->first();

        $date = Carbon::now();

        $hoy=$date->format('Y-m-d');

        $ordenes =  DB::table('alp_ordenes')->select('alp_ordenes.*')
          ->where('alp_ordenes.estatus','=', 8)
          ->get();

        foreach ($ordenes  as $orden) {

            $date = Carbon::parse($orden->created_at); 

            $now = Carbon::now();

            $diff = $date->diffInHours($now); 

            //dd($diff);

            if ($diff>$configuracion->vence_ordenes) {
                # code...

                $ord=AlpOrdenes::where('id', $orden->id)->first();

                $arrayName = array('estatus' => 4, 'estatus_pago'=>3 );

                $ord->update($arrayName);

                $detalles=AlpDetalles::where('id_orden', $orden->id)->get();

                foreach ($detalles as $detalle) {

                      $data_inventario = array(
                        'id_producto' => $detalle->id_producto, 
                        'cantidad' =>$detalle->cantidad, 
                        'operacion' =>'1', 
                        'id_user' =>'1'
                      );

                      AlpInventario::create($data_inventario);
                    
                }

                $descuentos=AlpOrdenesDescuento::where('id_orden',$orden->id)->get();

                foreach ($descuentos as $desc) {
                    
                    $d=AlpOrdenesDescuento::where('id', $desc->id)->first();

                    $d->delete();

                }

            }

        }

    }
}
