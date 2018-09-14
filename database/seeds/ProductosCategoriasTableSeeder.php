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
            ['id_producto' => 1,'id_categoria' => 1,'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_producto' => 2,'id_categoria' => 1,'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_producto' => 3,'id_categoria' => 1,'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_producto' => 4,'id_categoria' => 1,'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_producto' => 5,'id_categoria' => 1,'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_producto' => 6,'id_categoria' => 1,'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_producto' => 7,'id_categoria' => 1,'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_producto' => 8,'id_categoria' => 2,'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_producto' => 9,'id_categoria' => 3,'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_producto' => 10,'id_categoria' => 4,'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_producto' => 11,'id_categoria' => 5,'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_producto' => 12,'id_categoria' => 2,'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

        ]);
    }
}
