<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MarcasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alp_marcas')->insert([
            ['nombre_marca' => 'Avena Alpina','descripcion_marca' => 'Marca Avena Alpina','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        ]);
    }
}
