<?php
use App\Models\AlpMenuDetalle;
use Illuminate\Database\Seeder;
class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
	public function run()
    {
        /* Menu principal */
        DB::table('alp_menu_detalles')->insert([
            'name' => 'Inicio',
            'slug' => '/',
            'parent' => 0,
            'order' => 0,
            'id_menu' => 1,
        ]);
        DB::table('alp_menu_detalles')->insert([
            'name' => 'Todos los Productos',
            'slug' => 'productos',
            'parent' => 0,
            'order' => 1,
            'id_menu' => 1,
        ]);
        DB::table('alp_menu_detalles')->insert([
            'name' => 'Lácteos',
            'slug' => '#',
            'parent' => 0,
            'order' => 2,
            'id_menu' => 1,
        ]);
        DB::table('alp_menu_detalles')->insert([
            'name' => 'Quesos',
            'slug' => 'categoria/quesos',
            'parent' => 0,
            'order' => 4,
            'id_menu' => 1,
        ]);
        DB::table('alp_menu_detalles')->insert([
            'name' => 'Línea Finesse',
            'slug' => 'categoria/finesse',
            'parent' => 0,
            'order' => 5,
            'id_menu' => 1,
        ]);
        DB::table('alp_menu_detalles')->insert([
            'name' => 'Alpina Baby',
            'slug' => 'categoria/alpina-baby',
            'parent' => 0,
            'order' => 6,
            'id_menu' => 1,
        ]);
        DB::table('alp_menu_detalles')->insert([
            'name' => 'Otros',
            'slug' => '-',
            'parent' => 0,
            'order' => 7,
            'id_menu' => 1,
        ]);
                DB::table('alp_menu_detalles')->insert([
                    'name' => 'Leche',
                    'slug' => 'leche',
                    'parent' => 3,
                    'order' => 1,
                    'id_menu' => 1,
                ]);
                DB::table('alp_menu_detalles')->insert([
                    'name' => 'Derivados Lácteos',
                    'slug' => 'lacteos',
                    'parent' => 3,
                    'order' => 2,
                    'id_menu' => 1,
                ]);
                DB::table('alp_menu_detalles')->insert([
                    'name' => 'Postres y Dulces',
                    'slug' => 'postres-dulces',
                    'parent' => 7,
                    'order' => 1,
                    'id_menu' => 1,
                ]);
                DB::table('alp_menu_detalles')->insert([
                    'name' => 'Esparcibles e Ingredientes',
                    'slug' => 'esparcibles-ingredientes',
                    'parent' => 7,
                    'order' => 2,
                    'id_menu' => 1,
                ]);
                DB::table('alp_menu_detalles')->insert([
                    'name' => 'No Lácteos',
                    'slug' => 'no-lacteos',
                    'parent' => 7,
                    'order' => 3,
                    'id_menu' => 1,
                ]);
                DB::table('alp_menu_detalles')->insert([
                    'name' => 'Bebidas de Fruta',
                    'slug' => 'bebidas-frutas',
                    'parent' => 7,
                    'order' => 4,
                    'id_menu' => 1,
                ]);
                DB::table('alp_menu_detalles')->insert([
                    'name' => 'Temporada',
                    'slug' => 'Temporada',
                    'parent' => 7,
                    'order' => 5,
                    'id_menu' => 1,
                ]);
                DB::table('alp_menu_detalles')->insert([
                    'name' => 'Anchetas, Tablas y Otros',
                    'slug' => 'anchetas-tablas',
                    'parent' => 7,
                    'order' => 6,
                    'id_menu' => 1,
                ]);
         /* Menu Categorías */
         DB::table('alp_menu_detalles')->insert([
            'name' => 'Leche',
            'slug' => 'categoria/leche',
            'parent' => 0,
            'order' => 1,
            'id_menu' => 2,
        ]);
        DB::table('alp_menu_detalles')->insert([
            'name' => 'Derivados Lácteos',
            'slug' => 'categoria/lacteos',
            'parent' => 0,
            'order' => 2,
            'id_menu' => 2,
        ]); 
        DB::table('alp_menu_detalles')->insert([
            'name' => 'Quesos',
            'slug' => 'categoria/quesos',
            'parent' => 0,
            'order' => 3,
            'id_menu' => 2,
        ]); 
        DB::table('alp_menu_detalles')->insert([
            'name' => 'Finesse',
            'slug' => 'categoria/finesse',
            'parent' => 0,
            'order' => 4,
            'id_menu' => 2,
        ]); 
        DB::table('alp_menu_detalles')->insert([
            'name' => 'Alpina Baby',
            'slug' => 'categoria/alpina-baby',
            'parent' => 0,
            'order' => 5,
            'id_menu' => 2,
        ]); 
        DB::table('alp_menu_detalles')->insert([
            'name' => 'Postres y Dulces',
            'slug' => 'categoria/postres-dulces',
            'parent' => 0,
            'order' => 6,
            'id_menu' => 2,
        ]); 
        DB::table('alp_menu_detalles')->insert([
            'name' => 'Esparcibles e Ingredientes',
            'slug' => 'categoria/esparcibles-ingredientes',
            'parent' => 0,
            'order' => 7,
            'id_menu' => 2,
        ]); 
        DB::table('alp_menu_detalles')->insert([
            'name' => 'No Lácteos',
            'slug' => 'categoria/no-lacteos',
            'parent' => 0,
            'order' => 8,
            'id_menu' => 2,
        ]); 
        DB::table('alp_menu_detalles')->insert([
            'name' => 'Bebidas de Frutas',
            'slug' => 'categoria/bebidas-frutas',
            'parent' => 0,
            'order' => 9,
            'id_menu' => 2,
        ]);  
        DB::table('alp_menu_detalles')->insert([
            'name' => 'Temporada',
            'slug' => 'categoria/Temporada',
            'parent' => 0,
            'order' => 10,
            'id_menu' => 2,
        ]); 
        DB::table('alp_menu_detalles')->insert([
            'name' => 'Anchetas, Tablas y Otros',
            'slug' => 'categoria/anchetas-tablas',
            'parent' => 0,
            'order' => 11,
            'id_menu' => 2,
        ]);                
        /* Menu Marcas */
        DB::table('alp_menu_detalles')->insert([
            'name' => 'Avena Alpina',
            'slug' => 'marcas/avena_alpina',
            'parent' => 0,
            'order' => 1,
            'id_menu' => 3,
        ]);
        DB::table('alp_menu_detalles')->insert([
            'name' => 'Bon yurt',
            'slug' => 'marcas/bon-yurt',
            'parent' => 0,
            'order' => 2,
            'id_menu' => 3,
        ]);
        DB::table('alp_menu_detalles')->insert([
            'name' => 'Finesse',
            'slug' => 'marcas/finesse',
            'parent' => 0,
            'order' => 3,
            'id_menu' => 3,
        ]);
        DB::table('alp_menu_detalles')->insert([
            'name' => 'Yogurt Griego',
            'slug' => 'marcas/yogurt-griego',
            'parent' => 0,
            'order' => 4,
            'id_menu' => 3,
        ]);
        DB::table('alp_menu_detalles')->insert([
            'name' => 'Yox',
            'slug' => 'marcas/yox',
            'parent' => 0,
            'order' => 5,
            'id_menu' => 3,
        ]);
        DB::table('alp_menu_detalles')->insert([
            'name' => 'Regeneris',
            'slug' => 'marcas/regeneris',
            'parent' => 0,
            'order' => 6,
            'id_menu' => 3,
        ]);
        DB::table('alp_menu_detalles')->insert([
            'name' => 'Pudin',
            'slug' => 'marcas/pudin',
            'parent' => 0,
            'order' => 7,
            'id_menu' => 3,
        ]);
        DB::table('alp_menu_detalles')->insert([
            'name' => 'Queso Sabana',
            'slug' => 'marcas/queso-sabana',
            'parent' => 0,
            'order' => 8,
            'id_menu' => 3,
        ]);
        DB::table('alp_menu_detalles')->insert([
            'name' => 'Leche Alpina',
            'slug' => 'marcas/leche-alpina',
            'parent' => 0,
            'order' => 9,
            'id_menu' => 3,
        ]);
        DB::table('alp_menu_detalles')->insert([
            'name' => 'Queso Parmesano',
            'slug' => 'marcas/queso-parmesano',
            'parent' => 0,
            'order' => 10,
            'id_menu' => 3,
        ]);
    }
}