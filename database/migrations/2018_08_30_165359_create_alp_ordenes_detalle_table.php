<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlpOrdenesDetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alp_ordenes_detalle', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_orden');
            $table->integer('id_producto');
            $table->integer('cantidad');
            $table->decimal('precio_unitario',20,2);
            $table->decimal('precio_base',20,2);
            $table->decimal('precio_total',20,2);
            $table->decimal('precio_total_base',20,2);
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
        Schema::dropIfExists('alp_ordenes_detalle');
    }
}
