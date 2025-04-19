<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValorPagoAlpOrddenesPagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alp_ordenes_pagos', function(Blueprint $table)
		{
			$table->string('valor_pago')->nullable();
			$table->string('referencia')->nullable();
			$table->string('ticket')->nullable();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alp_ordenes_pagos', function(Blueprint $table)
		{
			$table->dropColumn('valor_pago');
			$table->dropColumn('referencia');
			$table->dropColumn('ticket');
		});
    }
}
