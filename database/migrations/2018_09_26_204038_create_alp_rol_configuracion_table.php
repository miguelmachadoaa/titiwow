<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlpRolConfiguracionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alp_rol_configuracion', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_rol');
            $table->integer('precio');
            $table->integer('referido');
            $table->integer('empresa');
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
        Schema::dropIfExists('alp_rol_configuracion');
    }
}
