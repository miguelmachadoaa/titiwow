<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlpAlmacenesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alp_almacenes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre_almacen');
            $table->string('descripcion_almacen');
            $table->integer('id_city');
            $table->string('hora');
            $table->string('correos');
            $table->string('minimo_compra');
            $table->string('tipo_almacen');
            $table->string('formato')->default(0);
            $table->integer('defecto');
            $table->string('descuento_productos')->default(1);
            $table->text('mensaje_promocion');
            $table->string('descuento_productos')->default();
            $table->integer('defecto');
            $table->string('id_mercadopago');
            $table->integer('mercadopago_sand');
            $table->string('key_mercadopago');
            $table->string('public_key_mercadopago')->nullable();
            $table->string('public_key_mercadopago_test')->nullable();
            $table->decimal('comision_mp',20,2);
            $table->decimal('comision_mp_baloto',20,2);
            $table->decimal('comision_mp_efecty',20,2);
            $table->decimal('retencion_fuente_mp',20,2);
            $table->decimal('retencion_iva_mp',20,2);
            $table->decimal('retencion_ica_mp',20,2);
            $table->string('epayco_sand')->nullable();
            $table->integer('epayco_id_cliente')->nullable();
            $table->string('epayco_key')->nullable();
            $table->string('epayco_public_key')->nullable();
            $table->string('epayco_private_key')->nullable();
            $table->string('codigo_almacen')->nullable();
            $table->string('imagen_almacen')->nullable();
            $table->string('alias_almacen')->nullable();
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
        Schema::dropIfExists('alp_almacenes');
    }
}
