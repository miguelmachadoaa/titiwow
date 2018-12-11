<?php

namespace App\Exports;

use App\User;
use App\Models\AlpOrdenes;
use App\Models\AlpClientes;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

use \DB;


class UsersExport implements FromView
{
    
    public function __construct(string $desde, string $hasta)
    {
        $this->desde = $desde;
        $this->hasta = $hasta;
    }



    public function view(): View
    {
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

          ->whereDate('users.created_at', '>=', $this->desde)
          ->whereDate('users.created_at', '<=', $this->hasta)
          ->get();

          //dd($ordenes);

          $usuario = array( );



          foreach ($users as $user ) {


            $amigos= AlpClientes::select(
        'alp_clientes.*',
        DB::raw('count(alp_clientes.id)  as cantidad_amigos')
        )
        ->groupBy('alp_clientes.id_embajador')
        ->where('alp_clientes.id_embajador', $user->id)
        ->first();

        if (isset($amigos->id)) {

        $user->cantidad_amigos=$amigos->cantidad_amigos;

           
        }else{
        $user->cantidad_amigos=0;

        }



        $ordenes= AlpOrdenes::select(
        'alp_ordenes.*',
        DB::raw('count(alp_ordenes.id)  as cantidad_ordenes'),
        DB::raw('sum(alp_ordenes.monto_total)  as monto_total_ordenes')

        )
        ->groupBy('alp_ordenes.id_cliente')
        ->where('alp_ordenes.id_cliente', $user->id)
        ->orderBy('alp_ordenes.id', 'desc')
        ->first();

       // dd($ordenes);


        if (isset($ordenes->id)) {

            $user->cantidad_ordenes=$ordenes->cantidad_ordenes;
            $user->monto_total_ordenes=$ordenes->monto_total_ordenes;
            

           
        }else{
            $user->cantidad_ordenes=0;
            $user->monto_total_ordenes=0;
            

        }


        $orden= AlpOrdenes::select(
        DB::raw('DATE_FORMAT(alp_ordenes.created_at, "%d/%m/%Y")  as fecha_ultima_compra')
        )
        ->where('alp_ordenes.id_cliente', $user->id)
        ->orderBy('alp_ordenes.id', 'desc')
        ->first();


         if (isset($orden->fecha_ultima_compra)) {

            
            $user->fecha_ultima_compra=$orden->fecha_ultima_compra;

           
        }else{
            
            $user->fecha_ultima_compra='N/A';

        }

        $usuario[]=$user;

          }

        return view('admin.exports.users', [
            'users' => $usuario
        ]);
    }
}