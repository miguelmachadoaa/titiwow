<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class FPagoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alp_formas_pagos')->insert([
            ['nombre_forma_pago' => 'ContraDespacho','descripcion_forma_pago' => 'Paga al Recibir el Producto','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_forma_pago' => 'MercadoPago','descripcion_forma_pago' => 'Pago en línea a través de mercadopago','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_forma_pago' => 'Descuento Nómina','descripcion_forma_pago' => 'Descuento de nómina alpinista','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        ]);
    }
}
