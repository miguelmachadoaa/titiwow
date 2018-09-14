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
            'name' => 'Padre1',
            'slug' => 'categoria/padre1',
            'parent' => 0,
            'order' => 2,
            'id_menu' => 1,
        ]);
    }
}