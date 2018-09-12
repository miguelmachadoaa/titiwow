<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ImpuestosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alp_impuestos')->insert([
            ['nombre_impuesto' => 'IVA','valor_impuesto' => 0.19, 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        ]);
    }
}
