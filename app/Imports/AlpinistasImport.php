<?php

namespace App\Imports;

use App\Models\AlpAlpinistas;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class AlpinistasImport implements ToCollection
{

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            $elalpin = AlpAlpinistas::where('codigo_alpi',$row[1])->get();

            if($elalpin->isEmpty()){

                AlpAlpinistas::create([
                    'documento_alpi'     => $row[0],
                    'codigo_alpi'    => $row[1], 
                    //'cod_oracle_cliente'    => $row[2], 
                    'estatus_alpinista' => 1
                ]);
    
            }
        }
    }
  
}
