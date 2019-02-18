<?php

namespace App\Console\Commands;

use App\User;
use App\Models\AlpConfiguracion;
use App\Models\AlpClientes;
use App\Models\AlpClientesHistory;
use Cartalyst\Sentinel\Laravel\Facades\Activation;


use App\Exports\CronNuevosUsuarios;
use Maatwebsite\Excel\Facades\Excel;

use Carbon\Carbon;
use Mail;
use DB;



use Illuminate\Console\Command;

class ActivarUsuarios extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'usuarios:activar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Proceso para activar los usuarios durante las noches ';

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


        $clientes=AlpClientes::whereNull('cod_oracle_cliente')->get();

        foreach ($clientes as $cli ) {

            $user_id = $cli->id_user_client;

            $user=User::where('id', $user_id)->first();

            Activation::remove($user);
                            //add new record
            Activation::create($user);

            $activation = Activation::exists($user);

            if ($activation) {

                Activation::complete($user, $activation->code);

            }

            $data = array(
                'estado_masterfile' => 1,
                'cod_oracle_cliente' => $cli->doc_cliente
            );

            $data_history = array(
                'id_cliente' => $cli->id_user_client, 
                'estatus_cliente' => 'activado',
                'notas' => 'Activado mediante proceso automatizado',
                'id_user' => 1
            );

            AlpClientesHistory::create($data_history);

            $cliente=AlpClientes::where('id_user_client', $user->id)->withTrashed()->first();

            $cliente->update($data);

            Mail::to($user->email)->send(new \App\Mail\UserAprobado($user->first_name, $user->last_name));
           
        }

    }
}
