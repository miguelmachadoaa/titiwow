<?php

namespace App\Console\Commands;

use App\User;
use App\Models\AlpConfiguracion;
use App\Exports\CronNuevosUsuarios;
use Maatwebsite\Excel\Facades\Excel;

use Carbon\Carbon;
use Mail;
use DB;



use Illuminate\Console\Command;

class NuevosUsuarios extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'usuarios:new';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envio de usuarios por activar dos veces al dia ';

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




        $archivo='/reportes/cronnuevosusuariosexport';



        $users= User::select(
        'users.*',
        DB::raw('DATE_FORMAT(users.created_at, "%d/%m/%Y")  as fecha'),
        'alp_clientes.doc_cliente as doc_cliente',
        'alp_clientes.cod_oracle_cliente as cod_oracle_cliente',
        'alp_clientes.id_embajador as id_embajador',
        'alp_clientes.telefono_cliente as telefono_cliente',
        'config_cities.city_name as city_name',
        'config_states.state_name as state_name',
        'alp_direcciones.principal_address as principal_address',
        'alp_direcciones.secundaria_address as secundaria_address',
        'alp_direcciones.edificio_address as edificio_address',
        'alp_direcciones.detalle_address as detalle_address',
        'alp_direcciones.barrio_address as barrio_address',
        'alp_direcciones_estructura.abrevia_estructura as abrevia_estructura',
        'roles.name as name_rol' 
         

        )
          ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
          ->join('alp_direcciones', 'users.id', '=', 'alp_direcciones.id_client')
          ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
          ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->join('role_users', 'users.id', '=', 'role_users.user_id')
          ->join('roles', 'role_users.role_id', '=', 'roles.id')
          ->whereNull('alp_clientes.cod_oracle_cliente')
          ->get();

          if (count($users)>0) {
              
         


       // Excel::store(new CronLogisticaExport(), $archivo);

            $enlace=storage_path('/app/'.$archivo);

            Mail::to($configuracion->correo_masterfile)->send(new \App\Mail\CronNuevosUsuarios($enlace, $hoy));

         }

    }
}
