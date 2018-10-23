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
    }
}