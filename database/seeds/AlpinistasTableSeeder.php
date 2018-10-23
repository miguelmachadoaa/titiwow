<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;


class AlpinistasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alp_cod_alpinistas')->insert([
            ['documento_alpi' => '343434','codigo_alpi' => '88888','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['documento_alpi' => '222222','codigo_alpi' => '22222','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['documento_alpi' => '333333','codigo_alpi' => '33333','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['documento_alpi' => '444444','codigo_alpi' => '44444','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        ]);
    }
}
