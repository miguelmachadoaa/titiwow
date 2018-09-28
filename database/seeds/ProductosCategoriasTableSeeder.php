<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ProductosCategoriasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alp_productos_category')->insert([
            ['id_producto' => 1,'id_categoria' => 2,'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_producto' => 1,'id_categoria' => 10,'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        ]);
    }
}
