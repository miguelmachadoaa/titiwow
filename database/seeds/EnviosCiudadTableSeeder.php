<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EnviosCiudadTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alp_forma_ciudad')->insert([
            ['id_forma' => 1,
            'id_ciudad' => 62,
            'dias' => 2,
            'hora' => '18:00',
            'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        ]);
    }
}
