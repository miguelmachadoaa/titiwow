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
                'id_user' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        ]);
    }
}
