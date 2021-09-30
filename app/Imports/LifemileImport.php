<?php

namespace App\Imports;

use App\Models\AlpLifeMiles;
use App\Models\AlpLifeMilesCodigos;
use App\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Sentinel;
use Illuminate\Http\Request;

class LifemileImport implements ToCollection
{

    public function collection(Collection $rows)
    {
        $lifemile= \Session::get('lifemile');
        $user_id= \Session::get('user_id');


        $i=0;

        foreach ($rows as $row) 
        {

            if ($i==0) {
                # code...
            }else{

                if ($row[1]!="MILES" || $row[1]!="") {

                   # dd($row);

                    $data = array(
                        'id_lifemile' => $lifemile, 
                        'parnert' => $row[0], 
                        'miles' => $row[1], 
                        'fecha_inicio' => $row[3], 
                        'fecha_final' => $row[4], 
                        'estatus' => $row[2], 
                        'code' => $row[5], 
                        'prueba' => $row[6], 
                        'id_user' => $user_id
                    );

                    AlpLifeMilesCodigos::create($data);

                }


            }

            
            $i++;
        }//endforeach

    }
  
}
