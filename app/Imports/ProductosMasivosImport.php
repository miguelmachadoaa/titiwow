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


                        $p=AlpProductos::select('alp_productos.id as id', 'alp_productos.precio_base as precio_base', 'alp_productos.referencia_producto as referencia_producto')->where('alp_productos.referencia_producto', trim($row[0]))->first();

                        if (isset($p->id)) {

                                $data_update = array(
                                    'nombre_producto' => $row[1], 
                                    'presentacion_producto' => '', 
                                    'referencia_producto' => $row[0], 
                                    'referencia_producto_sap' => $row[0], 
                                    'descripcion_corta' => $row[1], 
                                    'descripcion_larga' => $row[1], 
                                    'enlace_youtube' => '', 
                                    'id_categoria_default' => '1', 
                                    'id_marca' => '1', 
                                    'precio_base' => $row[3], 
                                    'unidad' => '', 
                                    'cantidad' => $row[5], 
                                );


                                $p->update($data_update);

                        }else{

                                $data_create = array(
                                    'nombre_producto' => $row[1], 
                                    'slug' => str_slug($row[1]), 
                                    'presentacion_producto' => '', 
                                    'referencia_producto' => $row[0], 
                                    'referencia_producto_sap' => $row[0], 
                                    'descripcion_corta' => $row[1], 
                                    'descripcion_larga' => $row[1], 
                                    'enlace_youtube' => '', 
                                    'id_categoria_default' => '1', 
                                    'id_marca' => '1', 
                                    'precio_base' => $row[3], 
                                    'unidad' => '', 
                                    'cantidad' => $row[5], 
                                );


                               $producto= AlpProductos::create($data_create);



                        }


        }//endforeach


    }
  
}
