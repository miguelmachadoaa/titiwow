<?php

namespace App\Imports;

use App\Models\AlpSaldo;
use App\Models\AlpClientes;
use App\Models\AlpAlmacenes;
use App\Models\AlpAlmacenProducto;
use App\Models\AlpProductos;
use App\Models\AlpInventario;
use App\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Sentinel;
use Illuminate\Http\Request;

class AlmacenImport implements ToCollection
{

    public function collection(Collection $rows)
    {
        $user_id = Sentinel::getUser()->id;

        $almacen= \Session::get('almacen');

        $inventario= \Session::get('inventario');

        AlpAlmacenProducto::where('id_almacen', $almacen)->delete();

        $i=0;

        foreach ($rows as $row) 
        {

            if ($i==0) {
                # code...
            }else{

                if ($row[1]>0) {

                        $p=AlpProductos::where('referencia_producto', trim($row[0]))->first();

                        if (isset($p->id)) {

                        $data = array(
                            'id_almacen' => $almacen, 
                            'id_producto' => $p->id, 
                            'id_user' => $user_id 
                        );

                        AlpAlmacenProducto::create($data);

                        if (isset($inventario[$p->id][$almacen])) {

                            $data_inventario = array(
                                'id_almacen' => $almacen, 
                                'id_producto' => $p->id, 
                                'cantidad' => abs($inventario[$p->id][$almacen]), 
                                'operacion' => 2, 
                                'notas' => 'Actualización de inventario por upload almacen', 
                                'id_user' => $user_id 
                            );

                            //dd($data_inventario);   

                            AlpInventario::create($data_inventario);

                            $data_inventario_nuevo = array(
                                'id_almacen' => $almacen, 
                                'id_producto' => $p->id, 
                                'cantidad' => $row[1], 
                                'operacion' => 1, 
                                'notas' => 'Actualización de inventario por upload almacen', 
                                'id_user' => $user_id 
                            );

                            AlpInventario::create($data_inventario_nuevo);


                        }else{

                            $data_inventario_nuevo = array(
                                'id_almacen' => $almacen, 
                                'id_producto' => $p->id, 
                                'cantidad' => $row[1], 
                                'operacion' => 1, 
                                'notas' => 'Actualización de inventario por upload almacen', 
                                'id_user' => $user_id 
                            );

                            AlpInventario::create($data_inventario_nuevo);


                        }


                    }

                
                    # code...
                }


            }

            
            $i++;
        }//endforeach

    }
  
}
