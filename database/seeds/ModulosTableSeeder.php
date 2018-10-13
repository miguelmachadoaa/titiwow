<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ModulosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alp_modulos')->insert([
            ['nombre_modulo' => 'productos','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_modulo' => 'categorias','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_modulo' => 'marcas','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_modulo' => 'ordenes','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_modulo' => 'formaspago','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_modulo' => 'estatus','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_modulo' => 'estatuspago','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_modulo' => 'empresas','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_modulo' => 'formasenvio','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_modulo' => 'rolenvios','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_modulo' => 'rolpagos','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_modulo' => 'rolconfiguracion','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_modulo' => 'impuestos','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_modulo' => 'menus','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_modulo' => 'transportistas','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_modulo' => 'sedes','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_modulo' => 'clientes','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_modulo' => 'users','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_modulo' => 'groups','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
        ]);
    }
}
