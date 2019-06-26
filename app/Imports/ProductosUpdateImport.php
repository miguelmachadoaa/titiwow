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

        $datos = array();

        $productos = array();

        foreach ($rows as $row) 
        {

            $row->rol=$rol;

            $datos[]=$row;

            if ($row[0]!=NULL) {

                if ($row[1]!=0) {
                   
                        $p=AlpProductos::select('alp_productos.id as id', 'alp_productos.precio_base as precio_base', 'alp_productos.referencia_producto as referencia_producto')->where('referencia_producto', $row[0])->first();

                        if (isset($p->id)) {
                            
                            $precio=AlpPrecioGrupo::where('id_producto', $p->id)->where('id_role', $rol)->first();

                            if (isset($precio->id)) {

                                
                                $data_precio = array(
                                    'operacion' => 2, 
                                    'precio' => $row[1], 
                                );

                                $precio->update($data_precio);   

                            }else{

                                $data_precio_new = array(
                                    'operacion' => 2, 
                                    'precio' => $row[1], 
                                    'id_producto' => $p->id, 
                                    'city_id' => '0', 
                                    'pum' => NULL, 
                                    'id_role' => $rol, 
                                    'id_user' => $user_id, 
                                );

                                AlpPrecioGrupo::create($data_precio_new);


                            }

                            $productos[]=$p;

                            $update = array(
                                'precio_base' => $row[2]
                            );

                            $p->update($update);

                        }
                }else{

                        $p=AlpProductos::select('alp_productos.id as id', 'alp_productos.precio_base as precio_base', 'alp_productos.referencia_producto as referencia_producto')->where('referencia_producto', $row[0])->first();

                        if (isset($p->id)) {

                            $productos[]=$p;

                             $update = array(
                                'precio_base' => $row[2]
                            );

                            $p->update();

                        }
                        
                }//endif row!=0

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
