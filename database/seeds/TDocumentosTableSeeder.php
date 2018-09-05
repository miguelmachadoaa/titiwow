<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TDocumentosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alp_tipo_documento')->insert([
            ['nombre_tipo_documento' => 'Cédula de Ciudadanía','abrev_tipo_documento' => 'CC','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_tipo_documento' => 'Cédula de Extranjería','abrev_tipo_documento' => 'CE','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_tipo_documento' => 'Pasaporte','abrev_tipo_documento' => 'PA','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ]);
    }
}
