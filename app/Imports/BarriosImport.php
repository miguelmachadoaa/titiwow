<?php

namespace App\Imports;

use App\Models\AlpSaldo;
use App\Models\AlpClientes;
use App\Barrio;
use App\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Sentinel;
use Illuminate\Http\Request;

class BarriosImport implements ToCollection
{

    public function collection(Collection $rows)
    {
        $user_id = Sentinel::getUser()->id;

        $i=0;

        foreach ($rows as $row) 
        {

            if ($i==0) {
                # code...
            }else{

                    $data = array(
                        'barrio_name' => $row[1], 
                        'city_id' => $row[2], 
                        'id_user' => $user_id
                    );

                    Barrio::create($data);

            }

            
            $i++;
        }//endforeach

    }
  
}
