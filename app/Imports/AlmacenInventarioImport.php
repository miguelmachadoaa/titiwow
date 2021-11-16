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

class AlmacenInventarioImport implements ToCollection
{

    protected $almacen = null;

    public function __construct(string $almacen) {
        $this->almacen = $almacen;
     }

    public function collection(Collection $rows)
    {
        $user_id = 1;

       # $almacen= \Session::get('almacen');

        //$inventario= \Session::get('inventario');

        AlpAlmacenProducto::where('id_almacen', $this->almacen)->delete();

        $i=0;

        foreach ($rows as $row) 
        {


            if ($i==0 || is_null($row[3]) ) {
                # code...
            }else{

                if (trim($row[4]==0)) {

                    $p=AlpProductos::where('slug', trim($row[0]))->first();

                    if (isset($p->id)) {

                            $data = array(
                                'id_almacen' => $this->almacen, 
                                'id_producto' => $p->id, 
                                'id_user' => $user_id 
                            );

                                AlpAlmacenProducto::create($data);

                                AlpInventario::where('id_producto', '=', $p->id)->where('id_almacen', '=', $this->almacen)->delete();

                                   if ($row[3]>0){

                                        $data_inventario_nuevo = array(
                                            'id_almacen' => $this->almacen, 
                                            'id_producto' => $p->id, 
                                            'cantidad' => $row[3], 
                                            'operacion' => 1, 
                                            'notas' => 'Actualización de inventario por upload almacen', 
                                            'id_user' => $user_id 
                                        );

                                   }else{

                                        $data_inventario_nuevo = array(
                                            'id_almacen' => $this->almacen, 
                                            'id_producto' => $p->id, 
                                            'cantidad' => 0, 
                                            'operacion' => 1, 
                                            'notas' => 'Actualización de inventario por upload almacen', 
                                            'id_user' => $user_id 
                                        );

                                   }

                                AlpInventario::create($data_inventario_nuevo);
                                   
                    }

                }

            }//else

            $i++;

        }//endforeach

    }
  
}
