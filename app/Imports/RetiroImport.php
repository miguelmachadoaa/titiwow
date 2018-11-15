<?php

namespace App\Imports;

use App\Models\AlpAlpinistas;
use App\RoleUser;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class RetiroImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            $alpinista =  AlpAlpinistas::select('*')
            ->where('alp_cod_alpinistas.codigo_alpi',$row[0])
            ->get();


           if(!$alpinista->isEmpty()){

                 AlpAlpinistas::where('codigo_alpi', $row[0])
                ->update(['estatus_alpinista' => 3]);

                foreach ($alpinista as $alpi) 
                {
                    RoleUser::where('user_id',$alpi->id_usuario_creado)
                    ->update(['role_id' => 9]);
                }
                
    
            }
        }
    }
}
