<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlpProductosImagenesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alp_productos_imagenes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_producto')->index();
            $table->string('imagen_producto');
            $table->integer('order')->default(0);
            $table->string('title')->nullable();
            $table->string('alt')->nullable();
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
        Schema::dropIfExists('alp_productos_imagenes');
    }
}
