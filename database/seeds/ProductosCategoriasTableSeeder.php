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
        ]);
    }
}
