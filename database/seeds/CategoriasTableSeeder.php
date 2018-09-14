<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CategoriasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alp_categorias')->insert([
            ['nombre_categoria' => 'Cat padre1','descripcion_categoria' => 'Categoria padre1','slug' => 'padre1','id_categoria_parent' => 0,'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_categoria' => 'Cat padre2','descripcion_categoria' => 'Categoria padre2','slug' => 'padre2','id_categoria_parent' => 0,'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_categoria' => 'Cat hija1','descripcion_categoria' => 'Categoria hija1','slug' => 'hija1','id_categoria_parent' => 1,'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_categoria' => 'Cat hija2','descripcion_categoria' => 'Categoria hija2','slug' => 'hija2','id_categoria_parent' => 1,'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_categoria' => 'Cat hija3','descripcion_categoria' => 'Categoria hija3','slug' => 'hija3','id_categoria_parent' => 2,'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ]);
    }
}
