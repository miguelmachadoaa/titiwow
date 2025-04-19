<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlpTransaccionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alp_transacciones', function (Blueprint $table) {
            $table->id();
            $table->string('id_orden')->nullable();
            $table->string('referencia')->nullable();
            $table->decimal('monto', 10, 2)->nullable();
            $table->decimal('valor', 10, 2)->nullable();
            $table->string('id_forma_pago')->nullable();
            $table->string('tipo')->nullable();
            $table->string('moneda')->nullable();
            $table->string('estado_registro')->nullable();
            $table->string('id_user')->nullable();
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
        Schema::dropIfExists('alp_transacciones');
    }
}
