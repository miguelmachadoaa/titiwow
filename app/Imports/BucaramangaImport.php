<?php

namespace App\Imports;

use App\Models\AlpAlpinistas;
use App\Models\AlpClientes;
use App\Models\AlpDirecciones;
use App\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Sentinel;
use Cartalyst\Sentinel\Laravel\Facades\Activation;

class BucaramangaImport implements ToCollection
{

    public function collection(Collection $rows)
    {


        $almacen= \Session::get('importalmacen');

        $direccion=AlpDirecciones::where('id_client', 'A'.$almacen)->first();

      //  dd($direccion);


        $i=0;
        foreach ($rows as $row) 
        {

           if ($i==0) {

               # code...
           }else{

            $u=User::where('email', $row[1].'@alpinago.com')->first();

            if (isset($u->id)) {
                # code...
            }else{

                if ($row[1]=='') {
                    # code...
                }else{

            $data = array(
                'first_name' => $row[2], 
                'last_name' => ' ', 
                'email' => $row[1].'@alpinago.com', 
                'password' => $row[1], 
            );

                $user = Sentinel::register($data, true);

                $data = array(
                'id_user_client' => $user->id, 
                'id_type_doc' => '1', 
                'doc_cliente' =>$row[1], 
                'telefono_cliente' => '',
                'habeas_cliente' => '',
                'estado_masterfile' =>'',
                'cod_oracle_cliente'=> $row[0],
                'id_empresa' =>'0',               
                'id_embajador' =>'0',               
                'id_user' =>0,               
                );

                $cliente=AlpClientes::create($data);

                $role = Sentinel::findRoleById(14); 

                $role->users()->attach($user);

                 $activation = Activation::exists($user);

                if ($activation) {

                    Activation::complete($user, $activation->code);

                }

                if (isset($direccion->id)) {
                    

                    $data_addres = array(
                        'id_client' => $user->id, 
                        'city_id' => $direccion->city_id, 
                        'id_estructura_address' => $direccion->id_estructura_address, 
                        'principal_address' => $direccion->principal_address,
                        'secundaria_address' => $direccion->secundaria_address,
                        'edificio_address' => $direccion->edificio_address,
                        'detalle_address' => $direccion->detalle_address,
                        'barrio_address'=> $direccion->barrio_address,             
                        'default_address'=> '1',             
                        'id_user' => $user->id         
                        );

                     AlpDirecciones::create($data_addres);



                }else{


                     $data_addres = array(
                        'id_client' => $user->id, 
                        'city_id' => '138', 
                        'id_estructura_address' => '1', 
                        'principal_address' => '12',
                        'secundaria_address' => '12',
                        'edificio_address' => '12',
                        'detalle_address' => '12',
                        'barrio_address'=> 'Bucaramanga',             
                        'default_address'=> '1',             
                        'id_user' => 0,               
                        );

                AlpDirecciones::create($data_addres);



                }


               }

            }//Existe el registro


           }//$i==0

            
        $i++;

        }
    }
  
}
