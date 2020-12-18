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


class Usuarios360Export implements FromView
{
    
    public function __construct()
    {
        
    }



    public function view(): View
    {
       $users= User::select(
        'users.*',
        'alp_clientes.genero_cliente as genero_cliente',
        'alp_clientes.doc_cliente as doc_cliente',
        'alp_clientes.telefono_cliente as telefono_cliente',
        'alp_clientes.marketing_email as marketig_email',
        'alp_clientes.marketing_sms as marketing_sms',
        )
          ->join('alp_clientes', 'users.id', '=', 'alp_clientes.id_user_client')
          ->where('alp_clientes.id','>', 1000)
          ->where('alp_clientes.id','<', 1120)
          ->get();


        return view('admin.exports.users360', [
            'users' => $users
        ]);
    }
}