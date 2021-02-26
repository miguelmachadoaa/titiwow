<?php

namespace App\Imports;

use App\Models\AlpCupones;
use App\Models\AlpCuponesAlmacen;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class CuponesImport implements ToCollection
{

    public function collection(Collection $rows)
    {

        $i=0;
        foreach ($rows as $row) 
        {

            $i++;

            if ($i>1) {
                
           
            $existcupon = AlpCupones::where('codigo_cupon',$row[0])->get();


            if($existcupon->isEmpty()){

                if (is_null($row[0])) {
                    # code...
                }else{

            //dd($row);


                     $id =  AlpCupones::create([
                    'codigo_cupon'     => $row[0],
                    'tipo_reduccion'    => $row[1], 
                    'valor_cupon'    => $row[2], 
                    'limite_uso'    => $row[3], 
                    'limite_uso_persona'    => $row[4], 
                    'fecha_inicio'    => '2021-02-04', 
                    //'fecha_inicio'    => $row[5], 
                    'fecha_final' => '2021-05-04', 
                    //'fecha_final' => $row[6], 
                    'monto_minimo' => $row[7], 
                    'maximo_productos' => $row[8], 
                    'origen' => 'Importado ', 
                    'id_user' => 1, 
                ]);
    
                    $condici = AlpCuponesAlmacen::create([
                    'id_cupon'     => $id->id,
                    'id_almacen'     => 1,
                    'id_user'     => 1,
                    ]);


                }

             
            }

             }
        }
    }
  
}
