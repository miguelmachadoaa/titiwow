<?php

namespace App\Imports;

use App\Models\AlpAlpinistas;
use App\Models\AlpClientes;
use App\Models\Users;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Sentinel;
use Cartalyst\Sentinel\Laravel\Facades\Activation;

class SaldoImport implements ToCollection
{

    public function collection(Collection $rows)
    {

        $i=0;
        foreach ($rows as $row) 
        {

           if ($i==0) {

               # code...
           }else{

            $data = array(
                'first_name' => $row[2], 
                'last_name' => ' ', 
                'email' => $row[1].'@gmail.com', 
                'password' => $row[1], 
            );

                $user = Sentinel::register($data, true);

                $data = array(
                'id_user_client' => $user->id, 
                'id_type_doc' => '1', 
                'doc_cliente' =>$row[1], 
                'telefono_cliente' => '',
                'habeas_cliente' => '',
                'estado_masterfile' =>'',
                'cod_oracle_cliente'=> $row[0],
                'id_empresa' =>'0',               
                'id_embajador' =>'0',               
                'id_user' =>0,               
                );

                $cliente=AlpClientes::create($data);

                $role = Sentinel::findRoleById(14); 

                $role->users()->attach($user);

                 $activation = Activation::exists($user);

                if ($activation) {

                    Activation::complete($user, $activation->code);

                }


           }

            
        $i++;

        }
    }
  
}
