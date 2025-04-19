<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlpCajasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alp_cajas', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('monto_inicial', 10, 2);
            $table->decimal('monto_final', 10, 2);
            $table->date('fecha_inicio');
            $table->date('fecha_cierre');
            $table->text('observacions')->nullable();
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
        Schema::dropIfExists('alp_cajas');
    }
}
