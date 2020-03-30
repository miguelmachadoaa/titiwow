<?php

namespace App\Imports;

use App\Models\AlpSaldo;
use App\Models\AlpClientes;
use App\Models\AlpAlmacenes;
use App\Models\AlpAlmacenProducto;
use App\Models\AlpProductos;
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

       // dd($almacen);

        AlpAlmacenProducto::where('id_almacen', $almacen)->delete();

        $i=0;

        foreach ($rows as $row) 
        {

            if ($i==0) {
                # code...
            }else{

                //dd(trim($row[0]));

                $p=AlpProductos::where('referencia_producto', trim($row[0]))->first();

                ///dd($p);

                if (isset($p->id)) {

                    $data = array(
                        'id_almacen' => $almacen, 
                        'id_producto' => $p->id, 
                        'id_user' => $user_id 
                    );

                    AlpAlmacenProducto::create($data);
                    # code...
                }

            }

            
            $i++;
        }//endforeach

    }
  
}
