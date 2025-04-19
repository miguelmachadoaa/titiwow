<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsAlpConfiguracionGeneralTable extends Migration
{
    public function up()
    {
        Schema::table('alp_configuracion_general', function(Blueprint $table)
		{
			$table->string('direccion_tienda')->nullable();
			$table->string('rif_tienda')->nullable();
			$table->string('tasa_dolar')->nullable();
			$table->string('nombre_impresora')->nullable();
			$table->string('columnas_impresora')->nullable();
			$table->string('mostrar_agotados')->nullable();
			$table->string('robots')->nullable();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alp_configuracion_general', function(Blueprint $table)
		{
			$table->dropColumn('direccion_tienda');
            $table->dropColumn('rif_tienda');
            $table->dropColumn('tasa_dolar');
            $table->dropColumn('nombre_impresora');
            $table->dropColumn('columnas_impresora');
            $table->dropColumn('mostrar_agotados');
            $table->dropColumn('robots');
		});
    }
}
