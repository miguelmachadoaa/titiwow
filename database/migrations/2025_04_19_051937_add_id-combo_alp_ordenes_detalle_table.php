<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdComboAlpOrdenesDetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alp_ordenes_detalle', function(Blueprint $table)
		{
			$table->string('id_combo')->nullable();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alp_ordenes_detalle', function(Blueprint $table)
		{
			$table->dropColumn('id_combo');
		});
    }
}
