<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlpSedesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alp_sedes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre_sede');
            $table->text('descripcion_sede')->nullable();
            $table->string('latitud_sede');
            $table->string('longitud_sede');
            $table->integer('estado_registro')->default(1);
            $table->integer('id_user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alp_sedes');
    }
}
