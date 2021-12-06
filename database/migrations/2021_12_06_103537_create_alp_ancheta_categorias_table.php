<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlpAnchetaCategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alp_ancheta_categorias', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_ancheta');
            $table->string('nombre_ancheta');
            $table->integer('cantidad_minima');
            $table->integer('cantidad_maxima');
            $table->integer('estado_registro')->default(1);
            $table->integer('id_user');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alp_ancheta_categorias');
    }
}
