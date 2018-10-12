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
            'name' => 'Productos',
            'slug' => 'productos',
            'parent' => 0,
            'order' => 1,
            'id_menu' => 1,
        ]);
        DB::table('alp_menu_detalles')->insert([
            'name' => 'Leche',
            'slug' => 'categoria/leche',
            'parent' => 0,
            'order' => 2,
            'id_menu' => 1,
        ]);
        DB::table('alp_menu_detalles')->insert([
            'name' => 'Lácteos',
            'slug' => 'categoria/lacteos',
            'parent' => 0,
            'order' => 3,
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
            'name' => 'Postres y Dulces',
            'slug' => 'categoria/postres-dulces',
            'parent' => 0,
            'order' => 5,
            'id_menu' => 1,
        ]);
        DB::table('alp_menu_detalles')->insert([
            'name' => 'Esparcibles e Ingredientes',
            'slug' => 'categoria/esparcibles-ingredientes',
            'parent' => 0,
            'order' => 6,
            'id_menu' => 1,
        ]);
        DB::table('alp_menu_detalles')->insert([
            'name' => 'Bebidas de Fruta',
            'slug' => 'categoria/bebidas-frutas',
            'parent' => 0,
            'order' => 7,
            'id_menu' => 1,
        ]);
    }
}