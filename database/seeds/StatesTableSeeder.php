<?php

use Illuminate\Database\Seeder;

class StatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('config_states')->insert([
            ['state_name' => 'Antioquia','country_id' => 47,'id_user' => 1],
            ['state_name' => 'Atlántico','country_id' => 47,'id_user' => 1],
            ['state_name' => 'Bolívar','country_id' => 47,'id_user' => 1],
            ['state_name' => 'Boyacá','country_id' => 47,'id_user' => 1],
            ['state_name' => 'Caldas','country_id' => 47,'id_user' => 1],
            ['state_name' => 'Cauca','country_id' => 47,'id_user' => 1],
            ['state_name' => 'Cesar','country_id' => 47,'id_user' => 1],
            ['state_name' => 'Córdoba','country_id' => 47,'id_user' => 1],
            ['state_name' => 'Cundinamarca','country_id' => 47,'id_user' => 1],
            ['state_name' => 'Guajira','country_id' => 47,'id_user' => 1],
            ['state_name' => 'Huila','country_id' => 47,'id_user' => 1],
            ['state_name' => 'Magdalena','country_id' => 47,'id_user' => 1],
            ['state_name' => 'Meta','country_id' => 47,'id_user' => 1],
            ['state_name' => 'Nariño','country_id' => 47,'id_user' => 1],
            ['state_name' => 'Norte de Santander','country_id' => 47,'id_user' => 1],
            ['state_name' => 'Quindío','country_id' => 47,'id_user' => 1],
            ['state_name' => 'Risaralda','country_id' => 47,'id_user' => 1],
            ['state_name' => 'Santander','country_id' => 47,'id_user' => 1],
            ['state_name' => 'Sucre','country_id' => 47,'id_user' => 1],
            ['state_name' => 'Tolima','country_id' => 47,'id_user' => 1],
            ['state_name' => 'Valle del Cauca','country_id' => 47,'id_user' => 1]
        ]);
    }
}
