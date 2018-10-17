<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;


class EnviosStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alp_envios_status')->insert([
            ['estatus_envio_nombre' => 'Recibido','estatus_envio_descripcion' => 'Recibido', 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['estatus_envio_nombre' => 'En Transito','estatus_envio_descripcion' => 'En Transito', 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['estatus_envio_nombre' => 'Entregado','estatus_envio_descripcion' => 'Entregado', 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
         ]);
    }
}
