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

    }
}