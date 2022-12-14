<?php

namespace App\Imports;

use App\Models\AlpCupones;
use App\Models\AlpCuponesAlmacen;
use App\Models\AlpCuponesCategorias;
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


                     $id =  AlpCupones::create([
                    'codigo_cupon'     => $row[0],
                    'tipo_reduccion'    => $row[1], 
                    'valor_cupon'    => $row[2], 
                    'limite_uso'    => $row[3], 
                    'limite_uso_persona'    => $row[4], 
                    'fecha_inicio'    => '2022-03-08', 
                    //'fecha_inicio'    => $row[5], 
                    'fecha_final' => '2022-12-31', 
                    //'fecha_final' => $row[6], 
                    'monto_minimo' => $row[7], 
                    'maximo_productos' => $row[8], 
                    'origen' => 'Datapower 30%', 
                    'id_user' => 1, 
                ]);
    
                    $condici = AlpCuponesAlmacen::create([
                    'id_cupon'     => $id->id,
                    'id_almacen'     => 1,
                    'id_user'     => 1,
                    ]);

                    $condici2 = AlpCuponesAlmacen::create([
                    'id_cupon'     => $id->id,
                    'id_almacen'     => 32,
                    'id_user'     => 1,
                    ]);
                    $condici6 = AlpCuponesAlmacen::create([
                        'id_cupon'     => $id->id,
                        'id_almacen'     => 30,
                        'id_user'     => 1,
                        ]);
                    $condici7 = AlpCuponesAlmacen::create([
                        'id_cupon'     => $id->id,
                        'id_almacen'     => 31,
                        'id_user'     => 1,
                        ]);
                    

                    $condici3 = AlpCuponesCategorias::create([
                        'id_cupon'     => $id->id,
                        'id_categoria'     => 40,
                        'condicion' => 0,
                        'id_user'     => 1,
                        ]);

                    $condici4 = AlpCuponesCategorias::create([
                            'id_cupon'     => $id->id,
                            'id_categoria'     => 15,
                            'condicion' => 0,
                            'id_user'     => 1,
                            ]);

                    $condici4 = AlpCuponesCategorias::create([
                        'id_cupon'     => $id->id,
                        'id_categoria'     => 8,
                        'condicion' => 0,
                        'id_user'     => 1,
                        ]);

                }

             
            }

             }
        }
    }
  
}
