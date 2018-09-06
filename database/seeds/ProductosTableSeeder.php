<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ProductosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alp_productos')->insert([
            ['nombre_producto' => 'Leche Entera','referencia_producto' => 'LECH001','referencia_producto_sap' => '3534534534','descripcion_corta' => 'Leche entera Alpina 1Lt','descripcion_larga' => 'Leche entera Alpina 1Lt caracteristicas tal y tal','imagen_producto' => '1Zizrmiw27.jpeg','seo_titulo' => 'Leche Entera','seo_descripcion' => 'Leche entera 1ltr','slug' => 'leche-entera','id_categoria_default' => 1,'id_marca' => 1,'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['nombre_producto' => 'Leche Entera2','referencia_producto' => 'LECH002','referencia_producto_sap' => '3534534222','descripcion_corta' => 'Leche entera Alpina 1Lt','descripcion_larga' => 'Leche entera Alpina 1Lt caracteristicas tal y tal','imagen_producto' => '1Zizrmiw27.jpeg','seo_titulo' => 'Leche Entera','seo_descripcion' => 'Leche entera 1ltr','slug' => 'leche-entera2','id_categoria_default' => 1,'id_marca' => 1,'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

            ]);

    }
}
