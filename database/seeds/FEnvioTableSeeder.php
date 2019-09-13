<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class FEnvioTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alp_formas_envios')->insert([
            ['nombre_forma_envios' => 'Estandart','sku' => 'E00001','email' => 'estandar@gmail.com','descripcion_forma_envios' => 'Envio Regular 24 Horas','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_forma_envios' => 'Express','sku' => 'E00002','email' => 'express@gmail.com','descripcion_forma_envios' => 'Envio Express 1 Hora','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_forma_envios' => 'Click & Collect','sku' => 'E00003','email' => 'click@gmail.com','descripcion_forma_envios' => 'Compra y Recogida en Tienda','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        ]);
    }
}
