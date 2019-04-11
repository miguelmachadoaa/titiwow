<?php

namespace App\Imports;

use App\Models\AlpAmigos;
use App\Models\AlpEmpresas;
use App\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;

use Sentinel;

class InvitacionesImport implements ToCollection
{

    public function collection(Collection $rows)
    {
        $user_id = Sentinel::getUser()->id;

        $i = 0;

        foreach ($rows as $row) 
        {
            $i++;

            $usuarioExiste = User::where('email',$row[2])->first();

           if(!isset($usuarioExiste->id)){

                $token=substr(md5(time().$i), 0, 10);

                $data = array(
                    'id_cliente' => 'E'.$row[3], 
                    'nombre_amigo' => $row[0], 
                    'apellido_amigo' => $row[1], 
                    'email_amigo' => $row[2], 
                    'token' => $token, 
                    'id_user' => $user_id
                );

                AlpAmigos::create($data);

                $empresa = AlpEmpresas::find($row[3]);

               // Mail::to($row[2])->send(new \App\Mail\NotificacionAfiliado($row[0], $row[1], $token, $empresa->nombre_empresa));
            }

        }
    }
  
}
