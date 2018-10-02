<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OrdenesEstatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alp_ordenes_estatus')->insert([
            ['estatus_nombre' => 'Recibido','descripcion_estatus' => 'Recibido', 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['estatus_nombre' => 'Confirmado','descripcion_estatus' => 'Confirmado', 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['estatus_nombre' => 'Entregado','descripcion_estatus' => 'Entregado', 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['estatus_nombre' => 'Cancelado','descripcion_estatus' => 'Cancelado', 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')]
        ]);
    }
}
