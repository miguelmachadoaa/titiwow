<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlpMenuDetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alp_menu_detalle', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo_menu');
            $table->string('url_menu');
            $table->string('imagen_menu')->nullable();
            $table->integer('id_menu_parent')->nullable();
            $table->integer('estado_registro')->default(1);
            $table->integer('orden');
            $table->integer('id_menu');
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
        Schema::dropIfExists('alp_menu_detalle');
    }
}
