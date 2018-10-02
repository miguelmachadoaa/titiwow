<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PAgosEstatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alp_pagos_status')->insert([
            ['estatus_pago_nombre' => 'En espera de pago','estatus_pago_descripcion' => 'En espera de pago', 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['estatus_pago_nombre' => 'Recibido','estatus_pago_descripcion' => 'Recibido', 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['estatus_pago_nombre' => 'Cancelado','estatus_pago_descripcion' => 'Cancelado', 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')]
        ]);
    }
}
