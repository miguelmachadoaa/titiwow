<?php

namespace App\Imports;

use App\Models\AlpCupones;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class CuponesImport implements ToCollection
{

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            $existcupon = AlpCupones::where('codigo_cupon',$row[0])->get();

            if($existcupon->isEmpty()){

                AlpCupones::create([
                    'codigo_cupon'     => $row[0],
                    'tipo_reduccion'    => $row[1], 
                    'valor_cupon'    => $row[2], 
                    'limite_uso'    => $row[3], 
                    'limite_uso_persona'    => $row[4], 
                    'fecha_inicio'    => $row[5], 
                    'fecha_final' => $row[6], 
                    'monto_minimo' => $row[7], 
                    'maximo_productos' => $row[8], 
                    'id_user' => 1, 
                ]);
    
            }
        }
    }
  
}