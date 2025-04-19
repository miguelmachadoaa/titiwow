<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsAlpDireccionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alp_direcciones', function(Blueprint $table)
		{
			$table->string('titulo')->nullable();
			$table->string('id_barrio')->nullable();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alp_direcciones', function(Blueprint $table)
		{
			$table->dropColumn('titulo');
			$table->dropColumn('id_barrio');
		});
    }
}
