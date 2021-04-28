<?php

namespace App\Console\Commands;

use App\Models\AlpConfiguracion;
use App\Models\AlpOrdenes;
use App\Models\AlpOrdenesHistory;
use App\Models\AlpEnvios;
use App\Exports\CronLogisticaExport;
use Maatwebsite\Excel\Facades\Excel;

use Carbon\Carbon;
use Mail;



use Illuminate\Console\Command;

class PedidosEnviados extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pedidos:enviados';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envio de pedidos para ser entregados diariamente';

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


        $ordenes=AlpOrdenes::where('alp_ordenes.estatus', '5')->get();

        $ban=0;

        foreach ($ordenes as $orden) {

            if ($ban<50) {

                $envio=AlpEnvios::where('id_orden','=', $orden->id)->where('estatus', '=', '7')->first();

                if (isset($envio->id)) {

                    echo $orden->id.'-';

                    $ban=$ban+1;

                    $data_update = array('estatus'=>3);

                    $orden->update($data_update);

                    $data_history = array(
                      'id_orden' => $orden->id, 
                      'id_status' => '3', 
                      'notas' => 'Orden Entregada Notificada por Compramas y actualizada por Cron',
                      'id_user' => 1
                    );

                    $history=AlpOrdenesHistory::create($data_history);
                    
                }

            
            }
        }
        
    }
}
