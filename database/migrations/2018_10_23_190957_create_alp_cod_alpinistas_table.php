<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlpCodAlpinistasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alp_cod_alpinistas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('documento_alpi');
            $table->string('codigo_alpi');
            $table->string('estatus_alpinista')->default(1)->comment('1: Dato Cargado, 2 Usuario Creado, 3 Alpinista de Baja');
            $table->integer('id_usuario_creado')->nullable();
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
        Schema::dropIfExists('alp_cod_alpinistas');
    }
}
