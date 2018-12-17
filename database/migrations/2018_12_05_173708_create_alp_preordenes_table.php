<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlpPreordenesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alp_preordenes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('referencia');
            $table->integer('id_cliente');
            $table->integer('id_forma_envio')->nullable();
            $table->integer('id_forma_pago')->nullable();
            $table->integer('id_address')->nullable();
            $table->decimal('monto_total',20,2)->nullable();
            $table->decimal('monto_total_base',20,2)->nullable();
            $table->string('preferencia_id');
            $table->text('json');
            $table->integer('estatus');
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
        Schema::dropIfExists('alp_preordenes');
    }
}
