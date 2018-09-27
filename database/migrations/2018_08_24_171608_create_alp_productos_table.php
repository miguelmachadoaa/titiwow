<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlpProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alp_productos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre_producto');
            $table->string('referencia_producto')->nullable();
            $table->string('referencia_producto_sap')->nullable();
            $table->string('descripcion_corta')->nullable();
            $table->text('descripcion_larga')->nullable();
            $table->string('imagen_producto')->nullable();
            $table->string('seo_titulo')->nullable();
            $table->string('seo_descripcion')->nullable();
            $table->string('slug')->unique();
            $table->integer('id_categoria_default');
            $table->integer('id_marca');
            $table->decimal('precio_base')->nullable();
            $table->integer('id_impuesto');
            $table->decimal('pum')->nullable();
            $table->integer('destacado')->nullable();
            $table->integer('order')->nullable();
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
        Schema::dropIfExists('alp_productos');
    }
}
