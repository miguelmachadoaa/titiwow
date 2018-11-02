<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alp_menu')->insert([
            ['nombre_menu' => 'Principal','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_menu' => 'Categorias','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_menu' => 'Marcas','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')]         
        ]);
    }
}
