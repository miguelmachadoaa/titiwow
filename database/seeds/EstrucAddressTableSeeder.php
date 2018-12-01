<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EstrucAddressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alp_direcciones_estructura')->insert([
            ['nombre_estructura' => 'Calle','abrevia_estructura' => 'CL','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_estructura' => 'Carrera','abrevia_estructura' => 'CR','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_estructura' => 'Avenida','abrevia_estructura' => 'AV','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_estructura' => 'Avenida Carrera','abrevia_estructura' => 'AK','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_estructura' => 'Avenida Calle','abrevia_estructura' => 'AC','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_estructura' => 'Circular','abrevia_estructura' => 'CIR','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_estructura' => 'Circunvalar','abrevia_estructura' => 'CV','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_estructura' => 'Diagonal','abrevia_estructura' => 'DG','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_estructura' => 'Manzana','abrevia_estructura' => 'MZ','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_estructura' => 'Transversal','abrevia_estructura' => 'TV','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_estructura' => 'Via','abrevia_estructura' => 'V','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')]
        ]);
    }
}
