<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;


class InventarioTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alp_inventarios')->insert([
            ['id_producto' => 1,'cantidad' => 9999,'operacion' => 1, 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        ]);
    }
}
