<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EmpresasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alp_empresas')->insert([
            ['nombre_empresa' => 'IBM','descripcion_empresa' => 'IBM de Colombia','descuento_empresa' => '10','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_empresa' => 'Claro','descripcion_empresa' => 'Claro de Colombia','descuento_empresa' => '12','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_empresa' => 'Cartonera','descripcion_empresa' => 'Cartonera de Colombia','descuento_empresa' => '15','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        ]);
    }
}
