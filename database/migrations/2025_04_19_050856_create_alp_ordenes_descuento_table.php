<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlpOrdenesDescuentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alp_ordenes_descuento', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_orden')->nullable();
            $table->string('codigo_cupon')->nullable();
            $table->decimal('monto_descuento', 10, 2)->nullable();
            $table->text('json')->nullable();
            $table->boolean('aplicado')->default(0);
            $table->integer('estado_registro')->default(1);
            $table->unsignedBigInteger('id_user')->nullable();
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
        Schema::dropIfExists('alp_ordenes_descuento');
    }
}
