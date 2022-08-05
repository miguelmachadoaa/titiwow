<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlpInventariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alp_inventarios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_almacen');
            $table->integer('id_producto');
            $table->integer('cantidad');
            $table->integer('operacion')->comment('1 agregar, 2 Descontar');
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
        Schema::dropIfExists('alp_inventarios');
    }
}
