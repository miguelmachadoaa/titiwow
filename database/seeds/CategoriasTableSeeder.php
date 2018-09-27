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
            ['nombre_categoria' => 'Leche','descripcion_categoria' => 'Leche en Todas sus presentaciones','slug' => 'leche','id_categoria_parent' => 0,'destacado' => 1,'order' => 1,'imagen_categoria' => 'm4ywUNjWzZ.png','css_categoria' => 'categoria1','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_categoria' => 'Lácteos','descripcion_categoria' => 'Productos derivados de Lácteos','slug' => 'lacteos','id_categoria_parent' => 0,'destacado' => 1,'order' => 2,'imagen_categoria' => 'VJG9Jb4gC8.jpeg','css_categoria' => 'categoria2','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_categoria' => 'Quesos','descripcion_categoria' => 'Nuestra gran variedad en Quesos','slug' => 'quesos','id_categoria_parent' => 0,'destacado' => 1,'order' => 3,'imagen_categoria' => 'caGTIgfP73.jpeg','css_categoria' => 'categoria3','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_categoria' => 'Postres y Dulces','descripcion_categoria' => 'Variedad de Postres y Dulces','slug' => 'postres-dulces','id_categoria_parent' => 0,'destacado' => 1,'order' => 4,'imagen_categoria' => '2migSoqS7l.jpeg','css_categoria' => 'categoria4','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_categoria' => 'Esparcibles e Ingredientes','descripcion_categoria' => 'Productos esparcibles e ingredientes para sus preparaciones','slug' => 'esparcibles-ingredientes','id_categoria_parent' => 0,'destacado' => 1,'order' => 5,'imagen_categoria' => 'HGtK6JtKwy.jpeg','css_categoria' => 'categoria5','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_categoria' => 'Bebidas de Fruta','descripcion_categoria' => 'Bebidas de Frutas','slug' => 'bebidas-frutas','id_categoria_parent' => 0,'destacado' => 1,'order' => 6,'imagen_categoria' => 'BjHN4x1Sal.jpeg','css_categoria' => 'categoria6','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_categoria' => 'Línea Finesse','descripcion_categoria' => 'Todos los Productos de Nuestra Línea Finesse','slug' => 'finesse','id_categoria_parent' => 0,'destacado' => 1,'order' => 7,'imagen_categoria' => 'aRPTi0Cidz.jpeg','css_categoria' => 'categoria7','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_categoria' => 'Alpina Baby','descripcion_categoria' => 'Línea Alpina Baby, Más Grandecitos y Complementarios','slug' => 'alpina-baby','id_categoria_parent' => 0,'destacado' => 1,'order' => 8,'imagen_categoria' => 'EmJ4eyq9S8.jpeg','css_categoria' => 'categoria8','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_categoria' => 'No Lácteos','descripcion_categoria' => 'Productos que no contienen Láctosa','slug' => 'no-lacteos','id_categoria_parent' => 0,'destacado' => 1,'order' => 9,'imagen_categoria' => '6XBY6txvRs.jpeg','css_categoria' => 'categoria9','id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_categoria' => 'Avena Alpina','descripcion_categoria' => 'Productos de Avena Alpina','slug' => 'avena-alpina','id_categoria_parent' => 2,'destacado' => NULL,'order' => NULL,'imagen_categoria' => NULL,'css_categoria' => NULL,'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ]);
    }
}
