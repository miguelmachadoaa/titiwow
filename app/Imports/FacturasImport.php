<?php

namespace App\Imports;

use App\Models\AlpFacturas;
use App\Models\AlpOrdenes;
use App\Models\AlpOrdenesHistory;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Sentinel;

class FacturasImport implements ToCollection
{

    public function collection(Collection $rows)
    {
        $user_id = Sentinel::getUser()->id;

        foreach ($rows as $row) 
        {
            $facuss = AlpFacturas::where('orden_compra',$row[0])->get();

            if($facuss->isEmpty()){

                $orden = AlpOrdenes::where('ordencompra',$row[0])->get();

                if($orden->isEmpty()){

                    AlpFacturas::create([
                        'orden_compra'     => $row[0],
                        'factura'    => $row[1], 
                        'id_orden'    => 0, 
                        'estatus_factura' => 2,
                        'id_user' => $user_id
                    ]);

                }else{

                    foreach ($orden as $ord) 
                    {
                        AlpFacturas::create([
                            'orden_compra'     => $row[0],
                            'factura'    => $row[1], 
                            'estatus_factura' => 1,
                            'id_orden'    => $ord->id,
                            'id_user' => $user_id
                        ]);
    
                        AlpOrdenes::where('estatus', 5)
                        ->where('id', $ord->id)
                        ->update(['estatus' => 6,'factura' => $row[1]]);

                        $data_history = array(
                            'id_orden' => $ord->id, 
                            'id_status' => 6, 
                            'notas' => "Actualizada de Forma Masiva", 
                            'id_user' => $user_id
                        );

                        AlpOrdenesHistory::create($data_history);

                    }
                      
                }
    
            }
        }
    }
  
}
