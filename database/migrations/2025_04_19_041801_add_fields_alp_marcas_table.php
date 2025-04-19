<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsAlpMarcasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alp_marcas', function(Blueprint $table)
		{
			$table->string('seo_titulo')->nullable();
			$table->text('seo_descripcion')->nullable();
			$table->text('robots')->nullable();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alp_marcas', function(Blueprint $table)
		{
			$table->dropColumn('seo_titulo');
			$table->dropColumn('seo_descripcion');
			$table->dropColumn('robots');
		});
    }
}
