<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RolEnvioTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alp_rol_envio')->insert([
            ['id_rol' => 9,'id_forma_envio' => 1, 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_rol' => 9,'id_forma_envio' => 2, 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_rol' => 9,'id_forma_envio' => 3, 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_rol' => 10,'id_forma_envio' => 1, 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_rol' => 10,'id_forma_envio' => 2, 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_rol' => 10,'id_forma_envio' => 3, 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_rol' => 11,'id_forma_envio' => 1, 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_rol' => 11,'id_forma_envio' => 2, 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_rol' => 11,'id_forma_envio' => 3, 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_rol' => 12,'id_forma_envio' => 1, 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_rol' => 12,'id_forma_envio' => 2, 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_rol' => 12,'id_forma_envio' => 3, 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')]
        ]);
    }
}
