<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ConfGralTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alp_configuracion_general')->insert([
            [
                'nombre_tienda' => 'AlpinaGo',
                'limite_amigos' => 10,
                'id_mercadopago' => 534534543534,
                'key_mercadopago' => '345345dfdgdfgdfgdfgdf',
                'minimo_compra' => 30000,
                'comision_mp' => 3.3865,
                'retencion_fuente_mp' => 1.5000,
                'retencion_iva_mp' => 15.0000,
                'retencion_ica_mp' => 0.4140,
                'id_user' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        ]);
    }
}
