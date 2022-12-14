<?php

namespace App\Exports;

use App\User;
use App\Models\AlpOrdenes;
use App\Models\AlpClientes;
use App\Models\AlpDirecciones;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

use \DB;


class UsersExport implements FromView
{
    
    public function __construct(string $desde, string $hasta, string $id_almacen)
    {
        $this->desde = $desde;
        $this->hasta = $hasta;
        $this->id_almacen = $id_almacen;
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
        'alp_clientes.marketing_email as marketing_email',
        'alp_clientes.habeas_cliente as habeas_cliente',
        'alp_clientes.genero_cliente as genero_cliente',
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
          ->where('alp_clientes.origen', '=', '0')
          ->whereDate('users.created_at', '>=', $this->desde)
          ->whereDate('users.created_at', '<=', $this->hasta)
          ->get();

          //dd($ordenes);

          $usuario = array( );



         /* foreach ($users as $user ) {

            $d = AlpDirecciones::select('alp_direcciones.*', 'config_cities.city_name as city_name', 'config_states.state_name as state_name','config_states.id as state_id','config_countries.country_name as country_name', 'alp_direcciones_estructura.nombre_estructura as nombre_estructura', 'alp_direcciones_estructura.id as estructura_id')
            ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
            ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
            ->join('config_countries', 'config_states.country_id', '=', 'config_countries.id')
            ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
            ->where('alp_direcciones.id_client', $user->id)
            ->where('alp_direcciones.default_address', '=', '1')
            ->first();*

            $user->dir=$d;

            $usuario[]=$user;

          }*/


        return view('admin.exports.users', [
            'users' => $users
        ]);
    }
}