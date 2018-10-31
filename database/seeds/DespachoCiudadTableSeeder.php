<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DespachoCiudadTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alp_despacho_ciudad')->insert([
            ['id_config' => 1,
            'id_ciudad' => 62,
            'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        ]);
    }
}
