<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DireccionesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alp_direcciones')->insert([
            ['id_client' => 9,
            'city_id' => 62,
            'id_estructura_address' => 1,
            'principal_address' => '120',
            'secundaria_address' => '14',
            'edificio_address' => '10',
            'detalle_address' => '405',
            'barrio_address' => 'Usaquen',
            'notas' => '',
            'id_user' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            ['id_client' => 10,
            'city_id' => 62,
            'id_estructura_address' => 2,
            'principal_address' => '122',
            'secundaria_address' => '16',
            'edificio_address' => '12',
            'detalle_address' => '406',
            'barrio_address' => 'Usaquen',
            'notas' => '100',
            'id_user' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            ['id_client' => 11,
            'city_id' => 62,
            'id_estructura_address' => 3,
            'principal_address' => '124',
            'secundaria_address' => '18',
            'edificio_address' => '14',
            'detalle_address' => '407',
            'barrio_address' => 'Usaquen',
            'notas' => '101',
            'id_user' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            ['id_client' => 12,
            'city_id' => 62,
            'id_estructura_address' => 4,
            'principal_address' => '126',
            'secundaria_address' => '20',
            'edificio_address' => '16',
            'detalle_address' => '17',
            'barrio_address' => 'Usaquen',
            'notas' => '102',
            'id_user' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
        ]);
    }
}
