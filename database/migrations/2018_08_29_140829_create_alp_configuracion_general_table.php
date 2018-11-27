<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlpConfiguracionGeneralTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alp_configuracion_general', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre_tienda');
            $table->string('limite_amigos');
            $table->string('minimo_compra');
            $table->integer('registro_publico');
            $table->string('id_mercadopago');
            $table->integer('mercadopago_sand');
            $table->string('key_mercadopago');
            $table->string('correo_admin')->nullable();
            $table->string('correo_shopmanager')->nullable();
            $table->string('correo_shopmanagercorp')->nullable();
            $table->string('correo_masterfile')->nullable();
            $table->string('correo_sac')->nullable();
            $table->string('correo_cedi')->nullable();
            $table->string('correo_logistica')->nullable();
            $table->string('correo_finanzas')->nullable();

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
        Schema::dropIfExists('alp_configuracion_general');
    }
}
