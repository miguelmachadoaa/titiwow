<?php

namespace App\Imports;

use App\Models\AlpProductos;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Sentinel;
use Illuminate\Http\Request;

class ProductosMasivosImport implements ToCollection
{

    public function collection(Collection $rows)
    {
       

        foreach ($rows as $row) 
        {

           

            if (trim($row[12])=='1') {


                        $p=AlpProductos::select('alp_productos.id as id', 'alp_productos.precio_base as precio_base', 'alp_productos.referencia_producto as referencia_producto')->where('alp_productos.id', trim($row[13]))->first();

                        if (isset($p->id)) {

                                $data_update = array(
                                    'nombre_producto' => $row[0], 
                                    'presentacion_producto' => $row[1], 
                                    'referencia_producto' => $row[2], 
                                    'referencia_producto_sap' => $row[3], 
                                    'descripcion_corta' => $row[4], 
                                    'descripcion_larga' => $row[5], 
                                    'enlace_youtube' => $row[6], 
                                    'id_categoria_default' => $row[7], 
                                    'id_marca' => $row[8], 
                                    'medida' => $row[9], 
                                    'unidad' => $row[10], 
                                    'cantidad' => $row[11], 
                                );


                                $p->update($data_update);

                        }else{

                           // dd($row);
                        }

            }//if row->0 != NULL

        }//endforeach


    }
  
}
