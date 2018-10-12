<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlpPuntosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alp_puntos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_orden');
            $table->integer('id_cliente');
            $table->integer('tipo')->comment('1 suma, 2 resta');
            $table->decimal('cantidad');
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
        Schema::dropIfExists('alp_puntos');
    }
}
