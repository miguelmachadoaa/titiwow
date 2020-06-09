<?php

namespace App\Imports;

use App\Models\AlpSaldo;
use App\Models\AlpClientes;
use App\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Sentinel;
use Illuminate\Http\Request;

class SaldoImport implements ToCollection
{

    public function collection(Collection $rows)
    {
        $user_id = Sentinel::getUser()->id;

        $fecha_vencimiento= \Session::get('fecha_vencimiento');

       // dd($fecha_vencimiento);

        $i=0;

        foreach ($rows as $row) 
        {

            if ($i==0) {
                # code...
            }else{

               // dd($row);

                $c=AlpClientes::where('doc_cliente', '=', $row[1])->first();

                if (isset($c->id)) {

                    $data = array(
                        'id_cliente' => $c->id_user_client, 
                        'saldo' => $row[10], 
                        'operacion' => '1', 
                        'fecha_vencimiento' => $fecha_vencimiento, 
                        'nota' => 'Recarga de saldo', 
                        'id_user' => $user_id
                    );

                    AlpSaldo::create($data);
                }

            }

            
            $i++;
        }//endforeach

    }
  
}
