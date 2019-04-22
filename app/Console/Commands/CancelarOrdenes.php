<?php

namespace App\Console\Commands;

use App\User;
use App\Models\AlpOrdenes;
use App\Models\AlpConfiguracion;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Mail;
use DB;



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

            $arrayName = array('estatus' => 4 );

            $ord->update($arrayName);

        }



      }



    }
}
