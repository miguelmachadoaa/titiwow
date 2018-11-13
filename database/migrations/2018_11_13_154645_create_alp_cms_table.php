<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlpCmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alp_cms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo_pagina');
            $table->text('texto_pagina')->nullable();
            $table->string('seo_titulo')->nullable();
            $table->string('seo_descripcion')->nullable();
            $table->string('slug')->unique();
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
        Schema::dropIfExists('alp_cms');
    }
}
