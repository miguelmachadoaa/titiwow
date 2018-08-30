<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlpOrdenesEstatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alp_ordenes_estatus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('estatus_nombre');
            $table->text('descripcion_estatus')->nullable();
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
        Schema::dropIfExists('alp_ordenes_estatus');
    }
}
