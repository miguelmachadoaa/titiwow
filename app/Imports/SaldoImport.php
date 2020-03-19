<?php

namespace App\Imports;

use App\Models\AlpSaldo;
use App\Models\User;
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

        foreach ($rows as $row) 
        {

            dd($row);

            $datos[]=$row;

            if ($row[0]!=NULL) {

                if ($row[1]!=0) {
                   
                        $data = array(
                            '' => , 
                        );
                }

            }//if row->0 != NULL

        }//endforeach

        $data_carga = array(
            'data_subida' => json_encode($datos), 
            'data_respaldo' => json_encode($productos), 
            'id_user' => $user_id, 
        );

        AlpCarga::create($data_carga);

    }
  
}
