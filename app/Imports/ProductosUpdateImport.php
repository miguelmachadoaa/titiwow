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

class ProductosUpdateImport implements ToCollection
{

    public function collection(Collection $rows)
    {
        $user_id = Sentinel::getUser()->id;

        $rol= \Session::get('rol');

        $cities= \Session::get('cities');

        $datos = array();

        $productos = array();

        foreach ($rows as $row) 
        {

            $row->rol=$rol;
            $row->city_id=$cities;


            $datos[]=$row;

            if ($row[0]!=NULL) {

                if ($row[1]!=0) {


                   
                        $p=AlpProductos::select('alp_productos.id as id', 'alp_productos.precio_base as precio_base', 'alp_productos.referencia_producto as referencia_producto')->where('referencia_producto', $row[0])->first();



                        if (isset($p->id)) {

            

                            
                            $precio=AlpPrecioGrupo::where('id_producto', $p->id)->where('id_role', $rol)->where('city_id', $cities)->first();

                            if (isset($precio->id)) {

                                $precio->delete();   

                            }

                                $data_precio_new = array(
                                    'operacion' => 3, 
                                    'precio' => $row[1], 
                                    'id_producto' => $p->id, 
                                    'city_id' => $cities, 
                                    'pum' => $row[2], 
                                    'id_role' => $rol, 
                                    'id_user' => $user_id, 
                                );

                                AlpPrecioGrupo::create($data_precio_new);



                            $productos[]=$p;

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
