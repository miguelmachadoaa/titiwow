<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PreciosRolTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alp_precios_grupos')->insert([
            ['id_producto' => 1,'id_role' => 4,'city_id' => 62,'operacion' => 3, 'precio' => 11295.00, 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_producto' => 2,'id_role' => 4,'city_id' => 62,'operacion' => 3, 'precio' => 9295.00, 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        ]);
    }
}
