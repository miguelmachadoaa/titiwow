<?php

namespace App\Exports;

use App\User;
use App\Models\AlpOrdenes;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

use \DB;


class ClientesExport implements FromView
{
    
    public function __construct(string $city_id)
    {
        $this->city_id = $city_id;
       
    }



    public function view(): View
    {
        $users= User::select(
        'users.*',
        DB::raw('DATE_FORMAT(users.created_at, "%Y%m%d")  as fecha_ingreso'),
        'alp_clientes.doc_cliente as doc_cliente',
        'alp_clientes.cod_oracle_cliente as cod_oracle_cliente',
        'config_cities.city_name as city_name',
        'config_states.state_name as state_name',
        'alp_direcciones.principal_address as principal_address',
        'alp_direcciones.secundaria_address as secundaria_address',
        'alp_direcciones.edificio_address as edificio_address',
        'alp_direcciones.detalle_address as detalle_address',
        'alp_direcciones.barrio_address as barrio_address',
        'alp_direcciones_estructura.abrevia_estructura as abrevia_estructura',
        'alp_clientes.id_embajador as id_embajador',
        'alp_clientes.telefono_cliente as telefono_cliente',
        'roles.name as name_rol' 
         

        )
          ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
          ->join('alp_direcciones', 'users.id', '=', 'alp_direcciones.id_client')
          ->join('alp_direcciones_estructura', 'alp_direcciones.id_estructura_address', '=', 'alp_direcciones_estructura.id')
          ->join('config_cities', 'alp_direcciones.city_id', '=', 'config_cities.id')
          ->join('config_states', 'config_cities.state_id', '=', 'config_states.id')
          ->join('role_users', 'users.id', '=', 'role_users.user_id')
          ->join('roles', 'role_users.role_id', '=', 'roles.id')
          ->where('alp_direcciones.city_id', $this->city_id)
          ->get();

         // dd($users);

        return view('admin.exports.clientes', [
            'usuarios' => $users
        ]);
    }
}

