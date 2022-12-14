<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlpCuponesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alp_cupones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo_cupon');
            $table->string('valor_cupon');
            $table->string('tipo_reduccion');
            $table->string('limite_uso');
            $table->string('limite_uso_persona');
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
        Schema::dropIfExists('alp_cupones');
    }
}
