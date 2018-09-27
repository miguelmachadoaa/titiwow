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
            ['nombre_producto' => 'Multiempaque x 6 Avena original vaso 250g','referencia_producto' => '7702001004416','referencia_producto_sap' => '441','descripcion_corta' => 'Alpina te trae Multiempaque x 6 Avena original vaso 250g. Alpina Alimenta tu vida.','descripcion_larga' => 'Nuestro Multiempaque x 6 Avena original vaso 250g es un producto que hemos creado pensando en cada uno de nuestros consumidores. ¡Disfrutalo!','imagen_producto' => 'htjml3aUJI.jpeg','seo_titulo' => 'Multiempaque x 6 Avena original vaso 250g','seo_descripcion' => 'Alpina te trae Multiempaque x 6 Avena original vaso 250g. Alpina Alimenta tu vida.','slug' => 'multiempaque-avena-original','id_categoria_default' => 2,'id_marca' => 1,'id_user' => 1,'id_impuesto' => 1,'pum' => 'Gramo a $0,12 pesos','pum' => 'Gramo a $0,12 pesos','medida' => '250g','precio_base' => 12550.00,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ]);

    }
}
