<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MenuDetalleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alp_menu_detalle')->insert([
            ['titulo_menu' => 'Inicio','url_menu' => '/home','orden' => 0,'id_menu' => 1,'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['titulo_menu' => 'Categoria1','url_menu' => '/productos','orden' => 0,'id_menu' => 1,'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        ]);
    }
}
