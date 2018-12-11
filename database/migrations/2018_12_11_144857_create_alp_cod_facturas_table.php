<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlpCodFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alp_cod_facturas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('orden_compra');
            $table->string('factura');
            $table->string('estatus_factura')->default(1)->comment('1: Dato Cargado, 2: Orden No encontrada');
            $table->integer('id_orden')->nullable();
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
        Schema::dropIfExists('alp_cod_facturas');
    }
}
