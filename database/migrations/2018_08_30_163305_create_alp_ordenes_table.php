<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlpOrdenesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alp_ordenes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('referencia');
            $table->integer('id_cliente');
            $table->integer('id_forma_envio');
            $table->integer('id_forma_pago');
            $table->integer('id_address');
            $table->decimal('monto_total',20,2);
            $table->decimal('monto_total_base',20,2);
            $table->decimal('base_impuesto',20,2);
            $table->decimal('valor_impuesto',20,2);
            $table->decimal('monto_impuesto',20,2);
            $table->decimal('comision_mp',20,4);
            $table->decimal('retencion_fuente_mp',20,4);
            $table->decimal('retencion_iva_mp',20,4);
            $table->decimal('retencion_ica_mp',20,4);
            $table->string('cod_oracle_pedido')->nullable();
            $table->string('ordencompra')->nullable();
            $table->string('factura')->nullable();
            $table->string('tracking')->nullable();
            $table->string('ip')->nullable();
            $table->integer('estatus');
            $table->integer('estatus_pago');
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
        Schema::dropIfExists('alp_ordenes');
    }
}
