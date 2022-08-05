<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlpAlmacenDespachoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alp_almacen_despacho', function (Blueprint $table) {
            $table->id();
            $table->integer('id_almacen');
            $table->integer('id_state');
            $table->integer('id_city');
            $table->integer('id_barrio');
            $table->integer('estado_registro')->default(1);
            $table->integer('id_user')->default(1);;
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
        Schema::dropIfExists('alp_almacen_despacho');
    }
}
