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
            $table->date('fecha_nacimiento')->nullable();
            $table->integer('marketing_cliente')->default(0);
            $table->integer('habeas_cliente')->default(0);
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
        Schema::dropIfExists('alp_clientes');
    }
}
