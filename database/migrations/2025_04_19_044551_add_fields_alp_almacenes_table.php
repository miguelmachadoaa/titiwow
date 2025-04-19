<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsAlpAlmacenesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //comision_mp_pse
        Schema::table('alp_almacenes', function(Blueprint $table)
		{
			$table->string('comision_mp_pse')->nullable();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alp_almacenes', function(Blueprint $table)
		{
			$table->dropColumn('comision_mp_pse');
		});
    }
}
