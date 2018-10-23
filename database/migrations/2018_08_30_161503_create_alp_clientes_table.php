<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlpClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alp_clientes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user_client');
            $table->integer('id_type_doc');
            $table->string('doc_cliente');
            $table->integer('genero_cliente');
            $table->string('telefono_cliente')->nullable();
            $table->integer('marketing_cliente')->default(0)->nullable();
            $table->integer('habeas_cliente')->default(0);
            $table->string('cod_alpinista')->nullable();
            $table->string('cod_oracle_cliente')->nullable();
            $table->integer('estado_masterfile')->default(0);
            $table->integer('estado_registro')->default(1);
            $table->integer('id_empresa')->default(0);
            $table->integer('id_embajador')->default(0);
            $table->text('nota')->nullable();
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
        Schema::dropIfExists('alp_clientes');
    }
}
