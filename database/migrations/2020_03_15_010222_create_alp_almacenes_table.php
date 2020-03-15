<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlpAlmacenesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alp_almacenes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre_almacen');
            $table->string('descripcion_almacen');
            $table->integer('id_city');
            $table->integer('defecto');
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
        Schema::dropIfExists('alp_almacenes');
    }
}
