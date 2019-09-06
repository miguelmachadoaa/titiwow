<?php

namespace App\Imports;

use App\Models\AlpCarga;
use App\Models\AlpPrecioGrupo;
use App\Models\AlpProductos;
use App\Models\AlpProductosAtributos;
use App\Models\AlpFacturas;
use App\Models\AlpOrdenes;
use App\Models\AlpOrdenesHistory;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Sentinel;
use Illuminate\Http\Request;

class ProductosPrecioBase implements ToCollection
{

    public function collection(Collection $rows)
    {
        $user_id = Sentinel::getUser()->id;

        $datos = array();

        $productos = array();


        foreach ($rows as $row) 
        {

            $datos[]=$row;

            if ($row[0]!=NULL) {

                if ($row[1]!=0) {
                   
                        $p=AlpProductos::select('alp_productos.id as id','alp_productos.pum as pum', 'alp_productos.precio_base as precio_base', 'alp_productos.referencia_producto as referencia_producto')->where('referencia_producto', $row[0])->first();

                        if (isset($p->id)) {

                            $productos[]=$p;

                            $data_precio_new = array(
                                'precio_base' => $row[1], 
                                'pum' => $row[2]
                            );

                            $p->update($data_precio_new);

                        }
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