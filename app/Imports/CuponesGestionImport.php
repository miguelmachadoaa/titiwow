<?php

namespace App\Imports;

use App\Models\AlpCupones;
use App\Models\AlpCuponesCategorias;
use App\Models\AlpCuponesAlmacen;
use App\Models\AlpCuponesMarcas;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class CuponesGestionImport implements ToCollection
{

    public function collection(Collection $rows)
    {

        $i=0;
        foreach ($rows as $row) 
        {

            $i++;

            if ($i>1) {

                    $c=AlpCupones::where('codigo_cupon', '=', $row[0])->first();

                    if ($c->id) {


                            $data_marca = array(
                                'id_cupon' => $c->id, 
                                'id_marca' => '41', 
                                'condicion' => '0', 
                                'id_user' => '1' 
                            );


                            $data_categoria = array(
                                'id_cupon' => $c->id, 
                                'id_categoria' => '15', 
                                'condicion' => '0', 
                                'id_user' => '1' 
                            );


                            $data_almacen = array(
                                'id_cupon' => $c->id, 
                                'id_almacen' => '1', 
                                'condicion' => '1', 
                                'id_user' => '1' 
                            );


                            AlpCuponesAlmacen::create($data_almacen);
                            AlpCuponesCategorias::create($data_categoria);
                            AlpCuponesMarcas::create($data_marca);


                    }



             

             }
        }
    }
  
}
