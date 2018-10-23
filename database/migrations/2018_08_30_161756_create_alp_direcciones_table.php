<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlpDireccionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alp_direcciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_client');
            $table->integer('city_id');
            //$table->string('nickname_address');
            $table->string('id_estructura_address');
            $table->string('principal_address');
            $table->string('secundaria_address');
            $table->string('edificio_address');
            $table->string('detalle_address');
            $table->string('barrio_address')->nullable();
            //$table->string('calle2_address')->nullable();
            //$table->string('codigo_postal_address')->nullable();
            //$table->string('telefono_address');
            $table->text('notas')->nullable();
            $table->integer('default_address')->default(1);
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
        Schema::dropIfExists('alp_direcciones');
    }
}
